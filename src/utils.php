<?php

function dump(mixed $dados):void
{
    echo "<pre>";
    var_dump($dados);
    echo "</pre>";
}

function sanitizar(mixed $entrada, string $tipo = 'texto'): mixed
{
    switch($tipo){
        case 'inteiro':
            return (int) filter_var($entrada, FILTER_SANITIZE_NUMBER_INT);

        case 'decimal':
            return (float) filter_var($entrada, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        case 'email':
            return trim(filter_var($entrada, FILTER_SANITIZE_EMAIL));

        case 'texto':
        default:
            return trim(filter_var($entrada, FILTER_SANITIZE_SPECIAL_CHARS));
    }
}

function codificarSenha(string $senha): string
{
    return password_hash($senha, PASSWORD_DEFAULT);
}

function verificarSenha(string $senhaForm, string $senhaBanco): string
{
    if(password_verify($senhaForm, $senhaBanco)){
        return $senhaBanco;
    } else {
        return codificarSenha($senhaForm);
    }
}

function formatarData(?string $data):string
{
    return $data ? date("d/m/Y", strtotime($data)) : "-";
}

function formatarPreco(float $preco): string 
{
    return "R$ ".number_format($preco, 2, ",", ".");
}


function ultimaAtualizacao():string
{
    // Configuração de fuso horário (timezone)
    date_default_timezone_set("America/Sao_Paulo");
    
    // Retorna a data em formato DIA/MÊS/ANO HORA:MINUTOS
    return date("d/m/Y H:i");
}


function definirClasseEstoque(int $quantidade, int $limite): string 
{
    // Nível Crítico (quantidade até 1): vermelho
    if($quantidade <= 1) return "table-danger";

    // Nível de Atenção (quantidade até metade do limite): amarelo
    if($quantidade <= ($limite / 2)) return "table-warning";

    // Padrão (sem cor)
    return "";
}