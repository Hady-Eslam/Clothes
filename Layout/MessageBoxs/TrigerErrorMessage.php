SetMessage(5000, '#E30300' ,
    '<p>Error Meaasege Code : <?php echo $Result[1]['Error Code'];?>
    </p><p>Something Goes Wrong</p>');

SetConsoleErrorMessage("<?php echo $Result[2];?>", "<?php echo $Result[3];?>", 
	"<?php echo $Result[1]['Error Location'];?>", "<?php echo $Result[1]['Error Code']?>",
	"<?php echo $Result[1]['Error Message'];?>");