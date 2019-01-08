<header>
		
<?php
		include_once NavBar;
		if ( isset($_SESSION['Name']) ){
?>
	<div style="display: inline-block;margin: 5;padding: 5;">
		<input onmouseenter="Show();" type="image" 
		src="<?php echo ProfilePicture; ?>"width="30" height="30">
<?php
			include_once MenuHeader;
?>
	</div>
<?php
		}
		else{
		?>
	<a style="width: 150px;display: inline-block;text-align: center;padding: 8;margin: 0;"
		href="<?php echo Login; ?>" class="Link">Log in</a>
		<?php
		}
?>
	
</header>