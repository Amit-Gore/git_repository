<?php
error_reporting(1);
 
$bookedArray=array();
//$freeDoctors=array();
$NumberofTuples= count($searchResult,COUNT_RECURSIVE)/26;
//echo $NumberofTuples;
for($i=0;$i<$NumberofTuples;$i++)
{
	if($searchResult[$i]['usedSlot']>=3)
	{
	 //echo "count grtrt thn 3.<br>";
	 //echo $searchResult[$i]['AppointmentSlot'];
	 $bookedArray[$searchResult[$i]['doc_id']][$searchResult[$i]['AppointmentDate']][]	=	$searchResult[$i]['AppointmentSlot'];
	 
	 
	}
	else
	{
	  if(isset($searchResult[$i]['AppointmentDate']))
	  $bookedArray[$searchResult[$i]['doc_id']][$searchResult[$i]['AppointmentDate']][]=NULL;
	   else
	  {
	  //$bookedArray[$searchResult[$i]['doc_id']][date("Y-m-d")][]=NULL;   //We have got an issue here, need attention:
	  #If appointment date is NULL then the problem occurs 
	  //$freeDoctors[$searchResult[$i]['doc_id']][]=1;
      }	
	}
	
}


