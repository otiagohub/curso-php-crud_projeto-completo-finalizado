<?php 
require_once __DIR__ . "/../config.php";
require_once BASE_PATH . "/src/fornecedor_crud.php";
require_once BASE_PATH . "/src/produto_crud.php";
require_once BASE_PATH . "/src/utils.php";

exigirLogin();
$erros = [];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $produto = [
        'nome' => sanitizar($_POST['nome']),
        'descricao' => sanitizar($_POST['descricao']) ?: null,
        'preco' => sanitizar($_POST['preco'], 'decimal'),
        'quantidade' => sanitizar($_POST['quantidade'], 'inteiro'),
        'fornecedor_id' => sanitizar($_POST['fornecedor_id'], 'inteiro'),
    ];

    $detalhes = [
        'peso' => sanitizar($_POST['peso'], 'decimal') ?: null,
        'dimensoes' => sanitizar($_POST['dimensoes']) ?: null,
        'data_validade' => sanitizar($_POST['data_validade']) ?: null,
        'codigo_barras' => sanitizar($_POST['codigo_barras']) ?: null
    ];

    // Validações
    if(empty($produto['nome'])){
        $erros[] = "O nome é obrigatório";
    }

    if(empty($produto['fornecedor_id'])){
        $erros[] = "Escolha um fornecedor";
    }

    if(trim($_POST['preco']) === ''){
        $erros[] = "O preço é obrigatório";
    } else if($produto['preco'] < 0){
        $erros[] = "Informe um preço válido";
    }

    if(trim($_POST['quantidade']) === ''){
        $erros[] = "A quantidade é obrigatória";
    } else if($produto['quantidade'] < 0){
        $erros[] = "Informe uma quantidade válida";
    }

    if(empty($erros)){
        try {
            // Iniciamos uma transação cujas operações serão feitas de forma agrupada
            $conexao->beginTransaction();

            $produto_id = inserirProduto($conexao, $produto);

            /* Se pelo menos um campo de detalhes foi preenchidos, insere o registro de detalhes */
            if($detalhes['peso'] || $detalhes['dimensoes'] || $detalhes['codigo_barras'] || $detalhes['data_validade']){
                // Adicionando o ID do novo produto aos detalhes antes de inserir o registro
                $detalhes['produto_id'] = $produto_id;
                inserirDetalhesDoProduto($conexao, $detalhes);
            }

            // Commit aplica/conclui as operações da transação
            $conexao->commit();

            header("location:listar.php");
            exit;
        } catch (Throwable $e) {
            // Se algo deu errado, desfaz as alterações
            $conexao->rollBack();

            if($e->getCode() === '23000'){
                $erros[] = "O código de barras já existe no sistema.";
            } else {
                $erros[] = "Erro ao inserir produto: <br>". $e->getMessage();
            }
        }
    }

}

$titulo = "Adicionar Produto |";
require_once BASE_PATH . "/includes/cabecalho.php"; 
?>

<section class="mb-4 border rounded-3 p-4 border-primary-subtle">
    <h3 class="text-center"><i class="bi bi-plus-circle-fill"></i> Adicionar Produto</h3>

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
            <div class="form-group mb-3">
                <label for="nome" class="form-label">Nome:</label>
                <input required value="<?=$_POST['nome'] ?? ''?>" type="text" name="nome" id="nome" class="form-control">
            </div>

            <div class="form-group mb-3">
                <label for="descricao" class="form-label">Descrição:</label>
                <textarea class="form-control" name="descricao" id="descricao"><?=$_POST['descricao'] ?? ''?></textarea>
            </div>

            <div class="form-group mb-3">
                <label for="preco" class="form-label">Preço:</label>
                <input required  value="<?=$_POST['preco'] ?? ''?>" type="number" name="preco" id="preco" class="form-control" min="0" step="0.01">
            </div>

            <div class="form-group mb-3">
                <label for="quantidade" class="form-label">Quantidade:</label>
                <input required  value="<?=$_POST['quantidade'] ?? ''?>" type="number" name="quantidade" id="quantidade" class="form-control" min="0">
            </div>

            <div class="form-group mb-3">
                <label for="fornecedor_id" class="form-label">Fornecedor:</label>
                <select required name="fornecedor_id" id="fornecedor_id" class="form-select">
                    <option value=""></option>

<?php 
$fornecedores = buscarFornecedores($conexao);
foreach($fornecedores as $fornecedor):
    /* Se o ID do fornecedor atual no loop for o mesmo que foi enviado no formulário $_POST,
    então guardamos o atributo selected na variável selecionado */
    $selecionado = (isset($_POST['fornecedor_id']) && $_POST['fornecedor_id'] == $fornecedor['id']) ? 'selected' : '';
?>
        <option <?=$selecionado?> value="<?=$fornecedor['id']?>"><?=$fornecedor['nome']?></option>
<?php 
endforeach;
?>
                </select>
            </div>

        </fieldset>

        <fieldset class="mt-4">
            <legend>Detalhes do Produto</legend>
            <div class="form-group mb-3">
                <label for="peso" class="form-label">Peso (kg):</label>
                <input  value="<?=$_POST['peso'] ?? ''?>" type="number" name="peso" id="peso" class="form-control" step="0.01">
            </div>

            <div class="form-group mb-3">
                <label for="dimensoes" class="form-label">Dimensões (LxAxP):</label>
                <input value="<?=$_POST['dimensoes'] ?? ''?>" type="text" name="dimensoes" id="dimensoes" class="form-control">
            </div>

            <div class="form-group mb-3">
                <label for="codigo_barras" class="form-label">Código de barras:</label>
                <input  value="<?=$_POST['codigo_barras'] ?? ''?>" type="text" name="codigo_barras" id="codigo_barras" class="form-control">
            </div>

            <div class="form-group mb-3">
                <label for="data_validade" class="form-label">Data de validade:</label>
                <input  value="<?=$_POST['data_validade'] ?? ''?>" type="date" name="data_validade" id="data_validade" class="form-control">
            </div>

        </fieldset>

        <button class="btn btn-success my-4" type="submit">
            <i class="bi bi-check-circle"></i> Salvar
        </button>
    </form>

</section>

<?php require_once BASE_PATH . "/includes/rodape.php"; ?>