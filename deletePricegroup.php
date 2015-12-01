<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 24.11.2015
 * Time: 08:34
 *
 * auf dieser seite wird das löschen von pricegroups verwaltet
 */
require_once 'includes/overall/header.php';

//nur angemeldete admins haben zugriff
if($user->isLoggedIn()) {
    if(Input::exists()) {
        //für jeden eintrag im post-array wird der entsprechende eintrag in der datenbank gelöscht
        foreach ($_POST['pricegroup'] as $id) {
            DB::getInstance()->delete('pricegroup', array('id', '=', $id));
        }
    }

?>

<h3>Löschen von PriceGroups</h3>
    <h6>Es werden nur PriceGroups angezeigt, die zur Zeit nicht in einem Event verwendet werden</h6>


<?php
    echo '<form action="" method="post">'."\n";
    $pricegroups=DB::getInstance()->getDeletablePriceGroups()->results();
    //nur wenn es pricegroups gibt die zur zeit nicht verwendet werden wird button und auswahl-checkboxen generiert
    if(count($pricegroups)) {
        foreach ($pricegroups as $row) {
            echo '<input type="checkbox" name="pricegroup[]" value="' . $row->id . '">' . $row->name . ' : ' . $row->price . '<br>';
        }
        echo '<input type="submit" value="Delete" >';
        echo '</form>' . "\n";
    }else{
        echo 'zur zeit können keine Pricegroups gelöscht werden';
    }


}else{
    Redirect::to('index.php');
}

require_once 'includes/overall/footer.php';