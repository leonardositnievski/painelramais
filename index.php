<?php
session_start();
include('App/Class/RamaisClass.php');
include('App/Class/UsuariosClass.php');

if(isset($_SESSION['login'])) {
    $obj = new Ramal();
    if (isset($_POST['data']) || isset($_GET['search']) || isset($_GET['status'])) {
        $search =  $_GET['search'];
        $status =  $_GET['status'];
        $filtro = [$search, $status];
        echo json_encode($obj->novosRamais($status, $search));
    } 
    else if (isset($_GET['logout'])) {
        $user = new Usuario();
        $user->logout();
    }
    else {
        echo $obj->ramais();
    }
} else if(isset($_POST['cadastro'])) {
    $user = new Usuario();
    $data = $_POST['cadastro'];
    $user->cadastrar($data);
}  else if(isset($_POST['login'])) {
    $user = new Usuario();
    $data = $_POST['login'];
    $user->login($data);
}  
else {
   echo 'Unauthenticated';
}
