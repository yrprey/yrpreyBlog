<?php

ob_start();
session_start();
error_reporting(1);

include("database.php");

if (isset($_COOKIE["user"])) {
    if (str_contains($_COOKIE["user"],"admin")) {
      $status = "administrator";
    }
    else {
      $status="";
    }
  }  
  $array = explode("-",$_COOKIE["user"]);
  $admin = $array[1];
  $user_id = $array[2];

  if (isset($_GET["search"])) {
    $search = $_GET["search"];
  }

include("database.php");

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="/assets/img/favicon.svg" title="YRprey">
</head>
<body>
<?php 
       if (isset($_COOKIE["user"])) {        
            include("navbar.php");
       }
       else {
            include("navbar1.php");
       }
?>
    <div class="container mt-5">
        <form class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Type search...">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button">Search</button>
                </div>
            </div>
        </form>        
        <div class="list-group">
        <?php

if (isset($_GET["search"])) {
    print "Your search :".$_GET["search"];
}
$query = "SELECT * FROM post where titulo LIKE '%$search%' OR description LIKE '%$search%' LIMIT 5";
$res = $mysqli->query($query);
$exist = $res->num_rows;

 if ($exist > 0) {
   while ($row = $res->fetch_assoc()) {
     $id = $row["id"];
     $titulo = $row["titulo"];
     $description = substr($row["description"],0,200)."...";

?>            
            <a href="post.php?id=<?php echo $id; ?>" class="list-group-item list-group-item-action">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1"><?php echo $titulo; ?></h5>
                </div>
                <p class="mb-1"><?php echo $description; ?></p>
            </a>
<?php
   }
}
else {
    ?>
    <div   class="container mt-5">
    <div class="card text-center">
        <div class="card-header">
            Sorry :(
        </div>
        <div class="card-body">
            
            <h5 class="card-title mt-3">No results were found</h5>
            <br><br>
        </div>
    </div>
</div>
<?php
}            
?>
            <!-- Adicione mais itens conforme necessário -->
        </div>
        </div>   
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

