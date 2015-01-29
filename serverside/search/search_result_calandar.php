<?php
/*
 * Created on Jan 23, 2015
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
ini_set('max_execution_time', 300); //300 seconds = 5 minutes 
/*$data = file_get_contents("php://input");
$objData = json_decode($data);
$doc_id=$objData->data;*/
error_reporting(E_ALL);
ini_set('display-errors','on');
include_once"../mysql_crud.php";
include('DoctorSearchModuleFunctions.php');
$php_to_json=SearchByName2("jagtap",0,100);
print_r(json_encode($php_to_json));		

	 ?>
