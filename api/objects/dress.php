<?php
require '../db_configuration.php';

class Dress{
    // database
    private $table_name = "dresses";

    // properties
    public $id;
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
    public function __construct(){}

    // read
    function read($id){  
        $sql = "SELECT * FROM `$this->table_name` WHERE id = " . $id;
        return run_sql($sql);
    }
}
?>