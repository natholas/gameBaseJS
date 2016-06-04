<?php
    require("setup.php");

    $data = new stdClass();
    $data->status = "failed";

    if (isset($_POST['code']) && isset($_SESSION['reset_code']) && isset($_SESSION['reset_for']) && isset($_POST['new_password'])) {

        // The user has entered a code that they received via email
        // We need to make sure that this code is correct

        if ($_POST['code'] == $_SESSION['reset_code']) {
            $email = $_SESSION['reset_for'];
            $password_hash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            if (!($stmt = $mysqli->prepare("UPDATE users SET password = ? WHERE email = ?"))) {
                die ("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
            }

            if (!$stmt->bind_param("ss", $password_hash, $email)) {
                die ("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
            }

            if (!$stmt->execute()) {
                die ("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
            }

            $data->status = "success";
        }

    } else if (isset($_POST['email'])) {

        // The user was an idiot and forgot their password...
        // Lets make sure that they are really a user
        $email = $_POST['email'];
        if (!($stmt = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE email = ?"))) {
            die ("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
        }

        if (!$stmt->bind_param("s", $email)) {
            die ("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
        }

        if (!$stmt->execute()) {
            die ("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        }

        if ($stmt->get_result()->fetch_assoc()['COUNT(*)'] > 0) {

            // This is a real user so lets generate a random code and set some session values for checking it later

            $_SESSION['reset_code'] = generateRandomString(8);
            $_SESSION['reset_for'] = $_POST['email'];

            $to      = $_POST['email'];
            $subject = 'Password reset code';
            $message = 'Your reset code is: '.$_SESSION['reset_code'];
            $headers = 'From: nathansecodary@gmail.com' . "\r\n" .
                'Reply-To: nathansecodary@gmail.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

            mail($to, $subject, $message, $headers);

            // Now that we have sent the email we need to let the client know we sent it

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
