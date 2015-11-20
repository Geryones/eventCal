<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 20.11.2015
 * Time: 09:53
 */

class EventOrganizer {



    public function organizeEvents($events){
        echo'<div class="container">'."\n";
        echo '<div class="row border">'."\n";

        $x=1;

        foreach($events as $event){
            $divider="";
            echo '<style>'."\n";
            echo '#event-'.$event->id.'{'."\n";
            echo 'background:linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url("'.Config::get('pictures/dirOrginal').$event->picture.'") no-repeat;'."\n";
            echo 'background-position: center;'."\n";
            echo 'background-size: cover;'."\n";
            echo  '}'."\n";
            echo '</style>'."\n";
            echo '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">'."\n";
            echo ' <div class="classWithPad" id="event-'.$event->id.'">'."\n";


            //inhalt muss noch dargestellt werden

            var_dump($event);




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

}