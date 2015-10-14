$(document).ready(function(){
  $("#page-refresher").click(function(){
 location.reload();
  });
});

$(document).ready(function() {
    cleanup();
});

function cleanup(){
  $("img").error(function(){
        $(this).remove();
    });

    $(".slideshow").remove();
}


// $(document).ready(function(){

//   var previousScroll = 0,
//     headerOrgOffset = $('#header').height();

//   $('#header-wrap').height($('#header').height());

//   $(window).scroll(function () {
//       var currentScroll = $(this).scrollTop();
//       if (currentScroll > headerOrgOffset) {
//           if (currentScroll > previousScroll) {
//               $('#header-wrap').slideUp('');
//           } else {
//               $('#header-wrap').slideDown();
//           }
//       } 
//       previousScroll = currentScroll;
//   });


// });



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

function loadNextArticle(){

  var item = $(document).find('.load-next:last');

  if ( $(item).data('load') == true)
  {
    var slug = $(document).find('.load-next:last').data("next");

        // if(articles.indexOf(slug) < 0){

          // articles.push(slug);
          
          

          $.ajax({
                url: '/load/' + slug,
                type: 'GET',
                success: function(data) {
    
                    $('#article-container').append(data);
                    cleanup();
                    tooltips();
                    return false;
                },
                error: function(XMLHttpRequest, textstatus, error) {
    
                    return false;
    
                }
            });
    
          //   return false;
          // }

        }
        return false

}




function removeCsrfToken()
{
    $("[name='_token']").remove();
}

// CMD/CTRL + SHIFT + F = search
$(document).keydown(function(event) {
        if((event.ctrlKey || event.metaKey) && event.shiftKey && event.which == 70) {
            $('#search-button').trigger("click");
            event.preventDefault();
            return false;
        };
    }
);

// CMD/CTRL + SHIFT + S = search
$(document).keydown(function(event) {
        if((event.ctrlKey || event.metaKey) && event.shiftKey && event.which == 83) {
            $('#search-button').trigger("click");
            event.preventDefault();
            return false;
        };
    }
);

// CMD/CTRL + SHIFT + A = add
$(document).keydown(function(event) {
        if((event.ctrlKey || event.metaKey) && event.shiftKey && event.which == 65) {
            $('#add-button').trigger("click");
            event.preventDefault();
            return false;
        };
    }
);

$(document).on('click', '.entity-follow', function(e){

  // console.log('got it');

  // e.preventDefault();

  // return false;

  var entity_id = $(this).data('id');
  var entity_slug = $(this).data('slug');
  var entity_text = $(this).data('text');

  // console.log($(document).find('#entity_' + entity_slug));

  var token = $('meta[name="csrf_token"]').attr('content');
  var user_id = $('meta[name="user"]').attr('content').replace(' ', '');

  if($(this).hasClass('entity-followtrue')){
    $(this).removeClass('entity-followtrue');
    $(this).addClass('entity-followfalse');
    $(this).text('Follow');

    var method = 'DELETE';

    snack("You have unfollowed '" + entity_text + "'");

  }else{
    $(this).addClass('entity-followtrue');
    $(this).removeClass('entity-followfalse');
    $(this).text('Unfollow');

    var method = 'POST';

    snack("You are now following '" + entity_text + "'");

  }

  var mirrored_item = $(document).find('#entity_' + entity_slug).find('.entity-follow');

  if($(mirrored_item).hasClass('entity-followtrue')){
    $(mirrored_item).removeClass('entity-followtrue');
    $(mirrored_item).addClass('entity-followfalse');
    $(mirrored_item).text('Follow');
  }else{
    $(mirrored_item).addClass('entity-followtrue');
    $(mirrored_item).removeClass('entity-followfalse');
    $(mirrored_item).text('Unfollow');
  }

$(document).find('#entity_the-npd-group').find('.entity-follow');

// $(document).find('.entities').tooltipster('destroy');
  tooltips();


  $(document).find('.entities').tooltipster('destroy');

   $.ajax({
                url: '/user/' + user_id + '/entities',
                type: 'POST',
                data: {_token:token, entity_id:entity_id, _method: method},
                success: function(data) {
                          
                },
                error: function(XMLHttpRequest, textstatus, error) {
    
                    return false;
    
                }
            });

});


function snack(text)
{ console.log('called');
  var options =  {
      content: text
  }

  return $.snackbar(options);
}
