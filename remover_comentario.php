<?php
// Verifique se o ID foi enviado via POST

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

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    
    $sql = "DELETE FROM comment WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Envie uma resposta de sucesso
        echo 'Comentário removido com sucesso!';
    } else {
        // Envie uma resposta de erro
        http_response_code(500); // Código de status HTTP 500 - Internal Server Error
        echo 'Erro ao remover o comentário do banco de dados.';
    }

    // Feche a conexão e o statement
    $stmt->close();
    $conn->close();

    // Por exemplo, execute uma consulta SQL para remover o comentário do banco de dados
    
    // Envie uma resposta de sucesso
    echo 'Comentário removido com sucesso!';
} else {
    // Envie uma resposta de erro
    http_response_code(400); // Código de status HTTP 400 - Bad Request
    echo 'Erro: ID do comentário não foi fornecido.';
}
?>
