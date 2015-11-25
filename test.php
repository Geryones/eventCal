<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 13.11.2015
 * Time: 11:48
 */
require_once 'includes/overall/header.php';

if($user->isLoggedIn()){

   $db=DB::getInstance();
    $event= $db->getUpCommingEvents('event')->first();
    $organizer = new EventOrganizer();

    $organizer->generateEvent($event);







}else{
    Redirect::to('index.php');
}





require_once 'includes/overall/footer.php';

?>
