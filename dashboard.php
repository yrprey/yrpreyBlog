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
  else {
    exit(header("location: dashboard.php"));
  }  
  $array = explode("-",$_COOKIE["user"]);
  $admin = $array[1];
  $user_id = $array[2];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - yrpreyBlog</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="/assets/img/favicon.svg" title="YRprey">
</head>
<body>
    <!-- Navbar -->
<?php
include("navbar.php")
?>
    <!-- Main content -->
    <div class="container mt-5">
        <div class="row">
            <!-- Cards in the center -->
            <div class="col-md-8">
                <div class="card-deck">
<?php

$query = "SELECT * FROM post where user_id='$user_id'";
        $res = $mysqli->query($query);
        $exist = $res->num_rows;

         if ($exist > 0) {
           while ($row = $res->fetch_assoc()) {
             $img = $row["img"];
             $titulo = $row["titulo"];
             $description = $row["description"];
             $comments = $row["comments"];
          ?>
                    <div class="card">
                        <img src="uploads/<?php echo $img; ?>" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $titulo; ?></h5>
                            <p class="card-text"><?php echo $description; ?>.</p>
                        </div>
                    </div>
            <?php
                        }
            }
else {
    ?>
 <div   class="container mt-5">
        <div class="card text-center">
            <div class="card-header">
                Message for your
            </div>
            <div class="card-body">
                
                <h5 class="card-title mt-3">You don't have any posts on your blog.</h5>
                <br><br>
                <a href="createPost.php" class="btn btn-success">Start now</a>
            </div>
        </div>
    </div>
<?php        
}            
            ?>

                </div>
            </div>
            <!-- Right side divs -->
            <div class="col-md-4">
            <a href="createPost.php" class="btn btn-success">Create Post</a>
            <a href="managePost.php" class="btn btn-primary">Manage Post</a><br><br>
                <div class="card mb-3">
                    <div class="card-header">
                        Post List
                    </div>
                    <div class="card-body">
                    <?php
$user_id="";
$query = "SELECT * FROM post where user_id='$user_id'";
        $res = $mysqli->query($query);
        $exist = $res->num_rows;

         if ($exist > 0) {
           while ($row = $res->fetch_assoc()) {
             $img = $row["img"];
             $titulo = $row["titulo"];
             $description = $row["description"];
             $comments = $row["comments"];
          ?>
                 <p class="card-text">Content of the first div.</p>
        <?php
           }
        }
        else {
            print '<p class="card-text">No exist posts</p>';
        }
        ?>
                    </div>
                </div>            
            </div>
        </div>
        </div>
    
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
