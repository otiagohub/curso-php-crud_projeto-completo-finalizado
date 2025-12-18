<?php 
require_once __DIR__ ."/config.php";
require_once BASE_PATH . "/src/utils.php";
require_once BASE_PATH . "/src/usuario_crud.php";


if($_SERVER['REQUEST_METHOD'] === "POST"){
    $email = sanitizar($_POST['email'] ?? '', 'email');
    $senha = $_POST['senha'] ?? '';

    if(empty($email) || empty($senha)){
        header("location:login.php?campos_obrigatorios");
        exit;
    }

    $usuario = buscarPorEmail($conexao, $email);
    
    if($usuario && password_verify($senha, $usuario['senha'])){
        login($usuario['id'], $usuario['nome']);
        header("location:index.php");
        exit;
    } else {
        header("location:login.php?login_invalido");
        exit;
    }
}

/* Array de mensagens e estilo/classe pra formatação */
$mensagens = [
    'acesso_proibido' => ['Acesso proibido! Você precisar estar logado(a) para acessar esta página', 'danger'],
    'campos_obrigatorios' => ['Campos obrigatórios não preenchidos!', 'warning'],
    'login_invalido' => ['E-mail e/ou senha inválidos', 'danger'],
    'logout' => ['Você saiu do sistema com sucesso!', 'success']
];

$titulo = "Login |";
require_once BASE_PATH . "/includes/cabecalho.php"; 
?>

        <section class="text-center mb-4 border rounded-3 p-4 border-primary-subtle">
            <h1 class="mb-2">Fly By Night</h1>
            <h2 class="fs-6 lead">Gerenciamento de Estoque</h2>

            <hr>

            <h3>Login</h3>

            <!-- Usamos o foreach para acessar cada elemento
             do array mensagens e extraimos a mensagem e o tipo -->
            <?php 
            foreach($mensagens as $elemento => [$mensagem, $tipo]): 
                if(isset($_GET[$elemento])):
            ?>
            <div class="alert alert-<?=$tipo?> text-center">
                <?=$mensagem?>
            </div>
            <?php 
                endif;
            endforeach; 
            ?>

            <p class="lead">Entre com seu e-mail e senha para acessar o sistema.</p>

            <form action="" method="post" class="w-50 mx-auto text-start mt-3">
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail:</label>
                    <input required type="email" name="email" id="email" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha:</label>
                    <input required type="password" name="senha" id="senha" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Entrar</button>
            </form>

        </section>
        
    

<?php require_once BASE_PATH . "/includes/rodape.php"; ?> 