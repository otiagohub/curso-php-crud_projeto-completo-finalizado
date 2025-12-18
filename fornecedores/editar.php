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

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nome = sanitizar($_POST['nome']);

    if(empty($nome)){
        $erro = "Preencha o campo nome.";
    } else {
        try {
            atualizarFornecedor($conexao, $id, $nome);
            header("location:listar.php");
            exit;
        } catch (Throwable $e) {
            $erro = "Erro ao atualizar o fornecedor. <br>".$e->getMessage();
        }
    }
}

$titulo = "Editar Fornecedor |";
require_once BASE_PATH . "/includes/cabecalho.php"; 
?>

<section class="mb-4 border rounded-3 p-4 border-primary-subtle">
    <h3 class="text-center"><i class="bi bi-pencil-fill"></i> Editar Fornecedor</h3>

    <?php if($erro): ?>
    <p class="alert alert-danger text-center"><?=$erro?></p>
    <?php endif; ?>

    <form action="" method="post" class="w-75 mx-auto">
        <input type="hidden" name="id" value="<?=$fornecedor['id'] ?? ''?>">
        <div class="form-group">
            <label for="nome" class="form-label">Nome:</label>
            <input required type="text" name="nome" id="nome" class="form-control" value="<?=$fornecedor['nome'] ?? ''?>">
        </div>
        <button class="btn btn-warning my-4" type="submit">
            <i class="bi bi-arrow-clockwise"></i> Salvar Alterações
        </button>
    </form>

</section>

<?php require_once BASE_PATH . "/includes/rodape.php"; ?>