<?php
// fornecedor_crud.php

function buscarFornecedores(PDO $conexao):array
{
    $sql = "SELECT * FROM fornecedores ORDER BY nome";
    $consulta = $conexao->prepare($sql);
    $consulta->execute();
    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}

function inserirFornecedor(PDO $conexao, string $nome): void
{
    $sql = "INSERT INTO fornecedores (nome) VALUES (:nome)";
    $consulta = $conexao->prepare($sql);
    $consulta->bindValue(":nome", $nome, PDO::PARAM_STR);
    $consulta->execute();
}

function buscarFornecedorPorId(PDO $conexao, int $id): ?array
{
    $sql = "SELECT * FROM fornecedores WHERE id = :id";
    $consulta = $conexao->prepare($sql);
    $consulta->bindValue(":id", $id, PDO::PARAM_INT);
    $consulta->execute();
    $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
    return $resultado ?: null;  
}


function atualizarFornecedor(PDO $conexao, int $id, string $nome):void
{
    $sql = "UPDATE fornecedores SET nome = :nome WHERE id = :id";
    $consulta = $conexao->prepare($sql);
    $consulta->bindValue(":id", $id, PDO::PARAM_INT);
    $consulta->bindValue(":nome", $nome, PDO::PARAM_STR);
    $consulta->execute();
}


function excluirFornecedor(PDO $conexao, int $id):void 
{
    $sql = "DELETE FROM fornecedores WHERE id = :id";
    $consulta = $conexao->prepare($sql);
    $consulta->bindValue(":id", $id, PDO::PARAM_INT);
    $consulta->execute();
}