<?php

include'includes/overall/header.php';


/**
 * seit auf der sich der admin einloggen kann
 */

if(Input::exists()){
    //token von user wird mit dem token auf dem server verglichen
    if(Token::check(Input::get('token'))){
        $validate = new Validate();

        //es wird geprüft ob man ein username und pw eingegeben hat
        $validation = $validate->check($_POST,array(
            'username'=>array('required'=>true),
            'password'=>array('required'=>true)
        ));

        //wenn beides eingegeben wurde
        if($validation->passed()){
            $user=new User();
            /**
             * falls der user auswählt, dass sich die seite an ihn erinnern soll wird diese information weitergegeben
             *
             */

            //in der datenbank wird nachgeschaut ob die kombination von username und password existiert
            $login = $user->login(Input::get('username'),Input::get('password'));

            //wenn der login erfolgreich ist
            if($login){
                //weiterleiten zu index
                //var_dump($user);
                Redirect::to('admin.php');
            }else{
                //login failed.. user wird informiert
                echo 'sry, login failed';
            }
        }else{
            //falls man vergisst ein pw oder username anzugeben
            foreach($validation->errors() as $error){
                echo $error,'<br>';
            }
        }
    }
}

?>


    <h2>Log in</h2>

        <form action="#" method="post">
            <ul id="login" class="list-unstyled">
                <li>
                    Username:<br>
                    <input type="text" name="username" id="username" autocomplete="off">
                </li>
                <li>
                    Password:<br>
                    <input type="password" name="password" id="password" autocomplete="off">
                </li>
                <li>
                    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                    <input type="submit" value="Log in">
                </li>

            </ul>
        </form>


<?php include'includes/overall/footer.php';?>