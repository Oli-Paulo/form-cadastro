<?php
include 'Connect.php';
include 'Usuario.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $login = new Usuario(email: $_POST['email'], senha: $_POST['senha']);
    if ($login->login()){
        header(header:'Location: system.php');
    } else {
        header(header:'Location: index.php?mensagem='.$login->getFail());
    }
}