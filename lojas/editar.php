<?php 
require_once __DIR__ . "/../config.php";
require_once BASE_PATH . "/src/loja_crud.php";
require_once BASE_PATH . "/src/utils.php";

exigirLogin();

$id = sanitizar($_GET['id'], 'inteiro');
$erro = null;

if(!$id){
    header("location:listar.php");
    exit;
}

try {
    $loja = buscarLojaPorId($conexao, $id);
    if(!$loja) $erro = "Loja não encontrada!";
} catch (Throwable $e) {
    $erro = "Erro ao buscar loja. <br>".$e->getMessage();
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nome = sanitizar($_POST['nome']);

    if(empty($nome)){
        $erro = "Preencha o campo nome!";
    } else {
        try {
            atualizarLoja($conexao, $id, $nome);
            header("location:listar.php");
            exit;
        } catch (Throwable $e) {
            $erro = "Erro ao atualizar a loja. <br>".$e->getMessage();
        }
    }
}

$titulo = "Editar Loja |";
require_once BASE_PATH . "/includes/cabecalho.php"; 
?>

<section class="mb-4 border rounded-3 p-4 border-primary-subtle">
    <h3 class="text-center"><i class="bi bi-pencil-fill"></i> Editar Loja</h3>

    <?php if($erro):?>
        <p class="alert alert-danger text-center"><?=$erro?></p>        
    <?php endif; ?>

    <form action="" method="post" class="w-75 mx-auto">
        <input type="hidden" name="id" value="<?=$loja['id'] ?? ''?>">
        <div class="form-group">
            <label for="nome" class="form-label">Nome:</label>
            <input required type="text" name="nome" id="nome" class="form-control" value="<?=$loja['nome'] ?? ''?>">
        </div>
        <button class="btn btn-warning my-4" type="submit">
            <i class="bi bi-arrow-clockwise"></i> Salvar Alterações
        </button>
    </form>

</section>

<?php require_once BASE_PATH . "/includes/rodape.php"; ?>