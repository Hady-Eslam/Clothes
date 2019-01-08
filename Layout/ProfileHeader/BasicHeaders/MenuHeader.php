<div class='Profile'>

	<div class='Profile_Item'>
		<p style="color: #FFFFFF;"><?php echo $_SESSION['Name']; ?></p>
	</div>

	<div class='Profile_Item' onmouseenter="In(this);" onmouseleave="Out(this);">
		<a href="<?php echo Main; ?>">Main Page</a>
	</div>
	
	<div class='Profile_Item' onmouseenter="In(this);" onmouseleave="Out(this);">
		<a href="<?php echo Profile; ?>">Profile</a>
	</div>
<?php
	if ( $_SESSION['Status'] == '0' ){
?>
	<div class='Profile_Item' onmouseenter="In(this);" onmouseleave="Out(this);">
		<a href="<?php echo Users; ?>">Users</a>
	</div>
<?php
	}
?>
	
	<div class='Profile_Item' onmouseenter="In(this);" onmouseleave="Out(this);">
		<a href="<?php echo Change; ?>">Change Password</a>
	</div>
	
	<div class='Profile_Item' onmouseenter="In(this);" onmouseleave="Out(this);">
		<a href="<?php echo Login; ?>">Log New User</a>
	</div>
	
	<div class='Profile_Item' onmouseenter="In(this);" onmouseleave="Out(this);">
		<a href="<?php echo SimiPages; ?>LogOutUser.php">Log Out</a>
	</div>

</div>