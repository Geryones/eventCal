<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 22.09.2015
 * Time: 08:50
 */


/**
 * Class User
 * hier wird der User und ein teil der SESSION verwaltet
 * Informationen ob ein user angemeldet ist oder nicht können hier abgerufen werden
 */
class User{
    private $_db,
            $_data,
            $_sessionName,
            $_isLoggedIn;

    /**
     * constructor, definiert einen user
     *
     * @param null/object $user klasse kann mit  oder ohne bereits vorhandenen user aufgerufen werden
     *
     * im constructor wird überprüft ob der user bereits existiert
     * falls er existiert, wird der loggedin - Status auf true gesetzt
     */
    public function __construct($user=null){
        $this->_db= DB::getInstance();
        $this->_sessionName=Config::get('session/session_name');


        if(!$user){

            if(Session::exists($this->_sessionName)){

                $user=Session::get($this->_sessionName);


                if($this->find($user)){
                    $this->_isLoggedIn=true;
                }
            }
        }else{
            $this->find($user);
        }
    }

    /**
     * funktion um einen user in der datenbank anzulegen
     *
     * @param array $fields assoziatives array mit den spaltennamen als key, und den entsprechenden werten
     * @throws Exception falls keine verbindung zur db hergestellt werden kann
     */
    public function create($fields=array()){
        if(!$this->_db->insert('users',$fields)){
            throw new Exception('there was a problem creating an account');
        }
    }

    /**
     * funktion um nach einem user in der db zu suchen
     *
     * @param null|int|string $user der benutzer der in der db gefunden werden soll( kann int oder string sein)
     * @return bool status ob der user gefunden wurde
     */
    public function find($user=null){
        if($user){
            $field=(is_numeric($user)) ?'ID':'username';
            $data= $this->_db->get('users',array($field,'=',$user));

            if($data->count()){
                $this->_data=$data->first();
                return true;
            }
        }
        return false;
    }

    /**
     * funktion um einen user ein-zu-loggen
     * falls er wählt, dass sich die seite an ihn erinnern soll, wird überprüft ob der user in der db
     * bereits einen unique id hat, sonst wird eine erzeugt. diese id wird dann in ein cookie geschrieben
     * wenn der user das nächste mal kommt und die id für einen existierenden user im cookie vorhanden ist, wird der user
     * automatisch eingeloggt
     *
     * @param null|string $username name des users, der sich versucht einzuloggen
     * @param null|string $password passwort des users
     * @return bool status ob login erfolgreich war
     */
    public function login($username=null, $password=null){

        //hier landet das login, wenn man remember me hat
        //es wird geprüft ob es daten im _data - array hat, wenn es welche hat, wird der user anhand dieser daten eingeloggt
        if(!$username && !$password && $this->exists()){
            Session::put($this->_sessionName,$this->data()->id);
        }else {
            $user = $this->find($username);


            if ($user) {

                if (password_verify($password, $this->data()->password)) {

                    /**
                     * nach einem erfolgreichem login wird eine session erstellt
                     * die session enthält unsere id
                     */


                    Session::put($this->_sessionName, $this->data()->id);


                    return true;
                }
            }
        }

        return false;
    }

    /**
     * funktion um informationen von einem user in der db zu ändern
     *
     * @param array $fields assoziatives array mit keys = spaltenbezeichnung in db
     * @param null|int $id  id des users
     * @throws Exception error, falls es ein problem beim updaten des users gab
     */
    public function update($fields=array(),$id=null){

        /**
         * wenn der user eingeloggt ist, wird seine id verwendet
         */
        if(!$id &&$this->isLoggedIn()){
            $id=$this->data()->id;
        }else{
            Redirect::to('login.php');
        }

        /**
         * falls das update der informationen nicht funktioniert gibt es einen error
         */
        if(!$this->_db->update('users',$id,$fields)){
            throw new Exception('there was a problem while updating');
        }
    }

    /**
     * funktion um die daten eines users zu erhalten
     * @return mixed assoziatives array mit informations-namen als key ( z.B.: loggedIn) und dem entsprechenden wert
     */
    public function data(){
        return $this->_data;
    }


    /**
     * funktion um zu überprüfen ob ein user existiert
     * @return bool status ob user existiert
     */
    public function exists(){
        return (!empty($this->_data))? true: false;
    }

    /**
     * funktion um einen user auszuloggen
     *
     * beim logout wird in der db die das token für den automatischen loging gelöscht
     * die session wird gelöscht sowie das cookie
     */
    public function logout(){
        Session::delete($this->_sessionName);

    }



    /**
     * funktion um abzufragen, ob ein user eingelogged ist oder nicht
     *
     * @return bool status ob user eingloggt ist
     */
    public function isLoggedIn(){
        return $this->_isLoggedIn;
    }

}