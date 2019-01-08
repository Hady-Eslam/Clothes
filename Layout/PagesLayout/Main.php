<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/Clothes.com/PHP/ROOT.php';
include_once Session;

$GLOBALS['Page_API_Error_Code'] = 'P11';
set_error_handler("Error_Handeler");


/* 
                            Check if User Is Logging in
*/
$_SESSION['Page Name'] = 'Main';
?>
<!DOCTYPE>
<html>
<head>
	<title>Main Page</title>
	<link rel="stylesheet" type="text/css" href="<?php echo HeaderCSS; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo CenterCSS; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo FooterCSS; ?>">
	<link rel="icon" type="image/JPG" href="<?php echo LOGO; ?>">

	<script src="<?php echo JQueryScript; ?>"></script>
    <script src="<?php echo DropBoxScript; ?>"></script>
    <script src="<?php echo KeepSessionAliveScript; ?>"></script>
</head>

<body>
	
	<?php include_once UserHeader; ?>

    <section>
    
    <?php 
    	if ( isset($_SESSION['Name']) )
    		include_once UserWorkHeader; 
    	?>

        <div class = 'SectionDiv' style="
        <?php
            if ( !isset($_SESSION['Name']) ){
            ?>
            margin-left: 200px;
            margin-right: 200px;
            height: 500px;
            <?php
            }
        ?>">

            <div>
            	<p style="margin-top: 200px;color: red;">Hello To Main Page</p>
            </div>

        </div>

    </section>
    
    <?php  include_once Footer; ?>

    <script type="text/javascript">
<?php
    if ( !isset($_SESSION['Name']) ){
    ?>
    	var LoginPage = '<?php echo Login; ?>';
        function GOToLogin(){
            location.href = LoginPage;
        }
    <?php
    }
?>
    var KeepSessionAlivePage = '<?php echo SimiPages; ?>KeepSessionAlive.php';
    </script>
</body>
</html>