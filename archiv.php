<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 18.11.2015
 * Time: 16:59
 *
 * auf dieser seite is das archiv, jeder hat zutritt
 * allerdings können admins das archiv weiterhin bearbeiten, das heisst events updaten oder löschen
 *
 */
include'includes/overall/header.php';


/*
 * hier findet die pagination statt
 * für die pagination braucht es die anzahl "posts" pro seite, dieser ist per default auf 5 gesetzt
 */
$pagination = new Pagination('event','archiv.php');
$eventOrganizer = new EventOrganizer();

$pageRows=5;
$actualPage=1;
$lastPage=$pagination->lastPage($pageRows);


if(isset($_GET['page'])){
    $actualPage=preg_replace('#[^0-9]#', '', $_GET['page']);
}

if($actualPage<1){
    $actualPage=1;
}else if($actualPage>$lastPage){
    $actualPage=$lastPage;
}

$content=$pagination->getContent($actualPage,$pageRows);



echo'<h1> this is archiv</h1>'."\n";

$eventOrganizer->organizeEvents($content,$user);

$controls= $pagination->createPaginationControls($actualPage,$pageRows);
?>



<div id="bottomNavigation">
    <div id = "pagination_controls">
        <?php echo $controls?>
    </div>
</div>

<?php




include'includes/overall/footer.php';?>
