<?php
/*
    -info
        php page    =>  FILTERS.php
        init name   =>  FILTERS
        class name  =>  FILTERSClass
        object name =>  FILTER

*/
include_once $_SERVER['DOCUMENT_ROOT'].'/Clothes.com/PHP/ROOT.php';

if ( isset($GLOBALS['Page_API_Error_Code']) )
    $GLOBALS['Page_API_Error_Code'] .= '.C5';
else
    $GLOBALS['Page_API_Error_Code'] = 'C5';
set_error_handler("Error_Handeler");

class FILTERSClass{

    public $FILTER_Result = '';
    
    /*
        - Return : 
            return array(0, 'Empty');
            return array(0, 'Too Long');
            return array(0, 'OK');
    */
    function FilterString($String, $Len){
        $this->FILTER_Result = filter_var($String, FILTER_SANITIZE_STRING);

        if ( empty($String) )
            return array(0, 'Empty');
        else if ( strlen($String) > $Len )
            return array(0, 'Too Long');
        return array(0, 'OK');
    }

    /*
        - Return : 
            return array(0, 'Empty');
            return array(0, 'Too Long');
            return array(0, 'OK');
    */
    function FilterPhone($Phone){
        $this->FILTER_Result = filter_var($Phone, FILTER_SANITIZE_STRING);

        if ( empty($Phone) )
            return array( 0, 'Empty');
        if ( strlen($Phone) != Phone_Len )
            return array(0, 'Too Long');
        return array(0, 'OK');
    }

    /*
        Return :
            return array(0, 'Empty');
            return array(0, 'Too Long');
            return array(0, 'Not Number');
            
            return array(0, 'Non Positive Value');
            return array(0, 'Negative Value');
            return array(0, 'Non Negative Value');
            return array(0, 'Positive Value');
            
            return array(0, 'OK');
    */
    function FilterNumber($Number, $Len, $Res){
        if ( ($Result = $this->FilterString($Number, $Len))[1] != 'OK' )
            return $Result;
        
        if ( is_numeric($this->FILTER_Result) == false )
            return array(0, 'Not Number');
    
    // i Want  Value > 0
        if ( $Res == 1 ){
            if ( $this->FILTER_Result <= 0 )
                return array(0, 'Non Positive Value');
        }
    // i Want Value >= 0
        else if ( $Res == 2 ){
            if ( $this->FILTER_Result < 0 )
                return array(0, 'Negative Value');
        }
    // i Want Value < 0
        else if ( $Res == 3 ){
            if ( $this->FILTER_Result >= 0 )
                return array(0, 'Non Negative Value');
        }
    // i Want Value <= 0
        else if ( $Res == 4 ){
            if ( $this->FILTER_Result > 0 )
                return array(0, 'Positive Value');
        }
        return array(0, 'OK');
    }
}

$FILTER = new FILTERSClass();
?>