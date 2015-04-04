
function jsTweet() {

    var urlTW = "https://twitter.com/intent/tweet?text=Text&url=http://my_url.com";
    window.open(urlTW, "", "toolbar=0, status=0, width=650, height=360");

}


jQuery(document).ready(function($){

    //from the href="#"
    jQuery('.ilenvideolock .boxxshare').on('click',function(event){
        //do something
        //prevent the click as is passed to the function as an event
        event.preventDefault();
        
        return false;
    });

    $(".ilenvideolock").on('click',function(){

        $(this).find('.ilenboxshare').css("display","block");
        $(this).find('.ilenboxshare').css("cursor","default");

    });


    // ilenvideolock twitter
    if (typeof twttr !== 'undefined') {
        twttr.ready(function(twttr) {
            twttr.events.bind('tweet', function(event) {
                console.log(new Date(), "Sweett, tweet callback: ", event);
                var id_twitter_video = $("#"+event.target.parentElement.className);
                var twitter_id_post = $(id_twitter_video).attr("data-id-post");
                var twitter_url = $(id_twitter_video).attr("data-url");
                var twitter_hash = $(id_twitter_video).attr("data-hash");

                //alert('tweet!!! id post:'+ twitter_id_post+' url: '+twitter_url+' hash: '+twitter_hash);
                ajax_shared_complete( twitter_id_post, twitter_url, twitter_hash, 'twitter' );
            });
        });
    }


    // hover google plus trigger events
    $('.google-plus-ilenvideolock').on('hover',function(){
        $(this).children("#igp-post-id").val( $(this).attr("data-id-post") );
        $(this).children("#igp-post-url").val( $(this).attr("data-url") );
        $(this).children("#igp-post-hash").val( $(this).attr("data-hash") );
    });

    // hover linkedin plus trigger events
    $('.linkedin-plus-ilenvideolock').on('hover',function(){
        $(this).children("#ile-post-id").val( $(this).attr("data-id-post") );
        $(this).children("#ile-post-url").val( $(this).attr("data-url") );
        $(this).children("#ile-post-hash").val( $(this).attr("data-hash") );
    });

 
});



function ilenvideolock_fb( post_id, url_path, id_hash, app_id ){
    var $ = jQuery;
 

    console.log( url_path );

    var string_app_id = "";
    if( app_id ){
        string_app_id = "&app_id="+app_id;
    }
    FB.ui({
        //method: isMobile() ? 'feed' : '../sharer/sharer.php?u=' +encodeURIComponent(document.URL)+ '&t=&pass=',
        //method: isMobile() ? 'share' : '../sharer/sharer.php?u=' +encodeURIComponent(document.URL)+ '&t=&pass=app_id=261667905712',
        method: isMobile() ? 'share' : '../../sharer/sharer.php?u=' +encodeURIComponent(document.URL)+ '&t=&pass='+app_id,
        link : document.URL,
        image : $('.ilenvideolock_img_'+id_hash).attr('src')
    }, function (response) {
        if( response ){
            ajax_shared_complete( post_id, url_path, id_hash, 'facebook' );
        }else{
            null; // upts!
        }
    });

    
}

function ilenvideolock_go(params) {
    //console.log(new Date(), " google go: ", params.state);
    var $ = jQuery;
    if( "on" == params.state )
    {
        //alert( $("#igp-post-id").val() + " " + $("#igp-post-url").val() + " " + $("#igp-post-hash").val()  );
        ajax_shared_complete( $("#igp-post-id").val(),  $("#igp-post-url").val() , $("#igp-post-hash").val() , 'googleplus');
    }
}

function ilenvideolock_go_close(params) {
    //console.log(new Date(), " google go close: ", params,+ " tt:"+tt);
    var $ = jQuery;
    if( "confirm" == params.type )
    {
        //alert( $("#igp-post-id").val() + " " + $("#igp-post-url").val() + " " + $("#igp-post-hash").val()  );
        ajax_shared_complete( $("#igp-post-id").val(),  $("#igp-post-url").val() , $("#igp-post-hash").val(), 'googleplus' );
    }
}


function ilenvideolock_linkedin( post_id, url_path, id_hash ) {

    var $ = jQuery;
    ajax_shared_complete( $("#igp-post-id").val(),  $("#igp-post-url").val() , $("#igp-post-hash").val(), 'linkedin' );

}

function ajax_shared_complete( post_id, url_path, id_hash, social ){

    var $ = jQuery;

  
    type_video = '<iframe width="'+$('.ilenvideolock_id_'+id_hash).attr('data-width')+'" height="'+$('.ilenvideolock_id_'+id_hash).attr('data-height')+'"  frameborder="0" allowfullscreen src="//www.youtube.com/embed/'+$('.ilenvideolock_id_'+id_hash).attr('data-id')+'?autoplay=1"></iframe>';

    $('.ilenvideolock_id_'+id_hash).css("background-image","none").html('').append( type_video );   
    type_video_db = "youtube";
    
    jQuery.ajax({
        type : "post",
        dataType: "json",
        url : AjaxVideo.ajaxurl,
        data : {
                'action': "ajax-video", 
                'post_id' : post_id, 
                'social':social,
                'video_id':$('.ilenvideolock_id_'+id_hash).attr('data-id'), 
                'type_video': type_video_db,
                'nonce': AjaxVideo.nonce
               },
        success: function(response) {
            console.log("success ok - nonce "+AjaxVideo.nonce+" time: "+response.times+" ");
        },
        error: function (jqXHR, textStatus, errorThrown, responseText){
            console.log("error: "+ responseText + " errorThrown:"+errorThrown+ " jqXHR:"+jqXHR+ " url:"+AjaxVideo.ajaxurl);
        }
    });

}