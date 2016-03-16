<?php

class Okruh
{
    private $okruh, $testovano;

    public function __construct($okruh){
    $this->okruh = $okruh;
    }

    public function noveSlovicko(){
        $remains = $this->remainingWords();
        if ($remains===0){
            $this->recountAll();
            echo "Pro toto testování jste se již naučili všechna slovíčka.";
            return false;
        } else {
            $sql = 'SELECT * FROM slovicka WHERE testovano <= ? AND vyrazeno < 1 AND okruh = ? ORDER BY RAND() LIMIT 1';
            $values = array($this->testovano, $this->okruh);
            $query = Databaze::dotaz($sql, $values);
            $slovicko = $query->fetch(PDO::FETCH_ASSOC);
            if ($slovicko !== false) {
                return $slovicko;
            } else {
                $this->zvyseniTestovani();
                echo "Pro toto testování jste již prošli všechna slovíčka. Začněte prosím nové testování.";
                return false;
            }
        }
    }

    private function remainingWords(){
        $sql = 'SELECT COUNT(*) FROM slovicka WHERE okruh = ? AND vyrazeno < 1';
        $values = array($this->okruh);
        $query = Databaze::dotaz($sql, $values);
        $remains = $query->fetch(PDO::FETCH_NUM)[0];
        return $remains;
    }

    public function getOkruh(){
        $sql = 'SELECT * FROM okruhy WHERE ID_ok = ?';
        $values = array($this->okruh);
        $query = Databaze::dotaz($sql, $values);
        return $query->fetch(PDO::FETCH_ASSOC);
        }

    public function printTest(){
        $okruh = $this->getOkruh();
        echo "<h2>Test z okruhu ". $okruh['okruh'] ."</h2>";
        $this->testovano = $okruh['testovano'];
        $slovicko = $this->noveSlovicko();
        if($slovicko !== false){
            echo "<form method='post' action='/test.php?okruh=". $this->okruh ."'>
                  <table><tr><td><label for='slovicko'>Přeložte slovíčko \"" . $slovicko['cesky'] . "\"</label></td>
                  <td><input type='text' name='slovicko' autocomplete='off' autofocus /></td>
                  <input type='hidden' name='ID' value = '". $slovicko['ID'] ."' />
                  <td><input type='submit' value='Ok'/></td></tr></table>
            </form>";
        }
    }

    public function zvyseniTestovani(){
        $sql = 'SELECT COUNT(*) FROM slovicka WHERE okruh = ?';
        $values = array($this->okruh);
        $query = Databaze::dotaz($sql, $values);
        $celkem = $query->fetch(PDO::FETCH_NUM)[0];
        $sql = 'SELECT COUNT(*) FROM slovicka WHERE okruh = ? AND vyrazeno = 1';
        $values = array($this->okruh);
        $query = Databaze::dotaz($sql, $values);
        $splneno = $query->fetch(PDO::FETCH_NUM)[0];
        $sql = 'UPDATE okruhy SET testovano=testovano+1, splneno = ?, celkem = ? WHERE ID_ok = ?';
        $values = array($splneno, $celkem, $this->okruh);
        Databaze::dotaz($sql, $values);
    }

    public function recountAll(){
        $sql = 'SELECT COUNT(*) FROM slovicka WHERE okruh = ?';
        $values = array($this->okruh);
        $query = Databaze::dotaz($sql, $values);
        $celkem = $query->fetch(PDO::FETCH_NUM)[0];
        $sql = 'SELECT COUNT(*) FROM slovicka WHERE okruh = ? AND vyrazeno = 1';
        $values = array($this->okruh);
        $query = Databaze::dotaz($sql, $values);
        $splneno = $query->fetch(PDO::FETCH_NUM)[0];
        $sql = 'UPDATE okruhy SET splneno = ?, celkem = ? WHERE ID_ok = ?';
        $values = array($splneno, $celkem, $this->okruh);
        Databaze::dotaz($sql, $values);
    }


    public function zkontrolujSlovicko($id, $slovicko){
        $sql='SELECT * FROM slovicka WHERE ID=?';
        $values = array($id);
        $query = Databaze::dotaz($sql, $values);
        $kontrola = $query->fetch(PDO::FETCH_ASSOC);
        $preklad = $kontrola['cizinsky'];
        if ($preklad===$slovicko) {
            echo "<p class='center'><img src='/okgreen.jpg' class='flag' />";
            if((($kontrola['uspechy']+1)>6) && (round((float)($kontrola['uspechy']+1)/($kontrola['testovano']+1), 2)>=0.7)){
                echo "  Slovíčko \"" . $kontrola['cesky'] . "\" bylo přeloženo správně jako \"" . $preklad . "\".
                Slovíčko bylo zařazeno mezi naučené. </p>";
                $sql = 'UPDATE slovicka SET testovano=testovano+1, uspechy = uspechy+1, vyrazeno = 1 WHERE ID = ?';
                $values=array($id);
                Databaze::dotaz($sql, $values);
            } else {
                echo "  Slovíčko \"" . $kontrola['cesky'] . "\" bylo přeloženo správně jako \"" . $preklad . "\".</p>";
                $sql = 'UPDATE slovicka SET testovano=testovano+1, uspechy = uspechy+1 WHERE ID = ?';
                $values = array($id);
                Databaze::dotaz($sql, $values);
            }
        } else {
            echo "<p class='center'><img src='/nored.jpg' class='flag' />";
            echo "  Slovíčko \"" . $kontrola['cesky'] . "\" mělo být přeloženo jako \"" . $preklad . "\".</p>";
            $sql = 'UPDATE slovicka SET testovano=testovano+1 WHERE ID = ?';
            $values=array($id);
            Databaze::dotaz($sql, $values);
        }
        $this->recountAll();
    }

}