<?php 
require_once __DIR__ . "/../config.php";
require_once BASE_PATH . "/src/loja_crud.php";
require_once BASE_PATH . "/src/utils.php";

exigirLogin();

$id = sanitizar($_GET['id'], 'inteiro');
$erro = null;

try {
    $loja = buscarLojaPorId($conexao, $id);
    if(!$loja) $erro = "Loja não encontrada!";
} catch (Throwable $e) {
    $erro = "Erro ao buscar loja. <br>".$e->getMessage();
}

if(isset($_GET['confirmar-exclusao'])){
    try {
        excluirLoja($conexao, $id);
        header("location:listar.php");
        exit;
    } catch (Throwable $e) {
        $erro = "Erro ao excluir loja. <br>".$e->getMessage();
    }
}

$titulo = "Excluir Loja |";
require_once BASE_PATH . "/includes/cabecalho.php"; 
?>

<section class="mb-4 border rounded-3 p-4 border-primary-subtle">
    <h3 class="text-center"><i class="bi bi-trash3-fill"></i> Excluir Loja</h3>

    <?php if($erro):?>
        <p class="alert alert-danger text-center"><?=$erro?></p>        
    <?php else: ?>
        <div class="alert alert-danger w-50 text-center mx-auto">
            <p>Deseja realmente excluir a loja <b><?=$loja['nome']?></b>?</p>
            <p>Caso existam registros de estoque dela, <b>eles também serão excluídos!</b></p>
            <a class="btn btn-secondary" href="listar.php"><i class="bi bi-x-circle"></i> Não</a>
            <a class="btn btn-danger" href="?id=<?=$loja['id']?>&confirmar-exclusao"><i class="bi bi-check-circle"></i> Sim</a>
        </div>
        
    <?php endif; ?>

</section>

<?php require_once BASE_PATH . "/includes/rodape.php"; ?>