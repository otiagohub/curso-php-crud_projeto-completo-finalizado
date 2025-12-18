<?php
/* Sobre sessão
Sessão (SESSION) no PHP é um recurso que permite guardar informações do usuário
enquanto ele navega entre as páginas. Essas informações/dados ficam armazenadas de maneira temporária no servidor.

Exemplos:

- Dados do usuário logado (nome, identificador, tipo de usuário)
- Dados temporários de carrinho de compra
- Preferências do usuário (tema, ajustes/configurações etc)
- Controle de acesso em geral */

function iniciarSessao(): void 
{
    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }
}

function exigirLogin(): void
{
    iniciarSessao();

    /* Verificando se NÃO EXISTE uma variável
    de sessão chamada id (identificador de algum usuário) */
    if(!isset($_SESSION['id'])){
        header("location:".BASE_URL."/login.php?acesso_proibido");
        exit;
    }
}

function usuarioEstaLogado(): bool
{
    iniciarSessao();
    return isset($_SESSION['id']);
}

function login(int $id, string $nome): void 
{
    iniciarSessao();
    $_SESSION['id'] = $id;
    $_SESSION['nome'] = $nome;
}

function logout():void 
{
    iniciarSessao();
    session_destroy();
    header("location:" .BASE_URL ."/login.php?logout");
    exit;
}