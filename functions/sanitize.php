<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 22.09.2015
 * Time: 08:52
 */

/**
 * funktion um ein string zu säubern, sollte alle script oder sql statements verhindern
 * zusätzliche sicherheit zum prepared statement
 * @param string $string der zu säubernde string
 * @return string der gesäubert wurde
 */
function escape($string){
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}