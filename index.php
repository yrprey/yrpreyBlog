<?php
  ob_start();
  session_start();
  error_reporting(1);

  include("database.php");
  if (isset($_COOKIE["user"])) {
    exit(header("location: dashboard.php"));
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication Page</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .main-content {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: calc(100vh - 56px); /* 100vh - height of the navbar */
            padding-top: 20px;
        }
        .text-container, .login-container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .text-container {
            flex: 1;
            margin-right: 15px;
        }
        .login-container {
            flex: 1;
            max-width: 400px;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #007bff;
        }
    </style>
    <link rel="icon" href="/assets/img/favicon.svg" title="YRprey">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#"><img src="assets/img/logo.webp" width="180"></a>
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
        </ul>
        <form class="form-inline my-2 my-lg-0" action="" method="post">
            <input class="form-control mr-sm-2" type="email" placeholder="Email" aria-label="Email" name="email">
            <input class="form-control mr-sm-2" type="password" placeholder="Password" aria-label="Password" name="password">
            <input class="btn btn-outline-success my-2 my-sm-0" type="submit" value="Login" name="login">
        </form>
    </div>
</nav>

<div class="container main-content">
    <div class="text-container">
    <?php

if (isset($_POST["create"]))  {

    $name = $_POST["name"];
    $email = $_POST["email"];     
    $password = $_POST["password"];

    $query  = "SELECT * FROM user where email = '$email'"; 

    $results = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));
  
    $rows = mysqli_num_rows($results);

    if ($rows == 0) {
  
    $sql = "INSERT INTO user (name,email,password,permission) VALUES (?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    
    // Verifique se a preparação da consulta foi bem-sucedida
    if ($stmt) {
        // Defina os valores dos parâmetros e seus tipos
        $qtde = 1; // Valor numérico
        
        // Vincule os parâmetros à instrução preparada
        $permission="user";
        $stmt->bind_param("ssss", $name, $email, $password, $permission);
  
    if ($stmt->execute()) {
        $tempo_expiracao = time() + 3600;
        $permission = "user";
        $user_id = $stmt->insert_id;
        $cookie =  $tempo_expiracao."-".$permission."-".$user_id;
    
        setcookie("user", $cookie, $tempo_expiracao);
    
        exit(header("location: dashboard.php"));
    }   
    }
  }
  else {
    print '<br><div class="alert alert-danger" role="alert">Impossible to include the record. Duplicate data.</div>';
  }
  }
  ?>        
        <h2>Welcome to Blog!</h2>
        <p>This system is based on a Blog but includes vulnerabilities for pentesters, application security professionals, and interested parties to identify and exploit vulnerabilities, such as:
<br>
<li>SQL Injection</li>
<li>XSS</li>
<li>LFI etc.</li>
    </div>
    <div class="login-container">

    <?php

if (isset($_POST["login"])) {
  $email = $_POST["email"];
  $password = $_POST["password"];

$query  = "SELECT * FROM user where email='" . $email . "'";

$result = mysqli_query($mysqli, $query) or die(mysqli_error($mysqli));

$row = mysqli_num_rows($result);

if (empty($_POST["email"]) AND empty($_POST["password"])) {

    print '    <div class="alert alert-danger" role="alert">
    Please, you need credentials!
  </div>';        

}
elseif ($row > 0) {

    $q1  = "SELECT * FROM user where email='" . $email . "' AND password='" . $password . "'";

    $res1 = mysqli_query($mysqli, $q1) or die(mysqli_error($mysqli));

    $exist = mysqli_num_rows($res1);

    if ($exist > 0) {

        $rw = mysqli_fetch_assoc($res1);
    
        $user_id = $rw["id"];
        $permission = $rw["permission"];

        $tempo_expiracao = time() + 3600;
        $cookie =  $tempo_expiracao."-".$permission."-".$user_id;

        setcookie("user", $cookie, $tempo_expiracao);

        exit(header("location: dashboard.php?id=$user_id"));
    }
    else {
        print '    <div class="alert alert-danger" role="alert">
        Invalid credentials!
      </div>';        

    }

}
else {
  print '    <div class="alert alert-danger" role="alert">
  Invalid e-mail!
</div>';
} 

}
?>
        <h2 class="text-center">Create Account</h2>
        <form action="" method="POST"   >
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter name...">
            </div>
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email...">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password...">
            </div>            
            <input type="submit" class="btn btn-primary btn-block" value="Create Account" name="create">
            <div class="text-center mt-3">
            </div>
        </form>
    </div>
</div>

<script src="js/bootstrap-3.3.5.js"></script>
<script src="js/jquery-1.5.1.js"></script>
<script src="js/lodash-3.9.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>

</body>
</html>
