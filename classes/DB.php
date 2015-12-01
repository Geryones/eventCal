<?php
/**
 * Created by PhpStorm.
 * User: mai714
 * Date: 22.09.2015
 * Time: 08:48
 */


/**
 * Class DB ist das herzstück, hier wird die verbindung mit der datenbank hergestellt und hier werden auch alle zugriffsfunktion verwaltet
 * ist ein database-wrapper, zusammen mit dem singleton kann man diese klasse von überall her in dieser applikation verwenden.
 * es wird pdo verwendet, um die reuseability zu erhöhen
 */
class DB{
    private static $_instance = null;

    /*
     * '_' vor den variabel namen um privat zu signalisieren
     */
    private $_pdo,
            $_query,
            $_error=false,
            $_results,
            $_count=0;

    /**
     * constructor wird ausgeführt sobald die klasse instanziert wird
     * hier wird die verbindung zur database hergestellt
     * falls die verbindung nicht möglich ist, wir die errormessage  asgegeben und die applikation getötet XD
     */
    private function __construct(){
        try{
            $this->_pdo= new PDO('mysql:host='.Config::get('mysql/host').';dbname='.Config::get('mysql/db'),Config::get('mysql/username'),Config::get('mysql/password'));
        }catch(PDOException $e){
            die($e->getMessage());
        }
    }

    /**
     * singelton, mit dieser funktion bekommt die instanz der db
     * @return DB|null instanz der db, falls noch nciht vorhanden, wird eine erstellt
     */
    public static function getInstance(){
        if(!isset(self::$_instance)){
            self::$_instance=new DB();
        }
        return self::$_instance;
    }

    /**
     * funktion für die abfrage der datenbank
     * @param string $sql   statement das vorbereitet werden soll
     * @param array $params enthält die werte die an die fragezeichen gebunden werden sollen
     * @return $this gibt bei erfolg die resultate als objekt zurück, bei einem fail die errormessage
     */
    public function query($sql,$params=array()){

        //echo' query erreicht<br>';
        //error meldung wird zurückgesetzt
        $this->_error=false;
        //check ob query richtig vorbereitet wurde
        if($this->_query=$this->_pdo->prepare($sql)){
           // echo ' query vorbereitet<br>';

            $x=1;//counter für parameter
            //wie viele parameter gibt es?
            if(count($params)){
                // hier bindet man den ersten wert im array, an das erste fragezeichen, für prepared-statement
               // echo 'parameter vorhanden<br>';
                foreach($params as $param){
                    $this->_query->bindValue($x,$param);
                    $x++;
                }
            }
            /*
             * check ob es richtig ausgeführt wurde
             * wenn ja werden die ergebnise als objekt gespeichert und die zeilen gezählt
             */

            //var_dump($this->_query);
            //echo '<br>';
            if($this->_query->execute()){
              // echo 'query ausgeführt <br>';
                $this->_results=$this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count=$this->_query->rowCount();
               // echo 'all hail hydra<br>';
            }else{
                //echo 'ein fehler<br>';
                //bei einem fehler wird die errormessage gespeichert
                $this->_error=true;
            }
        }

        return $this;
    }

    /**
     * @param string $action was soll gemacht werden? ( select, delete, update)
     * @param string $table welche tabelle soll verwendet werden
     * @param array $where selektions argumente
     * @param int $offset falls offset und rows gesetzt sind, offset gibt den startwert an
     * @param int $rows anzahl zeilen das ausgegeben werden sollen
     * @return $this|bool gibt das zurzeit verwendete objekt zurück, bei einem erfolg die gewünschten daten, bei einem error, die error-nachricht
     *
     * hier wird das query ($sql) für die funktion query erstellt
     * anschliessend wird die funktion query aufgerufen mit dem parameter $sql
     */
    public function action($action, $table, $where=array(),$offset=null,$rows=null){
        //echo'action erreicht<br>';
        if(count($where===3)){
            $operators=array('=','>','<','>=', '<=');

            $field=$where[0];    //zb id
            $operator=$where[1];    //zb =
            $value=$where[2];   // zb 1

            //where ist also in diesem fall id=1 ,

            //echo ' where check complete<br>';
            //echo $offset.' off und : '.$rows.'<br>';

            //hier wird überprüft ob der gewünschte operator bekannt ist wenn der error unbekannt ist, gibt es einen error
            if(in_array($operator, $operators)&&$offset==null&&$rows==null){
                //echo 'prepare statement ohne offset und rows<br>';
                $sql="{$action} FROM  {$table} WHERE {$field} {$operator} ?";
                // echo $sql.'<br>';
                //wenn es keinen error gibt, gibt es die daten zurück
                if(!$this->query($sql, array($value))->error()){
                    //echo 'theoretisch succes ohne offset und rows<br>';
                    return $this;
                }
            }elseif(in_array($operator, $operators)&& is_numeric($offset) && is_numeric($rows)){
                // echo ' check mit offset und row success<br>';
                $sql="{$action} FROM  {$table} WHERE {$field} {$operator} ? LIMIT {$offset},{$rows}";
                //echo $sql.'<br>';
                //wenn es keinen error gibt, gibt es die daten zurück
                if(!$this->query($sql, array($value))->error()){
                    //echo 'theoretisch success mit offset und rows<br>';
                    return $this;
                }

            }
        }

        return false;
    }

    /**
     * @param string $action  welche aktion soll ausgeführt werden ( select /  update / delete)
     * @param string $table  in welcher tabelle das geschehen soll
     * @param int|null $offset ab der wievielten zeile sollen die die resultate angzeigt werden, optional
     * @param int|null $rows wie viele zeilen sollen angezeigt werden,optional
     * @param string |null $orderRule  hier kann man asc  oder desc angeben
     * @param string|null $orderField an welchem feld es sortiert werden soll
     * @return $this|bool kontrolle ob es funktioniert hat
     *
     * funktion um alles aus einer tabelle zu auswählen / aktualisierten / löschen, optional kann man auch
     * ein offset und die anzahl zeilen angeben die man sehen will.
     *
     */
    public function actionAll($action, $table,$offset=null,$rows=null,$orderRule=null,$orderField=null){
        $sql="{$action} FROM {$table} ";
        if($orderRule==='ASC' || $orderRule === 'DESC' && $orderField!=null){
            $sql .=" ORDER BY {$orderField} {$orderRule} ";
        }
        if(is_numeric($offset)&& is_numeric($rows)){
            $sql .= "LIMIT {$offset},{$rows}";
        }

        if (!$this->query($sql)->error()) {
            return $this;
        }
        return false;
    }




    /**
     * @param string $table welche tabelle verwendet werden soll
     * @param array $where bedingungen
     * @return $this|bool|DB   ruft funktion action auf, welche bei einem erfolg daten aus der db liefert, sonst eine error msg
     *
     * funktion um daten aus der db abzurufen
     * es wird immer alles ausgewählt um es einfach zu halten
     */
    public function get($table, $where){
        //echo ' get erreicht<br>';
        return $this->action('SELECT *', $table, $where);
    }

    /**
     * Funktion um alle datensätze abzurufen
     * @param string $table  welche tabelle
     * @return $this|bool|DB bei erfolg, alle datensätze als objekt sonst false
     */
    public function getAll($table,$orderRule=null,$orderField=null){

        if($orderField===null && $orderRule===null){

            return $this->actionAll('SELECT *',$table);
        }else {

            return $this->actionAll('SELECT *', $table,null, null, $orderRule, $orderField);
        }
    }



    /**
     * @param string $table  welche tabelle werwendet werden soll
     * @param array $where  bedingung
     * @return $this|bool|DB ruft funktion action auf, welche bei einem erfolg daten aus der db löscht, sonst eine error msg
     *
     * funktion um daten in der db zu löschen
     */
    public function delete($table, $where){
        return $this->action('DELETE', $table, $where);
    }

    /**
     * @return int anzahl zeilen in einem objekt
     * falls es nichts zu zählen gibt es ein false
     *
     */
    public function count(){
        return $this->_count;
    }

    /**
     * @return mixed gibt die resultate als objekt zurück
     */
    public function results(){
        return $this->_results;
    }

    /**
     * @return mixed erster datensatz in einem objekt
     * gibt den ersten datensatz in einem objekt zurück
     */
    public function first(){
        return $this->results()[0];
    }

    /**
     * @param string $table  welche tabelle verwendet werden soll
     * @param array $fields assoziativer array mit spalten der tabelle und dem entsprechendem wert
     * @return bool kontrolle ob inster funktioniert hat
     *
     * funktion um daten in eine tabelle einzufügen
     */
    public function insert($table, $fields=array()){
        //hat es daten in fields?, sonst error
        if(count($fields)){
            $keys=array_keys($fields);
            $values='';
            $x=1;

            foreach($fields as $field){
                $values .='?';
                //um zwischen alle fragezeichen ein komma zu setzen, aber keines am ende
                if($x<count($fields)){
                    $values .=', ';
                }
                $x++;
            }
            // hier werden die spaltennamen der tabelle aus dem array fields verwendet und ein prepared statemend wird gemacht
            $sql = "INSERT INTO {$table} (`".implode('`, `',$keys) ."`) VALUES ({$values})";
            //hier wird das prepared-statement ausgeführt und die gewünschten werte für die jeweilige spalte an das richtige fragezeichen gebunden
            if(!$this->query($sql,$fields)->error()){
                //echo 'great success';
                return true;
            }
        }
        // echo $sql, '<br>';
        //echo 'insert failed';
        return false;
    }

    /**
     * @param string $table welche tabelle verwendet werden soll
     * @param int $id primary key des datensatzes der modifiziert wird
     * @param array $fields assoziatives array mit spaltenbezeichnung als key und dem entsprechendem wert
     * @return bool kontrolle ob es funktioniert hat
     *
     * funktion um einen datensatz in einer db zu aktualisieren / ändern
     */
    public function update($table, $id, $fields){
        $set='';
        $x=1;


        /*
         * hier wird ausgezählt wie viele felder aktualisiert werden
         * für jedes feld wird der key des assoziativen array genommen
         * für den wert wird ein fragezeichen gesetzt
         * z. B.: password = ?
         * zwischen den bezeichnung-werte Paaren wird ein , gesetzt
         */
        foreach($fields as $name =>$value){
            $set .="{$name}= ?";
            if($x<count($fields)){
                $set .= ', ';
            }
            $x++;
        }

        // hier wird das prepared statement geschrieben, mit den spaltenbezeichnungen = ?
        // zum beispiel password = ?
        $sql="UPDATE {$table} SET {$set} WHERE  id = {$id} ";

        //$sql sieht so aus (bsp) UPDATE users SET password=?, name=? WHERE id=3

        if(!$this->query($sql, $fields)->error()){
            //echo 'success';
            return true;
        }
        //echo 'fail';
        return false;
    }

    /**
     * @return bool|array false, falls es keine gegeben hat, sonst ein array mit allen error drin
     */
    public function error(){
        return $this->_error;
    }


    /**
     * funktion um alle genres abzurufen die zur zeit nicht verwendet werden
     * @return DB id der nicht verwendeten genres
     */
    public function getDeletableGenres(){
        $sql = 'select * from genre where not exists(select 1 from event where genre.id=event.fk_genre_id);';
        return $this->query($sql);
    }


    /**
     * funktion um alle pricegroups abzurufen welche zur zeit nicht verwendet werden
     * @return DB id der nicht verwendten pricgroups
     */
    public function getDeletablePriceGroups(){
        $sql='select * from pricegroup where not exists(select 1 from event_has_price where pricegroup.id=event_has_price.fk_pricegroup_id);';
        return $this->query($sql);
    }


    /**
     * Funktion um die kommenden Events abzurufen
     * @param string  $table welche tabelle benutzt werden soll
     * @param array $where array mit feld, operator und argument
     * @return DB datensatz als objekt
     */
    public function getUpCommingEvents($table,$where=array()){
        if(!count($where)) {
            $sql = "select * from {$table} where date > now() order by date asc";

            return $this->query($sql);
        }
        if(count($where===3)){
            $operators=array('=','>','<','>=', '<=');

            $field=$where[0];    //zb id
            $operator=$where[1];    //zb =
            $value=$where[2];   // zb 1

            //where ist also in diesem fall id=1 ,

            //echo ' where check complete<br>';
            //echo $offset.' off und : '.$rows.'<br>';

            //hier wird überprüft ob der gewünschte operator bekannt ist wenn der error unbekannt ist, gibt es einen error
            if(in_array($operator, $operators)&&$field!=null){
                $sql = "select * from {$table} where date > now()and {$field} {$operator} ? order by date asc";

                return $this->query($sql,array($value));
            }
        }

    }


    /**
     * Funktion um vergangene events abzurufen
     * @param string  $table name der tabelle
     * @param null|int  $offset ab welchem datensatz soll abgerufen werden
     * @param null|int  $rows wieviele zeilen sollen abgerufen werden
     * @return DB datensatz mit gewünschten zeilen als objekt
     */
    public function getArchivEvents($table,$offset=null,$rows=null){


        $sql="select * from {$table} where date < now()  order by date DESC ";
        if(is_null($offset)&& is_null($rows)){

            return $this->query($sql);
        }elseif(is_numeric($offset)&&is_numeric($rows)){
            $sql .= "LIMIT {$offset},{$rows}";

            return $this->query($sql);
        }
    }


    /**
     * Funktion um alle events abzurufen die in einem zeitlichen konflikt mit den parametern stehen
     * @param string $table  welche tabelle
     * @param string $field  welches feld soll überprüft werden
     * @param string $start startzeit ( datetime) (yyyy-mm-dd hh:mm:ss)
     * @param string $end endzeit ( datetime) (yyyy-mm-dd hh:mm:ss)
     * @return DB falls es events gibt die auch zu dieser zeit aktiv sind
     */
    public function getInterferingEvents($table,$field,$start,$end){
        $sql="select * from {$table} where {$field} between '{$start}' and '{$end}' ";

        return $this->query($sql);
    }


    /**
     * funktion um einen distinct count zu machen für kommende events ( variabel)
     * wird verwendet um alle genres abzurufen die vorkommen, bei der auswahl will man ja keine genres die gar nicht vorkommen
     * @param string $table  welche tabelle
     * @param string $field  welches feld
     * @return DB datensatz als objekt
     */
    public function getDistinctUpComming($table,$field){
        $sql = "select distinct {$field} from {$table}  where date > now()";

        return $this->query($sql);
    }


}