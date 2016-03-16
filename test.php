<?php
function nactiTridu($trida)
{
require("tridy/$trida.php");
}
spl_autoload_register("nactiTridu");


Databaze::pripojeni();
$stranka = new Strankovnik('Testování');
$jazyky = new Jazyky();
if(isset($_GET['okruh'])){
    $okruh=new Okruh($_GET['okruh']);
} else {
    header("Location: /index.php");
}



$stranka->printHead();
$stranka->printMenu();
if(isset($_POST['slovicko'])){
    $okruh->zkontrolujSlovicko($_POST['ID'], $_POST['slovicko']);
}
$okruh->printTest();
$stranka->printFooter();

