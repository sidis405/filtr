// $(function() {
   
// });

$('#search-button').click(function(){
    // $('#main-form .input-group').css('width', '30%');
    makeActionInput('GET', '/search', 'q', 'Start typing to search', 'glyphicon glyphicon-search');
    $('#main-form').addClass('search-form');
    doCsrfToken('remove');
});

$('#add-button').click(function(){
    // $('#main-form .input-group').css('width', '50%');
    makeActionInput('POST', '/', 'url', 'Paste an url to filtr', 'glyphicon glyphicon-refresh');
    $('#main-form').removeClass('search-form');
    doCsrfToken('add');
});

function makeActionInput(method, action, inputName, placeholder, iconClass){

    // $('#main-form .input-group').css('width', '100%');
    $('#main-form').attr('action', action);
    $('#main-form').attr('method', method);
    $('#main-form-input').attr('name', inputName);
    $('#main-form .input-group').css('width', '100%');
    $('#main-form-input').attr('placeholder', placeholder);
    $('#main-form-button i').attr('class', iconClass);

}

function doCsrfToken(action)
{
    if(action == 'remove')
    {
        removeCsrfToken();
    }else{
        addCsrfToken();
    }
}

function addCsrfToken()
{
    var token = $('meta[name="csrf_token"]').attr('content');

    console.log(token);

    $('<input>').attr('type','hidden').attr('name','_token').val(token).appendTo('#main-form');
}

function removeCsrfToken()
{
    $("[name='_token']").remove();
}