<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 13.11.2015
 * Time: 11:48
 */
require_once 'includes/overall/header.php';

var_dump($_POST);
$validation = new Validate();


if(Input::exists()){
    $validation->check($_POST,array(
        'eventDate' => array(
            'name' => 'Event Date',
            'required' => true,
            'date' => true
        )
    ));


    if(!$validation->passed()){
        foreach($validation->errors() as $error){
            echo $error.'<br>';
        }
    }else{
        echo 'succes';
    }
}
?>



<form action="" method="post">
    <label for="eventDate">Date of the Event</label><br>
    <input  type="date" name="eventDate" id="eventDate" required="required" value="<?php echo escape(Input::get('eventDate'));?>" ><br><br>
    <input type="submit" value="check">
</form>


<?php
require_once 'includes/overall/footer.php';

?>
