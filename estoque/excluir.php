<?php 
require_once __DIR__ . "/../config.php";
require_once BASE_PATH ."/src/estoque_crud.php";
require_once BASE_PATH ."/src/utils.php";

exigirLogin();

$loja_id = sanitizar($_GET['loja_id'], 'inteiro');
$produto_id = sanitizar($_GET['produto_id'], 'inteiro');
$erro = null;

if(!$loja_id || !$produto_id){
    header("Location:listar.php");
    exit;
}

try {
    $estoque = buscarEstoquePorIds($conexao, $loja_id, $produto_id);
    if(!$estoque) $erro = "Estoque não encontrado";
} catch (Throwable $e) {
    $erro = "Erro ao buscar estoque. <br>".$e->getMessage();
}

if(isset($_GET['confirmar-exclusao'])){
    try {
        excluirEstoque($conexao, $loja_id, $produto_id);
        header("location:listar.php");
        exit;
    } catch (Throwable $e) {
        $erro = "Erro ao excluir estoque. <br>".$e->getMessage();
    }
}

$titulo = "Excluir Estoque |";
require_once BASE_PATH . "/includes/cabecalho.php"; 
?>

<section class="mb-4 border rounded-3 p-4 border-primary-subtle">
    <h3 class="text-center"><i class="bi bi-trash3-fill"></i> Excluir produto do estoque da loja</h3>

    <?php if($erro): ?>
        <p class="alert alert-danger text-center"><?=$erro?></p>
    <?php else: ?>
    
    <div class="alert alert-danger w-50 text-center mx-auto">
        <p>Deseja realmente excluir o produto <b><?=$estoque['nome_produto'] ?? ''?></b> 
        da <b><?=$estoque['nome_loja'] ?? ''?></b>?</p>
        <a class="btn btn-secondary" href="listar.php"><i class="bi bi-x-circle"></i> Não</a>
        <a class="btn btn-danger" href="?loja_id=<?=$estoque['loja_id']?>&produto_id=<?=$estoque['produto_id']?>&confirmar-exclusao">
            <i class="bi bi-check-circle"></i> Sim</a>
    </div>
    
    <?php endif; ?>

</section>

<?php require_once BASE_PATH . "/includes/rodape.php"; ?>