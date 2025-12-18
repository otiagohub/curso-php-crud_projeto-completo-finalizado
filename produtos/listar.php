<?php 
require_once __DIR__ . "/../config.php";
require_once BASE_PATH . "/src/produto_crud.php";
require_once BASE_PATH . "/src/utils.php";

exigirLogin();

$termo = sanitizar($_GET['search'] ?? ""); 

$erro = null;
$erroValidacaoBusca = null;
$produtos = [];

if(isset($_GET['search']) && $termo === ''){
    $erroValidacaoBusca = "Por favor, digite um termo no campo de busca.";
}

try {
    $produtos = buscarProdutos($conexao, $termo);
} catch (Throwable $e) {
    $erro = "Erro ao buscar produtos. Detalhes: <br>".$e->getMessage();
}

$titulo = "Produtos |";
require_once BASE_PATH . "/includes/cabecalho.php"; 
?>

<section class="text-center mb-4 border rounded-3 p-4 border-primary-subtle">
    <h3><i class="bi bi-box-fill"></i> Produtos</h3>

    <!-- Mensagem de erro em caso de campo de busca vazio ao ser acionado -->
    <?php if($erroValidacaoBusca): ?>
    <p class="alert alert-danger text-center"><?=$erroValidacaoBusca?></p>
    <?php endif; ?>

    <!-- Mensagem de erro em caso de qualquer erro relacionado ao banco -->
    <?php if($erro): ?>
    <p class="alert alert-danger text-center"><?=$erro?></p>
    <?php endif; ?>

    <p>
        <a class="btn btn-primary" href="inserir.php">
            <i class="bi bi-plus-circle"></i> Adicionar novo produto
        </a>
    </p>

    <form action="" method="get" class="mx-auto my-4">
        <div class="row g-2 justify-content-center">
            <div class="col-auto">
                <input required class="form-control" type="search" name="search" id="search" placeholder="Buscar produto..." value="<?=$termo?>">
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-primary" type="submit">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>
        </div>
    </form>

    <?php 
    if($termo !== ""):
        if(!empty($produtos)):
            $mensagem = "<p class='text-muted'>Resultados para <b class='bg-info-subtle rounded p-1'>$termo</b></p>";
        else:
            $mensagem = "<p class='text-danger'>Nenhum produto foi encontrado</p>";
        endif;

        echo $mensagem;
    ?>
        <a href="listar.php" class="btn btn-sm btn-outline-secondary">&times; Limpar busca</a>
    <?php
    endif;
    ?>

    <div class="table-responsive">
        <table class="table table-hover caption-top">
            <caption>Quantidade de registros: <?=count($produtos)?></caption>
            <thead class="align-middle table-light">
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Fornecedor</th>
                    <th>Preço</th>
                    <th>Data de Validade</th>
                    <th colspan="2">Ações</th>
                </tr>
            </thead>
            <tbody>
<?php foreach($produtos as $produto): ?>
                <tr>
                    <td> <?=$produto['nome']?> </td>
                    <td> <?=$produto['descricao']?> </td>
                    <td> <?=$produto['fornecedor']?> </td>
                    <td> <?=formatarPreco($produto['preco'])?> </td>
                    <td> <?=formatarData($produto['data_validade'])?> </td>
                    <td><a href="editar.php?id=<?=$produto['id']?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i> Editar</a></td>
                    <td><a href="excluir.php?id=<?=$produto['id']?>" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> Excluir</a></td>
                </tr>
<?php endforeach; ?>

            </tbody>
        </table>
    </div>

</section>

<?php require_once BASE_PATH . "/includes/rodape.php"; ?>