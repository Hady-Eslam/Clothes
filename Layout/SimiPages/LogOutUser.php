<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/Clothes.com/PHP/ROOT.php';
include_once Session;

$GLOBALS['Page_API_Error_Code'] = 'SP1';
set_error_handler("Error_Handeler");

if ( $Session->DestroySession() == -1 ){
	include_once ErrorPage;
	exit();
}

header("Location:".Login);
exit();
?>