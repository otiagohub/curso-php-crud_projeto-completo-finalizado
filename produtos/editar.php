<?php 
require_once __DIR__ . "/../config.php";
require_once BASE_PATH . "/src/fornecedor_crud.php";
require_once BASE_PATH . "/src/produto_crud.php";
require_once BASE_PATH . "/src/utils.php";

exigirLogin();

$id = sanitizar($_GET['id'], 'inteiro');
$erros = [];

if(!$id){
    header("location:listar.php");
    exit;
}

try {
    $produto = buscarProdutoPorId($conexao, $id);
    if(!$produto) $erros[] = "Produto não encontrado";
} catch (Throwable $e) {
    $erros[] = "Erro ao buscar produto. <br>". $e->getMessage();
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $produtoAtualizado = [
        "nome" => sanitizar($_POST['nome']),
        "descricao" => sanitizar($_POST['descricao']) ?: null,
        "preco" => sanitizar($_POST['preco'], 'decimal'),
        "quantidade" => sanitizar($_POST['quantidade'], 'inteiro'),
        "fornecedor_id" => sanitizar($_POST['fornecedor_id'], 'inteiro'),
        "id" => $id,  // importante ter o id do produto visando o processo de update no banco
    ];

    $detalhesAtualizados = [
        "peso" => sanitizar($_POST['peso'], 'decimal') ?: null,
        "dimensoes" => sanitizar($_POST['dimensoes']) ?: null,
        "codigo_barras" => sanitizar($_POST['codigo_barras']) ?: null,
        "data_validade" => sanitizar($_POST['data_validade']) ?: null,
        "produto_id" => $id // importante ter o id do produto visando o processo de update no banco
    ];

    // Validações
    if(empty($produtoAtualizado['nome'])){
        $erros[] = "O nome é obrigatório";
    }

    if(empty($produtoAtualizado['fornecedor_id'])){
        $erros[] = "Escolha um fornecedor";
    }

    if(trim($_POST['preco']) === ''){
        $erros[] = "O preço é obrigatório";
    } else if($produtoAtualizado['preco'] < 0){
        $erros[] = "Informe um preço válido";
    }

    if(trim($_POST['quantidade']) === ''){
        $erros[] = "A quantidade é obrigatória";
    } else if($produtoAtualizado['quantidade'] < 0){
        $erros[] = "Informe uma quantidade válida";
    }

    if(empty($erros)){
        try {
            $conexao->beginTransaction();

            atualizarProduto($conexao, $produtoAtualizado);

            // Verificar se já existem detalhes para este produto
            $temDetalhes = !empty($produto['detalhe_id']);
            
            // Verificar se o usuário digitou algum detalhe
            $detalhesDigitados = !empty(array_filter([
                $detalhesAtualizados['peso'],
                $detalhesAtualizados['dimensoes'],
                $detalhesAtualizados['codigo_barras'],
                $detalhesAtualizados['data_validade']
            ]));

            // Se já tem detalhes do produto, então atualizamos o registro de detalhes
            if($temDetalhes){
                atualizarDetalhesDoProduto($conexao, $detalhesAtualizados);
            } else if($detalhesDigitados){
                // Se não tem detalhes do produto, e o usuário digitou algum detalhe, então é necessário inserir um novo registro de detalhes.
                inserirDetalhesDoProduto($conexao, $detalhesAtualizados);
            }

            $conexao->commit();
            header("location:listar.php");
            exit;
        } catch (Throwable $e) {
            $conexao->rollBack();

            if($e->getCode() === '23000'){
                $erros[] = "O código de barras já existe no sistema.";
            } else {
                $erros[] = "Erro ao inserir produto: <br>". $e->getMessage();
            }
        }
    }
}

$titulo = "Editar Produto |";
require_once BASE_PATH . "/includes/cabecalho.php"; 
?>

<section class="mb-4 border rounded-3 p-4 border-primary-subtle">
    <h3 class="text-center"><i class="bi bi-pencil-fill"></i> Editar Produto</h3>

    <?php if(!empty($erros)): ?>
        <div class="text-center">
            <ul class="list-group">
                <?php foreach($erros as $erro):?>                
                    <li class="list-group-item list-group-item-danger"><?=$erro?></li>
                <?php endforeach; ?>                
            </ul>
        </div>
    <?php endif;?>

    <form action="" method="post" class="w-75 mx-auto">
        <fieldset>
            <legend>Produto</legend>

            <input type="hidden" name="id" value="<?=$produto['produto_id'] ?? ''?>">

            <div class="form-group mb-3">
                <label for="nome" class="form-label">Nome:</label>
                <input required type="text" name="nome" id="nome" class="form-control" value="<?=$produto['nome'] ?? ''?>">
            </div>

            <div class="form-group mb-3">
                <label for="descricao" class="form-label">Descrição:</label>
                <textarea class="form-control" name="descricao" id="descricao"><?=$produto['descricao'] ?? ''?></textarea>
            </div>

            <div class="form-group mb-3">
                <label for="preco" class="form-label">Preço:</label>
                <input required value="<?=$produto['preco'] ?? ''?>" type="number" name="preco" id="preco" class="form-control" min="0" step="0.01">
            </div>

            <div class="form-group mb-3">
                <label for="quantidade" class="form-label">Quantidade:</label>
                <input required value="<?=$produto['quantidade'] ?? ''?>" type="number" name="quantidade" id="quantidade" class="form-control" min="0">
            </div>

            <div class="form-group mb-3">
                <label for="fornecedor_id" class="form-label">Fornecedor:</label>
                <select required name="fornecedor_id" id="fornecedor_id" class="form-select">
                    <option value=""></option>
                    
<?php 
$fornecedores = buscarFornecedores($conexao);
foreach($fornecedores as $fornecedor):
    // Se o fornecedor for o mesmo do produto, marcamos como selecionado; senão, não marcamos nada
    $selecionado = ($fornecedor['id'] === $produto['fornecedor_id']) ? 'selected' : '';
?>
                    <option value="<?=$fornecedor['id']?>" <?=$selecionado?>>
                        <?=$fornecedor['nome']?>
                    </option>
<?php 
endforeach;
?>

                </select>
            </div>

        </fieldset>

        <fieldset class="mt-4">
            <legend>Detalhes do Produto</legend>

            <input type="hidden" name="detalhe_id" value="<?=$produto['detalhe_id'] ?? ''?>">

            <div class="form-group mb-3">
                <label for="peso" class="form-label">Peso (kg):</label>
                <input value="<?=$produto['peso'] ?? ''?>" type="number" name="peso" id="peso" class="form-control" step="0.01">
            </div>

            <div class="form-group mb-3">
                <label for="dimensoes" class="form-label">Dimensões (LxAxP):</label>
                <input value="<?=$produto['dimensoes'] ?? ''?>" type="text" name="dimensoes" id="dimensoes" class="form-control">
            </div>

            <div class="form-group mb-3">
                <label for="codigo_barras" class="form-label">Código de barras:</label>
                <input value="<?=$produto['codigo_barras'] ?? ''?>" type="text" name="codigo_barras" id="codigo_barras" class="form-control">
            </div>

            <div class="form-group mb-3">
                <label for="data_validade" class="form-label">Data de validade:</label>
                <input value="<?=$produto['data_validade'] ?? ''?>" type="date" name="data_validade" id="data_validade" class="form-control">
            </div>

        </fieldset>

        <button class="btn btn-warning my-4" type="submit">
            <i class="bi bi-arrow-clockwise"></i> Salvar Alterações
        </button>
    </form>

</section>

<?php require_once BASE_PATH . "/includes/rodape.php"; ?>