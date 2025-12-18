<?php 
require_once __DIR__ . "/../config.php";
require_once BASE_PATH . "/src/fornecedor_crud.php";
require_once BASE_PATH . "/src/utils.php";

exigirLogin();

$erro = null;
$fornecedores = [];

try {
    $fornecedores = buscarFornecedores($conexao);
} catch (Throwable $e) {
    $erro = "Erro ao buscar fornecedores. <br>".$e->getMessage();
}

$titulo = "Fornecedores |";
require_once BASE_PATH . "/includes/cabecalho.php"; 
?>

<section class="text-center mb-4 border rounded-3 p-4 border-primary-subtle">
    <h3><i class="bi bi-people-fill"></i> Fornecedores</h3>

    <?php if($erro): ?>
    <p class="alert alert-danger text-center"><?=$erro?></p>
    <?php endif; ?>

    <p>
        <a class="btn btn-primary" href="inserir.php">
            <i class="bi bi-plus-circle"></i> Adicionar novo fornecedor
        </a>
    </p>

    <div class="table-responsive">
        <table class="table table-hover caption-top">
            <caption>Quantidade de registros: <?=count($fornecedores)?> </caption>
            <thead class="align-middle table-light">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th colspan="2">Ações</th>
                </tr>
            </thead>
            <tbody>

<?php foreach($fornecedores as $fornecedor): ?>
                <tr>
                    <td> <?=$fornecedor['id']?> </td>
                    <td> <?=$fornecedor['nome']?> </td>
                    <td><a href="editar.php?id=<?=$fornecedor['id']?>"
                    class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i> Editar</a></td>
                    <td><a href="excluir.php?id=<?=$fornecedor['id']?>"
                     class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Excluir</a></td>
                </tr>
<?php endforeach; ?>

            </tbody>
        </table>
    </div>

</section>

<?php require_once BASE_PATH . "/includes/rodape.php"; ?>