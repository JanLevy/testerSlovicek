<?php

/**
 * Created by PhpStorm.
 * User: Kamalpuri
 * Date: 19.2.2016
 * Time: 1:08
 */
class Jazyky
{

    public function __construct(){

    }

    public static function getJazyky(){
        $sql = 'SELECT * FROM jazyky';
        $query = Databaze::dotaz($sql);
        return $query;
    }

    public function printJazyky($form){
        $jazyky = self::getJazyky();
        while($jazyk = $jazyky->fetch(PDO::FETCH_ASSOC)){
            echo "<h3>" . $jazyk['jazyk'] . "</h3>";
            $okruhy=Okruhy::getOkruhy($jazyk['ID_jaz']);
            $counter = 0;
            while($okruh =$okruhy->fetch(PDO::FETCH_ASSOC)){
                $counter++;
                switch($form){
                    case 'test':
                        $action='Procházet okruh';
                        break;
                    case 'edit':
                        $action='Editovat okruh';
                }
                if ($counter === 1){
                    echo "<table>
                    <tr><th>Okruh</th><th>Projití</th><th>Naučených</th><th>Celkem slovíček</th><th>Úspěšnost</th>
                    <th>Akce</th></tr>";
                }
                echo "<tr>
                    <td>". $okruh['okruh'] ."</td>
                    <td>". $okruh['testovano']."</td>";
                    $sql = 'SELECT COUNT(ID) FROM slovicka WHERE vyrazeno = 1 AND okruh =?';
                    $values = array($okruh['ID_ok']);
                    $query = Databaze::dotaz($sql, $values);
                    $naucenych = $query->fetch(PDO::FETCH_NUM)[0];
                    echo" <td>". $naucenych . "</td>
                    <td>". $okruh['celkem'] ."</td>";
                    $sql = 'SELECT SUM(uspechy) FROM slovicka WHERE okruh = ?';
                    $values = array($okruh['ID_ok']);
                    $query = Databaze::dotaz($sql, $values);
                    $uspechy = $query->fetch(PDO::FETCH_NUM)[0];
                    $sql = 'SELECT SUM(testovano) FROM slovicka WHERE okruh = ?';
                    $values = array($okruh['ID_ok']);
                    $query = Databaze::dotaz($sql, $values);
                    $testovano = $query->fetch(PDO::FETCH_NUM)[0];
                    if((int)$testovano !==0){
                        echo "<td>". round((float)($uspechy/$testovano), 2) ."</td>";
                    }
                    else{
                        echo "<td>0</td>";
                    }
                echo "<td><a href='/". $form .".php?okruh=". $okruh['ID_ok'] ."'>". $action ."</a></td></tr>";
            }
            if($counter===0){
                echo "Nepodařilo se nalézt žádné okruhy k tomuto jazyku";
            } else {
                echo "</table>";
            }
        }
    }
}