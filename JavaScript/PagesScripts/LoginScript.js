$(document).ready(function(){

	$('#LogForm').submit(function(event){
		if ( CheckData() == false )
			event.preventDefault();
	})
})

function CheckData(){
	Result = true;
    if ( CheckLength('#Name', Name_Len ) == false )
        Result = false;

    if ( CheckLength('#Password', Password_Len ) == false )
        Result = false;
    return Result;
}