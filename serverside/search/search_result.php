<?php          
error_reporting(0);
ini_set('display-errors','on');
include_once("DoctorSearchModuleFunctions.php");
include_once("../mysql_crud.php");


# The request is a JSON request.
# We must read the input.
# $_POST or $_GET will not work!

$data = file_get_contents("php://input");
$objData = json_decode($data);
$resultArray=SearchByName2($objData->data,0,100);
$resultArray=json_encode($resultArray);
print_r($resultArray);



?>