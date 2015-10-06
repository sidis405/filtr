<script>
    //  $(document).ready(function(){

    // $(".next-preview").stick_in_parent({recalc_every: 1});

    // });

</script>

<script>
    if ({{ $link->status }} == 0)
    {
        $('#page-processing').show();
    }
</script>

<script src="{{ asset('/js/socket.io.js') }}"></script>

    <script>
    var socket = io(window.location.origin + ':6001');
    socket.on("link_{{$link->id}}:App\\Events\\Links\\LinkWasProcessed", function(message){
         
         if(message.data.command == 'reload')
         {
            $('#page-processing').hide();
            $('#page-refresher').show();
         }
         
     });

    socket.on("ping", function(message){
         console.log(message.payload);
     });
    </script>

    <script>
            $('#progress-container').attr('aria-valuenow', 0);
            $('#progress-container').css('width', '0%');

            var stream = [];

            var current_history_id = '';

             $(document).on('scroll',  function () {
                
                var articles = $('.single-article:in-viewport');
                

                if(articles instanceof Array)
                {
                    var currentArticle = $(articles[articles.length - 1]);
                }else{
                    var currentArticle = $(articles[0]);
                }

                var articleId = $(currentArticle).attr('id');
                var articleTitle = $(currentArticle).data('title');

                document.title = 'Filtr - ' + articleTitle;
                
                if(current_history_id !== articleId)
                {
                  window.history.pushState("string", articleId, articleId);

                    // if(stream.indexOf(articleId) < 0)
                    // {
                      current_history_id = articleId;
                      // stream.push(articleId); 
                    // }
                    
                }


                // $('.preview_container').removeClass('next-preview');
                // $('#next_preview_' + articleId).addClass('next-preview');


              var s = $(window).scrollTop(),
                    d = $(document).height(),
                    c = $(window).height();
                    scrollPercent = (s / (d-c)) * 100;
                    var position = scrollPercent;

               // $("#progress-read").html(position);
               $('#progress-container').attr('aria-valuenow', position.toString());
              $('#progress-container').css('width', position.toString() + '%');

            });


            $(window).scroll(function() {
                if($(window).scrollTop() == $(document).height() - $(window).height()) {
                // if($(window).scrollTop() > $(document).height() - $(window).height() - 200) {
                       loadNextArticle();
                       // $(document.body).trigger("sticky_kit:recalc");
                }
            });

    </script>
