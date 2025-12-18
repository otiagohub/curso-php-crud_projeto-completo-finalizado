<?php
// src/produto_crud.php

function buscarProdutos(PDO $conexao, string $busca = ''):array {
    $sql = "SELECT 
                produtos.id, produtos.nome, produtos.descricao, produtos.preco,
                fornecedores.nome AS fornecedor,
                detalhes_produto.data_validade
            FROM produtos
            LEFT JOIN fornecedores ON produtos.fornecedor_id = fornecedores.id
            LEFT JOIN detalhes_produto ON detalhes_produto.produto_id = produtos.id";
        
    $parametros = [];

    if(!empty($busca)){
        $sql .= " WHERE produtos.nome LIKE :busca OR produtos.descricao LIKE :busca";
        $parametros[':busca'] = "%$busca%";
    }

    $sql .= " ORDER BY produtos.id DESC";

    $consulta = $conexao->prepare($sql);
    $consulta->execute($parametros);
    return $consulta->fetchAll(PDO::FETCH_ASSOC);
}


function inserirProduto(PDO $conexao, array $produto):int {
    $sql = "INSERT INTO produtos (nome, descricao, preco, quantidade, fornecedor_id)
            VALUES(:nome, :descricao, :preco, :quantidade, :fornecedor_id)";

    $consulta = $conexao->prepare($sql);
    $consulta->bindValue(":nome", $produto['nome'], PDO::PARAM_STR);
    $consulta->bindValue(":descricao", $produto['descricao'], 
        is_null($produto['descricao']) ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $consulta->bindValue(":preco", $produto['preco'], PDO::PARAM_STR);
    $consulta->bindValue(":quantidade", $produto['quantidade'], PDO::PARAM_INT);
    $consulta->bindValue(":fornecedor_id", $produto['fornecedor_id'], PDO::PARAM_INT);

    $consulta->execute();

    /* Retorna o ID do produto recém-inserido */
    return (int) $conexao->lastInsertId();
}

function inserirDetalhesDoProduto(PDO $conexao, array $detalhes):void {
    $sql = "INSERT INTO detalhes_produto 
                (produto_id, peso, dimensoes, codigo_barras, data_validade) 
            VALUES(:produto_id, :peso, :dimensoes, :codigo_barras, :data_validade)";
    $consulta = $conexao->prepare($sql);

    $consulta->bindValue(":produto_id", $detalhes["produto_id"], PDO::PARAM_INT);

    $consulta->bindValue(":peso", $detalhes['peso'], 
        is_null($detalhes['peso']) ? PDO::PARAM_NULL : PDO::PARAM_STR);
    
    $consulta->bindValue(":dimensoes", $detalhes['dimensoes'], 
        is_null($detalhes['dimensoes']) ? PDO::PARAM_NULL : PDO::PARAM_STR);
    
    $consulta->bindValue(":codigo_barras", $detalhes['codigo_barras'], 
        is_null($detalhes['codigo_barras']) ? PDO::PARAM_NULL : PDO::PARAM_STR);
    
    $consulta->bindValue(":data_validade", $detalhes['data_validade'], 
        is_null($detalhes['data_validade']) ? PDO::PARAM_NULL : PDO::PARAM_STR);

    $consulta->execute();
}


function buscarProdutoPorId(PDO $conexao, int $id): ?array {
    $sql = "SELECT
                p.id AS produto_id,
                p.nome,
                p.descricao,
                p.preco,
                p.quantidade,
                f.id AS fornecedor_id,
                d.id AS detalhe_id,
                d.data_validade,
                d.peso,
                d.dimensoes,
                d.codigo_barras    
            FROM produtos p
            LEFT JOIN fornecedores f ON p.fornecedor_id = f.id
            LEFT JOIN detalhes_produto d ON p.id = d.produto_id
            WHERE p.id = :id";

    $consulta = $conexao->prepare($sql);
    $consulta->bindValue(":id", $id, PDO::PARAM_INT);
    $consulta->execute();
    return $consulta->fetch(PDO::FETCH_ASSOC) ?: null;
}


function atualizarProduto(PDO $conexao, array $produtoAtualizado): void
{
    $sql = "UPDATE produtos SET
                nome = :nome,
                descricao = :descricao,
                preco = :preco,
                quantidade = :quantidade,
                fornecedor_id = :fornecedor_id
            WHERE id = :id";

    $consulta = $conexao->prepare($sql);

    $consulta->bindValue(":id", $produtoAtualizado['id'], PDO::PARAM_INT);
    $consulta->bindValue(":nome", $produtoAtualizado['nome'], PDO::PARAM_STR);
    $consulta->bindValue(":descricao", $produtoAtualizado['descricao'], 
    is_null($produtoAtualizado['descricao']) ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $consulta->bindValue(":preco", $produtoAtualizado['preco'], PDO::PARAM_STR);
    $consulta->bindValue(":quantidade", $produtoAtualizado['quantidade'], PDO::PARAM_INT);
    $consulta->bindValue(":fornecedor_id", $produtoAtualizado['fornecedor_id'], PDO::PARAM_INT);

    $consulta->execute();
}

function atualizarDetalhesDoProduto(PDO $conexao, array $detalhes):void
{
    $sql = "UPDATE detalhes_produto SET
                peso = :peso,
                dimensoes = :dimensoes,
                codigo_barras = :codigo_barras,
                data_validade = :data_validade
            WHERE produto_id = :produto_id";

    $consulta = $conexao->prepare($sql);
    $consulta->bindValue(":produto_id", $detalhes['produto_id'], PDO::PARAM_INT);
    
    $consulta->bindValue(":peso", $detalhes['peso'], 
        is_null($detalhes['peso']) ? PDO::PARAM_NULL : PDO::PARAM_STR);
    
    $consulta->bindValue(":dimensoes", $detalhes['dimensoes'], 
        is_null($detalhes['dimensoes']) ? PDO::PARAM_NULL : PDO::PARAM_STR);
    
    $consulta->bindValue(":codigo_barras", $detalhes['codigo_barras'], 
        is_null($detalhes['codigo_barras']) ? PDO::PARAM_NULL : PDO::PARAM_STR);
    
    $consulta->bindValue(":data_validade", $detalhes['data_validade'], 
        is_null($detalhes['data_validade']) ? PDO::PARAM_NULL : PDO::PARAM_STR);

    $consulta->execute();

}


function excluirProduto(PDO $conexao, int $id):void
{
    $sql = "DELETE FROM produtos WHERE id = :id";
    $consulta = $conexao->prepare($sql);
    $consulta->bindValue(":id", $id, PDO::PARAM_INT);
    $consulta->execute();
}