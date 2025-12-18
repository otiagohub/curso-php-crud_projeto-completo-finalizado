<?php 
require_once __DIR__ . "/../config.php";
require_once BASE_PATH . "/src/loja_crud.php";
require_once BASE_PATH . "/src/utils.php";

exigirLogin();
$erro = null;

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $nome = sanitizar($_POST['nome']);

    if(empty($nome)){
        $erro = "Preencha o campo nome!";
    } else {
        try {
            inserirLoja($conexao, $nome);
            header("location:listar.php");
            exit;
        } catch (Throwable $e) {
            $erro = "Erro ao inserir loja. <br>".$e->getMessage();
        }
    }
}

$titulo = "Adicionar Loja |";
require_once BASE_PATH . "/includes/cabecalho.php"; 
?>

<section class="mb-4 border rounded-3 p-4 border-primary-subtle">
    <h3 class="text-center"><i class="bi bi-plus-circle-fill"></i> Adicionar Loja</h3>

    <?php if($erro):?>
        <p class="alert alert-danger text-center"><?=$erro?></p>        
    <?php endif; ?>

    <form action="" method="post" class="w-75 mx-auto">
        <div class="form-group">
            <label for="nome" class="form-label">Nome:</label>
            <input required value="<?=$_POST['nome'] ?? ''?>" type="text" name="nome" id="nome" class="form-control">
        </div>
        <button class="btn btn-success my-4" type="submit">
            <i class="bi bi-check-circle"></i> Salvar
        </button>
    </form>

</section>

<?php require_once BASE_PATH . "/includes/rodape.php"; ?>