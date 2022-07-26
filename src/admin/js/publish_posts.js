console.log('publish_posts')
(function($){
    $(document).ready(function(){
        console.log("READY!!")
        $('#wp_subsk_content_select_post_type').change(function(ev){
            const post_type = ev.target.value;
            console.log(post_type)
            $.ajax({
                type: 'post',
                url: wp_subsk_ajax.url,
                data: {
                    action: 'get_publish_posts',
                    post_type: post_type
                },
                beforeSend: function(){
                    console.log('Sending....')
                },
                success:function(response){
                    console.log(resonse)
                }
            })
        })
    })
    
})(jQuery);