<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 13.11.2015
 * Time: 09:38
 */

require_once 'includes/overall/header.php';



if($user->isLoggedIn()) {
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
            'eventName' => array(
                'name' => 'Event Name',
                'required' => true,
                'max' => 100
            ),
            'eventCast' => array(
                'name' => 'Cast',
                'max' => 255
            ),
            'eventDescription' => array(
                'name' => 'Event Description',
                'required' => true
            ),
            'eventDate' => array(
                'name' => 'Event Date',
                'required' => true,
                'date' => true
            ),
            'eventTimeHour' => array(
                'name' => 'Time of the Event ( Hours )',
                'required' => true,
                'minValue' => 0,
                'maxValue' => 23

            ),
            'eventTimeMinute' => array(
                'name' => 'Time of the Event ( Minutes )',
                'required' => true,
                'maxValue' => 59,
                'minValue' => 0
            ),
            'eventDuration' => array(
                'name' => 'Duration of the Event',
                'required' => true
            ),
            'eventPictureDescription' => array(
                'name' => 'Description of the EventPicture',
                'required' => true,
                'max' => 255
            ),
            'eventLink' => array(
                'name' => 'Event Link',
                'max' => 100
            ),
            'eventLinkDescription' => array(
                'name' => 'Description of the EventLink',
                'max' => 255
            ),
            'pricegroup' => array(
                'name' => 'Price Group',
                'required' => true
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

            //2015-11-12 15:26:53 das wollen wir
            $rawdate= new DateTime(Input::get('eventDate').' '.Input::get('eventTimeHour').':'.Input::get('eventTimeMinute').':00');
            $date =$rawdate->format('Y-m-d H:i:s');

            $eventName= Input::get('eventName');

            try {
                DB::getInstance()->insert('event', array(
                    'name' => $eventName,
                    'starring'=>Input::get('eventCast'),
                    'description'=>Input::get('eventDescription'),
                    'date'=>$date,
                    'duration'=>Input::get('eventDuration'),
                    'picture'=>$eventFileName,
                    'picturedescription'=>Input::get('eventPictureDescription'),
                    'link'=>Input::get('eventLink'),
                    'linkDescription'=>Input::get('eventLinkDescription'),
                    'fk_genre_id'=>Input::get('genre')

                ));

                $eventID=DB::getInstance()->get('event',array('name','=',$eventName))->first()->id;


                foreach(Input::get('pricegroup') as $price){
                    DB::getInstance()->insert('event_has_price',array(
                        'fk_pricegroup_id'=>intval($price),
                        'fk_event_id'=>$eventID
                    ));

                }

                Session::flash('success','Event successfully created');
                Redirect::to('admin.php');



            }catch (Exception $e){
                echo'something went terrible wrong<br>';
                die($e->getMessage());
            }

        }
    }

    ?>

  <h1>Create new Event</h1>
   <p>
        <form action="" method="post" enctype="multipart/form-data">
         <ul class="list-unstyled">
                <li>
                    <label for="eventName"> Eventname:</label><br>
                    <input type="text"  name="eventName" id="eventName" required="required"  value="<?php echo escape(Input::get('eventName'));?>"  ><br>
                </li>
                <li>
                    <label for="eventCast">Cast:</label><br>
                    <input type="text" name="eventCast" id="eventCast" value="<?php echo escape(Input::get('eventCast'));?>"  ><br>
                </li>
                <li>
                    <label for="eventDescription">Description:</label><br>
                   <textarea name="eventDescription" cols="40" rows="5" required="required" ><?php echo escape(Input::get('eventDescription'));?></textarea><br><br>

                </li>
                <li>
                    <label for="eventDate">Date of the Event</label><br>
                     <input  type="date" name="eventDate" id="eventDate" required="required" value="<?php echo escape(Input::get('eventDate'));?>" ><br><br>
                     <label>Start Time</label><br>
                     <label for="eventTimeHour">(Hour)</label> : <label for="eventTimeMinute">(Minutes)</label><br>
                     <input type="number" name="eventTimeHour" id="eventTimeHour" required="required" min="0" max="23" value="<?php echo escape(Input::get('eventTimeHour'));?>" >
                     :
                     <input type="number" name="eventTimeMinute" id="eventTimeMinute" required="required" min="0" max="59" value="<?php echo escape(Input::get('eventTimeMinute'));?>"  ><br>
                </li>
                <li>
                    <label for="eventDuration">Event Duration in Minutes</label><br>
                    <input type="number" name="eventDuration" id="eventDuration" required="required" min="0" value=<?php echo escape(Input::get('eventDuration'));?> ><br>
                </li>
                <li>
                    <label for="eventLink">Link</label><br>
                    <input type="text" name="eventLink" id="eventLink" value="<?php echo escape(Input::get('eventLink'));?>" ><br>
                </li>
                <li>
                    <label for="eventLinkDescription">Link Description</label><br>
                    <input type="text" name="eventLinkDescription" id="eventLinkDescription" value="<?php echo escape(Input::get('eventLinkDescription'));?>" ><br><br>
                </li>
                <li>
                    <label for="eventPicture">Picture for the Event</label><br>
                    <input type="file" name="eventPicture" id="eventPicture" required="required"  /><br>
                    <label for="eventPictureDescription">Picture Description</label><br>
                    <textarea name="eventPictureDescription" id="eventPictureDescription" cols="40" rows="5" required="required" ><?php echo escape(Input::get('eventPictureDescription'));?></textarea><br><br>
                </li>
                <li>
                    <label for="genre">Genre</label><br>
                    <select name="genre" required="required">
                        <?php
                            $genres=DB::getInstance()->getAll('genre')->results();
                            foreach($genres as $row){
                                echo'<option value="'.$row->id.'">'.$row->name.'</option>';
                            }
                        ?>
                    </select><br>

                    <br>
                </li>
                 <li>
                    <label>Pricegroups</label><br>
                    <?php
                    $pricegroup=DB::getInstance()->getAll('pricegroup')->results();
                    foreach($pricegroup as $row) {
                        echo '<input type="checkbox" name="pricegroup[]" value="' . $row->id . '">' . $row->name . ' : ' . $row->price . '<br>';
                    }
                    ?>
                     <br>
                </li>

                <li>

                   <input type="submit" value="Register">
                </li>
            </ul>
        </form>
    </p>
<?php
}else{
    Redirect::to('index.php');
}
require_once 'includes/overall/footer.php';