<?php
// src/loja_crud.php

function buscarLojas(PDO $conexao):array
{
    $sql = "SELECT id, nome FROM lojas ORDER BY nome";
    $consulta = $conexao->prepare($sql);
    $consulta->execute();
    return $consulta->fetchAll(PDO::FETCH_ASSOC);
    // return [];
}

function inserirLoja(PDO $conexao, string $nome):void
{
    $sql = "INSERT INTO lojas (nome) VALUES(:nome)";
    $consulta = $conexao->prepare($sql);
    $consulta->bindValue(":nome", $nome, PDO::PARAM_STR);
    $consulta->execute();
}

function buscarLojaPorId(PDO $conexao, int $id): ?array
{
    $sql = "SELECT id, nome FROM lojas WHERE id = :id";
    $consulta = $conexao->prepare($sql);
    $consulta->bindValue(":id", $id, PDO::PARAM_INT);
    $consulta->execute();
    $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
    return $resultado ?: null;
}

function atualizarLoja(PDO $conexao, int $id, string $nome):void 
{
    $sql = "UPDATE lojas SET nome = :nome WHERE id = :id";
    $consulta = $conexao->prepare($sql);
    $consulta->bindValue(":id", $id, PDO::PARAM_INT);
    $consulta->bindValue(":nome", $nome, PDO::PARAM_STR);
    $consulta->execute();
}

function excluirLoja(PDO $conexao, int $id):void
{
    $sql = "DELETE FROM lojas WHERE id = :id";
    $consulta = $conexao->prepare($sql);
    $consulta->bindValue(":id", $id, PDO::PARAM_INT);
    $consulta->execute();
}