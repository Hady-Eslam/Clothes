/*
    - info :
        javascript page     =>  CheckNumberOrNot.js
        init name           =>  CheckNumberOrNotScript

    -The Scripts it Depends On (init Name) :
        JQueryScript
*/

function isNumber(id){
	if ( isNaN( $(id).val() ) == false ){
		if ( $(id).css('border-color') != 'rgb(255, 0, 0)' )
			$(id).css('border-color','#645A60');
		return true;
	}
	$(id).css('border-color', 'red');
	return false;
}