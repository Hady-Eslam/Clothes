/*
	- info :
		javascript page    	=>  CheckLen.js
        init name   		=>  CheckLenScript

	-The Scripts it Depends On (ROOT Name) :
		JQueryScript
*/

function CheckLength(id, len){
    if ( $(id).val().length <= len && $(id).val().length != 0 ){
    	$(id).css('border-color','#645A60');
        return true;
    }
    $(id).css('border-color','red');
    return false;
}