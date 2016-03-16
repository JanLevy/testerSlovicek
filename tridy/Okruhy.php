<?php


class Okruhy
{
    public static function getOkruhy($jazyk){
        $sql = 'SELECT * FROM okruhy WHERE jazyk = ?';
        $values = array($jazyk);
        $query = Databaze::dotaz($sql, $values);
        return $query;
    }
}