<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 20.11.2015
 * Time: 09:53
 */

class EventOrganizer {


    public function organizeEvents($events,$user){
        echo'<div class="container">'."\n";
        echo '<div class="row border">'."\n";

        $x=1;

        foreach($events as $event){
            $divider="";
            echo '<style>'."\n";
            echo '#event-'.$event->id.'{'."\n";
            echo 'background:linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url("'.Config::get('pictures/dirOriginal').$event->picture.'") no-repeat;'."\n";
            echo 'background-position: center;'."\n";
            echo 'background-size: cover;'."\n";
            echo  '}'."\n";
            echo '</style>'."\n";
            echo '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">'."\n";
            echo ' <div class="classWithPad" id="event-'.$event->id.'">'."\n";


            $this->generateEvent($event);



            if ($user->isLoggedIn()) {
                echo '<table class="price">'."\n";
                echo '<tbody>'."\n";
                echo '<tr>'."\n";
                echo '<td>'."\n";


                echo '<form action="updateEvent.php" method="post" class="buttons">' . "\n";
                echo '<button type="submit" name="update" value="' . $event->id . '">Update</button>' . "\n";
                echo '</form>' . "\n";
                echo '</td>'."\n";

                echo '<td>'."\n";
                echo '<form action="deleteEvent.php" method="post" class="buttons">' . "\n";
                echo '<button type="submit" name="delete" value="' . $event->id . '">delete</button>' . "\n";
                echo '</form>' . "\n";
                echo '</td>'."\n";
                echo '</tr>'."\n";
                echo '</tbody>'."\n";
                echo '</table>'."\n";

            }


            echo '</div>'."\n";
            echo '</div>'."\n";

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





    public function generateEvent($event){

        $db=DB::getInstance();

        $genre = $db->get('genre',array('id','=',$event->fk_genre_id))->first()->name;

        $priceKeys=$db->get('event_has_price',array('fk_event_id','=',$event->id))->results();

        echo '<ul class="list-unstyled">'."\n";
        echo '<h7>'.$event->date.'</h7>'."\n";
        echo '<h4>'.$event->name.'</h4>'."\n";
        echo '<h5>'.$event->description.'</h5>'."\n";
        echo '<h4>'.$genre.'</h4>'."\n";
        echo '<h7> Dauer: '.$event->duration.' Minuten</h7>'."\n";

        if($event->link!="" && $event->linkDescription!=""){
            echo '<h5>Link: <a href="'.$event->link.'">-->'.$event->linkDescription.'<--</a> </h5>'."\n";
        }

        echo '<table>'."\n";
        echo '<tbody>'."\n";
        echo '<tr>'."\n";
        echo '<td colspan="2">--Preis--</td>'."\n";
        echo '</tr>'."\n";


        foreach($priceKeys as $price){
            $priceGroup=$db->get('pricegroup',array('id','=',$price->fk_pricegroup_id))->first();
            echo '<tr>'."\n";
            echo '<td>'.$priceGroup->name.':  </td>'."\n";
            echo '<td>'.$priceGroup->price.'</td>'."\n";
            echo '</tr>'."\n";
        }
        echo '</tbody>'."\n";
        echo '</table>'."\n";
        echo '</ul>'."\n";



    }


}