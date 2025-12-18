<?php
// src/estoque_crud.php

function buscarEstoques(PDO $conexao):array
{
    $sql = "SELECT
                lojas_produtos.loja_id,
                lojas_produtos.produto_id,
                lojas_produtos.estoque,
                lojas.nome AS nome_loja,
                produtos.nome AS nome_produto
            FROM lojas_produtos
            INNER JOIN lojas ON lojas_produtos.loja_id = lojas.id
            INNER JOIN PRODUTOS ON lojas_produtos.produto_id = produtos.id
            ORDER BY lojas.nome, produtos.nome";
    $consulta = $conexao->prepare($sql);
    $consulta->execute();
    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}


function inserirEstoque(PDO $conexao, int $loja_id, int $produto_id, int $estoque):void
{
    $sql = "INSERT INTO lojas_produtos (loja_id, produto_id, estoque)
            VALUES(:loja_id, :produto_id, :estoque)";

    $consulta = $conexao->prepare($sql);
    $consulta->bindValue(":loja_id", $loja_id, PDO::PARAM_INT);
    $consulta->bindValue(":produto_id", $produto_id, PDO::PARAM_INT);
    $consulta->bindValue(":estoque", $estoque, PDO::PARAM_INT);

    $consulta->execute();
}

function buscarEstoquePorIds(PDO $conexao, int $loja_id, int $produto_id): ?array 
{
    $sql = "SELECT 
                lojas_produtos.loja_id, lojas_produtos.produto_id, lojas_produtos.estoque,
                lojas.nome AS nome_loja, produtos.nome AS nome_produto
            FROM lojas_produtos
            INNER JOIN lojas ON lojas_produtos.loja_id = lojas.id
            INNER JOIN produtos ON lojas_produtos.produto_id = produtos.id
            WHERE lojas_produtos.loja_id = :loja_id AND lojas_produtos.produto_id = :produto_id";

    $consulta = $conexao->prepare($sql);
    $consulta->bindValue(":loja_id", $loja_id, PDO::PARAM_INT);
    $consulta->bindValue(":produto_id", $produto_id, PDO::PARAM_INT);
    $consulta->execute();
    $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
    return $resultado ?: null;
}


function atualizarEstoque(PDO $conexao, int $loja_id, int $produto_id, int $estoque):void 
{
    $sql = "UPDATE lojas_produtos SET estoque = :estoque
            WHERE loja_id = :loja_id AND produto_id = :produto_id";

    $consulta = $conexao->prepare($sql);
    $consulta->bindValue(":estoque", $estoque, PDO::PARAM_INT);
    $consulta->bindValue(":loja_id", $loja_id, PDO::PARAM_INT);
    $consulta->bindValue(":produto_id", $produto_id, PDO::PARAM_INT);
    $consulta->execute();
}


function excluirEstoque(PDO $conexao, int $loja_id, int $produto_id):void
{
    $sql = "DELETE FROM lojas_produtos WHERE loja_id = :loja_id AND produto_id = :produto_id";
    $consulta = $conexao->prepare($sql);
    $consulta->bindValue(":loja_id", $loja_id, PDO::PARAM_INT);
    $consulta->bindValue(":produto_id", $produto_id, PDO::PARAM_INT);
    $consulta->execute();
}