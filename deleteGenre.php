<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 24.11.2015
 * Time: 08:34
 */
require_once 'includes/overall/header.php';


if($user->isLoggedIn()) {
    if(Input::exists()){

        foreach($_POST['genre'] as $id){
            DB::getInstance()->delete('genre',array('id','=',$id));
        }
    }
?>

<h1>Löschen von Genres</h1>



<?php
    echo '<form action="" method="post">'."\n";
    $genres=DB::getInstance()->getDeletableGenres()->results();
    if(count($genres)) {
       echo '<h6>Es werden nur Genres angezeigt, die zur Zeit nicht in einem Event verwendet werden</h6>'."\n";
        foreach ($genres as $row) {
            echo '<input type="checkbox" name="genre[]" value="' . $row->id . '">' . $row->name . '<br>' . "\n";
        }
        echo '<input type="submit" value="Delete" >';
        echo '</form>' . "\n";
    }else{
        echo '<h4> Es können zur Zeit keine Genres gelöscht werden</h4>';
    }




}else{
    Redirect::to('index.php');
}
require_once 'includes/overall/footer.php';