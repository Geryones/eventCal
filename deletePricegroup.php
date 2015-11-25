<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 24.11.2015
 * Time: 08:34
 */
require_once 'includes/overall/header.php';


if($user->isLoggedIn()) {
    if(Input::exists()) {
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