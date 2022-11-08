<?php

require __DIR__. '/vendor/autoload.php';

define('TITLE','Cadastrar vaga');

use \App\Entity\Vaga;
$obVaga = new Vaga;

//VALIDAÇÃO DO POST
if(isset($_POST['titulo'], $_POST['descricao'], $_POST['ativo'])){
    $obVaga -> titulo = $_POST['titulo'];
    $obVaga -> descricao = $_POST['descricao'];
    $obVaga -> ativo = $_POST['ativo'];
    $obVaga -> cadastrar();
    // echo "<pre>"; print_r($obVaga); echo "</pre>"; exit;
    
    header('location: index.php?status=sucess');
    exit;
};

include __DIR__. '/includes/header.php';
include __DIR__. '/includes/formulario.php';
include __DIR__.'/includes/footer.php';