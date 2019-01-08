/*
	- info :
		javascript page    	=>  DropBox.js
        init name   		=>  DropBoxScript

	-The Scripts it Depends On (init Name) :
		JQueryScript

	- Data Needed :
        class  => .Profile
*/

$(document).ready(function(){
	$('body').attr('id', 'Body');
	$("#Body").click(function(){ 
		Hide();
	});
})

function Show() {
    $('.Profile').css('visibility','visible');
}

function Hide(){
	$('.Profile').css('visibility','hidden');
}

function In(The_Object){
    The_Object.style.backgroundColor = '#0070FF';
}
function Out(The_Object){
    The_Object.style.backgroundColor = 'black';
}