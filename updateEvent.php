<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 24.11.2015
 * Time: 09:47
 *
 *
 * auf dieser seite wird das udaten von einem bestehenden event verwaltet
 */
require_once 'includes/overall/header.php';



//nur angemeldete admins haben zugriff
if($user->isLoggedIn()) {

    if(!is_null($_POST['update'])) {

        $updateId = $_POST['update'];
        $_POST = array();
    }
    $db=DB::getInstance();
    //bestehender event wird in der db aufgerufen
    $event = $db->get('event', array('id', '=', $updateId))->first();




        $validation = new Validate();
        if (Input::exists()) {

            //regeln für alle felder, analog zum erstellen eines events
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

            //date-time stempel wird gebaut
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


            //wenn alles validiert werden konnte wird hier der event aktualisiert
            if ($validation->passed() ) {

                //2015-11-12 15:26:53 das wollen wir
                $rawdate = new DateTime(Input::get('eventDate') . ' ' . Input::get('eventTimeHour') . ':' . Input::get('eventTimeMinute') . ':00');
                $date = $rawdate->format('Y-m-d H:i:s');

                $eventName = Input::get('eventName');


                try {
                    //neue daten werden in der db gespeichert
                    $db->update('event', Input::get('id'), array(
                            'name' => $eventName,
                            'starring' => Input::get('eventCast'),
                            'description' => Input::get('eventDescription'),
                            'date' => $date,
                            'duration' => Input::get('eventDuration'),
                            'link' => Input::get('eventLink'),
                            'linkDescription' => Input::get('eventLinkDescription'),
                            'fk_genre_id' => Input::get('genre')

                        ));




                    $eventID = $db->get('event', array('name', '=', $eventName))->first()->id;

                    $rowCount=$db->get('event_has_price',array('fk_event_id','=',$_POST['delete']))->count();
                    //alte verknüpfungen mit pricegroups werden alle gelöscht
                    for($i=0;$i<$rowCount;$i++){
                        $db->delete('event_has_price',array('fk_event_id','=',$_POST['delete']));
                    }

                    //die neuen verknüpfungen werden gespeichert
                    foreach (Input::get('pricegroup') as $price) {
                        $db->insert('event_has_price', array(
                            'fk_pricegroup_id' => intval($price),
                            'fk_event_id' => $eventID
                        ));

                    }

                    Session::flash('success', 'Event successfully updated');
                   Redirect::to('admin.php');


                } catch (Exception $e) {
                    echo 'something went terrible wrong<br>';
                    die($e->getMessage());
                }

            }
        }


?>

<h1>Update Event</h1>

<h3>Due to a technical Problem, the Picture can only be changed on a other site
<form name="changePicture" method="post" action="changePicture.php">
    <br>
    <input type="hidden" name="id" value="<?php echo escape($event->id);?>">
    <input type="submit" value="Change Picture">
</form> <br>
We are sorry for the inconvenience </h3>


   <p>
        <form action="" method="post">
         <ul class="list-unstyled">
                <li>
                    <label for="eventName"> Eventname:</label><br>
                    <input type="text"  name="eventName" id="eventName" required="required"  value="<?php echo escape($event->name);?>"  ><br>
                    <input  type="hidden" name="id" value="<?php echo escape($event->id);?>">
                </li>
                <li>
                    <label for="eventCast">Cast:</label><br>
                    <input type="text" name="eventCast" id="eventCast" value="<?php echo escape($event->starring);?>"  ><br>
                </li>
                <li>
                    <label for="eventDescription">Description:</label><br>
                   <textarea name="eventDescription" cols="40" rows="5" required="required" ><?php echo escape($event->description);?></textarea><br><br>

</li>
<li>
    <label for="eventDate">Date of the Event</label><br>
    <input  type="date" name="eventDate" id="eventDate" required="required" value="<?php echo escape(substr($event->date,0,10));?>" ><br><br>
    <label>Start Time</label><br>
    <label for="eventTimeHour">(Hour)</label> : <label for="eventTimeMinute">(Minutes)</label><br>
    <input type="number" name="eventTimeHour" id="eventTimeHour" required="required" min="0" max="23" value="<?php echo escape(substr($event->date,11,2));?>" >
    :
    <input type="number" name="eventTimeMinute" id="eventTimeMinute" required="required" min="0" max="59" value="<?php echo escape(substr($event->date,14,2));?>"  ><br>
</li>
<li>
    <label for="eventDuration">Event Duration in Minutes</label><br>
    <input type="number" name="eventDuration" id="eventDuration" required="required" min="0" value=<?php echo escape($event->duration);?> ><br>
</li>
<li>
    <label for="eventLink">Link</label><br>
    <input type="text" name="eventLink" id="eventLink" value="<?php echo escape($event->link);?>" ><br>
</li>
<li>
    <label for="eventLinkDescription">Link Description</label><br>
    <input type="text" name="eventLinkDescription" id="eventLinkDescription" value="<?php echo escape($event->linkDescription);?>" ><br><br>
</li>

<li>
    <label for="genre">Genre</label><br>
    <select name="genre" required="required">
        <?php
    /*
     * hier wird das dropdown menü für die genres generiert
     * da immer nur ein genre pro event existiert eigent sich das dropdown
     */
        $genres=DB::getInstance()->getAll('genre')->results();

        foreach($genres as $row){
            if($event->fk_genre_id==$row->id){
                echo'<option value="'.$row->id.'" selected>'.$row->name.'</option>'."\n";

            }else{
                echo'<option value="'.$row->id.'">'.$row->name.'</option>'."\n";
            }

        }
        ?>
    </select><br>

    <br>
</li>

<li>
    <label>Pricegroups</label><br>
    <?php
    /*
     *hier wird das auswahl menü für die pricegroups  kreiert,
     * für jede pricegroup gibt es eine checkbox
     */
    $pricegroup=DB::getInstance()->getAll('pricegroup')->results();
    $selectedGroups=DB::getInstance()->get('event_has_price',array('fk_event_id','=',$event->id))->results();
    $prices=array();
    foreach($selectedGroups as $row){
        $prices[]=$row->fk_pricegroup_id;
    }
    foreach($pricegroup as $row) {

        if(in_array($row->id,$prices,true)){
            echo '<input type="checkbox" name="pricegroup[]" value="' . $row->id . '" checked>' . $row->name . ' : ' . $row->price . '<br>';
        }else{
            echo '<input type="checkbox" name="pricegroup[]" value="' . $row->id . '">' . $row->name . ' : ' . $row->price . '<br>';
        }
    }
    ?>
    <br>
</li>

<li>

    <input type="submit" value="Save Changes">
</li>
</ul>
</form>
</p>
<?php
}else{
    Redirect::to('index.php');
}
require_once 'includes/overall/footer.php';