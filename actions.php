<?php 
    require('class/Cadastro.php');
    if(isset($_POST["editar"])){
        $id = $_POST["editar"];
        header("location: update.php?name=$id");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actions</title>
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
    <?php 
        if(isset($_POST["remover"])){
            Cadastro::delete($_POST["remover"]);
        }
    ?>
    <p class="ok-msgs"><?php if(isset($_POST["remover"])){echo "Cadastro removido com sucesso!";}?></p>
    <div class="action-container">
    <div class="table-header"><h1>Dashboard</h1></div>
        <table>
            <tr>
                <th>Nome</th>
                <th>Data de nascimento</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Foto</th>
                <th>Ações</th>
            </tr>
            <?php
                $dados = Cadastro::read();
                if(!empty($dados)){
                    foreach($dados as $dado => $info){
                        $id = $info["id"];
                        $nome = $info["name"];
                        $data = $info["birth_date"];
                        $email = $info["email"];
                        $telefone = $info["phone"];
                        $pathFoto = $info["photo"];
                        echo "
                            <tr class='pessoa'>
                                <td>$nome</td>
                                <td>$data</td>
                                <td>$email</td>
                                <td>$telefone</td>
                                <td><img class='img-dash' src='imagens/$pathFoto' alt=''></td>
                                <td>
                                    <form method='POST' class='actions-menu'>
                                        <button class='editar' type='submit' value='$id' name='editar'>Editar</button>
                                        <button class='cancelar' type='submit' value='$id' name='remover' >Remover</button>
                                    </form>
                                </td>
                            </tr>
                        ";
                    }
                }else{
                    echo "<h2 class='card no-result'>Agenda sem cadastros</h2>";
                }
            ?>
        </table>
    </div>
</body>
</html>