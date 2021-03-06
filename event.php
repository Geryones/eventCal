<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 13.11.2015
 * Time: 09:38
 *
 *
 * auf dieser seit kann man einen neuen event anlegen
 */

require_once 'includes/overall/header.php';


//können nur angemeldete admins
if($user->isLoggedIn()) {
    $fileSaver = new SaveFile();


    $validation= new Validate();
    if(Input::exists()) {


        //vorgaben für bilder
        $validation->checkFile($_FILES, array(
            'eventPicture' => array(
                'name' => 'Picture',
                'type' => 'picture',
                'required' => true,
                'maxFileSize' => 512 * 1000,//erlaubt 0.5mb grosse bilder
                'maxWidth' => 1000,
                'maxHeight' => 1000
            )
        ));

        //vorgaben für inputfelder
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
                'required' => true,
                'miniValue'=>1
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

        //date-time stempel wird gebaut hmm
        $rawdate= new DateTime(Input::get('eventDate').' '.Input::get('eventTimeHour').':'.Input::get('eventTimeMinute').':00');
        $date =$rawdate->format('Y-m-d H:i:s');
        $duration=Input::get('eventDuration');
        $endTime = strtotime("+{$duration} minutes", strtotime($date));

        //hier wird überprüft ob ein event in einem zeitkonflikt mit einem anderen event steht
        $conflicts=DB::getInstance()->getInterferingEvents('event','date',$date,date('Y-m-d h:i:s', $endTime))->results();
        if(count($conflicts)){
            $validation->addError('Dieses Event steht in einem Zeitkonflikt mit einem bereits bestehendem Event');
            $validation->setPassed(false);
        }


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

        //wenn alles validiert werden konnte und es keine fehler gab wird das file gespeichert
        if ($validation->passed() && $validation->filePassed()) {
            $eventFileName=$fileSaver->savePicture($_FILES,'eventPicture');




            $eventName= Input::get('eventName');

            try {
                //event wird generiert
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

                //alle preisgruppen werden in der db gesichert
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

        <form action="#" method="post" enctype="multipart/form-data">
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
                    <label>Description:</label><br>
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
                    <input type="number" name="eventDuration" id="eventDuration" required="required" min="1" value="<?php echo escape(Input::get('eventDuration'));?>" ><br>
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
                    <label>Genre</label><br>
                    <select name="genre" required="required">
                        <option  value=""> Choose Genre</option>
                        <?php
                            $genres=DB::getInstance()->getAll('genre')->results();
                            foreach($genres as $row){
                                echo'<option value="'.$row->id.'">'.$row->name.'</option>'."\n";
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
                        echo '<input type="checkbox" name="pricegroup[]" value="' . $row->id . '">' . $row->name . ' : ' . $row->price . '<br>'."\n";
                    }
                    ?>
                     <br>
                </li>

                <li>

                   <input type="submit" value="Register">
                </li>
            </ul>
        </form>

<?php
}else{
    Redirect::to('index.php');
}
require_once 'includes/overall/footer.php';