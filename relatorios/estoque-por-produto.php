<?php 
require_once __DIR__ . "/../config.php";
require_once BASE_PATH . "/src/produto_crud.php";
require_once BASE_PATH . "/src/relatorio_crud.php";
require_once BASE_PATH . "/src/utils.php";

exigirLogin();

$produto_id = sanitizar($_GET['produto_id'] ?? null, 'inteiro');
$produtos = [];
$estoques = [];
$erro = null;

try {
    // Buscamos os produtos no BD e em ordem decrescente de id (como está na função original)
    $produtos = buscarProdutos($conexao);

    // Gerar um novo array contendo apenas os nomes dos produtos
    $nomeProdutos = array_column($produtos, 'nome');

    // Ordenar o array de produtos pelo nome dos produtos
    array_multisort($nomeProdutos, SORT_ASC, $produtos);
} catch (Throwable $e) {
    $erro = "Erro ao buscar produtos. <br>".$e->getMessage();
}


try {
    $estoques = $produto_id ? buscarEstoquePorProduto($conexao, $produto_id) : [];
} catch (Throwable $e) {
    $erro = "Erro ao buscar estoque. <br>".$e->getMessage();
}

$titulo = "Estoque por Produto |";
require_once BASE_PATH . "/includes/cabecalho.php"; 
?>

<section class="text-center mb-4 border rounded-3 p-4 border-primary-subtle">
    <h3><i class="bi bi-clipboard-data"></i> Estoque por Produtos</h3>

    <?php if($erro): ?>
        <p class="alert alert-danger text-center"><?=$erro?></p>
    <?php endif; ?>

    <?php if($produtos):?>
        <form action="" method="get" class="mx-auto my-4">
        <div class="row g-2 justify-content-center">
            <div class="col-auto">
                <label class="text-muted col-form-label" for="produto_id">Selecione o Produto:</label>
            </div>
            <div class="col-auto">
                <select onchange="this.form.submit()"
                name="produto_id" id="produto_id" class="form-select">
                    <option value=""></option>

                    <?php foreach($produtos as $produto): ?>
                    <option value="<?=$produto['id']?>"
                    <?=$produto['id'] === $produto_id ? 'selected' : '' ?>
                    >
                        <?=$produto['nome']?>
                    </option>
                    <?php endforeach; ?>

                </select>
            </div>
        </div>
    </form>
    <?php else: ?>
        <p class="alert alert-warning">Nenhum produto cadastrado ainda.</p>
    <?php endif; ?>

<?php if($produto_id && $estoques): ?>    
    <p class="fw-semibold">Estoque deste produto:</p>
    
    <div class="table-responsive">
        <table class="table table-hover caption-top">
            <caption>Quantidade de registros: <?=count($estoques)?></caption>
            <thead class="align-middle table-light">
                <tr>
                    <th>Loja</th>
                    <th>Estoque</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>

            <?php foreach($estoques as $estoque): ?>
                <tr>
                    <td> <?=$estoque['loja']?> </td>
                    <td> <?=$estoque['quantidade']?> </td>
                    <td>
                        <a class="btn btn-warning btn-sm" 
href="../estoque/editar.php?loja_id=<?=$estoque['loja_id']?>&produto_id=<?=$estoque['produto_id']?>">
                            <i class="bi bi-pencil-square"></i> Editar
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php elseif($produto_id): ?>    
    <p class="alert alert-warning">Nenhum registro de estoque para este produto.</p>
<?php endif; ?>    

</section>

<?php require_once BASE_PATH . "/includes/rodape.php"; ?>