<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 13.11.2015
 * Time: 09:39
 */
require_once 'includes/overall/header.php';


if($user->isLoggedIn()) {

    if(Input::exists()) {

        $validation = new Validate();

        $validation->check($_POST, array(
            'genre' => array(
                'name' => 'Genre',
                'required' => true,
                'max' => 45
            )
        ));
        if ($validation->passed()) {
            DB::getInstance()->insert('genre',array(
                'name'=>Input::get('genre')
            ));
        }
    }
    ?>

    <h1>this is genre</h1>

    <p>
        <form action="" method="post">
         <ul class="list-unstyled">
                <li>
                    <label for="genre"> New Genre</label><br>
                    <input type="text"  name="genre" id="genre" required="required">
                    <br><br>
                   <input type="submit" value="Create Genre">
                </li>
            </ul>
        </form>
    </p>


<?php
}else{
    Redirect::to('index.php');
}
require_once 'includes/overall/footer.php';