<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 13.11.2015
 * Time: 09:40
 */
require_once 'includes/overall/header.php';


if($user->isLoggedIn()) {

    if(Input::exists()) {

        $validation = new Validate();

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
        if ($validation->passed()) {
            DB::getInstance()->insert('pricegroup',array(
                'name'=>Input::get('priceDescription'),
                'price'=>Input::get('price')

            ));
        }else{
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