<?php


// this is the configuration when we are trying it on localhost
//  DEFINE('DATABASE_HOST', 'localhost');
//  DEFINE('DATABASE_DATABASE', 'abcd_db');
//  DEFINE('DATABASE_USER', 'root');
//  DEFINE('DATABASE_PASSWORD', '');


//this is the configuration when we host it to BLUEHOST
DEFINE('DATABASE_HOST', 'localhost');
DEFINE('DATABASE_DATABASE', 'icsbinco_abcd');
DEFINE('DATABASE_USER', 'icsbinco_abcd');
DEFINE('DATABASE_PASSWORD', 'iloveSILCP@$$');
 

$db = new mysqli(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_DATABASE);
$db->set_charset("utf8");
function run_sql($sql_script)
{
    global $db;
    // check connection
    if ($db->connect_error)
    {
        trigger_error(print_r(debug_backtrace()).'.Database connection failed: '  . $db->connect_error, E_USER_ERROR);
    }
    else
    {
        $result = $db->query($sql_script);
        if($result === false)
        {
            trigger_error('Stack Trace: '.print_r(debug_backtrace()).'Invalid SQL: ' . $sql_script . '; Error: ' . $db->error, E_USER_ERROR);
        }
        else if(strpos($sql_script, "INSERT")!== false)
        {
            return $db->insert_id;
        }
        else
        {
            return $result;
        }
    }
}
?>
