<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/Clothes.com/PHP/ROOT.php';
include_once Session;

$GLOBALS['Page_API_Error_Code'] = 'P4';
set_error_handler("Error_Handeler");


/* 
                            Check if User Is Logging in
*/
$_SESSION['Page Name'] = 'Profile';

if ( !isset($_SESSION['Name']) ){
    include_once UnAuthurithedUser;
    exit();
}
?>
<!DOCTYPE>
<html>
<head>
	<title>My Profile</title>
    <link rel="stylesheet" type="text/css" href="<?php echo HeaderCSS; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo CenterCSS; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo FooterCSS; ?>">
    <link rel="icon" type="image/JPG" href="<?php echo LOGO; ?>">

    <script src="<?php echo JQueryScript; ?>"></script>
    <script src="<?php echo DropBoxScript; ?>"></script>
    <script src="<?php echo SetMessageBoxScript; ?>"></script>
    <script src="<?php echo KeepSessionAliveScript; ?>"></script>

    <style type="text/css">
    	strong{
    		color: red;
    	}
    </style>
</head>
<body>

	<?php include_once UserHeader; ?>

    <section>
    
    <?php include_once UserWorkHeader; ?>

        <div class = 'SectionDiv'>

            <div class ='Title'>
                My Profile
            </div>

            <div class = 'SectionDiv' style="text-align: left;width: auto;">
            	<p><strong>Name</strong> : <?php echo $_SESSION['Name']; ?></p>
            	<p><strong>Status</strong> : 
            			<?php echo ($_SESSION['Status'] == 0 )? 'Admin': 'User'; ?></p>
            	<p><strong>Phone</strong> : <?php echo $_SESSION['Phone']; ?></p>
            	<p><strong>Sign UP Date</strong> : <?php echo $_SESSION['SignUPDate']; ?></p>
            </div>

        </div>

    </section>
    
    <?php  include_once Footer; ?>
    <script type="text/javascript">
        var KeepSessionAlivePage = '<?php echo SimiPages; ?>KeepSessionAlive.php';
    </script>

</body>
</html>