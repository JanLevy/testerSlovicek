<?php


class Strankovnik
{
    private $title;

    public function __construct($title)
    {
        $this->title = $title;
    }

    /*
     * Vytiskne hlavicku s nazvem stranky
     */
    public function printHead(){
        echo "
        <head>
            <meta charset='UTF-8'>
            <title>" . $this->title . "</title>
            <link rel='stylesheet' type='text/css' href='/stylesheet.css?". date('l jS \of F Y h:i:s A') ."' />
        </head>
        <body>
        <div id='container'>";
    }

    /*
     * Vytiskne navigacni menu
     */
    public function printMenu(){
        $stranky = array(
            '/index.php' => 'Výběr lekcí',
            '/spravaOkruhu.php' => 'Správa okruhů'
        );
        echo "
    <ul>";
        foreach ($stranky as $stranka => $popis){
            if ($this->title === $popis){
                echo "<li><a href = '" . $stranka . "' id='active'>" . $popis . "</a></li>";
            } else {
                echo "<li><a href = '" . $stranka . "'>" . $popis . "</a></li>";
            }
        }

        echo "
    </ul>";
    }

    /*
     * Vytiskne ukonceni stranky a paticku
     */
    public function printFooter(){
        echo "
            </div>
        <div id='footer'>'&copy;' Jan Levý 2016</div>
    </body>
    ";
    }
}