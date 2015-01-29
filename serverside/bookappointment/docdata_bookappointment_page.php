<?php
/*
 * Created on Jan 28, 2015
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 error_reporting(0);
ini_set('display-errors','on');
include_once("search/../DoctorSearchModuleFunctions.php");
include_once("../mysql_crud.php");

# The request is a JSON request.
# We must read the input.
# $_POST or $_GET will not work!
# :Amit Gore
$data = file_get_contents("php://input");
$objData = json_decode($data);
$DocId=$objData->data;
	$db= new Database();
	//$db->connect();
	$db->select('doctor_info','doctor_info.doc_id,count(doctor_info.doc_id) as usedSlot,
									ai.AppointmentDate,ai.AppointmentSlot, doctor_info.FirstName,doctor_info.LastName,
									doctor_info.speciality,doctor_info.DocImage,doctor_info.area,doctor_info.address,doctor_info.lat,doctor_info.lng,doctor_info.fee,
									sc.MonSlots ,sc.TuesSlots,sc.WedSlots,sc.ThurSlots,sc.FriSlots,sc.SatSlots,sc.SunSlots,
									ai.AppointmentId',
					        'doctor_schedule sc ON doctor_info.doc_id = sc.DoctorId 
							LEFT JOIN appointment_info ai ON doctor_info.doc_id = ai.DoctorId 
													   AND (ai.AppointmentDate BETWEEN CURDATE() AND CURDATE()+6)','doctor_info.doc_id ='.$DocId.'',NULL);
													   
    $res = $db->getResult();
    $res=json_encode($res);
    print_r($res);
	
 
?>
