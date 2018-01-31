<?php
/**
 * Created by PhpStorm.
 * User: krystofkosut
 * Date: 20.01.18
 * Time: 14:07
 */

class kosik
{
    private $_mysqli;
    public $cena;
    public $kosik_id;
    public $klient_id;
    public $obsah = array();

    public function __construct($mysqli)
    {
        $this->_mysqli = $mysqli;
        if (!isset($_SESSION['kosik'])){
            $this->createTempKosik();
        }
    }

    /**
     * @param $insert
     * @return insered id or false
     */
    public function createKosik($insert){
        $add = $this->_mysqli->insert("kosik", $insert);
        if ($add) {
            $kosik_id = $this->_mysqli->lastid();
            return $kosik_id;
        }
        else {
            return FALSE;
        }
    }

    public function createTempKosik(){
        $_SESSION['kosik']['user']          = "Anonym";
        $_SESSION['kosik']['obsah']         = array();
      //$_SESSION['kosik']['cenaCelkem']    = 0;
    }

    public function getKosikId(){


    }

    /**
     * @param $jidlo_id
     * @param $pocet
     * in menu
     */
    public function addInKosik($jidlo_id, $pocet){
       // $count = count($_SESSION['kosik']['obsah']);
        if (@!$_SESSION['kosik']['obsah'][$jidlo_id]){
            $_SESSION['kosik']['obsah'][$jidlo_id] = $pocet;
        } else {
            $_SESSION['kosik']['obsah'][$jidlo_id] = $_SESSION['kosik']['obsah'][$jidlo_id] + $pocet;
        }
       // $this->setObsah();
    }

    public function updateInKosik($jidlo_id, $pocet){
       $_SESSION['kosik']['obsah'][$jidlo_id] = $pocet;
    }

    public function tranferToObjednavka(){

        $this->deleteTempKosik();
    }

    /**
     * @return mixed
     */
    public function getKlientId()
    {
        return $this->klient_id;
    }

    public function checkKosik(){


    }

    /**
     * @param array $obsah
     */
    public function setObsah($obsah)
    {
        $this->obsah = $obsah;
    }

    /**
     * @return array
     */
    public function getObsah()
    {
        return $this->obsah;
    }

    public function getLastId(){
        $query = "SELECT id FROM kosik ORDER BY id DESC LIMIT 1";
        $kosik = get_row($query);
        return $kosik['id'];
    }

    /**
     *  In SESSION
     */
    public function deleteTempKosik(){
        unset($_SESSION['kosik']);
    }

    /**
     * In DB
     */
    public function deleteKosik(){

    }

    /**
     * @return mixed
     */
    public function setCena()
    {
        $cena_celkem = 0;
        foreach ($_SESSION['kosik']['obsah'] as $id => $pocet) {
            $sql = "SELECT cena FROM menuItem WHERE id='{$id}'";
            $cena = $this->_mysqli->get_row($sql);
            $cena_celkem = $cena_celkem+($cena['cena']*$pocet);
        }
        $this->cena = $cena_celkem;
    }

    /**
     * @return mixed
     */
    public function getCena()
    {
        $cena_celkem = 0;
        foreach ($_SESSION['kosik']['obsah'] as $id => $pocet) {
            $sql = "SELECT cena FROM menuItem WHERE id='{$id}'";
            $cena = $this->_mysqli->get_row($sql);
            $cena_celkem = $cena_celkem+($cena['cena']*$pocet);
        }
        return $cena_celkem;
    }

    public function getPocet(){
        $return = 0;
        foreach ($_SESSION['kosik']['obsah'] as $id => $pocet) {
            $return = $return+$pocet;
        }
        return $return;
    }

    public function getPocetOnJidloId($id, $default = FALSE){
        return ($_SESSION['kosik']['obsah'][$id])?$_SESSION['kosik']['obsah'][$id]:$default;
    }

    public function addToKosikHasMenuItem($insert){
        $add = $this->_mysqli->insert("kosik_has_menuItem", $insert);
        if ($add) {
            $kosik_id = $this->_mysqli->lastid();
            return $kosik_id;
        }
        else {
            return FALSE;
        }
    }

    public function updateKosikHasMenuItem($update, $where){
            $result 	= $this->_mysqli->update( 'kosik_has_menuItem', $update, $where, 1 );
            if ($result) {
                return true;
            } else {
                return false;
            }
        }

        public function numberRows($query){
            return $this->_mysqli->num_rows($query);
        }

        public function editKosik($update,$where)
        {
            $result 	= $this->_mysqli->update( 'kosik', $update, $where, 1 );
            if ($result) {
                return true;
            } else {
                return false;
            }
        }

}