<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/Clothes.com/PHP/ROOT.php';
include_once Session;
include_once FILTERS;
include_once HashClass;
include_once MySqlDB;

if ( isset($GLOBALS['Page_API_Error_Code']) )
    $GLOBALS['Page_API_Error_Code'] .= '.P3F1';
else
    $GLOBALS['Page_API_Error_Code'] = 'P3F1';
set_error_handler("Error_Handeler");

/*
    - Return : 
        return array(0, 'Empty');
        return array(0, 'Too Long');
        return array(0, 'Wrong Data');
        return array(-1, $MySql->Error, 'Checking Name Found Or Not', 'CheckData');
        return array(0, 'Found');

        return array(-1, $MySql->Error, 'inserting User into Database', 'Check Data');
        return array(0, 'Added');

        return array(-1, $MySql->Error, 'Deleting User From DataBase', 'Delete User');
        return array(0, 'Deleted');
*/
function CheckData(){

    if ( isset($_POST['SaveButton']) )
        return SaveUser();
    else if ( isset($_POST['DeleteButton']) )
        return DeleteUser();
    else if ( isset($_POST['EditButton']) )
        return EditUser();
}

/*
	- Return : 
        return array(0, 'Empty');
        return array(0, 'Too Long');
        return array(0, 'Wrong Data');
        return array(-1, $MySql->Error, 'Checking Name Found Or Not', 'CheckData');
        return array(0, 'Found');

        return array(-1, $MySql->Error, 'inserting User into Database', 'Check Data');
        return array(0, 'Added');
*/
function SaveUser(){
    $MySql = new MYSQLClass();
    $Hashing = new HashingClass();
    $FILTER = new FILTERSClass();

// Check Name
    if ( ( $Result = $FILTER->FilterString($_POST['N'], Name_Len) )[1] != 'OK' )
        return $Result;
    $GLOBALS['N'] = $FILTER->FILTER_Result;

    if ( ($Result = $MySql->isFound('SELECT * FROM users WHERE name = ?',
            array(
                $Hashing->Hash_Users($GLOBALS['N'])
            ))) == -1 )
        return array(-1, $MySql->Error, 'Checking Name Found Or Not', 'Save User');
    else if ( $Result == 1 )
        return array(0, 'Found');

// Check Status
    if ( ( $Result = $FILTER->FilterString($_POST['Status'], Status_Len) )[1] != 'OK' )
        return $Result;
    $GLOBALS['Status'] = $FILTER->FILTER_Result;
    if ( $GLOBALS['Status'] != 'Admin' && $GLOBALS['Status'] != 'User' )
        return array(0, 'Wrong Data');


// Check Phone
    if ( ( $Result = $FILTER->FilterString($_POST['Ph'], Phone_Len) )[1] != 'OK' )
        $GLOBALS['Ph'] = 'NO Phone';
    else
        $GLOBALS['Ph'] = $FILTER->FILTER_Result;
    
// Check Password
    if ( ( $Result = $FILTER->FilterString($_POST['P'], Password_Len) )[1] != 'OK' )
        return $Result;
    $GLOBALS['P'] = $FILTER->FILTER_Result;

// insert into DataBase
    if ( $MySql->excute('INSERT INTO users (status, name, password, phone, sign_up_date)'
            .' VALUES (?, ?, ?, ?, ?)',
            array(
                ($GLOBALS['Status'] == 'User')? '1' : '0',
                $Hashing->Hash_Users($GLOBALS['N']),
                $Hashing->Hash_Users($GLOBALS['P']),
                $Hashing->Hash_Users($GLOBALS['Ph']),
                date('D d-m-Y H:i:s')
            )) == -1 )
        return array(-1, $MySql->Error, 'inserting User into Database', 'Save User');
    return array(0, 'Added');
}

/*
    Return :
        return array(0, 'Empty');
        return array(0, 'Too Long');
        return array(-1, $MySql->Error, 'Deleting User From DataBase', 'Delete User');
        return array(0, 'Deleted');
*/
function DeleteUser(){
    $MySql = new MYSQLClass();
    $Hashing = new HashingClass();
    $FILTER = new FILTERSClass();

    // Check Name
    if ( ( $Result = $FILTER->FilterString($_POST['N'], Name_Len) )[1] != 'OK' )
        return $Result;
    $GLOBALS['N'] = $FILTER->FILTER_Result;

    if ( $MySql->excute('DELETE FROM users WHERE name = ?',
            array(
                $Hashing->Hash_Users($GLOBALS['N'])
            )) == -1 )
        return array(-1, $MySql->Error, 'Deleting User From DataBase', 'Delete User');
    return array(0, 'Deleted');
}

/*
    Return :
        return array(0, 'Empty');
        return array(0, 'Too Long');
        return array(0, 'Wrong Data');

        return array(-1, $MySql->Error, 'Checking The User Found Or Not','Edit Data');
        return array(0, 'Not Found');

        return array(-1, $MySql->Error, 'Editing The User Data', 'Edit Data');
        return array(0, 'Edited');
*/
function EditUser(){
    $MySql = new MYSQLClass();
    $Hashing = new HashingClass();
    $FILTER = new FILTERSClass();

// Check User ID
    if ( ( $Result = $FILTER->FilterString($_POST['ID'], ID_Len) )[1] != 'OK' )
        return $Result;
    $GLOBALS['ID'] = $FILTER->FILTER_Result;
    
// Check if ID is Found Or NOT
    if ( ($Result = $MySql->isFound('SELECT name FROM users WHERE id = ?',
            array(
                $GLOBALS['ID']
            ))) == -1 )
        return array(-1, $MySql->Error, 'Checking The User Found Or Not','Edit Data');
    else if ( $Result == 0 )
        return array(0, 'Not Found');

// Check User Status
    if ( ( $Result = $FILTER->FilterString($_POST['EStatus'], Status_Len) )[1] != 'OK' )
        return $Result;
    $GLOBALS['EStatus'] = $FILTER->FILTER_Result;
    if ( $GLOBALS['EStatus'] != 'Admin' && $GLOBALS['EStatus'] != 'User' )
        return array(0, 'Wrong Data');
    
// Check User Phone
    if ( ( $Result = $FILTER->FilterString($_POST['Ph'], Phone_Len) )[1] != 'OK' )
        $GLOBALS['Ph'] = 'NO Phone';
    else
        $GLOBALS['Ph'] = $FILTER->FILTER_Result;
    
// insert Data into Data Base
    if ( $MySql->excute('UPDATE users SET status = ?, phone = ? WHERE id = ?',
            array(
                ($GLOBALS['EStatus'] == 'User')? '1' : '0',
                $Hashing->Hash_Users($GLOBALS['Ph']),
                $GLOBALS['ID']
            )) == -1 )
        return array(-1, $MySql->Error, 'Editing The User Data', 'Edit Data');
    return array(0, 'Edited');
}
?>