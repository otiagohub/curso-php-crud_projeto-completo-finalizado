<?php
// Parâmetros de acesso ao servidor de banco de dados (SGBD)
$servidor = "localhost";
$usuario = "root";
$senha = "";
$banco = "flybynight_estoque";

// Script de conexão
try { 
    // Configurando o DSN (Data Source Name)
    $conexao = new PDO("mysql:host=$servidor;dbname=$banco;charset=utf8", $usuario, $senha);

    // Habilitando o lançamento de erros e exceções
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // echo "Conexão feita com sucesso!";'
} catch (Throwable $erro) {
    die("Falha na conexão: ".$erro->getMessage());
}