<?php
    require("setup.php");

    $data = new stdClass();

    // The user wants to logout.
    // We just have to destroy the session and remove the login_key cookie

    session_destroy();
    setcookie("login_key", "", 0, "/");

    $data->status = "success";

    echo json_encode($data);
?>
