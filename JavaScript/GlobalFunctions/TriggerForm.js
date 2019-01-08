/*
    - info :
        javascript page     =>  TriggerForm.js
        init name           =>  TriggerFormScript

    -The Scripts it Depends On (init Name) :
        JQueryScript
*/

function GO(id){
    $("<form id='TheSubmitForm' method='post'"
            +"enctype='multipart/form-data'></form>").appendTo('body');
    $(id).appendTo('#TheSubmitForm');
    $('#TheSubmitForm').trigger('submit');
}