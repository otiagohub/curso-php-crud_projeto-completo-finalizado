<?php 
require_once __DIR__ . "/../config.php";
require_once BASE_PATH . "/src/utils.php";
require_once BASE_PATH . "/src/usuario_crud.php";

exigirLogin();

$id = sanitizar($_GET['id'], 'inteiro');
$erro = null;

if(!$id){
    header("location:listar.php");
    exit;
}

try {
    $usuario = buscarUsuarioPorId($conexao, $id); 
    if(!$usuario) $erro = "Usuário não encontrado!";
} catch (Throwable $e) {
    $erro = "Erro ao buscar usuário <br> ".$e->getMessage();
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nome = sanitizar($_POST['nome']);
    $email = sanitizar($_POST['email'], 'email');
    $senhaForm = $_POST['senha'];

    if(empty($nome) || empty($email)){
        $erro = "Nome e e-mail são obrigatórios";
    } else {
        try {
            $senhaVerificada = empty($senhaForm) ? 
                                $usuario['senha'] : 
                                verificarSenha($senhaForm, $usuario['senha']);
            atualizarUsuario($conexao, $id, $nome, $email, $senhaVerificada);
            header("location:listar.php");
            exit;
        } catch (Throwable $e) {
            if($e->getCode() === '23000'){
                $erro = "E-mail já cadastrado. Por favor, use outro e-mail.";
            } else {
                $erro = "Erro ao atualizar usuário: <br>".$e->getMessage();
            }
        }
    }
}

$titulo = "Editar Usuário |";
require_once BASE_PATH . "/includes/cabecalho.php"; 
?>

<section class="mb-4 border rounded-3 p-4 border-primary-subtle">
    <h3 class="text-center"><i class="bi bi-pencil-fill"></i> Editar Usuário</h3>

    <?php if($erro): ?>
        <p class="alert alert-danger text-center"><?=$erro?></p>
    <?php endif; ?>

    <form action="" method="post" class="w-75 mx-auto">
        <input type="hidden" name="id" value="<?=$usuario['id'] ?? ''?>">
        <div class="form-group">
            <label for="nome" class="form-label">Nome:</label>
            <input required type="text" name="nome" id="nome" class="form-control" value="<?=$usuario['nome'] ?? ''?>">
        </div>

        <div class="form-group">
            <label for="email" class="form-label">E-mail:</label>
            <input required type="email" name="email" id="email" class="form-control" value="<?=$usuario['email'] ?? ''?>">
        </div>

        <div class="form-group">
            <label for="senha" class="form-label">Senha:</label>
            <input type="password" name="senha" id="senha" class="form-control" placeholder="Preencha apenas se for alterar a senha">
        </div>

        <button class="btn btn-warning my-4" type="submit">
            <i class="bi bi-arrow-clockwise"></i> Salvar Alterações
        </button>
    </form>

</section>

<?php require_once BASE_PATH . "/includes/rodape.php"; ?>