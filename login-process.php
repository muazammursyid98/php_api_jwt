<?php
session_start();
require_once('_database/db_connect.php'); // connect to database
$user_id = ""; // variable declaration
$msgs = array();

if($_POST){ // login() process
  $user_id = $_POST['user_id'];
  $results = $connect->query("SELECT * FROM user WHERE BINARY user_id='$user_id' LIMIT 1");
  if($results->num_rows == 1){ // user found
    $row = $results->fetch_array();
    if(password_verify($_POST['password'], $row['password'])){
      $logged_in_user = $row;
      if($logged_in_user['status'] != 2){
        if($logged_in_user['user_type'] != "staff"){
          $_SESSION['user']=$logged_in_user;
          $msg["msg"] = "You have successfully logged in";
        } else { 
          $msg["msg"] = "Wrong user ID / password combination";
        } 
      } else {
        $msg["msg"] = "This account have been deactivated";
      }
    } else {
      $msg["msg"] = "Wrong user ID / password combination";
    }
  } else { 
    $msg["msg"] = "User ID not found";
  }  
  echo json_encode($msg);
}
?>
