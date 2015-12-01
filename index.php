<?php
include'includes/overall/header.php';
/**
 * die hauptseite und einstiegspunkt
 * alle kommende events werden hier dargestellt
 *
 */

?>
<h1>Home</h1>
<?php
if(Session::exists('success')){
    echo '<h3>'. Session::flash('success').'</h3>';
}



$db=DB::getInstance();
$organizer = new EventOrganizer();
//hier werden alle genres aufgerufen die bei den kommenden events verwendet werden
$genres=$db->getDistinctUpComming('event','fk_genre_id')->results();
?>
<!-- hier wird ein dropdwon menu kreiert für die genres-->
<form method="post" action="">
    <select name="genre" required="required">
        <option value="all"> Show All</option>
        <?php

        foreach($genres as $genre){
            $row= $db->get('genre',array('id','=',$genre->fk_genre_id))->first();

            echo '<option value="' . $row->id . '">' . $row->name . '</option>' . "\n";


        }
        ?>
    </select>
    <input type="submit" value="Do it">

</form>
<h6> Es können nur Genres ausgewählt werden, die auch vorkommen</h6>


<?php

/*
 * hier kann man die events filtern nach genre, sobald man den button betätitgt werden nur noch die events
 * angezeigt, die dem gewünschten genre entsprechen
 */
if(Input::exists()){
    $selectedGenre=Input::get('genre');

    if($selectedGenre==="all"){
        $events=$db->getUpCommingEvents('event')->results();

    }else{
        $events=$db->getUpCommingEvents('event',array('fk_genre_id','=',$selectedGenre))->results();


    }

    $organizer->organizeEvents($events,$user);
}else{
    $events=$db->getUpCommingEvents('event')->results();
    $organizer->organizeEvents($events,$user);
}








include'includes/overall/footer.php';
?>

