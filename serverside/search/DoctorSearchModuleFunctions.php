<?php
ini_set('max_execution_time', 300);
require_once('../mysql_crud.php');
include_once('search_result_helper.php');

/*Requirement For FtechCalandar Function*/
include_once('search_result_calandar/AppointmentModuleFunctions.php');
include_once('search_result_calandar/PHPcustomefunctions.php');


//error_reporting(E_ALL);
error_reporting(0);
ini_set('display-errors','on');



function FetchCalandar($doc_id)
{

		$searchResult=FetchDoctorWeekAvailability($doc_id);//this array is used to populate bookedarray which is defined in findbookedarray.php
		//to get the doc_id and corresponding Schedule: A jugaad [Need advice from Abhishek]
		$AllDoctorSchedules=FetchSchedule($doc_id);
		#$ClinicDetails=FetchClinicData($doc_id);
		#$NumberofClinics= count($ClinicDetails, COUNT_RECURSIVE)/4;
		include_once('search_result_calandar/findbookedarray.php');
		$dayID=array();
		$dayID['Monday']='MonSlots';
		$dayID['Tuesday']='TuesSlots';
		$dayID['Wednesday']='WedSlots';
		$dayID['Thursday']='ThurSlots';
		$dayID['Friday']='FriSlots';
		$dayID['Saturday']='SatSlots';
		$dayID['Sunday']='SunSlots';
		
		$SlotTiming=array();
		$SlotTiming['A']='09:00 AM - 10:00 AM';
		$SlotTiming['B']='10:00 AM - 11:00 AM';
		$SlotTiming['C']='11:00 AM - 12:00 PM';
		$SlotTiming['D']='12:00 PM - 01:00 PM';
		$SlotTiming['E']='01:00 PM - 02:00 PM';
		$SlotTiming['F']='02:00 PM - 03:00 PM';
		$SlotTiming['G']='03:00 PM - 04:00 PM';
		$SlotTiming['H']='04:00 PM - 05:00 PM';
		$SlotTiming['I']='05:00 PM - 06:00 PM';
		$SlotTiming['J']='06:00 PM - 07:00 PM';
		$SlotTiming['K']='07:00 PM - 08:00 PM';
		$SlotTiming['L']='08:00 PM - 09:00 PM';
		
		$Slot_daytime=array();
		$Slot_daytime[0]='10';
		$Slot_daytime[1]='11';
		$Slot_daytime[2]='12';
		$Slot_daytime[3]='13';
		$Slot_daytime[4]='14';
		$Slot_daytime[5]='15';
		$Slot_daytime[6]='16';
		$Slot_daytime[7]='17';
		$Slot_daytime[8]='18';
		$Slot_daytime[9]='19';
		$Slot_daytime[10]='20';
		$Slot_daytime[11]='21';
		
		
		
		
		$SlotID=array();
		$SlotID[0]='A';
		$SlotID[1]='B';
		$SlotID[2]='C';
		$SlotID[3]='D';
		$SlotID[4]='E';
		$SlotID[5]='F';
		$SlotID[6]='G';
		$SlotID[7]='H';
		$SlotID[8]='I';
		$SlotID[9]='J';
		$SlotID[10]='K';
		$SlotID[11]='L';
		
		
		
		/*Usage Reasoining: By Amit Gore
		 * Solving the issue,where patient is seeing the availability for today's past time slots
		 * */
		     date_default_timezone_set('Asia/Kolkata');
		     $current_hour=date('H');
		
		     
		    $php_to_json = array();
     
		for($i=0;$i<=30;$i++)
						{
						$columndate=date("Y-m-d",time()+$i*86400);
						$columndate1=date("d M D", time()+$i*86400);
						$columnDay=date("l",time()+$i*86400);
						$DocSlotsColumnDate=explode(',',$AllDoctorSchedules[0][$dayID[$columnDay]]);
						
	
						//$php_to_json[$i]['formatted_date']=$columndate1;
						
						$formatted_dates=explode(" ",$columndate1);
						$php_to_json[$i]['date']=$formatted_dates[0];
						$php_to_json[$i]['month']=$formatted_dates[1];
						$php_to_json[$i]['day']=$formatted_dates[2];
						$php_to_json[$i]['standard_date']=$columndate;
						
						
						#$php_to_json[$i]['doc_id']=$doc_id;
	
						if(array_key_exists($doc_id,$bookedArray))
						$docid_exists=1; 
						else $docid_exists=0;
										 
								for($j=0;$j<12;$j++)
								{	
								
			
								if($i==0&&($Slot_daytime[$j]<=$current_hour))
								{
									#$php_to_json[$i]['red_slots'][]=$SlotTiming[$SlotID[$j]];
									$php_to_json[$i][$j]=0;
									#$php_to_json[$i]['red_slots'][]=$j;
								}
								else if($docid_exists==1)
								{
								
									if(array_key_exists($columndate,$bookedArray[$doc_id]))
									{
										if(in_array($SlotID[$j],$bookedArray[$doc_id][$columndate])==true||in_array($SlotID[$j],$DocSlotsColumnDate)==false)
										{
											#$php_to_json[$i]['red_slots'][]=$SlotTiming[$SlotID[$j]];
											$php_to_json[$i][$j]=0;
											#$php_to_json[$i]['red_slots'][]=$j;
											//RED BUTTON
										}
										else
											#$php_to_json[$i]['green_slots'][]=$SlotTiming[$SlotID[$j]];
											$php_to_json[$i][$j]=1;
											#$php_to_json[$i]['green_slots'][]=$j;
											//GREEN BUTTON
										
				
									}
									else{
										if(in_array($SlotID[$j],$DocSlotsColumnDate)==false)
										{
											#$php_to_json[$i]['red_slots'][]=$SlotTiming[$SlotID[$j]];
											$php_to_json[$i][$j]=0;
											#$php_to_json[$i]['red_slots'][]=$j;
											//RED BUTTON
										}
										else
										{
											#$php_to_json[$i]['green_slots'][]=$SlotTiming[$SlotID[$j]];
											$php_to_json[$i][$j]=0;
											#$php_to_json[$i]['green_slots'][]=$j;
											//GREEN BUTTON
										}
									}	
								}
								else if(in_array($SlotID[$j],$DocSlotsColumnDate)==false)
								{
									#$php_to_json[$i]['red_slots'][]=$SlotTiming[$SlotID[$j]];
									$php_to_json[$i][$j]=0;
									#$php_to_json[$i]['red_slots'][]=$j;
									//RED BUTTON
								}
								else
								{
								        #$php_to_json[$i]['green_slots'][]=$SlotTiming[$SlotID[$j]];
								        $php_to_json[$i][$j]=1;
										#$php_to_json[$i]['green_slots'][]=$j;
										//GREEN BUTTON
								}
			
								
								}// 12 slots for loop ends here
	                   
						}//30 days for loop slot ends here
						return $php_to_json;
		}	
		//Function Ends here...


function FetchClinicData($DocId)
  {
	$db= new Database();
	//$db->connect();
	$db->select('doctor_clinic','clinic_name,clinic_address,clinic_contact',NULL,'doctor_id='.$DocId.'');
	//$db->sql('SELECT ScheduleID,MonSlots,TuesSlots,WedSlots,ThurSlots,FriSlots,SatSlots,SunSlots,dc.clinic_name,dc.clinic_address,dc.clinic_contact FROM doctor_schedule LEFT JOIN doctor_clinic dc ON doctor_schedule.DoctorId=dc.doctor_id WHERE doctor_schedule.DoctorId='.$DocId)
	$res=$db->getResult();
	//$db->disconnect();
	//print_r($res[0]);
	return $res;
  }

function SearchByAreaSpeciality_fetchSchedule($search_string1,$search_string2,$offset, $rec_limit)
{
	 $search_string1 = preg_replace('/\s+/', '', $search_string1);
	 $search_string2 = preg_replace('/\s+/', '', $search_string2);
   //print_r(strlen($search_string));
   //print_r($search_string);
   if((strlen($search_string1)>=1 && $search_string1 !==' ')||(strlen($search_string2)>=1 && $search_string2 !==' '))
   {
		 $db=new Database();
		 $db->select('doctor_info','doctor_info.doc_id,
									doctor_info.FirstName,doctor_info.LastName,
									doctor_info.speciality,doctor_info.address,doctor_info.lat,doctor_info.lng,doctor_info.fee,doctor_info.degree,doctor_info.experience,
									sc.MonSlots ,sc.TuesSlots,sc.WedSlots,sc.ThurSlots,sc.FriSlots,sc.SatSlots,sc.SunSlots,doctor_info.DocImage
									',
					        'doctor_schedule sc ON doctor_info.doc_id = sc.DoctorId',
					 'area LIKE "%'.$search_string1.'%" OR speciality LIKE "%'.$search_string2.'%" LIMIT '.$offset.','.$rec_limit.'',NULL);
            $res = $db->getResult();
			//echo "<br><br><br><br>";
			//print_r($res);
			//echo "<br><br><br><br>";
			//exit();
			if($res){
					 $NumberofTuples= count($res, COUNT_RECURSIVE)/19;
					 //echo $NumberofTuples;
					 //exit();
					 $firstnameRel = 0.5; # firstname weight percentage
					 $lastnameRel = 0.5; # lastname weight percentage
					
						$relTbl = array();
						$index = 0;
						foreach($res as $entry) {
							$tmpRel = 0;
							$bufferRel = 0;
							# Add the firstname to the tmpRel
							similar_text($search_string1, $entry['address'], $bufferRel);
							$tmpRel += $bufferRel * $firstnameRel;
							# Add the text to the tmpRel
							similar_text($search_string2, $entry['speciality'], $bufferRel);
							$tmpRel += $bufferRel * $lastnameRel;
							# Add the rel to the relTbl
							$relTbl[$index] = $tmpRel;
							$index++;
						}
						 
						# Sort the refTbl by relevence
						arsort($relTbl);
						# Print the row in order by relevence
						//echo "<p><b>Data in order by relevence:</b><br>";
						$resultArray = array();
						$i=0;
						foreach($relTbl as $key => $value)
						{
						//echo"<br>";
						$resultArray[$i]['doc_id']=$res[$key]['doc_id'];
						$resultArray[$i]['FirstName']=$res[$key]['FirstName'];
						$resultArray[$i]['LastName']=$res[$key]['LastName'];
						$resultArray[$i]['address']=$res[$key]['address'];
						$resultArray[$i]['speciality']=$res[$key]['speciality'];
						$resultArray[$i]['fee']=$res[$key]['fee'];
						$resultArray[$i]['degree']=$res[$key]['degree'];
						$resultArray[$i]['experience']=$res[$key]['experience'];
						$resultArray[$i]['lat']=$res[$key]['lat'];
						$resultArray[$i]['lng']=$res[$key]['lng'];
						$resultArray[$i]['MonSlots']=$res[$key]['MonSlots'];
						$resultArray[$i]['TuesSlots']=$res[$key]['TuesSlots'];
						$resultArray[$i]['WedSlots']=$res[$key]['WedSlots'];
						$resultArray[$i]['ThurSlots']=$res[$key]['ThurSlots'];
						$resultArray[$i]['FriSlots']=$res[$key]['FriSlots'];
						$resultArray[$i]['SatSlots']=$res[$key]['SatSlots'];
						$resultArray[$i]['SunSlots']=$res[$key]['SunSlots'];
						$resultArray[$i]['DocImage']=$res[$key]['DocImage'];
						$i++;
						}
						//echo'<br><br><br>';
						return $resultArray;
						/*Now make a return statement to return this $resultArray*/
					}
				else{
					 #print_r('result is zero!search by area speciality fetch schedule');
					 $db->disconnect();
					 return false;
				   }
	 
	}
else
	{
		#print_r('Please Enter Valid Entry,string length problem');
		return false;	   
			  }		
}



function SearchByName_fetchSchedule($search_string,$offset,$rec_limit)
{
	 $search_string = preg_replace('/\s+/', '', $search_string);
   //print_r(strlen($search_string));
   //print_r($search_string);
   if(strlen($search_string)>=1 && $search_string !==' ')
   {
		 $db=new Database();
		 $db->select('doctor_info','doctor_info.doc_id,
									doctor_info.FirstName,doctor_info.LastName,
									doctor_info.speciality,doctor_info.address,doctor_info.lat,doctor_info.lng,doctor_info.fee,doctor_info.degree,doctor_info.experience,
									sc.MonSlots ,sc.TuesSlots,sc.WedSlots,sc.ThurSlots,sc.FriSlots,sc.SatSlots,sc.SunSlots,doctor_info.DocImage
									',
					        'doctor_schedule sc ON doctor_info.doc_id = sc.DoctorId',
					 ' FirstName LIKE "%'.$search_string.'%" OR LastName LIKE "%'.$search_string.'%" OR ss_name LIKE "%'.$search_string.'%" LIMIT '.$offset.','.$rec_limit.'',NULL);
					  
		    /*$db->sql('SELECT doctor_info.doc_id,doctor_info.FirstName,doctor_info.LastName,doctor_info.speciality,doctor_info.address,doctor_info.lat,doctor_info.lng,doctor_info.fee,doctor_info.degree,doctor_info.experience,
					  sc.MonSlots ,sc.TuesSlots,sc.WedSlots,sc.ThurSlots,sc.FriSlots,sc.SatSlots,sc.SunSlots FROM doctor_info LEFT JOIN doctor_schedule sc ON doctor_info.doc_id = sc.DoctorId 
					  WHERE levenshteinE("'.$search_string.'",`FirstName`)BETWEEN 0 AND 5 OR levenshteinE("'.$search_string.'", `LastName`) BETWEEN 0 AND 5 ');*/
            $res = $db->getResult();
			//echo "<br><br><br><br>";
			//print_r($res);
			//echo "<br><br><br><br>";
			//exit();
			if($res){
					 $NumberofTuples= count($res, COUNT_RECURSIVE)/19;
					 //echo $NumberofTuples;
					 //exit();
					 $firstnameRel = 0.5; # firstname weight percentage
					 $lastnameRel = 0.5; # lastname weight percentage
					
						$relTbl = array();
						$index = 0;
						foreach($res as $entry) {
							$tmpRel = 0;
							$bufferRel = 0;
							# Add the firstname to the tmpRel
							similar_text($search_string, $entry['FirstName'], $bufferRel);
							$tmpRel += $bufferRel * $firstnameRel;
							# Add the text to the tmpRel
							similar_text($search_string, $entry['LastName'], $bufferRel);
							$tmpRel += $bufferRel * $lastnameRel;
							# Add the rel to the relTbl
							$relTbl[$index] = $tmpRel;
							$index++;
						}
						 
						# Sort the refTbl by relevence
						arsort($relTbl);
						# Print the row in order by relevence
						//echo "<p><b>Data in order by relevence:</b><br>";
						$resultArray = array();
						$i=0;
						foreach($relTbl as $key => $value)
						{
						//echo"<br>";
						$resultArray[$i]['doc_id']=$res[$key]['doc_id'];
						$resultArray[$i]['FirstName']=$res[$key]['FirstName'];
						$resultArray[$i]['LastName']=$res[$key]['LastName'];
						$resultArray[$i]['address']=$res[$key]['address'];
						$resultArray[$i]['speciality']=$res[$key]['speciality'];
						$resultArray[$i]['fee']=$res[$key]['fee'];
						$resultArray[$i]['degree']=$res[$key]['degree'];
						$resultArray[$i]['experience']=$res[$key]['experience'];
						$resultArray[$i]['lat']=$res[$key]['lat'];
						$resultArray[$i]['lng']=$res[$key]['lng'];
						$resultArray[$i]['MonSlots']=$res[$key]['MonSlots'];
						$resultArray[$i]['TuesSlots']=$res[$key]['TuesSlots'];
						$resultArray[$i]['WedSlots']=$res[$key]['WedSlots'];
						$resultArray[$i]['ThurSlots']=$res[$key]['ThurSlots'];
						$resultArray[$i]['FriSlots']=$res[$key]['FriSlots'];
						$resultArray[$i]['SatSlots']=$res[$key]['SatSlots'];
						$resultArray[$i]['SunSlots']=$res[$key]['SunSlots'];
						$resultArray[$i]['DocImage']=$res[$key]['DocImage'];
						$i++;
						}
						//echo'<br><br><br>';
						return $resultArray;
						/*Now make a return statement to return this $resultArray*/
					}
				else{
					 #print_r('No entry!');
					 $db->disconnect();
					 return false;
				   }
	 
	}
else
	{
		#print_r('Please Enter Valid Entry');
		return false;	   
			  }		
}


function SearchByName2($search_string,$offset,$rec_limit) 
{	 
   //echo $offset;exit();
   
   $search_string = preg_replace('/\s+/', '', $search_string);
   //print_r(strlen($search_string));
   //print_r($search_string);
   if(strlen($search_string)>=1 && $search_string !==' ')
   {
		 $db=new Database();
		 $db->select('doctor_info','doctor_info.doc_id,count(doctor_info.doc_id) as usedSlot,
									ai.AppointmentDate,ai.AppointmentSlot, doctor_info.FirstName,doctor_info.LastName,
									doctor_info.speciality,doctor_info.DocImage,doctor_info.area,doctor_info.address,doctor_info.lat,doctor_info.lng,doctor_info.fee,
									sc.MonSlots ,sc.TuesSlots,sc.WedSlots,sc.ThurSlots,sc.FriSlots,sc.SatSlots,sc.SunSlots,
									ai.AppointmentId',
					        'doctor_schedule sc ON doctor_info.doc_id = sc.DoctorId 
							LEFT JOIN appointment_info ai ON doctor_info.doc_id = ai.DoctorId 
													   AND (ai.AppointmentDate BETWEEN CURDATE() AND CURDATE()+6)',
					 ' FirstName LIKE "%'.$search_string.'%" OR LastName LIKE "%'.$search_string.'%" OR ss_name LIKE "%'.$search_string.'%" GROUP BY doctor_info.doc_id,ai.AppointmentDate,ai.AppointmentSlot LIMIT '.$offset.','.$rec_limit.'',NULL);
            $res = $db->getResult();
			//echo "<br><br><br><br>";
			#print_r($res);
			//exit();
			if($res){
					 $NumberofTuples= count($res, COUNT_RECURSIVE)/21;
					 //echo $NumberofTuples;
					 //exit();
					 $firstnameRel = 0.5; # firstname weight percentage
					 $lastnameRel = 0.5; # lastname weight percentage
					
						$relTbl = array();
						$index = 0;
						foreach($res as $entry) {
							$tmpRel = 0;
							$bufferRel = 0;
							# Add the firstname to the tmpRel
							similar_text($search_string, $entry['FirstName'], $bufferRel);
							$tmpRel += $bufferRel * $firstnameRel;
							# Add the text to the tmpRel
							similar_text($search_string, $entry['LastName'], $bufferRel);
							$tmpRel += $bufferRel * $lastnameRel;
							# Add the rel to the relTbl
							$relTbl[$index] = $tmpRel;
							$index++;
						}
						 
						# Sort the refTbl by relevence
						arsort($relTbl);
						# Print the row in order by relevence
						//echo "<p><b>Data in order by relevence:</b><br>";
						$resultArray = array();
						$i=0;
						foreach($relTbl as $key => $value)
						{
						//echo"<br>";
						$resultArray[$i]['doc_calandar']=FetchCalandar($res[$key]['doc_id']);
						$resultArray[$i]['doc_id']=$res[$key]['doc_id'];
						$resultArray[$i]['usedSlot']=$res[$key]['usedSlot'];
						$resultArray[$i]['AppointmentDate']=$res[$key]['AppointmentDate'];
						$resultArray[$i]['AppointmentSlot']=$res[$key]['AppointmentSlot'];
						$resultArray[$i]['FirstName']=$res[$key]['FirstName'];
						$resultArray[$i]['LastName']=$res[$key]['LastName'];
						$resultArray[$i]['address']=$res[$key]['address'];
						$resultArray[$i]['area']=$res[$key]['area'];
						$resultArray[$i]['speciality']=$res[$key]['speciality'];
						$resultArray[$i]['DocImage']=$res[$key]['DocImage'];
						$resultArray[$i]['fee']=$res[$key]['fee'];
						$resultArray[$i]['lat']=$res[$key]['lat'];
						$resultArray[$i]['lng']=$res[$key]['lng'];
						$resultArray[$i]['MonSlots']=$res[$key]['MonSlots'];
						$resultArray[$i]['TuesSlots']=$res[$key]['TuesSlots'];
						$resultArray[$i]['WedSlots']=$res[$key]['WedSlots'];
						$resultArray[$i]['ThurSlots']=$res[$key]['ThurSlots'];
						$resultArray[$i]['FriSlots']=$res[$key]['FriSlots'];
						$resultArray[$i]['SatSlots']=$res[$key]['SatSlots'];
						$resultArray[$i]['SunSlots']=$res[$key]['SunSlots'];
						$resultArray[$i]['AppointmentId']=$res[$key]['AppointmentId'];
						$i++;
						}
						//echo'<br><br><br>';
						return $resultArray;
						/*Now make a return statement to return this $resultArray*/
					}
				else{
					 #print_r('No entry!');
					 $db->disconnect();
					 return false;
				   }
	 
	}
else
	{
		#print_r('Please Enter Valid Entry');
		return false;	   
			  }			 	   
}
 
 function SearchBySpecialityAndArea2($speciality,$area,$offset, $rec_limit)
{
	 $speciality = preg_replace('/\s+/', '', $speciality);
	 $area = preg_replace('/\s+/', '', $area);
	 //print_r($speciality);
	 //print_r($area);
	 if( !($speciality ==''&& $area==''))
	 {
		 $db=new Database();
		 //$db->connect();
		 $db->select('doctor_info','doctor_info.doc_id,count(doctor_info.doc_id) as usedSlot,
									ai.AppointmentDate,ai.AppointmentSlot, doctor_info.FirstName,doctor_info.LastName,
									doctor_info.speciality,doctor_info.address,doctor_info.lat,doctor_info.lng,doctor_info.fee,
									sc.MonSlots ,sc.TuesSlots,sc.WedSlots,sc.ThurSlots,sc.FriSlots,sc.SatSlots,sc.SunSlots,
									ai.AppointmentId',
					        'doctor_schedule sc ON doctor_info.doc_id = sc.DoctorId 
							LEFT JOIN appointment_info ai ON doctor_info.doc_id = ai.DoctorId 
													   AND (ai.AppointmentDate BETWEEN CURDATE() AND CURDATE()+6)',
					 'speciality LIKE "%'.$speciality.'%" OR area LIKE "%'.$area.'%" LIMIT '.$offset.','.$rec_limit.'',NULL);
		 $res=$db->getResult();
		 $db->disconnect();
	if($res){
			        $NumberofTuples= count($res, COUNT_RECURSIVE)/20;
					/*  echo $NumberofTuples;
					 echo"<br><br>";
					 print_r($res);
					 echo"<br><br>"; */
					 $specialityRel = 0.6; # speciality weight percentage
					 $areaRel = 0.4; # area weight percentage
					 //return $res;
						# Find relevence for each entry and store it in a new
						# array at the same index as the entry being searched.
						$relTbl = array();
						$index = 0;
						foreach($res as $entry) {
							$tmpRel = 0;
							$bufferRel = 0;
							# Add the speciality to the tmpRel
							similar_text($speciality, $entry['speciality'], $bufferRel);
							$tmpRel += $bufferRel * $specialityRel;
							# Add the area to the tmpRel
							similar_text($area, $entry['address'], $bufferRel);
							$tmpRel += $bufferRel * $areaRel;
							# Add the rel to the relTbl
							$relTbl[$index] = $tmpRel;
							$index++;
						}
						 
						# Sort the refTbl by relevence
						arsort($relTbl);
						# Print the row in order by relevence
						//echo "<p><b>Data in order by relevence:</b><br>";
						/* echo '<br>';
						print_r($relTbl);
						echo '<br>'; */
						$resultArray = array();
						$i=0;
						foreach($relTbl as $key => $value)
						{
						/* echo"<br>";
						echo $key;
						echo" and ";
						echo $value;
						echo "<br />". $res[$key]['FirstName'] . "". $res[$key]['LastName'] ." ". $res[$key]['address'] ." (". round($value, 3) ."%)";
						echo"<br>"; */
						$resultArray[$i]['doc_id']=$res[$key]['doc_id'];
						$resultArray[$i]['usedSlot']=$res[$key]['usedSlot'];
						$resultArray[$i]['AppointmentDate']=$res[$key]['AppointmentDate'];
						$resultArray[$i]['AppointmentSlot']=$res[$key]['AppointmentSlot'];
						$resultArray[$i]['FirstName']=$res[$key]['FirstName'];
						$resultArray[$i]['LastName']=$res[$key]['LastName'];
						$resultArray[$i]['address']=$res[$key]['address'];
						$resultArray[$i]['speciality']=$res[$key]['speciality'];
						$resultArray[$i]['fee']=$res[$key]['fee'];
						$resultArray[$i]['lat']=$res[$key]['lat'];
						$resultArray[$i]['lng']=$res[$key]['lng'];
						$resultArray[$i]['MonSlots']=$res[$key]['MonSlots'];
						$resultArray[$i]['TuesSlots']=$res[$key]['TuesSlots'];
						$resultArray[$i]['WedSlots']=$res[$key]['WedSlots'];
						$resultArray[$i]['ThurSlots']=$res[$key]['ThurSlots'];
						$resultArray[$i]['FriSlots']=$res[$key]['FriSlots'];
						$resultArray[$i]['SatSlots']=$res[$key]['SatSlots'];
						$resultArray[$i]['SunSlots']=$res[$key]['SunSlots'];
						$resultArray[$i]['AppointmentId']=$res[$key]['AppointmentId'];
						$i++;
						}
						//echo'<br><br><br>';
						//print_r($resultArray);
						return $resultArray;
						/*Now make a return statement to return this $resultArray*/
			 
			 }
		 else
		 {
			 #print_r('No entry!SearchBySpecialityAndArea function');
			 $db->disconnect();
			 return false;
		 }
	 }
	 else
	 {
	   #print_r('Please Enter Valid Entry,string length problem');
	   return false;
	  }
}








/*This function updates the count array generated*/
function generateRealtimeAvailability($countArray,$searchResult,$NumberofTuples)
{  
   $Slots=array('A','B','C','D','E','F','G','H','I','J','K','L');
  // print_r($searchResult);
   for($i=0;$i<$NumberofTuples;$i++)
  {
	//echo $countArray[$searchResult[$i]['doc_id']];
	//continue;
	if(isset($countArray[$searchResult[$i]['doc_id']]))
	{	
		for($j=0;$j<12;$j++){
		if($countArray[$searchResult[$i]['doc_id']][$Slots[$j]]=='0'||$countArray[$searchResult[$i]['doc_id']][$Slots[$j]]=='1')
		continue;
		if($countArray[$searchResult[$i]['doc_id']][$Slots[$j]]>3)
		  {
			 $countArray[$searchResult[$i]['doc_id']][$Slots[$j]]='0';
		  }
		else 
		  {
			$countArray[$searchResult[$i]['doc_id']][$Slots[$j]]='1';
		  }
	    }
    }
	else
	{
		for($j=0;$j<12;$j++){
		$countArray[$searchResult[$i]['doc_id']][$Slots[$j]]='1';
		}
	}
  }
  return $countArray;

}



/*This function updates the count array generated*/
function newgenerateRealtimeAvailability($countArray,$searchResult,$NumberofTuples)
{  
   $Slots=array('A','B','C','D','E','F','G','H','I','J','K','L');
  // print_r($searchResult);
   for($i=0;$i<$NumberofTuples;$i++)
  {
	//print_r($countArray[$searchResult[$i]['doc_id']][$searchResult[$i]['AppointmentDate']]);
	//continue;
	if(isset($countArray[$searchResult[$i]['doc_id']][$searchResult[$i]['AppointmentDate']]))
	{	
		for($j=0;$j<12;$j++){
		if($countArray[$searchResult[$i]['doc_id']][$searchResult[$i]['AppointmentDate']][$Slots[$j]]=='0'||$countArray[$searchResult[$i]['doc_id']][$searchResult[$i]['AppointmentDate']][$Slots[$j]]=='1')
		continue;
		if($countArray[$searchResult[$i]['doc_id']][$Slots[$j]]>3)
		  {
			 $countArray[$searchResult[$i]['doc_id']][$Slots[$j]]='0';
		  }
		else 
		  {
			$countArray[$searchResult[$i]['doc_id']][$Slots[$j]]='1';
		  }
	    }
    }
	else
	{
		for($j=0;$j<12;$j++){
		$countArray[$searchResult[$i]['doc_id']] [$searchResult[$i]['AppointmentDate']] [$Slots[$j]]='1';
		}
	}
  }
  return $countArray;

}


 
 
 

?>

