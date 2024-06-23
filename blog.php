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
        exit(header("location: index.php"));
}    
  $array = explode("-",$_COOKIE["user"]);
  $admin = $array[1];
  $user_id = $array[2];
  
  $query = "SELECT * FROM user where id='$user_id'";
          $res = $mysqli->query($query);
          $exist = $res->num_rows;
  
             $row = $res->fetch_assoc();
               $name = $row["name"];

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>yrpreyBlog</title>
        <!-- Favicon-->
        <link rel="icon" href="/assets/img/favicon.svg" title="YRprey">
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body>
        <!-- Responsive navbar-->
       <?php
       if (isset($_COOKIE["user"])) {        
        include("navbar.php");
       }
       ?>
        <!-- Page header with logo and tagline-->
        <header class="py-5 bg-light border-bottom mb-4">
            <div class="container">
                <div class="text-center my-5">
                    <h1 class="fw-bolder"><?php echo $name; ?>, welcome to Blog Home!</h1>
                    <p class="lead mb-0">This is your Blog page</p>
                </div>
            </div>
        </header>
        <!-- Page content-->
        <div class="container">
            <div class="row">
                <!-- Blog entries-->
                <div class="col-lg-8">
                    <!-- Featured blog post-->
<?php

$query = "SELECT * FROM post where user_id='$user_id'";
        $res = $mysqli->query($query);
        $exist = $res->num_rows;

         if ($exist > 0) {

           $row = $res->fetch_assoc();
           $id = $row["id"];
           $data = $row["data"];
           $titulo = $row["titulo"];
           $img = $row["img"];
           $description = $row["description"];           

?>
                    <div class="card mb-4">
                        <a href="#!"><img class="card-img-top" src="<?php echo $img; ?>" alt="..." /></a>
                        <div class="card-body">
                            <div class="small text-muted"><?php echo $data; ?></div>
                            <h2 class="card-title"><?php echo $titulo; ?></h2>
                            <p class="card-text"><?php echo $description; ?></p>
                            <a class="btn btn-primary" href="post.php?id=<?php echo $id; ?>">Read post →</a>
                        </div>
                    </div>
<?php
         }
?>
                    <!-- Nested row for non-featured blog posts-->
                    <div class="row">
                        <div class="row">
                            <!-- Blog post-->
                            <?php

$query = "SELECT * FROM post where user_id='$user_id' AND id <> '$id'";
        $res = $mysqli->query($query);
        $exist = $res->num_rows;

         if ($exist > 0) {

           while ($row = $res->fetch_assoc()) {
           $id = $row["id"];
           $data = $row["data"];
           $titulo = $row["titulo"];
           $img = $row["img"];
           $description = $row["description"];           

?>                             
&nbsp;&nbsp;&nbsp;&nbsp;
                            <div class="card mb-1" style="width: 320px;">
                                <a href="#!"><img class="card-img-top" src="<?php echo $img; ?>" alt="..." /></a>
                                <div class="card-body">
                                    <div class="small text-muted"><?php echo $data; ?></div>
                                    <h2 class="card-title h4"><?php echo $titulo; ?></h2>
                                    <p class="card-text"><?php echo $description; ?></p>
                                    <a class="btn btn-primary" href="post.php?id=<?php echo $id; ?>">Read more →</a>
                                </div>
                            </div>
                            <!-- Blog post-->
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
                    <!-- Pagination-->                    
                </div>
                <!-- Side widgets-->
                <div class="col-lg-4">
                    <!-- Search widget-->
                    <div class="card mb-4">
                        <div class="card-header">Search</div>
                        <div class="card-body">
                        <div class="input-group">
                                <form action="search.php" method="get">
                                <input class="form-control" type="text" name="search" placeholder="Enter search term..." aria-label="Enter search term..." aria-describedby="button-search" />
                                <input type="submit" class="btn btn-primary" id="button-search" value="Search">
                                </form>
                            </div>
                        </div>
                    </div>                    
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
        <!-- Footer-->
        <?php include("footer.php"); ?>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
