<?php
// usuario_crud.php

function buscarUsuarios(PDO $conexao): array
{
    $sql = "SELECT id, nome, email FROM usuarios ORDER BY nome";
    $consulta = $conexao->prepare($sql);
    $consulta->execute();
    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}

function inserirUsuario(
    PDO $conexao, string $nome, string $email, string $senha): void
{
    // Montando o comando SQL com parâmetros nomeados para os valores
    $sql = "INSERT INTO usuarios (nome, email, senha) 
            VALUES(:nome, :email, :senha)";

    // Preparando o comando para execução
    $consulta = $conexao->prepare($sql);

    // Associando os valores aos seus respectivos parâmetros nomeados
    $consulta->bindValue(':nome', $nome, PDO::PARAM_STR);
    $consulta->bindValue(':email', $email, PDO::PARAM_STR);
    $consulta->bindValue(':senha', $senha, PDO::PARAM_STR);

    $consulta->execute();            
}

function buscarUsuarioPorId(PDO $conexao, int $id): ?array
{
    $sql = "SELECT * FROM usuarios WHERE id = :id";
    $consulta = $conexao->prepare($sql);
    $consulta->bindValue(":id", $id, PDO::PARAM_INT);
    $consulta->execute();
    $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
    return $resultado ?: null;
} 

function atualizarUsuario(PDO $conexao, int $id, string $nome, string $email, string $senha): void
{
    $sql = "UPDATE usuarios SET nome = :nome, email = :email, senha = :senha
            WHERE id = :id";

    $consulta = $conexao->prepare($sql);

    $consulta->bindValue(":nome", $nome, PDO::PARAM_STR);
    $consulta->bindValue(":email", $email, PDO::PARAM_STR);
    $consulta->bindValue(":senha", $senha, PDO::PARAM_STR);
    $consulta->bindValue(":id", $id, PDO::PARAM_INT);

    $consulta->execute();
}


function excluirUsuario(PDO $conexao, int $id): void
{
    $sql = "DELETE FROM usuarios WHERE id = :id";
    $consulta = $conexao->prepare($sql);
    $consulta->bindValue(":id", $id, PDO::PARAM_INT);
    $consulta->execute();
}


function buscarPorEmail(PDO $conexao, string $email): ?array
{
    $sql = "SELECT * FROM usuarios WHERE email = :email";
    $consulta = $conexao->prepare($sql);
    $consulta->bindValue(":email", $email, PDO::PARAM_STR);
    $consulta->execute();
    $usuario = $consulta->fetch(PDO::FETCH_ASSOC);
    return $usuario ?: null;
}