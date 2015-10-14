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

            var boot_article_id = $('.single-article').first();


            console.log($('.single-article').first().attr('id'));

             $(document).on('scroll',  function () {
             // $(document).on('scroll', boot_article_id,  function () {

               
                var articles = $('.single-article:in-viewport');
                

                if(articles instanceof Array)
                {
                    var currentArticle = $(articles[articles.length - 1]);
                }else{
                    var currentArticle = $(articles[0]);
                }

                var articleId = $(currentArticle).attr('id');
                var articleTitle = $(currentArticle).data('title');


                document.title = articleTitle;
                // document.title = 'Filtr - ' + articleTitle;
                
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



                    var s = realScrollTop(currentArticle);
                    var d = $(document).find(currentArticle).height();
                    var c = $(window).height();
                    scrollPercent = (s / (d-c)) * 100;
                    var position = scrollPercent;

                    // console.log('s: ' + s);
                    // console.log('d: ' + d);
               // $("#progress-read").html(position);
               
               // snack(position.toString());

               $('#progress-container').attr('aria-valuenow', position.toString());
              $('#progress-container').css('width', position.toString() + '%');

            });
  

            function realScrollTop(current_element){

              var total_height = 0;
              var current_element_height = $(current_element).height();

              $(current_element).prevAll('.single-article').each(function(){

                  total_height += $(this).height();

              });

              return $(window).scrollTop() - total_height;

            }
    


            $(window).scroll(function() {
                if($(window).scrollTop() == $(document).height() - $(window).height()) {
                // if($(window).scrollTop() > $(document).height() - $(window).height() - 200) {
                       loadNextArticle();
                       // $(document.body).trigger("sticky_kit:recalc");
                }
            });

    </script>

