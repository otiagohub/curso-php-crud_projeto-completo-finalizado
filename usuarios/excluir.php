<?php 
require_once __DIR__ . "/../config.php";
require_once BASE_PATH . "/src/usuario_crud.php";
require_once BASE_PATH . "/src/utils.php";

exigirLogin();

$id = sanitizar($_GET['id'], 'inteiro');
$erro = null;

if($id === $_SESSION['id']){
    $erro = $_SESSION['nome'] . ", você não pode excluir a si mesmo!";
}

if(!$id){
    header("location:listar.php");
    exit;
}

try {
    $usuario = buscarUsuarioPorId($conexao, $id);
    if(!$usuario) $erro = "Usuário não encontrado!";
} catch (Throwable $e) {
    $erro = "Erro ao buscar usuário: <br>".$e->getMessage();
}

if(isset($_GET['confirmar-exclusao']) && !$erro){
    try {
        excluirUsuario($conexao, $id);
        header("location:listar.php");
        exit;
    } catch (Throwable $e) {
        $erro = "Erro ao excluir usuário: <br>".$e->getMessage();
    }
}


$titulo = "Excluir Usuário |";
require_once BASE_PATH . "/includes/cabecalho.php"; 
?>

<section class="mb-4 border rounded-3 p-4 border-primary-subtle">
    <h3 class="text-center"><i class="bi bi-trash3-fill"></i> Excluir Usuário</h3>

    <?php if($erro): ?>
        <p class="alert alert-danger text-center"><?=$erro?></p>
    <?php else: ?>

    <div class="alert alert-danger w-50 text-center mx-auto">
        <p>Deseja realmente excluir o usuário <b><?=$usuario['nome'] ?? ''?></b>?</p>
        <a class="btn btn-secondary" href="listar.php"><i class="bi bi-x-circle"></i> Não</a>
        <a class="btn btn-danger" 
        href="?id=<?=$usuario['id']?>&confirmar-exclusao">
            <i class="bi bi-check-circle"></i> 
            Sim</a>
    </div>

    <?php endif; ?>


</section>

<?php require_once BASE_PATH . "/includes/rodape.php"; ?>