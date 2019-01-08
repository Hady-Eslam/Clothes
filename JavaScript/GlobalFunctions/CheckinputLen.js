/*
	- info :
		javascript page    	=>  CheckinputLen.js
        init name   		=>  CheckinputLenScript

	-The Scripts it Depends On (ROOT Name) :
		JQueryScript
		CheckLenScript
*/

// Functions For input 
function CheckinputLen(The_Object, Len){
	if ( The_Object.value.length == 0 ){
		$('#'+The_Object.id).css('border-color', '#645A60');
		return ;
	}

    if ( CheckLength('#'+The_Object.id, Len) == true ){
        $('#'+The_Object.id).css('border-color', 'green');
        return ;
    }
    $('#'+The_Object.id).css('border-color', 'red');
}