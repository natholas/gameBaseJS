<?php
    require("setup.php");

    $data = new stdClass();
    $data->status = "failed";

    if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email']) && strlen($_POST['username']) > 3 && strlen($_POST['password']) > 3 && strlen($_POST['email']) > 3) {

        // Before we can create an account we first need to check to see if the username is available.
        $username = $_POST['username'];
        if (!($stmt = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE username = ?"))) {
            die ("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
        }

        if (!$stmt->bind_param("s", $username)) {
            die ("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
        }

        if (!$stmt->execute()) {
            die ("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        }

        $result = $stmt->get_result()->fetch_assoc()['COUNT(*)'];

        if ($result == 0) {

            // This username is unique and can be used.
            // Lets create a new user

            $username = $_POST['username'];
            $email = $_POST['email'];
            $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $login_key = generateRandomString();

            // Doing a quick check to see if the login_key is unique
            $sql = "SELECT COUNT(*) FROM users WHERE login_key = '$login_key'";
            if ($mysqli->query($sql)->fetch_assoc()['COUNT(*)'] > 0) {
                $login_key = generateRandomString();
            }

            // And lets insert this new user into the database

            if (!($stmt = $mysqli->prepare("INSERT INTO users (username, email, password, login_key) VALUES (?, ?, ?, ?)"))) {
                die ("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
            }

            if (!$stmt->bind_param("ssss", $username, $email, $password_hash, $login_key)) {
                die ("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
            }

            if (!$stmt->execute()) {
                die ("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            }

            // We only set a login_key cookie so that the front end can just do an info call to get their data.
            setcookie("login_key", $login_key, time() + 60 * 60 * 24 * 30 * 12, "/"); // 12 months
            $data->status = "success";
        }
    }

    echo json_encode($data);

    function generateRandomString($length = 32) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


?>
