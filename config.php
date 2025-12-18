<?php
/* BASE_PATH garante caminhos absolutos corretos ao incluir arquivos (includes, src etc).
Resolve caminhos de arquivos mesmo quando você está em subpastas. */
define('BASE_PATH', __DIR__);

/* BASE_URL garante URLs corretas ao gerar links (ex: login.php, fornecedores/listar.php) */
define('BASE_URL', '/curso-php-crud');

/* Importando/Carregando o script de conexão e disponibilizando
para todas as páginas que utilizam o config.php */
require_once BASE_PATH . '/src/banco.php';

require_once BASE_PATH . '/src/autenticacao.php';