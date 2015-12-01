<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 13.11.2015
 * Time: 11:48
 *
 * eine testpage
 * kann von den admins genutzt werden falls sie etwas ausprobieren möchten
 *
 * **warnung alle änderung die nicht aussschliesslich auf dieser seite gemacht werden beeinflussen die ganze app** !!!!
 *
 *
 */
require_once 'includes/overall/header.php';

if($user->isLoggedIn()){
    echo '<h1> this is testing ground</h1>';




}else{
    Redirect::to('index.php');
}





require_once 'includes/overall/footer.php';

?>
