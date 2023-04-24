<?php 
    require_once('db.php');
    class Cadastro extends DB{
        protected string $tabela = "users";
        
        function __construct(
            public string $nome, 
            public string $data, 
            public string $email, 
            public string $telefone, 
            public array $fotoInfo = [],
            public array $erro = [] ){
        }

        function cadastrar(){
            if (!preg_match("/^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ'\s]+$/",$this->nome)) {
                $this->erro["erro_nome"] = "Permitido apenas letras e espaços em branco!";
            }
            $dataAtual = strtotime(date('Y-m-d'));
            $dataInserida = strtotime($this->data);

            if($dataInserida>$dataAtual){
                $this->erro["erro_data"] = "Data inválida!";
            }else{
                $this->data = date("d-m-Y", strtotime($this->data));
            }

            if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
                $this->erro["erro_email"] = "Formato de email inválido!";
            }

            if(!is_numeric($this->telefone)){
                $this->erro["erro_telefone"] = "Permitido apenas números!";
            }

            $tamanhoMaxFoto = 2097152;
            $extensaoPermitida = array("jpg", "png", "jpeg");
            $extensaoFoto = pathinfo($this->fotoInfo["name"], PATHINFO_EXTENSION);
    
            if($this->fotoInfo["size"]>=$tamanhoMaxFoto){
                $this->erro["erro_foto"] = "Tamanho máximo 2mb";
            }else{
                if(in_array($extensaoFoto, $extensaoPermitida)){
                    $pastaImagens = "imagens/";
                    $tempNomeFoto = $_FILES["foto"]["tmp_name"];
                    $novoNomeFoto = uniqid().".$extensaoFoto";
                    if(empty($this->erro)){
                        move_uploaded_file($tempNomeFoto, $pastaImagens.$novoNomeFoto);
                        $this->insert($novoNomeFoto);
                    }
                }else{
                    $this->erro["erro_foto"] = "Tipo de arquivo inválido ($extensaoFoto), não foi possivel fazer upload.";
                }
            }
        }
        
        public function insert($novoNomeFoto){
            $sql = "INSERT INTO $this->tabela VALUES (null, ?, ?, ?, ?, ?)";
            $sql = DB::prepare($sql);
            $sql->execute(array($this->nome, $this->data, $this->email, $this->telefone, $novoNomeFoto));
        }

        public static function delete($id){
            $sql = "DELETE FROM users WHERE id=?";
            $sql = DB::prepare($sql);
            $sql->execute(array($id));
        }

        public static function read(){
            $sql = "SELECT * FROM users ORDER BY name";
            $sql = DB::prepare($sql);
            $sql->execute();
            $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $dados;
        }
        public static function readFilter($data){
            $sql = "SELECT * FROM users WHERE name LIKE '%$data%' ORDER BY name";
            $sql = DB::prepare($sql);
            $sql->execute();
            $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
            return $dados;
        }

        public static function readId($id){
            $sql = "SELECT * FROM users WHERE id=? LIMIT 1";
            $sql = DB::prepare($sql);
            $sql->execute(array($id));
            $dados = $sql->fetch(PDO::FETCH_ASSOC);
            return $dados;
        }

        public static function update($id, $nome, $data, $email, $telefone, $foto){
            $sql = "UPDATE users SET name=?, birth_date=?, email=?, phone=?, photo=? WHERE id=? LIMIT 1";
            $sql = DB::prepare($sql);
            $sql->execute(array($nome, $data, $email, $telefone, $foto, $id));
        }
    }
?>