<?php
function nactiTridu($trida)
{
    require("tridy/$trida.php");
}
spl_autoload_register("nactiTridu");
Databaze::pripojeni();
$stranka = new Strankovnik('Editace okruhů');
$formOkruh = new FormOkruh();

if(isset($_POST['delete'])){
    $toDelete = $_POST['delete'];
    $name = $_POST['cizinsky'];
    $sql = 'DELETE FROM slovicka WHERE id=?';
    $values = array($toDelete);
    Databaze::dotaz($sql, $values);
    $messages[] = "<span>Slovíčko \"" . $name . "\" bylo odstraněno. </span>";
    $okruh = new Okruh($_GET['okruh']);
    $okruh->recountAll();
}

if(isset($_POST['learnt'])){
    $learnt = $_POST['learnt'];
    $name = $_POST['cizinsky'];
    $sql = 'UPDATE slovicka SET vyrazeno = 1 WHERE id=?';
    $values = array($learnt);
    Databaze::dotaz($sql, $values);
    $messages[] = "<span>Slovíčko \"" . $name. "\" bylo označeno za naučené. </span>";
    $okruh = new Okruh($_GET['okruh']);
    $okruh->recountAll();
}

if(isset($_POST['add'])){
    $cesky = $_POST['cesky'];
    $cizinsky = $_POST['cizinsky'];
    $cisloOkruhu = $_POST['add'];
    $sql = 'INSERT INTO slovicka VALUES(?, ?, ?, ?, ?, ?, ?)';
    $values = array('', $cesky, $cizinsky, $cisloOkruhu, 0, 0, 0);
    Databaze::dotaz($sql, $values);
    $okruh = new Okruh($cisloOkruhu);
    $okruh->recountAll();
}

if (isset($_POST['recalculate'])){
    $okruh = new Okruh($_POST['recalculate']);
    $okruh->recountAll();
    $messages[] = "<span>Statistiky okruhu byly přepočítány. </span>";
}

$stranka->printHead();
$stranka->printMenu();
if (isset($messages)) {
    foreach ($messages as $message) {
        echo $message;
    }
}
if(isset($_GET['okruh'])){
    $formOkruh->printEditForm($_GET['okruh']);
}
$stranka->printFooter();

