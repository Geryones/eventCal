<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 23.09.2015
 * Time: 16:12
 *
 * hier wird der admin ausgeloggt
 */


include'includes/overall/header.php';


$user->logout();

Redirect::to('index.php');

include'includes/overall/footer.php';