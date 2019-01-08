<?php
/*
    -info
        php page    =>  URL.php
        init name   =>  URL
        class name  =>  URLClass
        object name =>  URL_REFERER

    - Errors
        8   =>  URL Error   =>  Error in Getting The Path Of The URL
*/
include_once $_SERVER['DOCUMENT_ROOT'].'/Clothes.com/PHP/ROOT.php';

if ( isset($GLOBALS['Page_API_Error_Code']) )
    $GLOBALS['Page_API_Error_Code'] .= '.C4';
else
    $GLOBALS['Page_API_Error_Code'] = 'C4';
set_error_handler("Error_Handeler");

class URLClass{

    // Error Array
    public $Error = array(
                    'Error Location'    => '',
                    'Error Code'        => '',
                    'Error Message'     => '',
                );
    public $URLResult = '';
    private function ErrorReturn($ErrorLocation, $ErrorCode, $ErrorMessage){
        $this->Error['Error Location'] = $ErrorLocation;
        $this->Error['Error Code'] = $ErrorCode;
        $this->Error['Error Message'] = $ErrorMessage;
        return -1;
    }

    function GetURLPath(){
        try{
            if ( !isset($_SERVER["HTTP_REFERER"]) )
                return 0;
            $URL = parse_url($_SERVER["HTTP_REFERER"], PHP_URL_PATH);
            $this->URLResult = 'http://localhost'.$URL;
            return 1;
        }
        catch(Exception $e){
            return ErrorReturn('URL Error', '8', 'Error in Getting The Path Of The URL');
        }
    }
}

$URL_REFERER = new URLClass();
?>