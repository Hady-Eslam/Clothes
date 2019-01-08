$(document).ready(function(){

	$('#ChangeForm').submit(function(event){
		if ( CheckData() == false )
			event.preventDefault();
	})
})

function CheckData(){
	Result = true;
    if ( CheckLength('#OldPassword', Password_Len ) == false )
        Result = false;

    if ( CheckLength('#Password', Password_Len ) == false )
        Result = false;

    if ( CheckLength('#ConPassword', Password_Len ) == false )
        Result = false;

    if ( CheckPassword() == false )
    	Result = false;
    return Result;
}