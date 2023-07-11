<?php
require_once __DIR__ . "/../../db_configuration.php";

class Dress{
    // properties
    public int $id;
    public $name;
    public $description;
    public $did_you_know;
    public $category;
    public $type;
    public $state_name;
    public $key_words;
    public $image_url;
    public $status;
    public $notes;

    // constructor
    public function __construct() {}

    // getter
    public static function getById($id){  
        $sql = "SELECT * FROM `dresses` WHERE id = " . $id;
        $result = run_sql($sql);

        if ($result->num_rows > 0) {
            $obj = $result->fetch_object('Dress');
            return $obj;
        }
        else {
            return false;
        }
    }

    public static function getByCategoryAndTypeAndKeyword($category, $type, $keyword){
        $sql = "SELECT * FROM `dresses` WHERE `category` LIKE '%".$category."%' AND `type` LIKE '%".$type."%' AND `key_words` LIKE '%".$keyword."%'";
        $result = run_sql($sql);

        $objs = array();
        if ($result->num_rows > 0) {
            // while...
            while ($obj = $result->fetch_object('Dress')) {
                array_push($objs, $obj);
            }
        }
        return $objs;
    }
}
?>