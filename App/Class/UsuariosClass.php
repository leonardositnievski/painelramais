<?php

class Usuario {

    private function connect() {
        $con = mysqli_connect('localhost', 'root', 'root', 'callcenter');

        
        if ($con->connect_error) {
            die("Connection failed: " . $con->connect_error);
        }

        mysqli_select_db($con, 'callcenter');

        return $con;
    }
    
    public function cadastrar($data) {
        $con = $this->connect();    
        $nome = $data['nome'];
        $email = $data['email'];
        $senha = md5($data['senha']);
        if($this->validaCadastro($data) == true) {
            $sql = "INSERT INTO usuarios (nome, email, senha)
            VALUES ('$nome', '$email', '$senha')";
            $query = $con->query($sql); 
            $con->close();
            if (!$query) {
                echo (' Não foi possível salvar o usuário'); 
                die;
            }
            $_SESSION['login'] = $email;
            echo json_encode(['sucesso' => "cadastrado com sucesso"]);
        }
    }

    private function validaCadastro($data) {
        $con = $this->connect();
        $email = $data['email'];
        $query = "SELECT email FROM usuarios where email = '$email'";

        $result = mysqli_query($con, $query);
        $r = mysqli_fetch_array($result);
        if($r !== null) {
            echo json_encode(['erro' => 'não foi possível realizar o cadastro. Este email já está cadastrado no nosso sistema', 'campo' => 'email']);
            return false;
        } else if($data['nome'] == null) {
            echo json_encode(['erro' => 'não foi possível realizar o cadastro. O campo nome deve ser preenchido', 'campo' => 'nome']);
            return false;
        }
        else if($data['email'] == null) {
            echo json_encode(['erro' => 'não foi possível realizar o cadastro. O campo email deve ser preenchido', 'campo' => 'email']);
            return false;
        }
        else if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['erro' => 'não foi possível realizar o cadastro. O campo email foi preenchido incorretamente', 'campo' => 'email']);
            return false;
        }
        else if($data['senha'] == null) {
            echo json_encode(['erro' => 'não foi possível realizar o cadastro. O campo senha deve ser preenchido', 'campo' => 'senha']);
            return false;
        }
        else if((strlen($data['senha'])) < 8) {
            echo json_encode(['erro' => 'não foi possível realizar o cadastro. O campo senha deve ter no mínimo 8 caracteres', 'campo' => 'senha']);
            return false;
        } else {
            return true;
        }
    }

    public function login($data) {
        $con = $this->connect();
        $email = $data['email'];
        $senha = md5($data['senha']);
        if($this->validaLogin($data) == true) {
            $query = "SELECT email FROM usuarios where email = '$email' and senha = '$senha'";
            $result = mysqli_query($con, $query);
            $r = mysqli_fetch_array($result);
            if($r == null) {
                echo json_encode(['invalido' => 'não foi possível efetuar o login. Email ou senha inválidos']);
            } else {
                $_SESSION['login'] = $email;
                echo json_encode(['sucesso' => "logado com sucesso"]);
            }
        }
    }

    private function validaLogin($data) {

        if($data['email'] == null) {
            echo json_encode(['erro' => 'não foi possível realizar o cadastro. O campo email deve ser preenchido', 'campo' => 'emailLogin']);
            return false;
        }
        else if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['erro' => 'não foi possível realizar o cadastro. O campo email foi preenchido incorretamente', 'campo' => 'emailLogin']);
            return false;
        }
        else if($data['senha'] == null) {
            echo json_encode(['erro' => 'não foi possível realizar o cadastro. O campo senha deve ser preenchido', 'campo' => 'senhaLogin']);
            return false;
        } else {
            return true;
        }
    }

    public function logout() {
        unset($_SESSION['login']);
        echo json_encode(['sucesso' => "deslogado com sucesso"]);
    }
}