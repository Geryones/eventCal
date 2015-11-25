<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 22.09.2015
 * Time: 08:51
 */

/*
 * muss fast überall am anfang implementiert werden und wird daher im file header.php aufgerufen
 * hier werden die globalen einstellungen vorgenommen
 */
session_start();
session_regenerate_id();
error_reporting(0);
$GLOBALS['config']=array(
    /*
     * hier sind die einstellungen für die datenbank gespeichert
     * der name bezeichnet zugleich auch die art der datenbank
     * da mit pdo gearbeitet wird, kann man hier für einen anderen datenbank typen einfach einen neuen array einfügen
     */
    'mysql'=>array(
        'host'=>'127.0.0.1',
        'username'=>'root',
        'password'=>'',
        'db'=>'eventCalendar'
    ),
    /*
     * einstellungen für die session
     * name und token
     */
    'session'=>array(
        'session_name'=>'user',
        'token_name'=>'token'
    ),
    /*
     * einstellungen für das speichern von bilder
     */
    'pictures'=>array(
        'dirOriginal'=>"../eventCal/files/pictures/",
        'dirThumbnail'=>"../eventCal/files/pictures/thumbnails/"
    )
);


/**
 * diese funktion ersetzt fast alle require_once
 * wenn man auf einer seite, wo init.php verwendet wird, einen auruf einer klasse macht wird diese automatisch
 *'required'--> aufruf DB = new DB(), dann wird in dieser funktion automatisch require_once 'classes/db.php'; ausgeführt
 * $class= gewünschte klasse
 */
spl_autoload_register(function($class){
    require_once 'classes/'.$class.'.php';
});


require_once 'functions/sanitize.php';

