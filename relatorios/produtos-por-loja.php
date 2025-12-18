<?php
require_once __DIR__ . "/../config.php";
require_once BASE_PATH . "/src/loja_crud.php";
require_once BASE_PATH . "/src/relatorio_crud.php";
require_once BASE_PATH . "/src/utils.php";

exigirLogin();

$lojas = [];
$erro = null;
$loja_id = sanitizar($_GET['loja_id'] ?? null, 'inteiro');
$produtos = [];

try {
    $lojas = buscarLojas($conexao);
} catch (Throwable $e) {
    $erro = "Erro ao buscar lojas. <br>" . $e->getMessage();
}

try {
    $produtos = $loja_id ? buscarProdutosPorLoja($conexao, $loja_id) : [];
} catch (Throwable $e) {
    $erro = "Erro ao buscar produtos. <br>" . $e->getMessage();
}

$titulo = "Produtos por Loja |";
require_once BASE_PATH . "/includes/cabecalho.php";
?>

<section class="text-center mb-4 border rounded-3 p-4 border-primary-subtle">
    <h3><i class="bi bi-box-seam"></i> Produtos por Loja</h3>

    <?php if ($erro): ?>
        <p class="alert alert-danger text-center"><?= $erro ?></p>
    <?php endif; ?>

<?php if($lojas): ?>
    <form action="" method="get" class="mx-auto my-4">
        <div class="row g-2 justify-content-center">
            <div class="col-auto">
                <label class="text-muted col-form-label" for="loja_id">Selecione a loja:</label>
            </div>
            <div class="col-auto">
                <select onchange="this.form.submit()"
                    name="loja_id" id="loja_id" class="form-select">
                    <option value=""></option>

                    <?php foreach ($lojas as $loja): ?>
                        <option <?= $loja["id"] === $loja_id ? 'selected' : '' ?>
                        value="<?= $loja['id'] ?>">
                            <?= $loja['nome'] ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>
        </div>
    </form>
<?php else: ?>    
    <p class="alert alert-warning">Nenhuma loja foi cadastrada ainda.</p>
<?php endif; ?>    

<?php if($loja_id && $produtos): ?>
    <p class="fw-semibold">Produtos disponíveis nesta loja:</p>

    <div class="table-responsive">
        <table class="table table-hover caption-top">
            <caption>Quantidade de registros: <?=count($produtos)?></caption>
            <thead class="align-middle table-light">
                <tr>
                    <th>Produto</th>
                    <th>Preço</th>
                    <th>Fornecedor</th>
                    <th>Estoque</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($produtos as $produto): ?>
                <tr>
                    <td> <?=$produto['produto']?> </td>
                    <td> <?=formatarPreco($produto['preco'])?> </td>
                    <td> <?=$produto['fornecedor']?> </td>
                    <td> <?=$produto['estoque']?> </td>
<td> 
    <a href="../estoque/editar.php?loja_id=<?=$produto['loja_id']?>&produto_id=<?=$produto['produto_id']?>" class="btn btn-warning btn-sm">
        <i class="bi bi-pencil-square"></i> Editar</a>
</td>
                </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </div>
<?php elseif($loja_id): ?>
    <p class="alert alert-warning">Nenhum produto encontrado para esta loja.</p>
<?php endif; ?>


</section>

<?php require_once BASE_PATH . "/includes/rodape.php"; ?>