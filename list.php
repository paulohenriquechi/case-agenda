<?php 
    require('class/Cadastro.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List</title>
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
    <div class="search-bar">
        <h2 class="search-bar-title">Pessoas Cadastradas</h2>
        <form action="" method="POST" class="search-bar-form">
        <input type="text" name="pesquisar" id="" placeholder="Digite o nome">
        <button type="submit">Pesquisar</button>
        </form>
    </div>
    <div class="grid-container">
        <?php
            if(isset($_POST["pesquisar"])){
                if(!empty($_POST["pesquisar"])){
                    $dados = Cadastro::readFilter($_POST["pesquisar"]);
                    if(!empty($dados)){
                        foreach($dados as $dado => $info){
                            $nome = $info["name"];
                            $data = $info["birth_date"];
                            $email = $info["email"];
                            $telefone = $info["phone"];
                            $pathFoto = $info["photo"];
                            echo "
                                <div class='card'>
                                    <p>$nome</p>
                                    <img src='imagens/$pathFoto' alt=''>
                                    <p>$data</p>
                                    <p>$email</p>
                                    <p>$telefone</p>
                                </div>";
                        }
                    }else{
                        echo "<div class='card no-result'><h1>A busca não retornou resultados!!</h1></div>";
                    }
                }
            }else{
                $dados = Cadastro::read();
                if(count($dados)>0){
                    foreach($dados as $dado => $info){
                        $nome = $info["name"];
                        $data = $info["birth_date"];
                        $email = $info["email"];
                        $telefone = $info["phone"];
                        $pathFoto = $info["photo"];
    
                        echo "
                            <div class='card'>
                                <p>$nome</p>
                                <img src='imagens/$pathFoto' alt='$nome'>
                                <p>$data</p>
                                <p>$email</p>
                                <p>$telefone</p>
                            </div>";
                    }
                }else{
                    echo "<div class='card no-result'><h2>Agenda sem cadastros!</h2></div>";
                }
            }
        ?>
        </div>
    </div>
</html>
