<?php
// Verifica se o ID do post foi enviado via POST
if (isset($_POST['id'])) {
    $postId = $_POST['id'];

include("database.php");

    // Query para remover o post do banco de dados
    $sql = "DELETE FROM post WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $postId);

    if ($stmt->execute()) {
        // Envie uma resposta de sucesso
        echo 'Post removido com sucesso!';
    } else {
        // Envie uma resposta de erro
        http_response_code(500); // Código de status HTTP 500 - Internal Server Error
        echo 'Erro ao remover o post do banco de dados.';
    }

    // Feche a conexão e o statement
    $stmt->close();
    $conn->close();
} else {
    // Envie uma resposta de erro
    http_response_code(400); // Código de status HTTP 400 - Bad Request
    echo 'Erro: ID do post não foi fornecido.';
}
?>
