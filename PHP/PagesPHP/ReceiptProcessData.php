<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/Clothes.com/PHP/ROOT.php';
include_once Session;
include_once FILTERS;
include_once HashClass;
include_once MySqlDB;

if ( isset($GLOBALS['Page_API_Error_Code']) )
    $GLOBALS['Page_API_Error_Code'] .= '.P6F1';
else
    $GLOBALS['Page_API_Error_Code'] = 'P6F1';
set_error_handler("Error_Handeler");

/*
	Return :
		return array(0, 'Empty');
        return array(0, 'Too Long');
        return array(0, 'Not Number');
        return array(0, 'Non Positive Value');

        return array(-1, $MySql->Error, 'Checking Name in DataBase', 'Pay Money');
        return array(-1, $MySql->Error, 'inserting Customer Name into DataBase','Pay Money');
        return array(-1, $MySql->Error, 'inserting Receipt Data into DataBase', 'Pay Money');
    	return array(0, 'Done');
*/
function CheckData(){
	$MySql = new MYSQLClass();
    $Hashing = new HashingClass();
    $FILTER = new FILTERSClass();

// Check Customer Name
    if ( ( $Result = $FILTER->FilterString($_POST['N'], Name_Len) )[1] != 'OK' )
        return $Result;
    $GLOBALS['N'] = $FILTER->FILTER_Result;

// Check Money
    if ( ( $Result = $FILTER->FilterNumber($_POST['M'], Money_Len, 1) )[1] != 'OK' )
        return $Result;
    $GLOBALS['M'] = $FILTER->FILTER_Result;

// Check Note
    if ( ( $Result = $FILTER->FilterString($_POST['Note'], Note_Len) )[1] != 'OK' )
        $GLOBALS['Note'] = 'No Note';
    else
    	$GLOBALS['Note'] = $FILTER->FILTER_Result;

// Check Name is in DataBase Or Not
    if ( ($Result = $MySql->isFound('SELECT * FROM customers WHERE name = ? AND status = ?',
    			array(
    				$Hashing->Hash_Customers($GLOBALS['N']),
    				$GLOBALS['PageStatus']
    			))) == -1 )
    	return array(-1, $MySql->Error, 'Checking Name in DataBase', 'Pay Money');
    else if ( $Result == 0 ){

    // insert Name into DataBase
    	if ( $MySql->excute('INSERT INTO customers (name, status) VALUES (?, ?)',
    			array(
    				$Hashing->Hash_Customers($GLOBALS['N']),
    				$GLOBALS['PageStatus']
    			)) == -1 )
    		return array(-1, $MySql->Error, 'inserting Customer Name into DataBase',
    			'Pay Money');
    }

// insert Data into DataBase
    if ( $MySql->excute('INSERT INTO receipt (status, name, money, note, receipt_date)'
    				.'VALUES(?, ?, ?, ?, ?)',
			array(
				$GLOBALS['PageStatus'],
				$Hashing->Hash_Customers($GLOBALS['N']),
				$Hashing->Hash_Customers($GLOBALS['M']),
				$Hashing->Hash_Customers($GLOBALS['Note']),
				date('D d/m/Y H:i:s')
			)) == -1 )
    	return array(-1, $MySql->Error, 'inserting Receipt Data into DataBase'
    			, 'Pay Money');
    return array(0, 'Done');
}
?>