<?php
// Verifique se o ID foi enviado via POST
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    
    // Faça o que for necessário para remover o comentário com o ID fornecido
    // Por exemplo, execute uma consulta SQL para remover o comentário do banco de dados
    
    // Envie uma resposta de sucesso
    echo 'Comentário removido com sucesso!';
} else {
    // Envie uma resposta de erro
    http_response_code(400); // Código de status HTTP 400 - Bad Request
    echo 'Erro: ID do comentário não foi fornecido.';
}
?>
