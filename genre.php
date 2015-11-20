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

    <h3>Neues Genre erfassen</h3>
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
    <br> <br>
    <h3>Löschen von Genres</h3>
    <h6>Es werden nur Genres angezeigt, die zur Zeit nicht in einem Event verwendet werden</h6>


<?php
    echo '<form action="" method="post">'."\n";
    $genres=DB::getInstance()->getDeletableGenres()->results();
    foreach($genres as $row) {
        echo '<input type="checkbox" name="genre[]" value="' . $row->id . '">' . $row->name . '<br>'."\n";
    }
    echo'<input type="submit" value="Delete" >';
    echo '</form>'."\n";

}else{
    Redirect::to('index.php');
}
require_once 'includes/overall/footer.php';