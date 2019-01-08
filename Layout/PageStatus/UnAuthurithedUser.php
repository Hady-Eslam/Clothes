<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/Clothes.com/PHP/ROOT.php';
?>
<!DOCTYPE>
<html>
<head>
	<title>Un Authurithed User</title>
	<link rel="stylesheet" type="text/css" href="<?php echo HeaderCSS; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo CenterCSS; ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo FooterCSS; ?>">
	<link rel="icon" type="image/JPG" href="<?php echo LOGO; ?>">

	<script src="<?php echo JQueryScript; ?>"></script>
    <script src="<?php echo DropBoxScript; ?>"></script>
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

            <?php   include_once MessageBox; ?>

            <div style="text-align: center;margin-top: 200px;color: red">
				<P>You  Can't Enter This Page</P>
            </div>

        </div>

    </section>
    
    <?php  include_once Footer; ?>

    <script type="text/javascript">
    	var LoginPage = '<?php echo Login; ?>';
<?php
    if ( !isset($_SESSION['Name']) ){
    ?>
        function GOToLogin(){
            location.href = LoginPage;
        }
    <?php
    }
    
    if ( isset($_SESSION['Name']) )
        include_once HeaderList;
?>
    </script>
</body>
</html>