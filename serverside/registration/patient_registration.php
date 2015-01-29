<?php
$data = file_get_contents("php://input");
$objData = json_decode($data);
print_r($objData->fname);



?>