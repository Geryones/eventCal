<?php
include'includes/overall/header.php';
/**
 * die hauptseite und einstiegspunkt
 *
 */

?>
<h1>Home</h1>
<?php
if(Session::exists('success')){
    echo '<h3>'. Session::flash('success').'</h3>';
}


$events= DB::getInstance()->getUpCommingEvents('event')->results();

$organizer = new EventOrganizer();


?>

<h2>Upcoming Events</h2>
<?php

$organizer->organizeEvents($events,$user);
?>








<?php

include'includes/overall/footer.php';
?>

