/*
    - info :
        javascript page     =>  SetConsoleErrorMessage.js
        init name           =>  SetConsoleErrorMessageScript
*/

function SetConsoleErrorMessage(Proccess, Location_info, Location, Code, Message){
	console.log(
        "Proccess : "+Proccess+" \n"+
        'Error Location info : '+Location_info+" \n\n"+
        'Error Location = '+Location+" \n"+
        'Error Code = '+Code+" \n"+
        'Error Message = '+Message+" \n"
    );
}