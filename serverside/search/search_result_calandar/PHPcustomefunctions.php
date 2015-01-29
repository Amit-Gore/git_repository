<?php

function arrayintersect($arr1,$masterArr)
{
   $length1=count($arr1);
   $lengthma=count($masterArr);
   //echo $masterArr;exit();
   //echo $arr1;exit();
   //echo $lengthma;exit();
   //echo $lengthma;
   //echo $length1;exit();
   //echo $length1.'<br>';
   $hash=array();
   for($i=0;$i<$lengthma;$i++)
   {
     $hash[ord($masterArr[$i])]=1;
   }
   //print_r($hash);exit();
   for($i=0;$i<$length1;$i++)
   {
     if(isset($hash[ord($arr1[$i])]))
	 {
	 if($hash[ord($arr1[$i])]==1)return true;
	 }
   }
   return false;
   
}
#$array=['B','C','D','I','J','K','L'];
#$Master=['M','N'];

#if(arrayintersect($array,$Master)==true) echo"YES";
#else echo "NO";
