<?php

$unidade = "";
$checkin = "";
$checkout = "";
$hospedes = "";
$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    $unidade = $_POST['unidade'];
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $hospedes = $_POST['hospedes'];

    if (!empty($unidade) && !empty($checkin) && !empty($checkout) && !empty($hospedes) ){
        
        if($hospedes == "1"){
            header("Location: fotos1.html");
        }elseif($hospedes == "2"){
            header("Location: fotos2.html");
        }elseif($hospedes == "3"){
            header("Location: fotos3.html");
        }
    } else{
        header("Location: index.html");
    }
}