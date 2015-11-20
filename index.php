<?php
include'includes/overall/header.php';
/**
 * die hauptseite und einstiegspunkt
 *
 */

?>
<h1>Home</h1>
<?php


$events= DB::getInstance()->getUpCommingEvents('event')->results();

$organizer = new EventOrganizer();


?>

<h2>Upcoming Events</h2>
<?php

$organizer->organizeEvents($events);
?>








<?php

include'includes/overall/footer.php';
?>

