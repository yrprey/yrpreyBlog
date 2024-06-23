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

include("database.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Post - YrpreyBlog</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="/assets/img/favicon.svg" title="YRprey">
</head>
<?php
include("navbar.php");
?>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">My Posts</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php

$query = "SELECT * FROM post where user_id='$user_id'";
$res = $mysqli->query($query);
$exist = $res->num_rows;

 if ($exist > 0) {
   while ($row = $res->fetch_assoc()) {
     $id = $row["id"];
     $titulo = $row["titulo"];
     $data = $row["data"];
 
?>                
                <tr>
                    <td><?php echo $titulo; ?></td>
                    <td><?php echo $data; ?></td>
                    <td>
                        <button type="button" class="btn btn-danger btn-remove-post" data-post-id="<?php echo $id; ?>">Remove</button>
                    </td>
                </tr>
<?php
  }
}
?>                
                <!-- Adicione mais linhas conforme necessário -->
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.btn-remove-post').on('click', function() {
                var postId = $(this).data('post-id');
                $.ajax({
                    url: 'remover_post.php',
                    type: 'POST',
                    data: { id: postId },
                    success: function(response) {
                        alert('Post removido com sucesso!');
                        location.reload(); // Atualiza a página após remover o post
                    },
                    error: function(xhr, status, error) {
                        alert('Erro ao remover post. Por favor, tente novamente.');
                    }
                });
            });
        });
    </script>
</body>
</html>
