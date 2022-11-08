<?php

require __DIR__. '/vendor/autoload.php';

define('TITLE','Editar vaga');

use \App\Entity\Vaga;

    //validação do ID
if(!isset($_GET['id'])  or !is_numeric($_GET['id'])){
    header('location: index.php?status=error');
    exit;
}
//CONSULTA A VAGA
$obVaga = Vaga::getVaga($_GET['id']);
//   echo "<pre>"; print_r($obVaga); echo "</pre>"; exit;

//VALIDAÇÃO DA VAGA
if(!$obVaga instanceof Vaga){
    header('location: index.php?status=error');
    exit;
}

//VALIDAÇÃO DO POST
if(isset($_POST['excluir'])){
    $obVaga -> excluir();

    
    header('location: index.php?status=sucess');
    exit;
};

include __DIR__. '/includes/header.php';
include __DIR__. '/includes/confirmar-exclusao.php';
include __DIR__.'/includes/footer.php';