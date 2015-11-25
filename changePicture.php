<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 24.11.2015
 * Time: 16:34
 */
require_once 'includes/overall/header.php';

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



        $validation->checkFile($_FILES, array(
            'eventPicture' => array(
                'name' => 'Picture',
                'type' => 'picture',
                'required' => true,
                'maxFileSize' => 3072 * 1000,//erlaubt 3MB grosse bilder
                'maxWidth' => 4000,
                'maxHeight' => 4000
            )
        ));




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
            foreach ($validation->errors() as $error) {
                echo $error . '<br>';
            }
        }





        if ($validation->passed() && $validation->filePassed()) {
            $eventFileName=$fileSaver->savePicture($_FILES,'eventPicture');





            try {
                $old=$db->get('event',array('id','=',Input::get('eventId')))->first()->picture;


                $db->update('event',Input::get('eventId'), array(
                    'picture'=>$eventFileName,
                    'picturedescription'=>Input::get('eventPictureDescription'),
                ));


                unlink(Config::get('pictures/dirOriginal').$old);



                //Session::flash('success','EventPicture successfully changed');
                //Redirect::to('admin.php');



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