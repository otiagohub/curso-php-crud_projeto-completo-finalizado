<?php 
require_once __DIR__ . "/../config.php";
require_once BASE_PATH . "/src/fornecedor_crud.php";
require_once BASE_PATH . "/src/utils.php";

exigirLogin();

$id = sanitizar($_GET['id'], 'inteiro');
$erro = null;

if(!$id){
    header("location:listar.php");
    exit;
}

try {
    $fornecedor = buscarFornecedorPorId($conexao, $id);
    if(!$fornecedor) $erro = "Fornecedor não encontrado!";
} catch (Throwable $e) {
    $erro = "Erro ao buscar fornecedor. <br>".$e->getMessage();
}

if(isset($_GET['confirmar-exclusao'])){
    try {
        excluirFornecedor($conexao, $id);
        header("location:listar.php");
        exit;
    } catch (Throwable $e) {
        if($e->getCode() === '23000'){
            $erro = "<b>".$fornecedor['nome']."</b> está vinculado a outros registros no banco de dados, e não pode ser excluído.";
        } else {
            $erro = "Erro ao excluir fornecedor. <br>".$e->getMessage();
        }
    }
}

$titulo = "Excluir Fornecedor |";
require_once BASE_PATH . "/includes/cabecalho.php"; 
?>

<section class="mb-4 border rounded-3 p-4 border-primary-subtle">
    <h3 class="text-center"><i class="bi bi-trash3-fill"></i> Excluir Fornecedor</h3>

    <?php if($erro): ?>
    <p class="alert alert-danger text-center"><?=$erro?></p>
    <?php else: ?>
    <div class="alert alert-danger w-50 text-center mx-auto">
        <p>Deseja realmente excluir o fornecedor <b><?=$fornecedor['nome'] ?? ''?></b>?</p>
        <a class="btn btn-secondary" href="listar.php"><i class="bi bi-x-circle"></i> Não</a>
        <a class="btn btn-danger" href="?id=<?=$fornecedor['id']?>&confirmar-exclusao">
            <i class="bi bi-check-circle"></i> Sim</a>
    </div>
    <?php endif; ?>


</section>

<?php require_once BASE_PATH . "/includes/rodape.php"; ?>