<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 13.11.2015
 * Time: 12:08
 */
require_once 'includes/overall/header.php';
?>

<?php
if(intval($_POST['eventTimeHour'])<10){
    $hour='0'.$_POST['eventTimeHour'];
}
if(intval($_POST['eventTimeMinute'])<10){
    $minute='0'.$_POST['eventTimeMinute'];
}


$date=$_POST['eventDate'].' '.$hour.':'.$minute.':00';
echo $date;
?>


<?php

require_once 'includes/overall/footer.php';
