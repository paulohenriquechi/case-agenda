<?php 
        define('SERVIDOR', 'localhost');
        define('USUARIO', 'root');
        define('SENHA', '');
        define('BANCO', 'agenda');
    
        function limparPost($dado){
            $dado = trim($dado);
            $dado = stripslashes($dado);
            $dado = htmlspecialchars($dado);
            return $dado;
        }
?>