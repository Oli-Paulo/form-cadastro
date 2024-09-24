<?php
include 'Connect.php';
include 'Usuario.php';



if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['formMode']) && $_POST['formMode'] === 'add' ) {
    
    $usuarios = new Usuario(
        nome: $_POST['nome'],
        email: $_POST['email'],
        telefone: $_POST['telefone'],
        cpf: $_POST['cpf'],
        senha: $_POST['senha'],
        confirmSenha: $_POST['confirmSenha']
    );
    if ($usuarios->inserir()) {
        header(header: 'Location: '.$_POST['path'].'?mensagem=sucess');
    } else {
        header(header: 'Location: '.$_POST['path'].'?mensagem=' . $usuarios->getFail());
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $usuarios = new Usuario();
    if (
        isset($_GET['id']) && isset($_GET['action'])
        && $_GET['action'] == 'delete'
    ) {
        if ($usuarios->delete(id: intval(value: $_GET['id']))) {
            header(header: 'Location: system.php?mensagem=success');
        } else {
            header(header: 'Location: system.php?mensagem=error');
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica se a requisição é para atualização
    if (isset($_POST['formMode']) && $_POST['formMode'] === 'edit' && isset($_POST['id'])) {
        $usuarios = new Usuario();
        $usuarios->setId(id: (int)$_POST['id']);
        $usuarios->setNome(nome: $_POST['nome']);
        $usuarios->setTelefone(telefone: $_POST['telefone']);
        $usuarios->setEmail(email: $_POST['email']);
        $usuarios->setCpf(cpf: $_POST['cpf']);
        $usuarios->setSenha(senha: $_POST['senha']);
        $usuarios->setConfirmsenha(confirmSenha: $_POST['confirmSenha']);

        if ($usuarios->update()) {
            header(header: 'Location: '.$_POST['path'].'?mensagem=success');
            exit;
        } else {
            header(header: 'Location: '.$_POST['path'].'?mensagem=error&detail=' . urlencode(string: $usuarios->getFail()));
            exit;
        }
    } elseif (isset($_POST['formMode']) && $_POST['formMode'] === 'add') {
        // Verifica se a requisição é para inserção
        $usuarios = new Usuario(nome: $_POST['nome'], email: $_POST['email'], telefone: $_POST['telefone'], cpf: $_POST['cpf'], senha: $_POST['senha'], confirmSenha: $_POST['confirmSenha']);
        
        if ($usuarios->inserir()) {
            header(header: 'Location: ' . $_POST['path'] . '?mensagem=success');
            exit;
        } else {
            header(header: 'Location: ' . $_POST['path'] . '?mensagem=error&detail=' . urlencode(string: $usuarios->getFail()));
            exit;
        }
    } 
} 