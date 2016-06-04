<?php
    require("setup.php");

    $data = new stdClass();
    $data->status = "failed";

    // The user wishes to login
    // Lets make sure they supplied the needed info
    if (isset($_POST['username']) && isset($_POST['password'])) {

        $username = $_POST['username'];

        // Lets look up in the database to see if the password matches
        if (!($stmt = $mysqli->prepare("SELECT password, user_id, login_key FROM users WHERE username = ?"))) {
            die ("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
        }

        if (!$stmt->bind_param("s", $username)) {
            die ("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
        }

        if (!$stmt->execute()) {
            die ("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        }

        $result = $stmt->get_result()->fetch_object();

        if ($result && password_verify($_POST['password'], $result->password)) {

            // The username was found and the password is correct
            // This means that we can set up the cookie and send success back to the client
            setcookie("login_key", $result->login_key, time() + 60 * 60 * 24 * 30 * 12, "/"); // 12 months
            $data->status = "success";
        }
    }

    echo json_encode($data);
?>
