<?php
include 'dbcon2.php';
session_start();
$state = $_SESSION['state'];
$check_hash = md5($_SESSION['name']);
$check_hash_sql = "SELECT `id` FROM `user` WHERE `token` = '$check_hash'";
$check_hash_result = mysqli_query($con, $check_hash_sql);
$row_num = mysqli_num_rows($check_hash_result);
if ($state!='active' || $row_num==0)
   header("Location: index.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Smart Attendance</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="styletest.css">
    <style>
        .icon {
            /* padding-left: 0.5rem; */
            padding-right: 0.5rem;
        }
        .nav-item {
            font-size: 2rem;
            /* padding-left: 0.5rem; */
            padding-right: 4rem;
        }

        .item2 {
            padding-right: 0;
        }

        .navbar-header {
            padding-right: 2rem;
        }

        .my-hr-line {
            padding: 0.02px 0;
        }

        .fsize {
            font-size: 1.25rem;
        }
    </style>
</head>

<body>
    <script type="text/javascript">
        function display(op) {
            var firstButton = document.getElementById('search_details');
            var secButton = document.getElementById('search_date');
            if (firstButton.style.display == 'none' && op == 1) {
                secButton.style.display = 'none';
                firstButton.style.display = 'block';
            } else {
                secButton.style.display = 'block';
                firstButton.style.display = 'none';
            }
        }
    </script>

    <div id="container-fluid">
        <nav class="navbar navbar-expand-md bg-dark navbar-dark">
            <!-- Brand -->
            <div class="navbar-header">
                <img src="logo.png" width="50px" height="45px" style="margin-top:4px;">
            </div>
            <!-- Toggler/collapsibe Button -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar links -->
            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php"><i class="fa fa-home icon" aria-hidden="true"></i>Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="details.php"><i class="fa fa-info-circle icon" aria-hidden="true"></i>Details</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="attendance.php"><i class="fa fa-calendar-check-o icon" aria-hidden="true"></i>Attendance</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item item2">
                        <a class="nav-link" href="logout.php"><i class="fa fa-sign-out icon" aria-hidden="true"></i>LogOut</a>
                    </li>
                </ul>
            </div>
        </nav>
        <h1 align="center" style="padding-top: 2rem;">VNR VJIET</h1>
        <p align="center" id="bd">
            <span align="center" style="font-size: 28px;"><b>Student Attendance</b></span>
            <br><br><button class="cls" onclick="display(1)">Search By Student Details</button><br><br>
            <button class="cls" onclick="display(2)">Search By Date</button>
        <div id="con">

            <form method="GET" action="" style="display: none; margin:2rem;" id="search_details" border="1">
                <div class="row">
                    <!-- <div class="col-2"></div> -->
                    <div class="col-3 fsize"><label><b>Name:</b></label><input type="text" class="form-control" name="name" placeholder="Name" maxlength=20 required></div>
                    <!-- <div class="col-2"></div> -->
                    <div class="col-3 fsize"><label><b>Roll: </b></label><input type="text" class="form-control" name="roll" placeholder="Roll" maxlength=4 required></div>
                    <!-- <div class="col-2"></div> -->
                    <div class="col-3 fsize"><label><b>Class: </b></label><input type="text" class="form-control" name="class" placeholder="e.g: I/II/III..." maxlength=8 required></div>
                    <!-- <div class="col-2"></div> -->
                    <div class="col-3 fsize"><label><b>Date: </b></label><input type="date" class="form-control" name="date" required></div>
                </div><br>
                <br>
                <div style="text-align: center;">
                    <button type="submit" name="Login" class="btn btn-outline-secondary" value="details">Submit</button>
                </div>
            </form>
            <form method="GET" action="" style="display: none; margin:2rem;" id="search_date">
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-sm-3"><label><b>Class:</b> </label><input type="text" class="form-control" name="class" placeholder="e.g: I/II/III..." maxlength=8 required></div>
                    <div class="col-sm-3"><label><b>Date:</b> </label><input type="date" class="form-control" name="date" required></div>
                </div>
                <br>
                <div style="text-align: center;">
                    <button type="submit" name="Login" class="btn btn-outline-secondary" value="date">Submit</button>
                </div>
            </form>
            </p>
            <?php
            include 'dbcon2.php';
            include 'sanitize.php';
            if (isset($_GET['Login'])) {
                $type = sanitize($_GET['Login']);
                $class = sanitize($_GET['class']);
                $date = sanitize($_GET['date']);
                if ($type == 'details') {
                    $name = sanitize($_GET['name']);
                    $roll = sanitize($_GET['roll']);
                    if (strlen($roll) == 1) {
                        $new_roll = "0" . $roll;
                    }
                    $pre_sql = "SELECT id FROM `student` WHERE name = '$name' AND roll = $roll AND class = '$class'";
                    $rlt = mysqli_query($con, $pre_sql);
                    if (mysqli_num_rows($rlt) > 0) {
                        $sql = "SELECT `d_id` FROM `attend` WHERE `date` = '$date' AND `details` LIKE '%-$new_roll-%'";
                        $result = mysqli_query($con, $sql);
                        if (mysqli_num_rows($result) > 0) {
                            $status = 'Present';
                        } else {
                            $status = 'Absent';
                        }
                        // echo "Name: <b>$name</b> || Class: <b>$class</b> || Roll: <b>$new_roll</b><br>Attendance status on Date: $date : <b>$status</b>";
                        echo "<div style='text-align:center'>";
                        echo "<tr>";
                            echo "<td>Name: <b>$name</b></td>";
                        echo "</tr>\n";
                        echo "<br>";
                        echo "<tr>";
                            echo "<td>Class: <b>$class</b></td>";
                        echo "</tr>\n";
                        echo "<br>";
                        echo "<tr>";
                            echo "<td>Roll: <b>$new_roll</b></td>";
                        echo "</tr>\n";
                        echo "<br>";
                        echo "<tr>";
                            echo "<td>Status: <b>$status</b></td>";
                        echo "</tr>\n";
                        echo "<br>";
                        echo "<tr>";
                            echo "<td>Date: <b>$date</b></td>";
                        echo "</tr>\n";
                        echo "<br>";
                        echo "</div>";
                    } else {
                        echo "Wrong Student details entered!";
                    }
                } else {
                    $sql = "SELECT `details` FROM `attend` WHERE `date` = '$date' AND `class`='$class'";
                    $result = mysqli_query($con, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        $str = $row['details'];
                        $str = substr($str, 1, strlen($str) - 2);
                        $arr = explode("-", $str);
                        $sql = "UPDATE `student` SET `status` = 'Present' WHERE `student`.`class` = '$class' AND `student`.`roll` = ";
                        $len = count($arr);
                        for ($i = 0; $i < $len; $i++) {
                            $run_sql = $sql . (int)$arr[$i];
                            mysqli_query($con, (string)$run_sql);
                        }
                        $show = "SELECT `name`, `roll`, `status` FROM `student` WHERE `class` = '$class'";
                        $show_result = mysqli_query($con, $show);
                        if (mysqli_num_rows($show_result) > 0) {
            ?>
                            <table class="table-striped" border="3" class="table-sm" align="center">
                                <tr>
                                    <th> Name</th>
                                    <th> Roll</th>
                                    <th> Class</th>
                                    <th> Status</th>
                                </tr>
                <?php
                            while ($row = mysqli_fetch_row($show_result)) {
                                echo "<tr><td>" . $row[0] . "</td>";
                                echo "<td>" . $row[1] . "</td>";
                                echo "<td>" . $class . "</td>";
                                if ($row[2] == 'Present')
                                    echo "<td><font color=green>" . $row[2] . "</font></td></tr>";
                                else
                                    echo "<td><font color=red>" . $row[2] . "</font></td></tr>";
                            }
                            $sql = "UPDATE `student` SET `status` = 'Absent' WHERE `student`.`class` = '$class' AND `student`.`roll` = ";
                            $len = count($arr);
                            for ($i = 0; $i < $len; $i++) {
                                $run_sql = $sql . (int)$arr[$i];
                                mysqli_query($con, (string)$run_sql);
                            }
                        } else {
                            echo "No data found!!!";
                        }
                    } else
                        echo "No data found!!!";
                }
            }
                ?>

            </table>

            <hr style="height:2px;width:98%;color:gray;background-color:gray">
    <div id="con" class="jumbotron" style="text-align:center;">
        <h4>Contact Us:</h4>
        <font size="3px">Phone No: +91-40-23042761<br>
            Email: vnrvjiet2021@gmail.com<br>
            Address: Bachupally, Hyderabad, Telangana, India</font>
        <br>
    </div>
    <hr style="height:2px; width:98%; color:gray; background-color:gray">
    <p align=center style="color:grey"><b>Copyright &#9400; 2021, 2G4E</b></p>
    </div>
    <link rel=" stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>