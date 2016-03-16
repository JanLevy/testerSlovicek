<?php

class FormOkruh
{

    public function __construct(){

    }

    public function printNewForm(){
        echo "<h2>Vytvořit nový okruh</h2>
        <form method='POST' action ='/spravaOkruhu.php'><table><tr>
        <td><label for='okruh'>Jméno okruhu</label></td>
        <td><label for='jazyk'>Jazyk okruhu</label></td></tr>
        <tr><td><input type='text' name='okruh' /></td>
        <td><select name='jazyk'>";
            $jazyky = Jazyky::getJazyky();
            while($jazyk = $jazyky->fetch(PDO::FETCH_ASSOC)){
                echo "<option value = '". $jazyk['ID_jaz']."'>" . $jazyk['jazyk'] . "</option>";
            }
        echo "</select></td></tr></table>
        <input type='hidden' name='type' value='formOkruh' />
        <input type='submit' value='Vytvořit okruh!' />
        </form>";
    }

    public function handlePost(){
        $okruh = $_POST['okruh'];
        $jazyk = (int)$_POST['jazyk'];
        $sql = 'INSERT INTO okruhy VALUES (?, ?, ?, ?, ?, ?)';
        $values = array('', $okruh, 0, 0, 0, $jazyk);

        Databaze::dotaz($sql, $values);
    }

    public function printEditForm($okruh){

        $sql = 'SELECT * FROM slovicka WHERE okruh = ?';
        $values = array($okruh);
        $slovicka = Databaze::dotaz($sql,$values);
        $counter = 0;
        while($slovicko = $slovicka->fetch(PDO::FETCH_ASSOC)){
            $counter++;
            if($counter === 1){
                echo "<h1>Slovíčka k okruhu č." . $okruh . "</h1>
               <table><tr><th>Č.</th></th><th>Česky</th><th>Překlad</th><th>Úspěšnost</th><th>Testováno</th><th>Naučeno</th>
               <th>Již nezkoušet?</th><th>Odstranit</th></tr>";
            }
            if($slovicko['vyrazeno']===1){
                echo "<tr id='vyrazeno'>";
            } else {
                echo "<tr>";
            }
            echo "<td>". $counter ."</td>
            <td>" . $slovicko['cesky']. "</td>
            <td>" . $slovicko['cizinsky']. "</td>";
                if($slovicko['uspechy'] > 0 ){
                echo "<td>" . round((float)$slovicko['uspechy']/$slovicko['testovano'], 2) . "</td>";
                } else {
                echo "<td>0</td>";
                }
            echo "<td>" . $slovicko['testovano'] . "</td>";
            if($slovicko['vyrazeno'] === 1){
                echo"<td>Ano</td>";
            }else {
                echo"<td>Ne</td>";
            }
            echo" <td><form action='/edit.php?okruh=". $okruh ."' method='post'>
            <input type='hidden' name='learnt' value='". $slovicko['ID'] . "' />
            <input type='hidden' name='cizinsky' value='". $slovicko['cizinsky'] . "'/>
            <input type='submit' value='Již nezkoušet'/>
            </form></td>";
            echo" <td><form action='/edit.php?okruh=". $okruh ."' method='post'>
            <input type='hidden' name='delete' value='". $slovicko['ID'] . "' />
            <input type='hidden' name='cizinsky' value='". $slovicko['cizinsky'] . "'/>
            <input type='submit' value='Odstranit'/>
            </form></td></tr>";
        }
        if($counter > 0){
            echo "</table>";
        } else {
            echo "<span>Nepodařilo se nalézt žádná slovíčka pro tento okruh.</span>";
        }
        $this->addNewVocForm($okruh);
        $this->addRecalculateButton($okruh);
    }

    public function addNewVocForm($okruh){
        echo "<h2>Přidat nové slovíčko</h2>";
        echo "<form method='post' action='/edit.php?okruh=" . $okruh . "'>
            <table><tr><td><label for='cesky'>Slovíčko česky :</label></td>
            <td><input type='text' name='cesky' /></td></tr>
            <tr><td><label for='cizinsky'>Překlad :</label></td>
            <td><input type='text' name='cizinsky' /></td></tr></table>
            <input type='hidden' name='add' value='" . $okruh . "' />
            <input type='submit' value='Přidat slovíčko' />
        </form>";
    }

    private function addRecalculateButton($okruh){
        echo "<h2>Přepočítat statistiky okruhu</h2>
            <form method='post' action='/edit.php?okruh=" . $okruh . "'>
            <input type='hidden' name='recalculate' value='" . $okruh . "' />
            <input type='submit' value='Přepočítat' />
            </form>";

    }

}