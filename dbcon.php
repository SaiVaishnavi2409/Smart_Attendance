<?php
$con = @mysqli_connect("localhost","root","");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
@mysqli_select_db("attendance", $con);
?>