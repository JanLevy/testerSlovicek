<?php
function nactiTridu($trida)
{
    require("tridy/$trida.php");
}
spl_autoload_register("nactiTridu");


Databaze::pripojeni();
$stranka = new Strankovnik('Správa okruhů');
$formOkruh = new FormOkruh();
$jazyky = new Jazyky();

if(isset($_POST['type'])){
    switch($_POST['type']){
        case 'formOkruh':
            $formOkruh->handlePost();
            $messages[]= 'Nový okruh byl přidán';
        break;
    }

}

$stranka->printHead();
$stranka->printMenu();
if (isset($messages)) {
    foreach ($messages as $message) {
        echo $message;
    }
}
echo "<h1>Správa okruhů</h1>";
$formOkruh->printNewForm();
echo"<h2>Editovat okruh</h2>";
$jazyky->printJazyky('edit');

$stranka->printFooter();

