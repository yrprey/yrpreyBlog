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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['image']) && isset($_POST['title']) && isset($_POST['description'])) {
        $title = $_POST['title'];
        $description = $_POST['description'];
        $image = $_FILES['image'];

        // Verifique se houve erro no upload
        if ($image['error'] === UPLOAD_ERR_OK) {

            $uploadFile = $uploadDir . basename($image['name']);

            // Move o arquivo para o diretório de uploads
            if (move_uploaded_file($image['tmp_name'], $uploadFile)) {

                $query = "SELECT * FROM user where id='$user_id'";
                $res = $mysqli->query($query);
                $row = $res->fetch_assoc();
                $name = $row["name"];
                $data = date('l jS \of F Y');
                // Inserir dados no banco de dados
                $comments=0;
                $stmt = $mysqli->prepare("INSERT INTO post (user_id, autor, data, titulo, img, description, comments) VALUES (?, ?, ?, ?, ?, ?,?)");
                $stmt->bind_param("issssss", $user_id, $name, $data, $title, $uploadFile,$description,$comments);
                
                if ($stmt->execute()) {
                    $post_id = $stmt->insert_id;                    
                    echo '<a href="post.php?id='.$post_id.'">You post: '.$post_id.'</a>';
                } else {
                    echo 'Erro ao inserir os dados no banco de dados: ' . $stmt->error;
                }

                $stmt->close();
            } else {
                echo 'Erro ao mover o arquivo enviado.';
            }
        } else {
            echo 'Erro no upload do arquivo.';
        }
    } else {
        echo 'Faltam dados no formulário.';
    }
} else {
    echo 'Método não permitido.';
}

$mysqli->close();
?>
