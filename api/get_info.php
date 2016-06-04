<?php
    require("setup.php");

    $data = new stdClass();
    $data->status = "failed";

    // First we make sure that the user is logged in and has all the correct info saved in the session
    if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])) {

        // They are logged in so we can assign these values to the object that we will return
        $data->status = "success";
        $data->user_id = $_SESSION['user_id'];
        $data->user_name = $_SESSION['user_name'];

        // We can also lookup if they are currently in a game
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT current_game_id, points FROM users WHERE user_id = $user_id";
        $result = $mysqli->query($sql)->fetch_object();

        if ($result) {
            // They are in a game so lets add that to the object
            $data->current_game_id = $result->current_game_id;
            $data->points = $result->points;
        }

    }

    // If the user doesn't have a correct session then we can check to see if they have a cookie with a login_key
    else if (isset($_COOKIE['login_key'])) {

        // They do have a cookie set. Lets see if it matches a players login_key
        $login_key = $_COOKIE['login_key'];

        if (!($stmt = $mysqli->prepare("SELECT username, points, current_game_id, user_id FROM users WHERE login_key = ?"))) {
            die ("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
        }

        if (!$stmt->bind_param("s", $login_key)) {
            die ("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
        }

        if (!$stmt->execute()) {
            die ("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        }

        $result = $stmt->get_result()->fetch_object();

        if ($result) {

            // If there was a result then the login_key is valid
            // Lets set up the session_start
            $_SESSION['user_id'] = $result->user_id;
            $_SESSION['user_name'] = $result->username;
            $_SESSION['current_game_id'] = $result->current_game_id;

            // And lets set the values to be returned
            $data->status = "success";
            $data->user_id = $_SESSION['user_id'];
            $data->user_name = $_SESSION['user_name'];
            $data->points = $result->points;
            $data->current_game_id = $result->current_game_id;
        }

    }

    echo json_encode($data);


?>
