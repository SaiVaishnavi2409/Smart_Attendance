<?php
    include 'dbcon2.php';
    session_start();
    $state = $_SESSION['state'];
    $check_hash = md5($_SESSION['name']);
    $check_hash_sql = "SELECT `id` FROM `user` WHERE `token` = '$check_hash'";
    $check_hash_result = mysqli_query($con,$check_hash_sql);
    $row_num = mysqli_num_rows($check_hash_result);
    if ($state!='active' || $row_num==0)
      header("Location: index.php");
?>
<!DOCTYPE html>
<html>
<head>
<!DOCTYPE html>
<html>

<head>
  <title>Smart Attendance</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" type="text/css" href="styletest.css">
  <!-- fa fa icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <style>
    /* .bg {
      background: rgb(227, 251, 241);
      background: linear-gradient(90deg, rgba(227, 251, 241, 0.981127485173757) 40%, rgba(199, 231, 239, 1) 100%);
    } */

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
  </style>
</head>

<body style="background-color: #27EF9F, #0DB8DE;">
  <div id="container">
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
            <a class="nav-link" href="homestudent.php"><i class="fa fa-home icon" aria-hidden="true"></i>Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="subject.php"><i class="fa fa-info-circle icon" aria-hidden="true"></i>Subjects</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="details1.php"><i class="fa fa-info-circle icon" aria-hidden="true"></i>Details</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="attendance1.php"><i class="fa fa-calendar-check-o icon" aria-hidden="true"></i>Attendance</a>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item item2">
            <a class="nav-link" href="logout.php"><i class="fa fa-sign-out icon" aria-hidden="true"></i>LogOut</a>
          </li>
        </ul>
      </div>
    </nav>
    <div>
        <h1 align="center" style="padding-top: 2rem;">VNRVJIET</h1>
        <h2 align="center">Subjects</h2>
        <p align="center" id="bd">
            <?php
                include("sanitize.php");
                echo "<span align=center style='font-size: 28px;'><u>Select the subject</u></span><br><br>";
                include("dbcon2.php");
                $uname=sanitize($_SESSION['name']);
                $sqlt="SELECT class FROM student s inner join user u on u.id=s.uid and username='$uname' ";
                $rsult = mysqli_query($con, $sqlt);
                if (!$rsult) {
                  printf("Error: %s\n", mysqli_error($con));
                  exit();
                }
                
                if(mysqli_num_rows($rsult) > 0){
                  $row = mysqli_fetch_assoc($rsult);
                  $class=$row['class'];
                  $sql = "SELECT t.sid as sid,s.name as name  FROM teaches t inner join subject s on s.id=t.sid and t.class='$class' ";
                  $result = mysqli_query($con, $sql);
                  if (!$result) {
                    printf("Error: %s\n", mysqli_error($con));
                    exit();
                  }
                  // echo mysqli_num_rows($result);
                  $i = 1;
                  if(mysqli_num_rows($result) > 0){
                      while($row = mysqli_fetch_assoc($result)){
                          echo "<a class='cls' href='subject.php?sid=".$row['sid']."&submit=submit&class=".$class."'> ".$row['name']."</a> ";
                          if ($i%3==0)
                              echo "<br><br>";
                          $i++;
                      }
                  }
                  else
                      echo "There is no data.";
              }
                if (isset($_GET['submit'])) {
                    $sid = sanitize($_GET['sid']);
                    $class = sanitize($_GET['class']);
                    echo "<br><hr style='height:2px;width:98%;color:gray;background-color:gray'>";
                    echo "<div id=con><svg width='1em' height='1em' viewBox='0 0 16 16' class='bi bi-caret-right-square-fill' fill='currentColor' xmlns='http://www.w3.org/2000/svg'>
  <path fill-rule='evenodd' d='M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm5.5 10a.5.5 0 0 0 .832.374l4.5-4a.5.5 0 0 0 0-.748l-4.5-4A.5.5 0 0 0 5.5 4v8z'/>
</svg> <b>Subject: $sid</b>";
                    echo "<br>-------------------------------------------------<br>";
                    $query = "SELECT s.name as name,s.description as descrip,te.name as faculty , t.class as class FROM subject s inner join teaches t on t.sid=s.id inner join teacher te on te.id=t.tid and s.id = '$sid' and class='$class'";
                    $std_details = mysqli_query($con, $query);
                    if (!$std_details) {
                      printf("Error: %s\n", mysqli_error($con));
                      exit();
                    }
                    if (mysqli_num_rows($std_details)>0) {
                      $row = mysqli_fetch_assoc($std_details) ;
                      echo "<b>Name  : </b>".$row['name']."</br>";
                      echo "<b>Description : </b>".$row['descrip']."</br>";
                      echo "<b>Faculty : </b>".$row['faculty']."</br>";
                      echo "<b>Class : </b>".$row['class']."</br>";   
                    }
                }
            ?>
            </div>
        </p>
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
</html>