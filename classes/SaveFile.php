<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 29.10.2015
 * Time: 10:44
 */


/**
 * Class SaveFile wird verwendet um Files zu speichern
 * bevor ein file gespeichert wird, muss es umbenannt werden, wird auch hier gemacht
 */
class SaveFile {

    private $_user;

    
    public function __construct(){
        $this->_user=new User();
    }

    /**
     *
     * Funktion um ein bild zu speichern
     * @param array $files das bild in array form ( $_files or $_post)
     * @param string $field name des input-feldes
     * @return bool|string bool falls was nicht funktioniert, string mit dem filename falls es funktioniert
     */
    public function savePicture($files, $field)
    {
       // echo 'still alife <br>';
        //der bild name
        $fileName=escape($files[$field]['name']);

        //neuer name = eine-id und der alte name
        $newFileName = uniqid().'_'.$fileName;
        //der temporär name
        $tmpName = $files[$field]['tmp_name'];


        //das bild wird umbenannt und verschoben
        if(!move_uploaded_file($tmpName, Config::get('pictures/dirOriginal').$newFileName)){
            return false;
        }


        return $newFileName;

    }


    /************wird zur zeit nicht verwendet************
     *
     *
     *
     * funktion um ein thumbnail von einem bild zu erstellen, speichern und in der datenbank einzutragen
     * @param string $originalFilename name des orginal bildes
     * @param int $width gewünschte breite des neuen bilds
     * @param int $height geünschte höhe des neuen bilds
     * @return bool statusmeldung
     */
    public function createResized($originalFilename,$width=null,$height=null){
        //masse für das thumbnail werden definiert
        if($width===null){
            $thumbWidht=75;
        }else{
            $thumbWidht=$width;
        }
        if($height===null){
            $thumbHeight=50;
        }else{
            $thumbHeight=$height;
        }

        //speicherort wird aus dem globalen array ausgelesen
        $thumbdir=Config::get('pictures/dirThumbnail');
        //da der name des orginals bereits unique ist, hänge ich einfach noch thumb_ davor
        $thumbname='thumb_'.$originalFilename;

        //liste mit allen relevanten eigenschaften des orginals
        list($originalWidth,$originalHeight,$originalType,$orginalAttr)=getimagesize(Config::get('pictures/dirOriginal').$originalFilename);



        //wenn breite grösser ist, wird die breite = max thumbWidth und die höhe im verhältnis berechnet
        if($originalWidth>$originalHeight){
            $newWidth=$thumbWidht;
            $newHeight=intval($originalHeight*$newWidth/$originalWidth);
        }else{
            //hier ist das bild hochformat, das heisst die breite wird anhand der höhe berechnet
            $newHeight=$thumbHeight;
            $newWidth=intval($originalWidth*$newHeight/$originalHeight);
        }
        //hier werden die schwarzen ränder berechnet ( falls das bild nicht den proportionen des thumbs entspricht, dammit die proportionen des bildes erhalten bleiben
        $randX=intval(($thumbWidht-$newWidth)/2);//ränder links und rechts
        $randY=intval(($thumbHeight-$newHeight)/2); //ränder oben und unten

        //für unterschiedliche imagetypen braucht es unterschiedliche funktionen
        switch($originalType){
            case 1:
                $imageType='ImageGIF';
                $imageCreateFrom='ImageCreateFromGIF';
                break;
            case 2:
                $imageType='ImageJPEG';
                $imageCreateFrom='ImageCreateFromJPEG';
                break;
            case 3:
                $imageType='ImagePNG';
                $imageCreateFrom='ImageCreateFromPNG';
                break;
        }

        //falls es einen entsprechenden typ gab trifft diese bedingung zu
        if($imageType){
            $originalImage=$imageCreateFrom(Config::get('pictures/dirOriginal').$originalFilename);
            $newImage=imagecreatetruecolor($thumbWidht,$thumbHeight);
            imagecopyresized($newImage,$originalImage,$randX,$randY,0,0,$newWidth,$newHeight,$originalWidth,$originalHeight);
            $imageType($newImage,$thumbdir.$thumbname);
        }

        //mit dem namen des orginals ermittle ich noch seine id, die id schreibe ich beim thumbnail dazu, dammit ich weiss zu welchem bild es gehört
        //da alle thumbnails gleich gross sind, verzichte ich darauf, die grössen und so abzuspeichern
        $originalImageID=DB::getInstance()->get('picture',array('name','=',$originalFilename))->first()->id;

        //thumbnail wird zusammen mit id von parentPicture abgespeichert
        if(!DB::getInstance()->insert('thumbnail',array(
            'name'=>$thumbname,
            'parentPicture'=>$originalImageID
        ))){
            //wenn es nicht funktioniert gibt es false zurück
            return false;
        }
        //erfolg
        return true;

    }

}