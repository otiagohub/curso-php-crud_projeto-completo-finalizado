<?php 
require_once __DIR__ . "/../config.php";
require_once BASE_PATH . "/src/relatorio_crud.php";
require_once BASE_PATH . "/src/fornecedor_crud.php";
require_once BASE_PATH . "/src/utils.php";

exigirLogin();

$fornecedores = [];
$produtos = [];
$erro = null;

$fornecedor_id = sanitizar($_GET["fornecedor_id"] ?? null, 'inteiro');

try {
    $fornecedores = buscarFornecedores($conexao);
} catch (Throwable $e) {
    $erro = "Erro ao buscar fornecedores. <br>".$e->getMessage();
}

try {
    $produtos = $fornecedor_id ? buscarProdutosPorFornecedor($conexao, $fornecedor_id) : [];
} catch (Throwable $e) {
    $erro = "Erro ao buscar produtos. <br>".$e->getMessage();
}

$titulo = "Produtos por Fornecedor |";
require_once BASE_PATH . "/includes/cabecalho.php"; 
?>

<section class="text-center mb-4 border rounded-3 p-4 border-primary-subtle">
    <h3><i class="bi bi-people"></i> Produtos por Fornecedor</h3>

    <?php if($erro): ?>
        <p class="alert alert-danger text-center"><?=$erro?></p>
    <?php endif; ?>

<?php if($fornecedores): ?>
    <form action="" method="get" class="mx-auto my-4">
        <div class="row g-2 justify-content-center">
            <div class="col-auto">
                <label class="text-muted col-form-label" for="fornecedor_id">Selecione o fornecedor:</label>
            </div>
            <div class="col-auto">
                <select onchange="this.form.submit()"
                 name="fornecedor_id" id="fornecedor_id" class="form-select">
                    <option value=""></option>

                    <?php foreach($fornecedores as $fornecedor): ?>
                    <option <?=$fornecedor['id'] === $fornecedor_id ? 'selected' : ''?>
                    value="<?=$fornecedor['id']?>">
                        <?=$fornecedor['nome']?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </form>
<?php else: ?>
    <p class="alert alert-warning">Nenhum fornecedor cadastrado ainda.</p>
<?php endif; ?>


<?php if($fornecedor_id && $produtos): ?>
    <p class="fw-semibold">Produtos fornecidos por este fornecedor:</p>
    
    <div class="table-responsive">
        <table class="table table-hover caption-top">
            <caption>Quantidade de registros: <?=count($produtos)?></caption>
            <thead class="align-middle table-light">
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Total</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($produtos as $produto): ?>
                <tr>
                    <td> <?=$produto['nome']?> </td>
                    <td> <?=$produto['descricao']?> </td>
                    <td> <?=formatarPreco($produto['preco'])?> </td>
                    <td> <?=$produto['quantidade']?></td>
                    <td> <?=formatarPreco($produto['total'])?> </td>
                    <td>
                        <a class="btn btn-warning btn-sm" 
                        href="../produtos/editar.php?id=<?=$produto['id']?>">
                            <i class="bi bi-pencil-square"></i> Editar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php elseif($fornecedor_id): ?>
    <p class="alert alert-warning">Nenhum produto encontrado para este fornecedor.</p>
<?php endif; ?>
</section>

<?php require_once BASE_PATH . "/includes/rodape.php"; ?>