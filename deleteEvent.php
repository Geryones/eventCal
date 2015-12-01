<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 24.11.2015
 * Time: 09:47
 *
 * auf dieser seite wird das löschen von einem ganzen event verwaltet
 */
require_once 'includes/overall/header.php';

//nur angemeldete user haben zugriff auf diese funktion
if($user->isLoggedIn()){

    $db=DB::getInstance();
    //wenn im post-array ein eintrag mit delete ist( kommt vom button)
    $rowCount=$db->get('event_has_price',array('fk_event_id','=',$_POST['delete']))->count();
    //zuerst wird in der tabelle event_has_price alle einträge gelöscht mit der id des betreffenden events
    for($i=0;$i<$rowCount;$i++){
        $db->delete('event_has_price',array('fk_event_id','=',$_POST['delete']));
    }

    //dann wird der event selber gelöscht
    $db->delete('event',array('id','=',$_POST['delete']));

    Session::flash('success','you deleted an Event');
    Redirect::to('index.php');

}else{
    Redirect::to('index.php');
}


require_once 'includes/overall/footer.php';