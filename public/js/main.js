$('.search-form').keyup(function(){

    var query = $('#main-form-input').val();
    if(query.length > 2 && $('#main-form').hasClass('search-form'))
    {
        // console.log(query);
        makeSearchTT(query);
    }

});

function strip(html)
{
   var tmp = document.createElement("DIV");
   tmp.innerHTML = html;
   return tmp.textContent || tmp.innerText || "";
}

function makeSearchTT(query){

         $('#main-form-input').typeahead({
              onselect: function (obj) {
                  console.log(strip(obj.text));

                  $('#main-form-input').val(strip(obj.text));


                  if(obj.type == 'titles')
                  {
                    window.location.href = obj.slug;
                  }else{
                    $('.search-form').submit();
                  }
                },

              source: function(typeahead, query) {
                return $.ajax({
                  url: "/search-titles-keywords?q=" + query,
                  success: (function(_this) {
                    return function(data) {

                        var suggestions = [];

                        for (index = data.length - 1; index >= 0; --index) {
                            if(data[index]._type == 'titles')
                            {
                                // console.log(data[index].highlight.title);
                                suggestions.push({text: data[index].highlight.title[0], type: data[index]._type, slug: data[index]._source.slug, domain: data[index]._source.domain});

                            }else if(data[index]._type == 'keywords'){
                                // console.log(data[index].highlight.text);
                                suggestions.push({text: data[index].highlight.text[0], type: data[index]._type});
                            }
                        }

                        // console.log(suggestions);

                      return typeahead.process(suggestions);
                    };
                  })(this)
                });
              },
              property: "text"
            });
}


$('#search-button').click(function(){
    // $('#main-form .input-group').css('width', '30%');
    makeActionInput('GET', '/search', 'q', 'Start typing to search', 'glyphicon glyphicon-search');
    $('#main-form').addClass('search-form');
    $('#search-button').addClass('active');
    $('#add-button').removeClass('active');
    doCsrfToken('remove');
});

$('#add-button').click(function(){
    // $('#main-form .input-group').css('width', '50%');
    makeActionInput('POST', '/', 'url', 'Paste an url to filtr', 'glyphicon glyphicon-refresh');
    $('#main-form').removeClass('search-form');
    $('#add-button').addClass('active');
    $('#search-button').removeClass('active');
    doCsrfToken('add');
});

function makeActionInput(method, action, inputName, placeholder, iconClass){

    // $('#main-form .input-group').css('width', '100%');
    $('#main-form').attr('action', action);
    $('#main-form').attr('method', method);
    $('#main-form-input').attr('name', inputName);
    $('#main-form-input').focus();
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

    $('<input>').attr('type','hidden').attr('name','_token').val(token).appendTo('#main-form');
}

function removeCsrfToken()
{
    $("[name='_token']").remove();
}