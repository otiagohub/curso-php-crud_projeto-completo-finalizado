<?php 
require_once __DIR__ . "/../config.php";
require_once BASE_PATH . "/src/relatorio_crud.php";
require_once BASE_PATH . "/src/utils.php";

exigirLogin();

$limite = sanitizar($_GET['limite'] ?? 5, 'inteiro');
$produtosComEstoqueBaixo = [];
$erro = null;

try {
    $produtosComEstoqueBaixo = buscarProdutosComEstoqueBaixo($conexao, $limite);
} catch (Throwable $e) {
    $erro = "Erro ao buscar produtos com estoque baixo. <br>".$e->getMessage();
}

$titulo = "Estoque Baixo |";
require_once BASE_PATH . "/includes/cabecalho.php"; 
?>

<section class="text-center mb-4 border rounded-3 p-4 border-primary-subtle">
    <h3><i class="bi bi-exclamation-triangle"></i> Estoque Baixo</h3>

    <?php if($erro): ?>
        <p class="alert alert-danger text-center"><?=$erro?></p>
    <?php endif; ?>

    <form action="" method="get" class="mx-auto my-4">
        <div class="row g-2 justify-content-center">
            <div class="col-auto">
                <label class="text-muted col-form-label" for="limite">Exibir produtos com estoque abaixo de:</label>
            </div>
            <div class="col-auto">
                <input type="number" name="limite" id="limite" class="form-control" min="1" value="<?=$limite?>">
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit">Filtrar</button>
            </div>
        </div>
    </form>
    
<?php if($produtosComEstoqueBaixo): ?>    
    <div class="table-responsive">
        <table class="table table-hover caption-top">
            <caption>Quantidade de registros: <?=count($produtosComEstoqueBaixo)?></caption>
            <thead class="align-middle table-light">
                <tr>
                    <th>Produto</th>
                    <th>Loja</th>
                    <th>Estoque</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
<?php 
foreach($produtosComEstoqueBaixo as $produto):
    $classe = definirClasseEstoque($produto['quantidade'], $limite);
?>
                <tr class="<?=$classe?>">
                    <td> <?=$produto['produto']?> </td>
                    <td> <?=$produto['loja']?> </td>
                    <td> <?=$produto['quantidade']?> </td>
                    <td>
<a href="../estoque/editar.php?loja_id=<?=$produto['loja_id']?>&produto_id=<?=$produto['produto_id']?>" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil-square"></i> Editar</a>
                    </td>
                </tr>
<?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <p class="alert alert-warning">
        Nenhum produto com estoque abaixo de <?=$limite?> foi encontrado
    </p> 
<?php endif; ?>

</section>

<?php require_once BASE_PATH . "/includes/rodape.php"; ?>