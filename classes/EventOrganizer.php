<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 20.11.2015
 * Time: 09:53
 */

/**
 * Class EventOrganizer
 * class welche die organisation der events regelt, also die darstellung
 */
class EventOrganizer {

    /**
     * funktion um die events in kacheln darzustellen, kacheln haben für eingeloggte user ein bearbeiten und lösch-button
     * @param object $events enthält die events
     * @param object $user der momentane user, wenn der user nicht angemeldet ist sieht er keine buttons
     */
    public function organizeEvents($events,$user){
        echo'<div class="container">'."\n";
        echo '<div class="row border">'."\n";

        $x=1;

        foreach($events as $event){
            $divider="";
            echo '<style>'."\n";
            echo '#event-'.$event->id.'{'."\n";
            //css für jedes div, enthält den background etc
            echo 'background:linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url("'.Config::get('pictures/dirOriginal').$event->picture.'") no-repeat;'."\n";
            echo 'background-position: center;'."\n";
            echo 'background-size: cover;'."\n";
            echo  '}'."\n";
            echo '</style>'."\n";
            echo '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">'."\n";
            echo ' <div class="classWithPad" id="event-'.$event->id.'" title="'.$event->picturedescription.'">'."\n";

            //content  wird hier generiert
            $this->generateEvent($event);


            //falls eingeloggt werden noch die buttons generiert
            if ($user->isLoggedIn()) {
                echo '<table>'."\n";
                echo '<tbody>'."\n";
                echo '<tr>'."\n";
                echo '<td>'."\n";


                echo '<form action="updateEvent.php" method="post" class="buttons btn">' . "\n";
                echo '<button type="submit" name="update" value="' . $event->id . '">Update</button>' . "\n";
                echo '</form>' . "\n";
                echo '</td>'."\n";

                echo '<td>'."\n";
                echo '<form action="deleteEvent.php" method="post" class="buttons btn">' . "\n";
                echo '<button type="submit" name="delete" value="' . $event->id . '">delete</button>' . "\n";
                echo '</form>' . "\n";
                echo '</td>'."\n";
                echo '</tr>'."\n";
                echo '</tbody>'."\n";
                echo '</table>'."\n";

            }


            echo '</div>'."\n";
            echo '</div>'."\n";
            //hier wird das layout responsible gemacht, je nach fenster breite werden unterschieldiche viele events nebeneinander angezeigt
            if($x%2==0){
                echo'<!-- every 2nd col -->'."\n";
                $divider='<div class="visible-xs visible-sm clearfix divider"></div>'."\n";
            }
            if($x%3==0){
                echo'<!-- every 3nd col -->'."\n";
                $divider=' <div class="visible-md clearfix divider"></div>'."\n";
            }
            if($x%4==0){
                echo'<!-- every 4nd col -->'."\n";
                $divider='<div class="visible-xs visible-sm visible-lg clearfix divider"></div>'."\n";
            }
            if($x%6==0){
                echo'<!-- every 6nd col -->'."\n";
                $divider='<div class="visible-xs visible-sm clearfix divider"></div><div class="visible-md clearfix divider"></div>'."\n";
            }
            $x++;
            echo $divider;

        }



        echo' </div>'."\n";
        echo' </div>'."\n";
    }


    /**
     * Funktion um den inhalt eines events zu generieren
     * @param object $event  einzelner event in form eines objekts
     */
   private function generateEvent($event){

        $db=DB::getInstance();

       //genre wird für den event abgerufen
        $genre = $db->get('genre',array('id','=',$event->fk_genre_id))->first()->name;

       //die id's für alle pricegroupen die ein event hat werden abgerufen
        $priceKeys=$db->get('event_has_price',array('fk_event_id','=',$event->id))->results();

        echo '<ul class="list-unstyled">'."\n";
        echo '<li><h6>'.$event->date.'</h6></li>'."\n";
        echo '<li><h4>'.$event->name.'</h4></li>'."\n";
        echo '<li><h5>'.$event->description.'</h5></li>'."\n";
        echo '<li><h4>'.$genre.'</h4></li>'."\n";
        echo '<li><h6> Dauer: '.$event->duration.' Minuten</h6></li>'."\n";

       //wenn der link nicht lehr ist und die link description auch nicht wird ein link eingehängt
        if($event->link!="" && $event->linkDescription!=""){
            echo '<li><h5>Link: <a href="'.$event->link.'">-->'.$event->linkDescription.'<--</a> </h5></li>'."\n";
        }
       echo '</ul>'."\n";

        echo '<table>'."\n";
        echo '<tbody>'."\n";
        echo '<tr>'."\n";
        echo '<td colspan="2">--Preis--</td>'."\n";
        echo '</tr>'."\n";

        //für jede id von pricegroup wird nun ein tabelleneintrag gemacht im fertigen event
        foreach($priceKeys as $price){
            $priceGroup=$db->get('pricegroup',array('id','=',$price->fk_pricegroup_id))->first();
            echo '<tr>'."\n";
            echo '<td>'.$priceGroup->name.':  </td>'."\n";
            echo '<td>'.$priceGroup->price.'</td>'."\n";
            echo '</tr>'."\n";
        }
        echo '</tbody>'."\n";
        echo '</table>'."\n";




    }


}