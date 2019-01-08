<?php
/*
    -info
        php page    =>  Session.php
        init name   =>  Session
        class name  =>  SessionClass
        object name =>  Seesion
*/
include_once $_SERVER['DOCUMENT_ROOT'].'/Clothes.com/PHP/ROOT.php';
include_once MySqlDB;
include_once HashClass;

if ( isset($GLOBALS['Page_API_Error_Code']) )
    $GLOBALS['Page_API_Error_Code'] .= '.C3';
else
    $GLOBALS['Page_API_Error_Code'] = 'C3';
set_error_handler("Error_Handeler");

class MySessionHandeler implements SessionHandlerInterface{
    
	private $MySql;
    private $Hashing;

    public function open($savePath, $sessionName){
    	$this->MySql = new MYSQLClass();
    	$this->Hashing = new HashingClass();
        return true;
    }

    public function close(){
        return true;
    }

    public function destroy($id){
        return true;
    }

    public function gc($maxlifetime){
        return true;
    }

    public function read($id){
    	if ( ($Result = $this->MySql->FetchOneRow('SELECT * FROM session WHERE id = ?',
		    		array(
		    			$this->Hashing->Hash_Session($id),
		    		))) == -1 || $Result == 0 )
    		return '';
    	$this->Hashing->Get_Hashed_Session($this->MySql->Fetched_Data['data']);
    	return $this->Hashing->HashedText;
    }

    public function write($id, $data){
    	if ( $this->MySql->excute('REPLACE INTO session (id, data, session_date) '
    				.'VALUES (?, ?, ?)',
		    		array(
		    			$this->Hashing->Hash_Session($id),
		    			$this->Hashing->Hash_Session($data),
		    			date('D d-m-Y H:i:s')
		    		)) == -1 )
    		return true;
        return true;
    }
}


class SessionClass{

    // Error Array
    public $Error = array(
                    'Error Location'    => '',
                    'Error Code'        => '',
                    'Error Message'     => '',
                );
    private $MySql;
    private $Hashing;

    private function ErrorReturn($ErrorLocation, $ErrorCode, $ErrorMessage){
        $this->Error['Error Location'] = $ErrorLocation;
        $this->Error['Error Code'] = $ErrorCode;
        $this->Error['Error Message'] = $ErrorMessage;
        return -1;
    }

    function __construct(){

        $this->MySql = new MYSQLClass();
        $this->Hashing = new HashingClass();

        session_set_save_handler(new MySessionHandeler(), true);
        register_shutdown_function('session_write_close');
/*
    session_start Must be writen Before Any Output (echo, print, print_r)
*/
        session_start();
    }

    function NewSessionID(){
        session_regenerate_id(true);
    }

    function DestroySession(){
        if ( $this->MySql->excute('DELETE FROM session WHERE id = ?',
                    array(
                        $this->Hashing->Hash_Session(session_id()),
                    )) == -1 )
            return array(-1, $this->MySql->Error, 'Destroying Session', 'Destroy Session');
        session_unset();
        session_destroy();
        return array(0, 'Done');
    }
}

$Session = new SessionClass();
?>