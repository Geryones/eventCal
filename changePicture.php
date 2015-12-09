<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 24.11.2015
 * Time: 16:34
 *
 * hier wird das wechseln von einem event-bild verwaltet
 */
require_once 'includes/overall/header.php';
/*
 * nur ein admin kann das machen
 */
if($user->isLoggedIn()){

    echo '<br><br>';

    if(!is_null($_POST['id'])) {

        $updateId = $_POST['id'];
        $_POST = array();
    }
    $db=DB::getInstance();

    $event = $db->get('event', array('id', '=', $updateId))->first();


    $fileSaver = new SaveFile();


    $validation= new Validate();
    if(Input::exists()) {


        //regeln für das bild
        $validation->checkFile($_FILES, array(
            'eventPicture' => array(
                'name' => 'Picture',
                'type' => 'picture',
                'required' => true,
                'maxFileSize' => 512 * 1000,//erlaubt 0.5MB grosse bilder
                'maxWidth' => 1000,
                'maxHeight' => 1000
            )
        ));



        //regeln für die picture-description
        $validation->check($_POST, array(
            'eventPictureDescription' => array(
                'name' => 'Description of the EventPicture',
                'required' => true,
                'max' => 255
            )

        ));


        if (!$validation->passed()) {
            foreach ($validation->errors() as $error) {
                echo $error . '<br>';
            }
        }


        if (!$validation->filePassed()) {
            foreach ($validation->fileErrors() as $error) {
                echo $error . '<br>';
            }
        }



        //wenn beides validiert werden konnte wird das bild gespeichert
        if ($validation->passed() && $validation->filePassed()) {
            $eventFileName=$fileSaver->savePicture($_FILES,'eventPicture');





            try {
                //name des alten bildes wird abgerufen um es später löschen zu können
                $old=$db->get('event',array('id','=',Input::get('eventId')))->first()->picture;

                //name des neuen bildes wird in die db gespeichert
                $db->update('event',Input::get('eventId'), array(
                    'picture'=>$eventFileName,
                    'picturedescription'=>Input::get('eventPictureDescription'),
                ));

                //altes bild wird gelöscht
                unlink(Config::get('pictures/dirOriginal').$old);



                Session::flash('success','EventPicture successfully changed');
                Redirect::to('admin.php');



            }catch (Exception $e){
                echo'something went terrible wrong<br>';
                die($e->getMessage());
            }

        }
    }


    ?>


    <form action="" method="post" enctype="multipart/form-data">
        <ul class="list-unstyled">
            <li>
                <label for="eventPicture">Picture for the Event</label><br>
                <input type="file" name="eventPicture" id="eventPicture" required="required"  /><br>
                <label for="eventPictureDescription">Picture Description</label><br>
                <textarea name="eventPictureDescription" id="eventPictureDescription" cols="40" rows="5" required="required" ><?php echo escape($event->picturedescription);?></textarea><br><br>
            </li>
            <input  type="hidden" name="eventId" value="<?php echo escape($event->id);?>">
            <input type="submit" value="Change Picture">
        </ul>

    </form>


<?php
}else{
    Redirect::to('index.php');
}


require_once 'includes/overall/footer.php';