<?php
include'includes/overall/header.php';
/**
 * die hauptseite und einstiegspunkt
 *
 */

?>

        <h1>Home</h1>
        <p>
            <h2>Upcomming Event</h2>
            <?php

           $event= DB::getInstance()->getAll('event','ASC','date')->first();

            var_dump($event);

            ?>
        </p>







<?php

include'includes/overall/footer.php';
?>

