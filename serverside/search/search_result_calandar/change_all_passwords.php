<?php
include_once('mysql_crud.php');
$password="19aug";
$password=crypt($password);
$db=new Database();
$db->select('doctor_info','doc_id',NULL,
					 NULL,NULL);
$res=$db->getResult();
$NumberofDoctors= count($res, COUNT_RECURSIVE)/2;
for($i=0;$i<$NumberofDoctors;$i++)
{
$db->update('doctor_info',array('password'=>$password),'doc_id = '.$res[$i]['doc_id'].'');
echo $password;
echo" Added for";
echo $res[$i]['doc_id'];
echo"<br>";
}

?>