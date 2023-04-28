<?php 
    require_once('class/config.php');
    require_once('autoload.php');

    $id = $_GET["name"];
    $dadosAntigos = Cadastro::readId($id);
    if(empty($dadosAntigos)){
        $erro_id = "Cadastro não encontrado!";
    }else{
        $nomeAntigo = $dadosAntigos["name"];
        $dataAntiga = new DateTime($dadosAntigos["birth_date"]);
        $dataAntiga->format('d-m-Y');
        $emailAntigo = $dadosAntigos["email"];
        $telefoneAntigo = $dadosAntigos["phone"];
        $fotoAntiga = $dadosAntigos["photo"];
    }

    if(isset($_POST["nome"])&&isset($_POST["data"])&&isset($_POST["email"])&&isset($_POST["telefone"])){
        $nome = limparPost($_POST["nome"]);
        $data = limparPost($_POST["data"]);
        $email = limparPost($_POST["email"]);
        $telefone = limparPost($_POST["telefone"]);
        $fotoNova = $_FILES["foto"];
        echo var_dump($fotoNova);
        echo var_dump($fotoAntiga);
        $erro = [];

        if(empty($nome)||empty($data)||empty($email)||empty($telefone)){
            $erro_geral = "Todos os campos são obrigatórios!";
        }else{
            if (!preg_match("/^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ'\s]+$/",$nome)) {
                $erro["erro_nome"] = "Permitido apenas letras e espaços em branco!";
            }
            
            $dataAtual = strtotime(date('Y-m-d'));
            $dataInserida = strtotime($data);

            if($dataInserida>$dataAtual){
                $erro["erro_data"] = "Data inválida!";
            }else{
                $data = date("d-m-Y", strtotime($data));
            }

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $erro["erro_email"] = "Formato de email inválido!";
            }

            if(!is_numeric($telefone)){
                $erro["erro_telefone"] = "Permitido apenas números!";
            }

            if(empty($fotoNova["name"])){
                $foto = $fotoAntiga;
            }else{
                $tamanhoMaxFoto = 2097152;
                $extensaoPermitida = array("jpg", "png", "jpeg");
                $extensaoFoto = pathinfo($fotoNova["name"], PATHINFO_EXTENSION);
        
                if($fotoNova["size"]>=$tamanhoMaxFoto){
                    $erro["erro_foto"] = "Tamanho máximo 2mb";
                }else{
                    if(in_array($extensaoFoto, $extensaoPermitida)){
                        $pastaImagens = "imagens/";
                        $tempNomeFoto = $_FILES["foto"]["tmp_name"];
                        $novoNomeFoto = uniqid().".$extensaoFoto";
                        if(empty($erro)){
                            move_uploaded_file($tempNomeFoto, $pastaImagens.$novoNomeFoto);
                            $foto = $novoNomeFoto;
                        }
                    }else{
                        $erro["erro_foto"] = "Tipo de arquivo inválido ($extensaoFoto), não foi possivel fazer upload.";
                    }
                }

            }


            if(empty($erro)){
                Cadastro::update($id, $nome, $data, $email, $telefone, $foto);
                header("location: index.php?name=success");
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="header">
        <h2>Case Agenda</h2>
        <ul>
            <li><a href="./index.php">Cadastrar</a></li>
            <li><a href="./list.php">Listar</a></li>
            <li><a href="./actions.php">Ações</a></li>
        </ul>
    </header>
    <p class="ok-msgs"><?php if(isset($sucesso)){echo $sucesso;} ?></p>
    <p class="erro-msg"><?php if(isset($erro_id)){echo $erro_id;} ?></p>
    <p class="erro-msg"><?php if(isset($erro_geral)){echo $erro_geral;} ?></p>
    <div class="main-container">
        <form action="" method="POST" class="container" enctype="multipart/form-data">
            <h1>Atualizar Dados</h1>
            <div class="input-container">
                <label for="nome">Nome:</label>
                <input <?php if(isset($erro["erro_nome"])||isset($erro_geral)){echo "class='erro-input'";} ?>
                type="text" name="nome" id="" placeholder="Nome completo" required <?php if(isset($nomeAntigo)){echo "value='".$nomeAntigo."'";} ?>>
                <p class="erro-msg">
                    <?php if(isset($erro["erro_nome"])){echo $erro["erro_nome"];}?>
                </p>
            </div>
            <div class="input-container">
                <label for="data">Data de nascimento:</label>
                <input <?php if(isset($erro["erro_data"])||isset($erro_geral)){echo "class='erro-input'";} ?> type="date" name="data" id="" required <?php if(isset($dataAntiga)){echo "value='".$dataAntiga->format("Y-m-d")."'";} ?>>
                <p class="erro-msg">
                    <?php if(isset($erro["erro_data"])){echo $erro["erro_data"];}?>
                </p>
            </div>
            <div class="input-container">
                <label for="email">E-mail:</label>
                <input <?php if(isset($erro["erro_email"])||isset($erro_geral)){echo "class='erro-input'";} ?> type="email" name="email" id="" placeholder="email@email.com" required <?php if(isset($emailAntigo)){echo "value='".$emailAntigo."'";} ?>>
                <p class="erro-msg">
                    <?php if(isset($erro["erro_email"])){echo $erro["erro_email"];}?>
                </p>
            </div>
            <div class="input-container">
                <label for="">Telefone:</label>
                <input <?php if(isset($erro["erro_telefone"])||isset($erro_geral)){echo "class='erro-input'";} ?> type="text" name="telefone" id="" placeholder="Apenas números (ex: 11999999999)" required maxlength="11" <?php if(isset($telefoneAntigo)){echo "value='".$telefoneAntigo."'";} ?>>
                <p class="erro-msg">
                    <?php if(isset($erro["erro_telefone"])){echo $erro["erro_telefone"];}?>
                </p>
            </div>
            <div class="input-container">
                <label for="">Foto de perfil:</label>
                <input <?php if(isset($erro["erro_foto"])||isset($erro_geral)){echo "class='erro-input'";} ?> type="file" name="foto" id="foto" placeholder="Tamanho máximo 2MB">
                <p class="erro-msg">
                    <?php if(isset($erro["erro_foto"])){echo $erro["erro_foto"];}?>
                </p>
            </div>
            <div class="input-container">
                <button type="submit" name="enviar" value="ok" id="enviar">Atualizar</button>
            </div>
        </form>
    </div>
</body>
</html>