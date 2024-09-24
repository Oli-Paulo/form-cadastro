<?php

class Usuario
{
    private $id;
    private $nome;
    private $email;
    private $telefone;
    private $cpf;
    private $senha;
    private $confirmSenha;
    private const ENTIDADE = 'usuarios';
    private $fail;

    public function getFail(): string{
        return $this->fail;
    }

    public function __construct($nome = NULL, $email = NULL, $telefone = NULL,
    $cpf = NULL, $senha = NULL, $confirmSenha = NULL) {

        $this->nome = $nome;
        $this->email = $email;
        $this->telefone = $telefone;
        $this->cpf = $cpf;
        $this->senha = $senha;
        $this->confirmSenha = $confirmSenha;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }
    public function setNome($nome): void {
        $this->nome = $nome;
    }
    public function setTelefone($telefone): void {
        $this->telefone = $telefone;
    }
    public function setEmail($email): void {
        $this->email = $email;
    }
    public function setCpf($cpf): void {
        $this->cpf = $cpf;
    }
    public function setSenha($senha): void {
        $this->senha = $senha;
    }
    public function setConfirmsenha($confirmSenha): void {
        $this->confirmSenha = $confirmSenha;
    }

    //Método de inserir no banco de dados
    public function inserir(): bool {

        if ($this->verificacao()) {
            return false;
        }
        if ($this->registroUnico()) {
            $this->fail = 'Já existe um registro com este e-mail ou CPF.';
            return false;
        }
        if (!$this->verificarSenha()) {
            $this->fail = 'Senha não coinciden.';
            return false;
        }

        // Implementar a conexão com o banco de dados
        $pdo = Connect::getConnection();

        // Inserir os dados do objeto na tabela 'usuarios'
        $res = $pdo -> prepare(query: "INSERT INTO " . self::ENTIDADE .
        "(nome, email, telefone, cpf, senha) VALUES (:nome, :email, :telefone, :cpf, :senha)");
        $res->bindValue(param: ':nome', value: $this->nome);
        $res->bindValue(param: ':email', value: $this->email);
        $res->bindValue(param: ':telefone', value: $this->telefone);
        $res->bindValue(param: ':cpf', value: $this->cpf);
        $res->bindValue(param: ':senha', value: $this->senha);
        
        // Retornar o ID do novo registro
        return $res->execute();
    }
    // Método para verificar campos obrigatorios
    public function verificacao(): bool {
        if (empty($this->nome) || empty($this->email) || empty($this->telefone) || 
        empty($this->cpf) || empty($this->senha)) {
            $this->fail = 'Todos os campos são obrigatórios.';
            return true;
        }
        if (strlen(string: $this->nome) > 255 || strlen(string: $this->email) > 255 ||
        strlen(string: $this->telefone) > 20 || strlen(string: $this->cpf) > 14 ||
        strlen(string: $this->senha) > 255) {
            $this->fail = 'Existem caracteres excedentes.';
            return true;
        }
        if (strlen(string: $this->senha) < 8) {
            $this->fail = 'A senha precisa ter no mínimo 8 caracteres.';
            return true;
        }
        return false;
    }
    public function registroUnico(): bool
    {
        $pdo = Connect::getConnection();
        $res = $pdo->prepare(query: "SELECT * FROM ".self::ENTIDADE." WHERE email = :email OR cpf = :cpf");
        $res->bindValue(param: ':email', value: $this->email);
        $res->bindValue(param: ':cpf', value: $this->cpf);
        $res->execute();
        if ($res->rowCount() > 0) {
            return true;
        }
        return false;
    }
    public function verificarSenha(): bool
    {
        if ($this->senha == $this->confirmSenha){
            return true;
        }
        return false;
    }
    public static function listar(): array
    {
        $pdo = Connect::getConnection(); //boter a conexao com o banco de dados
        $res = $pdo->query("SELECT * FROM " . self::ENTIDADE); //execuar a consulta no banco de dados
        return $res->fetchAll(PDO::FETCH_ASSOC); //retornar um array ass da consulta feita
    }
    public function listarId($id): array
    {
        $pdo = Connect::getConnection();
        $res = $pdo->prepare(query: "SELECT * FROM " . self::ENTIDADE . " WHERE id = :id");
        $res->bindValue(param: ':id', value: $id);
        $res->execute();
        return $res->fetch(mode: PDO::FETCH_ASSOC);
    }
    
    //Metodo para verificar email e senha do login
    public function login(): bool
    {
        $pdo = Connect::getConnection();
        $res = $pdo->prepare(query: "SELECT * FROM ". self::ENTIDADE. " WHERE email = :email AND senha = :senha");
        $res->bindValue(param: ':email', value: $this->email);
        $res->bindValue(param: ':senha', value: $this->senha);
        $res->execute();
       
        if ($res->rowCount() > 0) {
            return true;
        }
        else {
            $this->fail = 'Email ou senha inválidos ';
            return false;
        }
       
    }
    public function delete(int $id): bool
    {
        $pdo = Connect::getConnection();
        $res = $pdo->prepare(query: "DELETE FROM " . self::ENTIDADE . " WHERE id = :id");
        $res->bindValue(param: ':id', value: $id);
        return $res->execute();
    }
    //Método para atualizar as pessoas
    public function update(): bool {
        $pdo = Connect::getConnection();
    
        // Verifica se todos os campos obrigatórios estão preenchidos
        if (empty($this->nome) || empty($this->email) || empty($this->telefone) || empty($this->cpf) || empty($this->id)) {
            $this->fail = 'Todos os campos devem estar preenchidos';
            return false; // Abortando o update no banco de dados
        }
    
        $res = $pdo->prepare(query: "UPDATE " . self::ENTIDADE . " SET nome = :nome, email = :email, telefone = :telefone, cpf = :cpf WHERE id = :id");
        $res->bindValue(param: ':nome', value: $this->nome);
        $res->bindValue(param: ':telefone', value: $this->telefone);
        $res->bindValue(param: ':email', value: $this->email);
        $res->bindValue(param: ':cpf', value: $this->cpf);
        $res->bindValue(param: ':id', value: $this->id);
        return $res->execute();
    }
}