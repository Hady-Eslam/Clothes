<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/Clothes.com/PHP/ROOT.php';
include_once Session;
include_once FILTERS;
include_once HashClass;
include_once MySqlDB;

if ( isset($GLOBALS['Page_API_Error_Code']) )
    $GLOBALS['Page_API_Error_Code'] .= '.P2F1';
else
    $GLOBALS['Page_API_Error_Code'] = 'P2F1';
set_error_handler("Error_Handeler");

/*
	- Return : 
        return array(0, 'Empty');
        return array(0, 'Too Long');

        return array(-1, $MySql->Error, 'Check The Password is Right', 'Check Data');
        return array(0, 'Wrong Password');
        return array(-1, $MySql->Error, 'inserting Password into DataBase', 'Check Data');
        return array(0, 'Done');
*/
function CheckData(){
	$MySql = new MYSQLClass();
    $Hashing = new HashingClass();
    $FILTER = new FILTERSClass();

// Check Old Password
    if ( ( $Result = $FILTER->FilterString($_POST['OP'], Password_Len) )[1] != 'OK' )
        return $Result;
    $GLOBALS['OP'] = $FILTER->FILTER_Result;

// Check Old Password
    if ( ( $Result = $FILTER->FilterString($_POST['P'], Password_Len) )[1] != 'OK' )
        return $Result;
    $GLOBALS['P'] = $FILTER->FILTER_Result;

// Check Password in DataBase
    if ( ($Result = $MySql->isFound('SELECT * FROM users WHERE name = ? AND password = ?',
                array(
                    $Hashing->Hash_Users($_SESSION['Name']),
                    $Hashing->Hash_Users($GLOBALS['OP'])
                ))) == -1 )
        return array(-1, $MySql->Error, 'Check The Password is Right', 'Check Data');
    else if ( $Result == 0 )
        return array(0, 'Wrong Password');

// Change Password
    if ( $MySql->excute('UPDATE users SET password = ? WHERE name = ?',
            array(
                $Hashing->Hash_Users($GLOBALS['P']),
                $Hashing->Hash_Users($_SESSION['Name'])
            )) == -1 )
        return array(-1, $MySql->Error, 'inserting Password into DataBase', 'Check Data');
    return array(0, 'Done');
}
?>