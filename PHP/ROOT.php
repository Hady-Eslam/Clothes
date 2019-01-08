<?php
if ( isset($GLOBALS['Page_API_Error_Code']) )
    $GLOBALS['Page_API_Error_Code'] .= '.i';
else
    $GLOBALS['Page_API_Error_Code'] = 'i';
set_error_handler("Error_Handeler");

function Error_Handeler($Error_Level, $Error_Message, $Error_File, $Error_Line) {
    ?>
    <!DOCTYPE>
    <html>
    <head>
        <title>Error in Loading</title>
    </head>
    <body>
        <p>Error Title = Error in Loading Page</p>
        <p>Page API Error Code = <?php echo $GLOBALS['Page_API_Error_Code'];?></p>
        <p>Error Discription = Something Goes Wrong</p>
        <p>Error Line = <?php echo $Error_Line; ?></p>
        <p>Error Message = <?php echo $Error_Message;?></p>
            
<?php
        $POS = strrpos($Error_File, "\\");
        if ( $POS !== false ){
?>              <p>Error File = <?php echo substr($Error_File, $POS+1); ?></p>
<?php
        }
?>
    </body>
    </html>
<?php
    exit();
}

// Error Object Functions
function ObjectErrorReturn($ErrorLocation, $ErrorCode, $ErrorMessage){
    $GLOBALS['Error']['Error Location'] = $ErrorLocation;
    $GLOBALS['Error']['Error Code'] = $ErrorCode;
    $GLOBALS['Error']['Error Message'] = $ErrorMessage;
    return $GLOBALS['Error'];
}

define('ROOT', $_SERVER['DOCUMENT_ROOT'].'/Clothes.com');
define('HTTP_ROOT', 'http://localhost/Clothes.com');

// PHP Folders
	// Functions
	define('PHP', ROOT.'/PHP/');

		// Class
		define('Classes', PHP.'Classes/');

			define('MySqlDB', Classes.'MySqlDB.php');
			define('HashClass', Classes.'Hashing.php');
			define('Session', Classes.'Session.php');
			define('URL', Classes.'URL.php');
			define('FILTERS', Classes.'FILTERS.php');
			define('JSON', Classes.'JSON.php');
		
		// Pages Functions
			define('PagesPHP', PHP.'PagesPHP/');
			

// Layouts
	// Layouts
	define('Layout_HTTP', 'http://localhost/Clothes.com/Layout/');
	define('Layout_ROOT', ROOT.'/Layout/');

	//Profile Header
	define('ProfileHeader', Layout_ROOT.'ProfileHeader/');
		// Small Headers
		define('BasicHeaders', ProfileHeader.'BasicHeaders/');

		define('NavBar', BasicHeaders.'NavBar.php');
		define('MenuHeader', BasicHeaders.'MenuHeader.php');

		// Big Headers
		define('UserHeader', ProfileHeader.'UserHeader.php');

	// Work Header
	define('WorkHeader', Layout_ROOT.'WorkHeader/');

		define('UserWorkHeader', WorkHeader.'UserWorkHeader.php');
		define('HeaderList', WorkHeader.'HeaderList.php');
		
	// Footer
	define('Footer', Layout_ROOT.'Footers/Footer.php');

	// Message Boxs
	define('MessageBox', Layout_ROOT.'MessageBoxs/MessageBox.php');
	define('TrigerErrorMessage', Layout_ROOT.'MessageBoxs/TrigerErrorMessage.php');

	// Page Status
	define('ClosedPage', Layout_ROOT.'PageStatus/ClosedPage.php');
	define('UnAuthurithedUser', Layout_ROOT.'PageStatus/UnAuthurithedUser.php');
	define('LogOutPage', Layout_ROOT.'PageStatus/LogOutPage.php');
	define('CanNotAccessNow', Layout_ROOT.'PageStatus/CanNotAccessNow.php');
	define('ErrorPage', Layout_ROOT.'PageStatus/ErrorPage.php');

	// Simi Pages
	define('SimiPages', Layout_HTTP.'SimiPages/');

	// Pages
		define('PagesLayout', Layout_HTTP.'PagesLayout/');
			
			// Pages
			define('Login', PagesLayout.'Login.php');
			define('Change', PagesLayout.'Change.php');
			define('Users', PagesLayout.'Users.php');
			define('Profile', PagesLayout.'Profile.php');
			
			define('Clients', PagesLayout.'Clients.php');
			define('Receipt', PagesLayout.'Receipt.php');
			define('NewType', PagesLayout.'NewType.php');
			define('StoreMovement', PagesLayout.'StoreMovement.php');
			define('Bill', PagesLayout.'Bill.php');
			define('Purchases', PagesLayout.'Purchases.php');
			define('Main', PagesLayout.'Main.php');


// CSS Folder 
define('CSS', 'http://localhost/Clothes.com/CSS/');
	
	//Main CSS Folders
	define('HeaderCSS', CSS.'Header.CSS');
	define('CenterCSS', CSS.'Center.CSS');
	define('FooterCSS', CSS.'Footer.CSS');



// Picture Folder
define('Pictures', HTTP_ROOT.'/Pictures/');
	
	// Main Pictures
	define('LOGO', Pictures.'LOGO.PNG');
	define('ProfilePicture', Pictures.'ProfilePicture.JPG');
	define('EditPicture', Pictures.'EditPicture.PNG');
	define('DeletePicture', Pictures.'DeletePicture.PNG');
	define('AddPicture', Pictures.'AddPicture.PNG');

// JavaScript Folder
define('JavaScript', 'http://localhost/Clothes.com/JavaScript/');

	// important functions
	define('JQueryScript', JavaScript.'jquery-3.3.1.js');
	define('DropBoxScript', JavaScript.'DropBox.js');
	define('SetMessageBoxScript', JavaScript.'SetMessageBox.js');
	define('KeepSessionAliveScript', JavaScript.'KeepSessionAlive.js');

	// Global Functions
	define('Global_Scripts', JavaScript.'GlobalFunctions/');

		// Functions
		define('CheckLenScript', Global_Scripts.'CheckLen.js');
		define('CheckinputLenScript', Global_Scripts.'CheckinputLen.js');
		define('CheckinLenAndNumScript', Global_Scripts.'CheckinLenAndNum.js');
		define('ConfirmPasswordScript', Global_Scripts.'ConfirmPassword.js');
		define('TriggerFormScript', Global_Scripts.'TriggerForm.js');
		define('isNumberScript', Global_Scripts.'isNumber.js');
		define('SetConsoleErrorMessageScript', Global_Scripts.'SetConsoleErrorMessage.js');
	
	// Pages Scripts
	define('PagesScripts', JavaScript.'PagesScripts/');

// Length
	
	// Log
	define('Name_Len', 40);		// 100
	define('Password_Len', 40); // 100
	define('Phone_Len', 11); // 20

	// Users
	define('ID_Len', 11); //250
	define('Status_Len', 5); // 20

	// Clients
	define('Address_Len', 100); // 150

	// Receipt
	define('Money_Len', 13); // 20
	define('Note_Len', 500); // 600
?>