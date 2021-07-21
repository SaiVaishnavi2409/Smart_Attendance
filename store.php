<?php
    include 'dbcon.php';
    include 'sanitize.php';
    if ($_GET['token'] = '182ED54D634B78F47A31A68360C152BA') {
        $class = sanitize($_GET['class']);
        $data = sanitize($_GET['data']);
        $date = date("Y-m-d");
        $check = "SELECT `d_id` FROM `attend` WHERE `date`='$date' AND `class`='$class' AND `details`='$data'";
        $check_result = mysql_query($check);
        if (mysql_num_rows($check_result)==0) {
            $sql = "INSERT INTO `attend` (`d_id`, `date`, `class`, `details`) VALUES (NULL, '$date', '$class', '$data');";
            mysql_query($sql);
        }
    }
    header("Location: index.php");
?>