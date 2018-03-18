<?php
/**
 * Created by PhpStorm.
 * User: kkosu
 * Date: 11.03.2018
 * Time: 18:12
 */

namespace core;
use database\database;
use database\jidlo;
use database\kategorie;
use database\objednavka;
use core\upload;
use core\core;

class form
{
    private $data = array();
    private $forms = array(
        'addKategorie' => 'addKategorie',
        'edit_jidlo' => 'editJidlo',
        'edit_kategorie' => 'editKategorie',
        'pridat_jidlo' => 'pridatJidlo',
        'upload_img' => 'uploadImgJidlo',
        'obj-status' => 'objStatus'
    );

    public function __construct($data){

        $database = new database();

        foreach ($data as $key => $value) {
            $this->data[$key] = $database->filter($value);
        }

        foreach ($this->forms as $name => $function){
            if (isset($this->data[$name])){
                $this->$function();
                continue;
            }
        }
    }

    private function addKategorie() {

         $kategorieClass = new kategorie();

        if (isset($_FILES['icon']['name'])){
            $upload = new upload($_FILES['icon'], "cs_CS");
            // Overeni zda je obrazek uspesne nahran do tmp slozky
            if ($upload->uploaded) {
                // Manipulace s obrazkem
                $upload->image_resize = true;
                $upload->image_ratio = true;
                $upload->image_x = 226;
                $upload->image_y = 226;
                $upload->image_ratio_crop = true;
                // Presuneme fotku ze slozky temp
                $upload->Process("../img/");
                // Jestli se zadarilo
                if ($upload->processed) {
                    $icon   = $_FILES['icon']['name'];
                } else {
                    die($upload->error);
                }
            }
        } else {
            $icon = '';
        }

        if (isset($_FILES['background']['name'])){
            $upload = new upload($_FILES['background'], "cs_CS");
            // Overeni zda je obrazek uspesne nahran do tmp slozky
            if ($upload->uploaded) {
                // Manipulace s obrazkem
                $upload->image_resize = true;
                //  $upload->image_ratio = true;
                $upload->image_x = 3000;
                $upload->image_y = 1500;
                //  $upload->image_ratio_crop = true;
                // Presuneme fotku ze slozky temp
                $upload->Process("../img/");
                // Jestli se zadarilo
                if ($upload->processed) {
                    $background   = $_FILES['background']['name'];
                } else {
                    die($upload->error);
                }
            }
        } else {
            $background = '';
        }
       //$background = (isset($background))?$background:"";
       // $icon       = (isset($this->data['icon']))?$this->data['icon']:"";
        $topmenu    = (isset($this->data['topmenu']))?1:0;
        $url        = strtolower($kategorieClass->remove_accents($this->data['nazev']));

        $insert = array(
            'nazev' => "{$this->data['nazev']}",
            'topmenu' => $topmenu,
            'icon'  => $icon,
            'background' => $background,
            'url'   => "{$url}"
        );

        $insered_id = $kategorieClass->addKategorie($insert);
        if ($insered_id){
            // return true;
            header("Location: ?page=nastaveni&action=kategorie");
        } else {
            return false;
            //echo 'error';
        }

    }

    private function editJidlo() {
        $menuItem = new jidlo();
        $menu       = (!isset($this->data['menu']))     ? 0 : 1 ;

        $where = [
            'id' => $this->data['id']
        ];

        $update = array(
            'nazev'       => "{$this->data['nazev']}",
            'popis'       => "{$this->data['popis']}",
            'ingredience' => "{$this->data['ingredience']}",
            'kategorie'   => "{$this->data['kategorie']}",
            'gramaz'      => "{$this->data['gramaz']}",
            'cena'        => $this->data['cena']
        );

        $edit = $menuItem->editJidlo($update, $where);

        if ($_FILES['my_field']['name'] != "") {

            // trida upload preda $_FILES
            // uprava nazvu aby nebyly mezery
            $_FILES['my_field']['name'] = str_replace(" ", "-", mt_rand() . $_FILES['my_field']['name']);
            // Upload object
            $upload = new upload($_FILES['my_field'], "cs_CS");
            // Overeni zda je obrazek uspesne nahran do tmp slozky
            if ($upload->uploaded) {
                // Manipulace s obrazkem
                $upload->image_resize = true;
                $upload->image_ratio = true;
                $upload->image_x = 226;
                $upload->image_y = 226;
                // $upload->image_precrop              = '5%';
                $upload->image_ratio_crop           = true;
                // Presuneme fotku ze slozky temp
                $upload->Process("../img/");
                // Jestli se zadarilo
                if ($upload->processed) {
                    // Vlozeni fotky/obrazku do databaze
                    // priprava promennych
                    $photo_name = $_FILES['my_field']['name'];
                    // Object pro manipulaci s tabulkou fotka v databazy

                    $update = array(
                        'nazev' => $photo_name
                    );
                    $where = array(
                        'jidlo_id' => $this->data['id']
                    );

                    $upload_foto = $menuItem->editFotka($update, $where);
                    /*var_dump($update); var_dump($where);
                    var_dump($upload_foto); die();*/
                }
            }
        } else {
            $upload_foto = true;
        }


            if ($edit AND $upload_foto) {
                header("Location: ?page=jidlo&action=prehled");
            } else {
                // error
                echo "error";
            }

    }

    private function editKategorie(){
        $kategorieClass= new kategorie();
        $kategorieClass->setUpKategorie($this->data['id']);

        if (isset($_FILES['icon']['name']) AND $_FILES['icon']['name'] != ""){

            $upload = new upload($_FILES['icon'], "cs_CS");
            // Overeni zda je obrazek uspesne nahran do tmp slozky
            if ($upload->uploaded) {
                // Manipulace s obrazkem
                $upload->image_resize = true;
                //  $upload->image_ratio = true;
                $upload->image_x = 226;
                $upload->image_y = 226;
                // $upload->image_ratio_crop = true;
                // Presuneme fotku ze slozky temp
                $upload->Process("../img/");
                // Jestli se zadarilo
                if ($upload->processed) {
                    $icon   = $_FILES['icon']['name'];
                } else {
                    die($upload->error);
                }
            }
        } else {
            $icon = $kategorieClass->icon;
        }

        if (isset($_FILES['background']['name'])  AND $_FILES['background']['name'] != ""){
            $upload = new upload($_FILES['background'], "cs_CS");
            // Overeni zda je obrazek uspesne nahran do tmp slozky
            if ($upload->uploaded) {
                // Manipulace s obrazkem
                $upload->image_resize = true;
                //  $upload->image_ratio = true;
                $upload->image_x = 3000;
                $upload->image_y = 1500;
                //  $upload->image_ratio_crop = true;
                // Presuneme fotku ze slozky temp
                $upload->Process("../img/");
                // Jestli se zadarilo
                if ($upload->processed) {
                    $background   = $_FILES['background']['name'];
                } else {
                    die($upload->error);
                }
            }
        } else {
            $background = $kategorieClass->background;
        }

        $topmenu    = (isset($this->data['topmenu']))?1:0;
        $url        = strtolower($kategorieClass->remove_accents($this->data['nazev']));

        $update = array(
            'nazev' => "{$this->data['nazev']}",
            'topmenu' => $topmenu,
            'icon'  => $icon,
            'background' => $background,
            'url'   => "{$url}"
        );

        $where = array(
            'id' => $this->data['id']
        );

        $result = $kategorieClass->updateKategorie($update, $where, $kategorieClass->url);
        if ($result){
            header("Location: ?page=nastaveni&action=kategorie");
        } else {
            echo '<h2>ERROR</h2>';
            echo '<pre>';
            var_dump($result);
            echo '</pre>';
        }
    }

    private function pridatJidlo(){
        $menuItem = new jidlo();

        $insert_jidlo = array(
            'nazev'       => "{$this->data['nazev']}",
            'popis'       => "{$this->data['popis']}",
            'ingredience' => "{$this->data['ingredience']}",
            'kategorie'   => "{$this->data['kategorie']}",
            'gramaz'      => "{$this->data['gramaz']}",
            'cena'        => $this->data['cena']
        );



        $jidlo_id = $menuItem -> addJidlo($insert_jidlo);

        if ($jidlo_id != FALSE) {
            header("Location: ?page=jidlo&action=upload_img&id={$jidlo_id}");
        } else {
            # error
        }
    }

    private function uploadImgJidlo(){
        $jidlo = new jidlo();

        if (isset($this->data['preskocit'])){
            $upload_img = $jidlo->addImageToJidlo($this->data['jidlo_id']);
            # upload default image
            header("Location: ?page=jidlo&action=detail&id=$this->data['jidlo_id']");
        }
        elseif(isset($this->data['nahrat'])) {

        //var_dump($_FILES);

        // trida upload preda $_FILES
        // uprava nazvu aby nebyly mezery
        $_FILES['my_field']['name'] = str_replace(" ", "-", mt_rand().$_FILES['my_field']['name']);
            // Upload object
        $upload = new upload($_FILES['my_field'], "cs_CS");
            // Overeni zda je obrazek uspesne nahran do tmp slozky
        if ($upload->uploaded) {
            // Manipulace s obrazkem
        $upload->image_resize = true;
        $upload->image_ratio = true;
        $upload->image_x = 226;
        $upload->image_y = 226;
        $upload->image_ratio_crop           = true;
            // Presuneme fotku ze slozky temp
        $upload->Process("../img/");
            // Jestli se zadarilo
        if ($upload->processed) {
            // Vlozeni fotky/obrazku do databaze
            // priprava promennych
        $photo_name   = $_FILES['my_field']['name'];
            // Object pro manipulaci s tabulkou fotka v databazy
        $insert_fotka = $jidlo->addImageToJidlo($this->data['jidlo_id'], $photo_name);
        if ($insert_fotka){
        header("Location: ?page=jidlo&action=prehled");
            } else {
                echo "error";
            }

            } else {
                die($upload->error);
            }
        }

        }
    }

    private function finishOrder(){

    }

    private function objStatus(){
        $objednavka = new objednavka();

        switch ($this->data['obj-status']){
            case 'Storno':
                    if(!$objednavka->changeStatus(3,$this->data['id'])){
                        \core\core::debugLog("Change obj status to: 3 Failed");
                    }
                break;

            case 'Vydat':
                    if(!$objednavka->changeStatus(2,$this->data['id'])){
                \core\core::debugLog("Change obj status to: 2 Failed");
            }
                break;

            default:
                    //error
                    \core\core::debugLog('Bad status to change status value: '.$this->data['obj-status']);
                break;
        }
    }
}