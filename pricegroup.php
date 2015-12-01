<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 13.11.2015
 * Time: 09:40
 *
 *
 * auf dieser seite kann der admin neue pricegroups erfassen
 */
require_once 'includes/overall/header.php';

//nur angemeldete admin haben zugriff
if($user->isLoggedIn()) {

    if(Input::exists()) {

        $validation = new Validate();
        //regeln für  die pricegroup
        $validation->check($_POST, array(
            'priceDescription' => array(
                'name' => 'Preisgruppen Bezeichnung',
                'required' => true,
                'max' => 20
            ),
            'price'=>array(
                'name'=>'Preis',
                'required'=>true,
                'max'=>20
            )
        ));
        //wenn alles validiert werden konnte wird ein eintrag in der db gemacht
        if ($validation->passed()) {
            DB::getInstance()->insert('pricegroup',array(
                'name'=>Input::get('priceDescription'),
                'price'=>Input::get('price')

            ));
        }else{
            //falls es probleme gab, werden hier die erros ausgegeben
            foreach ($validation->errors() as $error) {
                echo $error, '<br>';
            }
        }
    }
    ?>

    <h1>this is pricegroup</h1>

    <p>
        <form action="" method="post">
         <ul class="list-unstyled">
                <li>
                    <label for="priceDescriptin">Bezeichnung der Preisgruppe</label><br>
                    <input type="text"  name="priceDescription" id="priceDescriptin" required="required">
                    <br><br>
                    <label for="price"> Betrag</label><br>
                    <input type="text"  name="price" id="price" required="required">
                    <br><br>
                   <input type="submit" value="Create PriceGroup">
                </li>
            </ul>
        </form>
    </p>



<?php

}else{
    Redirect::to('index.php');
}

require_once 'includes/overall/footer.php';