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
  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de Publicação</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .custom-file-input ~ .custom-file-label::after {
            content: "Procurar";
        }
        .custom-file-label::after {
            content: "Selecionar arquivo";
        }
        #progressBar {
            display: none;
        }
    </style>
    <link rel="icon" href="/assets/img/favicon.svg" title="YRprey">
</head>
<body>
<?php
include("navbar.php");
?>
    <div class="container mt-5">
        <h2>Nova Publicação</h2>
        <form id="uploadForm" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Título</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Descrição</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="image">Upload de Imagem</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="image" name="image" required>
                    <label class="custom-file-label" for="image">Escolher arquivo</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submeter</button>
            <div class="progress mt-3" id="progressBar">
                <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
            </div>
        </form>
        <div id="result" class="mt-3"></div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Atualiza o nome do arquivo na label do botão personalizado
            $('#image').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').html(fileName);
            });

            $("#uploadForm").on('submit', function(event) {
                event.preventDefault();
                
                var formData = new FormData(this);
                
                $("#progressBar").show();
                
                $.ajax({
                    url: 'upload.php', // Substitua pelo URL do servidor onde os dados serão enviados
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = evt.loaded / evt.total;
                                percentComplete = parseInt(percentComplete * 100);
                                $(".progress-bar").css("width", percentComplete + "%");
                                $(".progress-bar").attr("aria-valuenow", percentComplete);
                                $(".progress-bar").text(percentComplete + "%");
                            }
                        }, false);
                        return xhr;
                    },
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('Referer', document.referrer);
                    },
                    success: function(response) {
                        $("#progressBar").hide();
                        $(".progress-bar").css("width", "0%");
                        $(".progress-bar").attr("aria-valuenow", 0);
                        $(".progress-bar").text("0%");
                        $("#result").html('<div class="alert alert-success">'+response+'</div>');
                    },
                    error: function(xhr, status, error) {
                        $("#progressBar").hide();
                        $(".progress-bar").css("width", "0%");
                        $(".progress-bar").attr("aria-valuenow", 0);
                        $(".progress-bar").text("0%");
                        $("#result").html('<div class="alert alert-danger">Erro ao enviar publicação. Por favor, tente novamente.</div>');
                    }
                });
            });
        });
    </script>
</body>
</html>
