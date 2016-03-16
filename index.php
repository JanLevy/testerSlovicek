<?php
function nactiTridu($trida)
{
    require("tridy/$trida.php");
}
spl_autoload_register("nactiTridu");


Databaze::pripojeni();
$stranka = new Strankovnik('Výběr lekcí');
$jazyky = new Jazyky();

$stranka->printHead();
$stranka->printMenu();
echo "<h1>Výběr lekcí</h1>";
$jazyky->printJazyky('test');



$stranka->printFooter();

