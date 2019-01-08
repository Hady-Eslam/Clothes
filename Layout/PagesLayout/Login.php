<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/Clothes.com/PHP/ROOT.php';
include_once Session;

$GLOBALS['Page_API_Error_Code'] = 'P1';
set_error_handler("Error_Handeler");


/* 
                            Check if User Is Logging in
*/
$_SESSION['Page Name'] = 'Log in';
$Result = array(-2 ,'');

if ( $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['N'])
    && isset($_POST['P']) && isset($_SERVER["HTTP_REFERER"]) ){

    include_once URL;
    if ( $URL_REFERER->GetURLPath() == -1 ){
        include_once ErrorPage;
        exit();
    }

    if ( $URL_REFERER->URLResult != Login ){
        include_once UnAuthurithedUser;
        exit();
    }

/*
    - Return : 
        return array(0, 'Empty');
        return array(0, 'Too Long');
        return array(-1, $MySql->Error, 'Check The Name And Password', 'Check Data');
        return array(0, 'Wrong Password');
        return array(0, 'Not Found');

        return array(-1, $Hashing->Error, 'Getting Hashed Name From DataBase',
                                'Open Session');
        return array(-1, $Hashing->Error, 'Getting Hashed phone From DataBase',
                                'Open Session');
        return array(0, 'Done');
*/
    include_once PagesPHP.'LoginProcessData.php';
    $Result = CheckData();
    if ( $Result[0] == 0 ){

        if ( $Result[1] == 'Empty' || $Result[1] == 'Too Long' ){
            header("Location:".Login);
            exit();
        }
        else if ( $Result[1] == 'Done' ){
            header("Location:".Main);
            exit();
        }
    }
}
?>
<!DOCTYPE >
<html>
<head>
	<title>Log in</title>
    <link rel="stylesheet" type="text/css" href="<?php echo HeaderCSS; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo CenterCSS; ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo FooterCSS; ?>">
    <link rel="icon" type="image/JPG" href="<?php echo LOGO; ?>">

    <script src="<?php echo JQueryScript; ?>"></script>
    <script src="<?php echo DropBoxScript; ?>"></script>
    <script src="<?php echo SetMessageBoxScript; ?>"></script>
    <script src="<?php echo KeepSessionAliveScript; ?>"></script>

    <script src="<?php echo CheckLenScript; ?>"></script>
    <script src="<?php echo CheckinputLenScript; ?>"></script>

    <script src="<?php echo PagesScripts; ?>LoginScript.js"></script>
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

            <div class ='Title'>
                Log in
            </div>

            <?php   include_once MessageBox; ?>

            <form id='LogForm' method="post" enctype="multipart/form-data"
                action="<?php echo $_SERVER['PHP_SELF']; ?>">

                <div>
                    <input class='Input_Data' type="text" id='Name' name="N" 
                        placeholder="Enter Your Name" 
                        oninput="CheckinputLen(this, Name_Len);"
                        <?php
                            if ( $Result[0] != -2 )
                                echo "value='".$GLOBALS['N']."'";
                        ?>>
                </div>
                
                <div>
                    <input class='Input_Data' id='Password' type="Password" name="P"
                        placeholder="Enter Password" 
                        oninput="CheckinputLen(this, Password_Len);">
                </div>

                <div>
                    <input type="submit" value="Log in" class='Button' style="width: 100px;">
                </div>

            </form>
        </div>

    </section>
    
    <?php  include_once Footer; ?>

    <script type="text/javascript">
<?php
    if ( $Result[0] == 0 ){
        if ( $Result[1] == 'Wrong Password' ){
        ?>
            $('#Password').css('border-color', 'red');
        <?php
        }
        else{
        ?>
            $('#Name').css('border-color', 'red');
        <?php
        }
    }
    else if ( $Result[0] == -1 )
        include_once TrigerErrorMessage;
?>
    </script>

    <script type="text/javascript">
        var Name_Len = <?php echo Name_Len; ?>;
        var Password_Len = <?php echo Password_Len; ?>;

        var MyPage = '<?php echo Login; ?>';
        var KeepSessionAlivePage = '<?php echo SimiPages; ?>KeepSessionAlive.php';
    </script>
</body>
</html>