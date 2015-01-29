<?php
error_reporting(E_ALL);
ini_set('display-errors','on');
//recent changes : mysql_escape_string implemented

#include_once('../../mysql_crud.php');

//$crud=new Database();
/*
Appointment Booking Module:
This module contains functions which basically 
*/

   function FetchDoctorWeekAvailability($doc_id)
   {
    $db=new Database();
		 $db->select('doctor_info','doctor_info.doc_id,count(doctor_info.doc_id) as usedSlot,
									ai.AppointmentDate,ai.AppointmentSlot, doctor_info.FirstName,doctor_info.LastName,doctor_info.schema_enumeration,
									doctor_info.speciality,doctor_info.schema_enumeration,doctor_info.address,doctor_info.lat,doctor_info.lng,doctor_info.experience,doctor_info.degree,doctor_info.aboutdoctor,doctor_info.gender,doctor_info.fee,doctor_info.DocImage,
									sc.MonSlots ,sc.TuesSlots,sc.WedSlots,sc.ThurSlots,sc.FriSlots,sc.SatSlots,sc.SunSlots,
									ai.AppointmentId',
					        'doctor_schedule sc ON doctor_info.doc_id = sc.DoctorId 
							LEFT JOIN appointment_info ai ON doctor_info.doc_id = ai.DoctorId 
													   AND (ai.AppointmentDate BETWEEN CURDATE() AND CURDATE()+30)',
					 'doctor_info.doc_id='.$doc_id.' GROUP BY doctor_info.doc_id,ai.AppointmentDate,ai.AppointmentSlot',NULL);
            $res = $db->getResult();
			return $res;
			
			
    
   }






  function getRegisteredDocID()
  {
    $db =new Database();
	//$db->connect();
	$db->select('registered_doctor','rnumber');
	$res=$db->getResult();
	//$db->disconnect();
	
	return $res ;
  }
  
  //Fetch the static schedule of a doctor(front end use)
  function FetchSchedule($DocId)
  {
	$db= new Database();
	//$db->connect();
	$db->select('doctor_schedule','ScheduleID,MonSlots,TuesSlots,WedSlots,ThurSlots,FriSlots,SatSlots,SunSlots',NULL,'DoctorId='.$DocId.'');
	//$db->sql('SELECT ScheduleID,MonSlots,TuesSlots,WedSlots,ThurSlots,FriSlots,SatSlots,SunSlots,dc.clinic_name,dc.clinic_address,dc.clinic_contact FROM doctor_schedule LEFT JOIN doctor_clinic dc ON doctor_schedule.DoctorId=dc.doctor_id WHERE doctor_schedule.DoctorId='.$DocId)
	$res=$db->getResult();
	//$db->disconnect();
	//print_r($res[0]);
	return $res;
  }
  
   
   //Returns ScheduleID if Available,otherwise False;
   function CheckDoctorAvailability($DocId,$Day,$Slot)
   {
    $db= new Database();
	//$db->connect();
	$db->select('day','DayId',NULL,'Day= "'.$Day.'"');
	$res=$db->getResult();
	//print_r($res[0]['DayId']);
	$DayId=$res[0]['DayId'];
	$db->select('doctor_schedule',"$DayId",NULL,'DoctorId='.$DocId.'');
	$res=$db->getResult();
	//print_r($res[0][$DayId]);
	
	
	$SlotsArr=explode(',',$res[0][$DayId]);
	
	//print_r($SlotsArr);
			 if(!in_array($Slot,$SlotsArr))
			{	
			  //$db->disconnect();
			  return false;
			}
			else 
			{
			  //$db->disconnect();
			  return true;
			} 
	}
   
   
    function CheckDoctorDayAvailability($DocId,$Day)
   {
    $db= new Database();
	//$db->connect();
	$db->select('day','DayId',NULL,'Day= "'.$Day.'"');
	$res=$db->getResult();
	//print_r($res[0]['DayId']);
	$DayId=$res[0]['DayId'];
	$db->select('doctor_schedule',"$DayId",NULL,'DoctorId='.$DocId.'');
	$res=$db->getResult();
	//print_r($res[0][$DayId]);
	$SlotsArr=explode(',',$res[0][$DayId]);
	
			return $SlotsArr;
    	
   }
   
   
   
  
   function CheckSlotAvailability($DocId,$Date,$Slot,$SubSlot)
   {
		$Day=date('l', strtotime($Date));
		if(CheckDoctorAvailability($DocId,$Day,$Slot))
		{
		  $db= new Database();
		  //$db->connect();
		  $db->select('appointment_info','AppointmentId',NULL,'DoctorId="'.mysql_escape_string($DocId).'"AND AppointmentDate="'.mysql_escape_string($Date).'" AND AppointmentSlot="'.mysql_escape_string($Slot).'" AND AppointmentSubSlot="'.mysql_escape_string($SubSlot).'"');
		  $res=$db->getResult();
		  //print_r($res[0]);
				  if($res)
				  {
					echo"Not Available";
					//$db->disconnect();
					return false;
				  }
				  else 
				  {
				   //echo"Available";
				   //$db->disconnect();
				   return true;
				  }
		}
		else
		{
		  echo"Doctor Not Available";
		  return false;
		}
    }
   
   //'". mysql_escape_string($name) ."',
   function BookAppointment($DocId,$PatientId,$AppointmentDate,$AppointmentSlot,$Reason,$AppointmentStatus,$p_name)
   {
	 //echo $Reason;exit();
	 #if(CheckSlotAvailability($DocId,$AppointmentDate,$AppointmentSlot,$AppointmentSubSlot))
	 #{
		  $db= new Database();
		  $db->connect();
		  $db->insert('appointment_info',array('DoctorId'=> $DocId ,'PatientId'=>$PatientId
		              ,'AppointmentDate'=>$AppointmentDate,'AppointmentSlot'=>$AppointmentSlot
					  ,'Reason'=>$Reason
					  ,'PatientName'=>$p_name
					  ,'AppointmentStatus'=>$AppointmentStatus));
					 
		  $res = $db->getResult();
		   #print_r($res);exit();
		  $db->disconnect();
		  return $res;
	      //extendible: If appointment has been booked,run the mail script to patient
		  //And notify the Doctor
	 #}
   }
   
   function CancelAppointment($AppointmentId)
   {
      $db= new Database();
	  //$db->connect();
	  $db->delete('appointment_info','AppointmentId="'.mysql_escape_string($AppointmentId).'"');
	  $res = $db->getResult();
	  //$db->disconnect();
      //print_r($res);
	  return $res;
	  
   }
   
   
   function ChangeAppointmentSchedule($IsRescheduleByDoc,$AppointmentId,$AppointmentDate,$AppointmentSlot,$AppointmentSubSlot)
   {
	
		  $db= new Database();
		  //$db->connect();
		  $db->select('appointment_info','DoctorId,PatientId,Reason,AppointmentStatus',NULL,'AppointmentId="'.$AppointmentId.'"');
		  $res=$db->getResult();
		  //var_dump($res);
		print_r($res[0]['AppointmentStatus']);
		if($res[0]['AppointmentStatus']=="completed"
		   ||$res[0]['AppointmentStatus']=="rejected"
		   ||$res[0]['AppointmentStatus']=="cancelled")
		 {
		   echo"Makes no sense!";
		   //$db->disconnect();
		   return false;
		 }
				
		if($res[0]['AppointmentStatus']=="confirmed"||$res[0]['AppointmentStatus']=="WaitingForPatient")
		{
			echo"Confirmed/WaitingForPatient Block ";
			if($IsRescheduleByDoc==true AND $res[0]['AppointmentStatus']=="confirmed")// Doctor is re-scheduling the confirmed appointment
					{
					   
					   BookAppointment($res[0]['DoctorId'],$res[0]['PatientId'],$AppointmentDate,$AppointmentSlot,$AppointmentSubSlot,$res[0]['Reason'],'WaitingForPatient');
					   CancelAppointment($AppointmentId);
					   //$db->disconnect();
					   return true;
					   /*NotifyPatient(); 
						and after the patient's response,change the appointment 
						status accordingly via calling ChangeAppointmentStatus()function*/
					}
			else if ($IsRescheduleByDoc==true AND $res[0]['AppointmentStatus']=="WaitingForPatient")
					{
					   BookAppointment($res[0]['DoctorId'],$res[0]['PatientId'],$AppointmentDate,$AppointmentSlot,$AppointmentSubSlot,$res[0]['Reason'],'WaitingForPatient');
					   CancelAppointment($AppointmentId);
					   //$db->disconnect();
					   return true;
					   /*Don't need to notify patient as status is already 'WaitingForPatient'*/
					}
		    else  //Patient is re-scheduling the confirmed Appointment
					{
					   
					   BookAppointment($res[0]['DoctorId'],$res[0]['PatientId'],$AppointmentDate,$AppointmentSlot,$AppointmentSubSlot,$res[0]['Reason'],'WaitingForDoctor');
					   CancelAppointment($AppointmentId);
					   //$db->disconnect();
					   return true;
					   /*NotifyDoctor(); 
						and after the Doctor's response,change the appointment 
						status accordingly via calling ChangeAppointmentStatus()function*/	
					}							
		}
		
		if($res[0]['AppointmentStatus']=="WaitingForDoctor")
		{
		   echo"WaitingForDoctor block ";
		   BookAppointment($res[0]['DoctorId'],$res[0]['PatientId'],$AppointmentDate,$AppointmentSlot,$AppointmentSubSlot,$res[0]['Reason'],'WaitingForDoctor');
		   CancelAppointment($AppointmentId);
		   //$db->disconnect();
		   return true;
		}
	
	}
   
   function ChangeAppointmentStatus($AppointmentId,$AppointmentStatus)
   {
		  $db= new Database();
		  //$db->connect();
		  /*Mistake for upate command ...check out !*/
		  $db->update('appointment_info',array('AppointmentStatus'=>'". mysql_escape_string($AppointmentStatus)."','AppointmentId="'.$AppointmentId.'"'));
		  $res=$db->getResult();
		  if($res[0]){//$db->disconnect();
						return true;}
		  else {//$db->disconnect();
					return false;}
   }
  
   
   

?>