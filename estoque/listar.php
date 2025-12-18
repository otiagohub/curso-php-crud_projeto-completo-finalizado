<?php 
require_once __DIR__ . "/../config.php";
require_once BASE_PATH . "/src/estoque_crud.php";
require_once BASE_PATH . "/src/utils.php";

exigirLogin();

$erro = null;
$estoques = [];

try {
    $estoques = buscarEstoques($conexao);
} catch (Throwable $e) {
    $erro = "Erro ao buscar estoques. <br>".$e->getMessage();
}

$titulo = "Estoque |";
require_once BASE_PATH . "/includes/cabecalho.php"; 
?>

<section class="text-center mb-4 border rounded-3 p-4 border-primary-subtle">
    <h3><i class="bi bi-stack"></i> Estoque das Lojas</h3>

    <?php if($erro):?>
        <p class="alert alert-danger text-center"><?=$erro?></p>        
    <?php endif; ?>

    <p>
        <a class="btn btn-primary" href="inserir.php">
            <i class="bi bi-plus-circle"></i> Novo registro de estoque
        </a>
    </p>

    <div class="table-responsive">
        <table class="table table-hover caption-top">
            <caption>Quantidade de registros: <?=count($estoques)?></caption>
            <thead class="align-middle table-light">
                <tr>
                    <th>Loja</th>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th colspan="2">Ações</th>
                </tr>
            </thead>
            <tbody>

<?php foreach($estoques as $estoque):?>
                <tr>
                    <td><?=$estoque['nome_loja']?></td>
                    <td><?=$estoque['nome_produto']?></td>
                    <td><?=$estoque['estoque']?></td>
<td><a href="editar.php?loja_id=<?=$estoque['loja_id']?>&produto_id=<?=$estoque['produto_id']?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i> Editar</a></td>
<td><a href="excluir.php?loja_id=<?=$estoque['loja_id']?>&produto_id=<?=$estoque['produto_id']?>" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Excluir</a></td>
                </tr>
<?php endforeach; ?>

            </tbody>
        </table>
    </div>

</section>

<?php require_once BASE_PATH . "/includes/rodape.php"; ?>