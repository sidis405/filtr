<script>
    var articles = ['{{ $link->slug }}']
</script>

<script src="{{ asset('/js/socket.io.js') }}"></script>

    <script>
    var socket = io('http://127.0.0.1:6001');
    socket.on("link_{{$link->id}}:App\\Events\\Links\\LinkWasProcessed", function(message){
         
         if(message.data.command == 'reload')
         {
            // alert('post processing is done. time to refresh the page');
            $('#page-refresher').show();
         }
         
     });

    socket.on("ping", function(message){
         console.log(message);
     });
    </script>

    <script>
            $('#progress-container').attr('aria-valuenow', 0);
            $('#progress-container').css('width', '0%');

             $(document).on('scroll',  function () {
                
                var articles = $('.single-article:in-viewport');

                if(articles instanceof Array)
                {
                    var currentArticle = $(articles[articles.length - 1]);
                }else{
                    var currentArticle = $(articles[0]);
                }

                var articleId = $(currentArticle).attr('id');

                // console.log(articleId);


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
                }
            });

    </script>