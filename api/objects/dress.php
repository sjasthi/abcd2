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

    /**
     * this function takes 1 parameters which is the integer id to be searched. the function
     * returns the dress object with that id, or null if none exists.
     */
    public static function getById($id){  
        $sql = "SELECT * FROM `dresses` WHERE id = " . $id;
        $result = run_sql($sql);

        // since id is the unique key, there is at most one result to return
        if ($result->num_rows > 0) {
            $obj = $result->fetch_object('Dress');
            return $obj;
        }
        else {
            return null;
        }
    }

    /**
     * this function takes 3 parameters which are arrays of strings. the parameters correspond
     * to the database columns being searched. the function returns an array of dress objects
     * which satisfy all of the parameters. an empty array is returned if none match.
     */
    public static function getByCategoryAndTypeAndKeyword($categories, $types, $keywords){
        // build string
        $sql = "SELECT * FROM `dresses` WHERE ";
        foreach ($categories as $category) {
            $sql .= "`category` LIKE '%".$category."%' AND ";
        }
        foreach ($types as $type) {
            $sql .= "`type` LIKE '%".$type."%' AND ";
        }
        foreach ($keywords as $keyword) {
            $sql .= "`key_words` LIKE '%".$keyword."%' AND ";
        }

        // remove the last "AND " from the string
        $sql = substr($sql, 0, -4);

        // store the results
        $objs = array();
        $result = run_sql($sql);
        if ($result->num_rows > 0) {
            while ($obj = $result->fetch_object('Dress')) {
                array_push($objs, $obj);
            }
        }
        return $objs;
    }
}
?>