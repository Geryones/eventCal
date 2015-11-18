<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 17.11.2015
 * Time: 11:11
 */
include 'includes/overall/header.php';



if($user->isLoggedIn()){

    if(Session::exists('success')){
        echo '<h3>'. Session::flash('success').'</h3>';
    }

    ?>
    <h1> Admin Bereich</h1>
    <p>Hello <a href="profile.php?user=<?php echo escape($user->data()->username); ?>"> <?php echo escape($user->data()->username); ?> !</a>

    <ul class="list-unstyled">
        <li> <a href="logout.php"> Log out</a> </li>
        <li> <a href="changepassword.php"> change password</a> </li>
        <li> <a href="register.php"> register a new admin</a></li>
        <li> <a href="event.php"> create new event</a></li>
    </ul>


<?php
}else{
    echo '<p> You need to  <a href="login.php"> log in </a>  </p>';
}






include 'includes/overall/footer.php';
?>