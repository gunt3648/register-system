<?php
  session_start();

  $username = "";
  $email = "";
  $errors = array();

  //connect to database
  $db = mysqli_connect('localhost', 'root', '', 'registration');

  //if regis button is clicked
  if(isset($_POST['register'])) {
    $username= mysql_real_escape_string($_POST['username']);
    $email= mysql_real_escape_string($_POST['email']);
    $password= mysql_real_escape_string($_POST['password']);
    $password_confirmed= mysql_real_escape_string($_POST['password_confirmed']);
    
    if(empty($username)) {
      array_push($errors, "Username is required");
    }
    if(empty($email)) {
      array_push($errors, "Email is required");
    }
    if(empty($password)) {
      array_push($errors, "Password is required");
    }
    if($password != $password_confirmed) {
      array_push($errors, "Password do not match");
    }

    if(count($errors) == 0) {
      $password = md5($password); //security
      $sql = "INSERT INTO users (username, email, password) 
        VALUES ('$username', '$email', '$password')";
      mysqli_query($db, $sql);
      $_SESSION['username'] = $username;
  	  $_SESSION['success'] = "You are now logged in";
  	  header('location: index.php');
    }
  }
  //login
  if(isset($_POST['login'])) {
    $username = mysql_real_escape_string($_POST['username']);
    $password = mysql_real_escape_string($_POST['password']);

    if(empty($username)) {
      array_push($errors, "Username is required");
    }
    if(empty($password)) {
      array_push($errors, "Password is required");
    }

    if (count($errors) == 0) {
      $password = md5($password);
      $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
      $results = mysqli_query($db, $query);
      if (mysqli_num_rows($results) == 1) {
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "You are now logged in";
        header('location: index.php');
      }else {
        array_push($errors, "Wrong username or password");
      }
    }
  }

  //logout
  if(isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header('location: login.php');
  }

?>