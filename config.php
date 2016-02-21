<?php
$DB_host = "localhost";//serwer bazy danych 
$DB_user = "root"; //login
$DB_pass = ""; //hasło
$DB_name = "data_test";

try
{
     $DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}",$DB_user,$DB_pass);
     $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
     echo $e->getMessage();
}

include_once 'core.class.php';
$data = new DataInfo($DB_con);
?>