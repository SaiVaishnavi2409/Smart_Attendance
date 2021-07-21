<?php
    session_start();
    $_SESSION['state'] = "";
    $_SESSION['name'] = "";
    header("Location: index.php");
?>