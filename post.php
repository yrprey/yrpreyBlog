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

if (isset($_GET["id"])) {
    $id = $_GET["id"];
}
else {
    $id = "0";
}

if (isset($_POST["text"])) {

    $text = $_POST["text"];

    $query = "SELECT * FROM user where id='$user_id'";
                $res = $mysqli->query($query);
                $row = $res->fetch_assoc();
                $name = $row["name"];

    $stmt = $mysqli->prepare("INSERT INTO comment (post_id, user_id, name, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $id, $user_id, $name,$text);
    
    if ($stmt->execute()) {
    }

}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Blog Post - yrpreyBlog</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
        <link rel="icon" href="/assets/img/favicon.svg" title="YRprey">
    </head>
    <body>
        <!-- Responsive navbar-->
<?php
       if (isset($_COOKIE["user"])) {        
        include("navbar.php");
       }
?>
        <!-- Page content-->
        <div class="container mt-5">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Post content-->
                    <article>
                        <!-- Post header-->
                        <header class="mb-4">
                            <!-- Post title-->
                            <?php

$query = "SELECT * FROM post where id='$id'";
        $res = $mysqli->query($query);
        $exist = $res->num_rows;

        if ($exist >0) {
    
             $row = $res->fetch_assoc();
             $img = $row["img"];
             $titulo = $row["titulo"];
             $description = $row["description"];
             $comments = $row["comments"];
             $data = $row["data"];        
             $author = $row["autor"];
             $author_id = $row["user_id"];
          ?>                             
                            <h1 class="fw-bolder mb-1"><?php echo $titulo; ?></h1>
                            <!-- Post meta content-->
                            <div class="text-muted fst-italic mb-2">Posted on <?php echo $data; ?> by <?php echo $author; ?></div>
                            <!-- Post categories-->
                        </header>
                        <!-- Preview image figure-->
                        <figure class="mb-4"><img class="img-fluid rounded" src="<?php echo $img; ?>" alt="..." /></figure>
                        <!-- Post content-->
                        <section class="mb-5">
                            <p class="fs-5 mb-4"><?php echo $description; ?></p>
                        </section>
                    </article>
                    <!-- Comments section-->
                    <section class="mb-5">
                        <div class="card bg-light">
                            <div class="card-body">
                                <!-- Comment form-->
                                 <?php
                                    if (!empty($user_id)) {
                                        ?>
                                <form class="mb-4" action="" method="post"><textarea class="form-control" rows="3" placeholder="Join the discussion and leave a comment!" name="text"></textarea>
                                <p></p>
                                <input type="submit" class="btn btn-primary" id="button-search" value="Post" name="post">
                            </form>
                                
                                <?php
                                    }
                                    ?>
                                <!-- Comment with nested comments-->
<?php

$query = "SELECT * FROM comment where post_id='$id'";
$res = $mysqli->query($query);

while ($row = $res->fetch_assoc()) {
    $id_comment = $row["id"];
$name = $row["name"];
$userid = $row["user_id"];
$comment = $row["comment"];


    ?>
                                    <div class="d-flex mb-4" id="teste-<?php echo $id; ?>">
                                    <div class="d-flex">
                                    <div class="flex-shrink-0"><img class="rounded-circle" src="https://dummyimage.com/50x50/ced4da/6c757d.jpg" alt="..." /></div>
                                    <div class="ms-3">
                                    <div class="fw-bold"><?php echo $name; ?></div>
                                    <?php echo $comment; ?>
                                    </div>
                                    </div>
                                    <?php
                                        if ($user_id == $userid) {
                                            ?>
                                    <a href="#" class="btn-close" aria-label="Close" style="margin-top: -10px;margin-left: 500px;" data-id="<?php echo $id_comment; ?>"></a>
                                    <?php
                                        }
                                    ?>
                                    </div>
<?php
}
?>                                    
                                    </div>
                        <?php
        }
        ?>
                    </section>
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
                    <!-- Categories widget-->
                   
                    <!-- Side widget-->
                    <div class="card mb-4">
                        <div class="card-header">Related Post</div>
                        <div class="card-body">
<?php

$query = "SELECT * FROM post where user_id='$author_id' AND id <> $id LIMIT 5";
$res = $mysqli->query($query);
$exist = $res->num_rows;

 if ($exist > 0) {
   while ($row = $res->fetch_assoc()) {
     $id = $row["id"];
     $titulo = $row["titulo"];
     print "<p><a href='post.php?id=$id'>$titulo</a></p>";
   }
}
?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer-->
        <footer class="py-5 bg-dark">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Website 2023</p></div>
        </footer>
        <!-- Bootstrap core JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

        <script>
$(document).ready(function() {
    $('.btn-close').on('click', function() {
        var id = $(this).data('id');
        
        $.ajax({
            url: 'remover_comentario.php',
            type: 'POST',
            data: { id: id },
            success: function(response) {
                // Lidar com a resposta da página PHP
                alert('Comentário removido com sucesso!');
                $('#teste-' + id).fadeOut('slow', function() {
                    $(this).remove();
                });
            },
            error: function(xhr, status, error) {
                alert('Erro ao remover comentário. Por favor, tente novamente.');
                
            }
        });
    });
});
</script>

        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
    </body>
</html>
