<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 24.11.2015
 * Time: 09:47
 */
require_once 'includes/overall/header.php';


if($user->isLoggedIn()){
    var_dump($_POST);
    $db=DB::getInstance();
    $rowCount=$db->get('event_has_price',array('fk_event_id','=',$_POST['delete']))->count();

    for($i=0;$i<$rowCount;$i++){
        $db->delete('event_has_price',array('fk_event_id','=',$_POST['delete']));
    }

    $db->delete('event',array('id','=',$_POST['delete']));

    Session::flash('success','you deleted an Event');
    Redirect::to('index.php');

}else{
    Redirect::to('index.php');
}


require_once 'includes/overall/footer.php';