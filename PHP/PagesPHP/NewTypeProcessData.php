<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/Clothes.com/PHP/ROOT.php';
include_once Session;
include_once FILTERS;
include_once HashClass;
include_once MySqlDB;

if ( isset($GLOBALS['Page_API_Error_Code']) )
    $GLOBALS['Page_API_Error_Code'] .= '.P7F1';
else
    $GLOBALS['Page_API_Error_Code'] = 'P7F1';
set_error_handler("Error_Handeler");

/*
	return array(0, 'Empty');
        return array(0, 'Too Long');
        return array(0, 'Not Number');
        return array(0, 'Negative Value');

        return array(0, 'Wrong Data');
        return array(0, 'Not in Range');

    	return array(-1, $MySql->Error, 'inserting New Type into DataBase', 'Check Data');
    	return array(0, 'Done');
*/
function CheckData(){
	$MySql = new MYSQLClass();
    $Hashing = new HashingClass();
    $FILTER = new FILTERSClass();

// Check Group
	if ( ($Result = $FILTER->FilterString($_POST['G'], Name_Len))[1] != 'OK' )
        return $Result;
    $GLOBALS['G'] = $FILTER->FILTER_Result;
    if ( $GLOBALS['G'] != 'Pantes' && $GLOBALS['G'] != 'T-Shert' && $GLOBALS['G'] != 'Cap')
    	return array(0, 'Wrong Data');

// Check Name
    if ( ($Result = $FILTER->FilterString($_POST['N'], Name_Len))[1] != 'OK' )
        return $Result;
    $GLOBALS['N'] = $FILTER->FILTER_Result;

// Check Factory
    if ( ($Result = $FILTER->FilterString($_POST['F'], Name_Len))[1] != 'OK' )
        return $Result;
    $GLOBALS['F'] = $FILTER->FILTER_Result;

// Check Factory Name in DataBase Or Not
    if ( ($Result = $MySql->isFound('SELECT name FROM customers WHERE status = ? AND '
                .'name = ?',
                array(
                    '2',
                    $Hashing->Hash_Customers($GLOBALS['F'])
                ))) == -1 )
        return array(-1, $MySql->Error, 'Checking Factory Name in DataBase Or Not', 
                'Check Data');
    else if ( $Result == 0 ){
        if ( $MySql->excute('INSERT INTO customers(name, status) VALUES(?, ?)',
                array(
                    $Hashing->Hash_Customers($GLOBALS['F']),
                    '2'
                )) == -1 )
            return array(-1, $MySql->Error, 'inserting Factory Name into DataBase', 
                'Check Data');
    }

// Check WholeSale Price
    if ( empty($_POST['WP']) )
        $GLOBALS['WP'] = '0';
    else if ( ($Result = $FILTER->FilterNumber($_POST['WP'], Money_Len, 2))[1] != 'OK' )
        return $Result;
    else
        $GLOBALS['WP'] = $FILTER->FILTER_Result;

// Check WholeSale Disccount
    if ( empty($_POST['WD']) )
        $GLOBALS['WD'] = '0';
    else if ( ($Result = $FILTER->FilterNumber($_POST['WD'], Money_Len, 2))[1] != 'OK' )
        return $Result;
    else
        $GLOBALS['WD'] = $FILTER->FILTER_Result;
    if ( $GLOBALS['WD'] < 0 || $GLOBALS['WD'] > 100 )
    	return array(0, 'Not in Range');

// Check Retail Price
    if ( empty($_POST['RP']) )
        $GLOBALS['RP'] = '0';
    else if ( ($Result = $FILTER->FilterNumber($_POST['RP'], Money_Len, 2))[1] != 'OK' )
        return $Result;
    else
        $GLOBALS['RP'] = $FILTER->FILTER_Result;

// Check Retail Diccount
    if ( empty($_POST['RD']) )
        $GLOBALS['RD'] = '0';
    else if ( ($Result = $FILTER->FilterNumber($_POST['RD'], Money_Len, 2))[1] != 'OK' )
        return $Result;
    else
        $GLOBALS['RD'] = $FILTER->FILTER_Result;
    if ( $GLOBALS['RD'] < 0 || $GLOBALS['RD'] > 100 )
    	return array(0, 'Not in Range');

// Check Quantity in Store
    if ( empty($_POST['Q']) )
        $GLOBALS['Q'] = '0';
    else if ( ($Result = $FILTER->FilterNumber($_POST['Q'], Money_Len, 2))[1] != 'OK' )
        return $Result;
    else
        $GLOBALS['Q'] = $FILTER->FILTER_Result;

// Store in Data Base
    if ( $MySql->excute('INSERT INTO types (name, type_group, factory, wholesale_price, '
    		.'wholesale_discount, retail_price, retail_discount, quantity_in_store) '
            .' VALUES(?, ?, ?, ?, ?, ?, ?, ?)',
    		array(
    			$Hashing->Hash_Type($GLOBALS['N']),
    			$Hashing->Hash_Type($GLOBALS['G']),
    			$Hashing->Hash_Type($GLOBALS['F']),
    			$GLOBALS['WP'],
    			$GLOBALS['WD'],
    			$GLOBALS['RP'],
    			$GLOBALS['RD'],
    			$GLOBALS['Q']
    		)) == -1 )
    	return array(-1, $MySql->Error, 'inserting New Type into DataBase', 'Check Data');
    return array(0, 'Done');
}
?>