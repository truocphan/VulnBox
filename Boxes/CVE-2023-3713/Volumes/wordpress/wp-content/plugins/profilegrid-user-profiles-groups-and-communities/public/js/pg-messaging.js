/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
(function( $ ) {
	'use strict';
       
function threadWindowOpen (){
    var openThread = document.querySelector(".pg-thread-open a");
openThread.addEventListener("click", (e) => {
  var sidebar = document.querySelector(".pg-message-box-sidebar");
  sidebar.classList.toggle("opened");
});
    
}

threadWindowOpen();

    $(".pg-message-action").click(function () {
        $(this).toggleClass("pg-action-active");

    });


//$(".open.secondthread a").click(function(){
//  alert('Hey you clicked')
//});


if($('#pg-messages').length)
    {
        // console.log("working");
        //refresh_messenger();
        setTimeout(function(){pm_get_messenger_notification('','nottyping');}, 1000);
        $("#typing_on .pm-typing-inner").hide();
    }
    
    var pmDomColor = jQuery(".pmagic").find("a").css('color');
    jQuery( ".pg-msg-list-wrap" ).css('border-color', pmDomColor); 
    jQuery( ".pg-msg-list-wrap" ).css('background', pmDomColor); 
    jQuery( ".pg-no-thread" ).parent( ".pg-msg-list-wrap" ).removeAttr('style');
    jQuery( "#chat_message_form svg" ).css('fill', pmDomColor); 
    jQuery( "#send_msg_btn svg" ).css('fill', pmDomColor); 
    jQuery(".pmagic #unread_thread_count").css('background-color', pmDomColor);
    jQuery(".pg-msg-conversation-list .pg-unread-count").css('background-color', pmDomColor);
    jQuery(".pg-message-box-sidebar .pg-message-box-action .pg-new-thread svg").css('fill', pmDomColor);
    jQuery(".pg-msg-thread-container .pg-msg-thread-header .pg-msg-thread-wrap svg").css('fill', pmDomColor);
    jQuery(".pg-msg-thread-container .pg-msg-thread-header .pg-msg-thread-wrap span").css('color', pmDomColor);
    
 
//--- General action  -----   
    
    
  $(".pg-new-thread-action svg, .pg-thread-action-controller-overlay").click(function(){
  $(".pg-thread-action-controller, .pg-thread-action-controller-overlay").toggle();
});




    
})(jQuery);
var notification_request = null;  
function pm_get_messenger_notification(timestamp, activity)
{
    if (activity === undefined)activity = '';
    var tid = jQuery('#thread_hidden_field').val();
    var data = {'action': 'pm_get_messenger_notification',
        'timestamp': timestamp,
        'activity': activity,
        'tid': tid
    };
    if(notification_request !== null){
        notification_request.abort();

    }

    notification_request = jQuery.get(pg_msg_object.ajax_url, data, function (response)
    {
        if (response)
        {
           
            var obj = jQuery.parseJSON(response);           
            if(jQuery.isEmptyObject(obj))
            {
                setTimeout(function(){pm_get_messenger_notification('')},4000);  
            }
            else
            {
                if (obj.activity == 'typing') {
                    jQuery("#typing_on .pm-typing-inner").show();
                    if(jQuery('.pg-users-search-list-wrap div.pg-message-list').length)
                    {
                      jQuery(".pg-users-search-list-wrap").scrollTop( jQuery(".pg-users-search-list-wrap div.pg-message-list:last").offset().top);
                    }
                }
                if (obj.activity == 'nottyping') {
                    jQuery("#typing_on .pm-typing-inner").hide();
                }
                if (obj.data_changed === true)
                {
                    pg_show_all_threads(tid);
                    show_thread_messages(tid,1);
                    pm_messenger_notification_extra_data('');

                }
                setTimeout(function () {
                    pm_get_messenger_notification(obj.timestamp)
                }, 4000);


            }
            // call the function again, this time with the timestamp we just got from server.php

        }else{
       //console.log("error in notif");    
       }
    
    });


}


function pm_messenger_notification_extra_data(x){
  //console.log(x);
    //console.log("extra data working");
    var data = {'action': 'pm_messenger_notification_extra_data'};

    jQuery.get(pg_msg_object.ajax_url, data, function (response)
    {
        if (response)
        {
            var obj = jQuery.parseJSON(response);
            //console.log(obj.unread_threads);
            if (obj.unread_threads !== 0)
            {
                console.log(obj.unread_threads);
                if(x!==undefined || x=='')
                {
                    x =  jQuery("#unread_thread_count").html();
                }
                console.log(x);
                jQuery("#unread_thread_count").addClass("thread-count-show"); 
                jQuery("#unread_thread_count").html(obj.unread_threads);  
               
//                if(jQuery('#thread_pane').length)
//                {
//                    pg_activate_new_thread(obj.rid);
//                }
                
                if(x<obj.unread_threads)
                {
                    jQuery("#msg_tone")[0].play();
                }
                
                   
            }else{
                    //jQuery("#unread_thread_count").html('');   
                    //jQuery("#unread_thread_count").removeClass("thread-count-show"); 
            
                
            }
          
        }

    });
}


function pg_msg_open_tab()
{
    pg_msg_loader();  
     jQuery("#unread_thread_count").html('');   
    jQuery("#unread_thread_count").removeClass("thread-count-show");
   jQuery('.pg-message-box-sidebar').addClass('opened');
}

function pg_show_new_thread()
{
    jQuery('#pg-msg-thread-container .pg-msg-thread-header').hide();
    jQuery('#pg-msg-thread-container #pg-new-msg').show();
    jQuery('.pg-users-search-list-wrap').html('');
    jQuery('.emojionearea-editor').attr('placeholder','');
    jQuery('#send_msg_btn').attr('disabled','disabled');
    jQuery(".pg-message-box-sidebar").removeClass('opened');
}

function pg_start_new_thread(){
    var autocomplete_request = null;
jQuery("#receipent_field").autocomplete({
     appendTo: ".pg-users-search-list-wrap",
     minLength: 3,
    source: function (request, response) 
            {
                    if (autocomplete_request != null) 
                    {
                        autocomplete_request.abort();
                    }

                    var name = jQuery("#receipent_field").val();
                    var data = {'action': 'pm_autocomplete_user_search', 'name': name};
                    autocomplete_request = jQuery.post(pg_msg_object.ajax_url, data, function (resp) {
                            if(resp) 
                            {
                                var x = jQuery.parseJSON(resp);
                                response(x);
                                jQuery("#pm-autocomplete ul li").attr("tabindex",'0');
                            }
                            else
                            {
                                //response([{ label: 'No results found.', val: ''}]);
                               // jQuery('.pg-users-search-list-wrap').text('No user found');
                               // console.log("err in autocomplete field");
                               
                            }
                        });

            },
    select: function (event, ui) 
            {
                event.preventDefault();
                //jQuery("#receipent_field").attr("value", "@"+ui.item.label);
                if(ui.item.id!="")
                {
                    jQuery("#receipent_field_rid").val(ui.item.id);
                    pg_activate_new_thread(ui.item.id);
                }
                //activate_thread_with_uid(ui.item.id,0);

            }

});
}

function pg_msg_loader()
{
    jQuery(document).ready(function() {
        /*emoji area JS */
    var el = jQuery("#pg_messaging_text").emojioneArea({
  	pickerPosition: "top",
  	filtersPosition: "bottom",
    tones: false,
    autocomplete: false,
    inline: true,
    hidePickerOnBlur: true,
    events: {
    keyup: function (editor, event) {
        if(event.which == 13) {
            jQuery(this).blur();
            jQuery('#send_msg_btn').focus().click();
        }
        
    }}
  });
  if(jQuery('.pg-users-search-list-wrap div.pg-message-list').length)
    {
      jQuery(".pg-users-search-list-wrap").scrollTop( jQuery(".pg-users-search-list-wrap div.pg-message-list:last").offset().top);
      var date = jQuery(".pg-msg-list-wrap .active .pg-msg-thread-time").html();
      jQuery("#pg-msg-thread-container .pg-msg-thread-header .pg-msg-thread-time").html(date);
    }
  /*message scrolling js */
    jQuery('.pg-users-search-list-wrap').scroll(function() 
    {
        var tid = jQuery('#thread_hidden_field').val();

        if(jQuery('#load_more_message').length)
        {
            console.log(jQuery('#load_more_message').length);
                if (jQuery('.pg-users-search-list-wrap').offset().top - 100 <= jQuery('#load_more_message').offset().top)
                {
                        if(!jQuery('#load_more_message').attr('loaded'))
                        {
                            jQuery('#load_more_message').attr('loaded', true);
                            var pagenum= jQuery('#load_more_message').attr('pagenum');
                            pagenum=parseInt(pagenum)+1;
                            show_thread_messages(tid,pagenum);
                        }
                }
        }
    });
    
    
    jQuery(".emojionearea-editor").focusin(function() {
          
            var activity = 'typing';
            pm_get_messenger_notification('', activity);
        });

    jQuery(".emojionearea-editor").focusout(function() {
         
            var activity = 'nottyping';
            pm_get_messenger_notification('', activity);
        });
    
        jQuery(".pg-message-action").click(function () {
        jQuery(this).toggleClass("pg-action-active");

    });


        
        
        jQuery(".pg-thread-open a").click(function () {
            //alert('Yes you clicked');
            jQuery(".pg-message-box-sidebar").toggleClass("opened");
        });
        
        
        
        
       jQuery(".pg-thread-new-msg a").click(function () {
           // alert('Yes you clicked');
         jQuery(".pg-message-box-sidebar").addClass("opened");
        });
        
        
        // Reponsive//GUI Engine
        jQuery(window).resize(function () {

         mobileSizer();


        });
        
        jQuery(document).ready(function () {

         mobileSizer();

        });
        
        
        function mobileSizer() {


            var messageBoxArea = jQuery('.pmagic').innerWidth();

            if (messageBoxArea < 460) {

                jQuery('.pg-message-box-container').addClass('pg-message-box-small');
                jQuery('.pg-message-box-container').removeClass('pg-message-box-medium');


            } else {

                jQuery('.pg-message-box-container').addClass('pg-message-box-medium');
                jQuery('.pg-message-box-container').removeClass('pg-message-box-small');
            }

        }
        ;
        
      
        
    
    });
    
}

pg_msg_loader();

function pm_messenger_send_chat_message(event) {
    var activity = 'nottyping';
    pm_get_messenger_notification('', activity);
    event.preventDefault();
    if( jQuery("#pg_messaging_text").val()===''){
        alert(pg_msg_object.empty_chat_message);
        return false;
    }
    if(jQuery("#receipent_field_rid").val()===''){
        alert("Enter a valid receipent");
        return false;
    }
    var form = jQuery("#chat_message_form");
    var mid = jQuery("#mid").val();
    var form_values = form.serializeArray();
    pm_messenger_send_message(form_values);
    var content = jQuery.trim(jQuery(".emojionearea-editor").html());
    jQuery(".emojionearea-editor").html('');
     jQuery("#pg_messaging_text").val('');
    if(mid=="")
    {
     var html = '<div id="" class="pg-message-list pg-user-self-msg pm-sending-msg" ><div class="pg-message-box"><p>'+content+'</p></div><div class="pg-msg-thread-time">'+ pg_msg_object.seding_text +'</div></div>';
     jQuery(".pg-users-search-list-wrap").append(html);
    }
    else
    {
        var html = '<p>'+content+'</p>';
        jQuery('#pg-msg_id_'+mid+' .pg-message-box').html(html);
        jQuery('#pg-msg_id_'+mid).focus();
    }
}

function pm_messenger_send_message(form_values) {
    //console.log("sending message ");
    var tid = jQuery('#thread_hidden_field').val();
    var data = {};
    jQuery.each(form_values, function () {
        if (data[this.name] !== undefined) {
            if (!data[this.name].push) {
                data[this.name] = [data[this.name]];
            }
            data[this.name].push(this.value);
        } else {
            data[this.name] = this.value;
        }
    });
    
    jQuery.post(pg_msg_object.ajax_url, data, function (resp) {
        if(data['new_thread']=="1")
        {
            pg_show_all_threads(resp);
        }
        show_thread_messages(resp,1);
        jQuery('#new_thread').val("0");
        jQuery('#mid').val("");
//        jQuery(".pg-users-search-list-wrap").scrollTop( jQuery(".pg-users-search-list-wrap div.pg-message-list:last").offset().top);       
    });
}


function pg_show_msg_panel(uid,rid,tid)
{
     jQuery("#unread_thread_count").html('');   
    jQuery("#unread_thread_count").removeClass("thread-count-show");
    var search = jQuery('#pg-msg-search-box').val();
    var data = {'action': 'pg_show_msg_panel', 'uid': uid,'rid': rid,'tid':tid,search:search};
   //console.log(data);
    jQuery("#pg-msg-thread-container").html('<div><div class="pm-loader"></div></div>');
    var pmDomColor = jQuery(".pmagic").find("a").css('color');
    jQuery(".pm-loader").css('border-top-color', pmDomColor);
    jQuery('.pg-msg-conversation-list').removeClass('active');
    jQuery(".pg-message-box-sidebar").removeClass('opened');
    jQuery('#pg-msg-thread-'+tid).addClass('active');
    jQuery.post(pg_msg_object.ajax_url, data, function (resp) {
        jQuery('#pg-msg-thread-'+tid+' .pg-unread-count' ).remove();
        jQuery('#pg-msg-thread-container').html(resp);
        jQuery('#new_thread').val("1");
        pg_msg_loader();
         pm_messenger_notification_extra_data(1);
         
    if(search!=='')
    {
     
        jQuery(".pg-users-search-list-wrap:contains("+search+")").html(function(_, html) {
            return  html.replaceAll(search, '<span class="msg-search-result">'+search+'</span>')
        });

     setTimeout(function () {
     jQuery(".pg-users-search-list-wrap").scrollTop(jQuery(".pg-users-search-list-wrap div.pg-message-list .msg-search-result:last").offset().top - jQuery(".pg-users-search-list-wrap div:first").offset().top);
      //console.log(jQuery(".pg-users-search-list-wrap div.pg-message-list .msg-search-result:first").offset().top - jQuery(".pg-users-search-list-wrap div:first").offset().top);
      }, 400);
    }
        
    });
}

function pg_activate_new_thread(uid)
{
    var data = {action:'pm_activate_new_thread',uid: uid};
    jQuery.post(pg_msg_object.ajax_url, data, function (resp) 
    {
        //console.log(resp.tid);
        jQuery('.pg-msg-list-wrap').html(resp.threads);
        pg_show_msg_panel(resp.sid,resp.rid,resp.tid);
    },'JSON');
    
     jQuery(".pg-message-box-sidebar").removeClass('opened');
}

function pg_activate_last_thread()
{
    var data = {action:'pm_activate_last_thread'};
    jQuery.post(pg_msg_object.ajax_url, data, function (resp) 
    {
        //console.log(resp.tid);
        jQuery('.pg-msg-list-wrap').html(resp.threads);
        pg_show_msg_panel(resp.sid,resp.rid,resp.tid);
        var pmDomColor = jQuery(".pmagic").find("a").css('color');
        jQuery( ".pg-msg-list-wrap" ).css('border-color', pmDomColor); 
        jQuery( ".pg-msg-list-wrap" ).css('background', pmDomColor); 
        jQuery( ".pg-no-thread" ).parent( ".pg-msg-list-wrap" ).removeAttr('style');
    },'JSON');
}

function pg_show_all_threads(tid)
{
     var data = {action: 'pg_show_all_threads',tid:tid};
    jQuery.post(pg_msg_object.ajax_url, data, function (resp) {
        jQuery('.pg-msg-list-wrap').html(resp);
        var pmDomColor = jQuery(".pmagic").find("a").css('color');
        jQuery( ".pg-msg-list-wrap" ).css('border-color', pmDomColor); 
        jQuery( ".pg-msg-list-wrap" ).css('background', pmDomColor); 
        jQuery( ".pg-no-thread" ).parent( ".pg-msg-list-wrap" ).removeAttr('style');
     });
    
}

function pg_search_threads(search)
{
    
     var data = {action: 'pg_search_threads',search:search};
    jQuery.post(pg_msg_object.ajax_url, data, function (resp) {
        jQuery('.pg-msg-list-wrap').html(resp);
        var pmDomColor = jQuery(".pmagic").find("a").css('color');
        jQuery( ".pg-msg-list-wrap" ).css('border-color', pmDomColor); 
        jQuery( ".pg-msg-list-wrap" ).css('background', pmDomColor); 
        jQuery( ".pg-no-thread" ).parent( ".pg-msg-list-wrap" ).removeAttr('style');
     });
    
}

function show_thread_messages(tid,loadnum) 
{
    jQuery("#unread_thread_count").html('');   
    jQuery("#unread_thread_count").removeClass("thread-count-show");
                    
    var offset = new Date().getTimezoneOffset();
    var nonce = pg_msg_object.nonce;
    var data = {'action': 'pm_messenger_show_messages', 'tid': tid,'loadnum': loadnum,'timezone':offset,'nonce':nonce};
   console.log(data);
    jQuery.post(pg_msg_object.ajax_url, data, function (resp) {
       pm_messenger_notification_extra_data(1);
        //console.log(resp);
        if(jQuery('.pg-msg-list-wrap').length)
        {
            if(loadnum == "1" )
            {
                jQuery(".pg-users-search-list-wrap").html(resp);
                if (jQuery(".pg-users-search-list-wrap div.pg-message-list:last").length)
                {
                    jQuery(".pg-users-search-list-wrap").scrollTop( jQuery(".pg-users-search-list-wrap div.pg-message-list:last").offset().top);
                }
            }
            else
            {
                jQuery(".pg-users-search-list-wrap").prepend(resp);
                jQuery(".pg-users-search-list-wrap").scrollTop( jQuery("#load_more_message").offset().top+500);
            }
            
            jQuery(".pg-message-action").click(function () {
                jQuery(this).toggleClass("pg-action-active");

            });
        
        }
    });
    

}

function pg_msg_delete_thread(tid,uid,mid)
{
    
    if (tid == undefined)
    {   
        return false;
    }
    else
   {
       jQuery('#pg_edit_group_html_container').html('');
       jQuery('.pm-popup-mask, #pm-edit-group-popup, .pg-blog-dialog-mask').hide();
       jQuery('#pg-msg-thread-'+tid).remove();
   }
    var data = {action: 'pm_messenger_delete_threads', 'tid':tid,'uid':uid,'mid':mid};
    jQuery.post(pg_msg_object.ajax_url, data, function (resp) {
       if(resp=="true")
       {
           pg_activate_last_thread();
       }
         
    });
    
    
}

function pg_msg_delete_thread_confirmbox(tid,uid,mid)
{
    
    if (tid == undefined)
    {   
        return false;
    }
    
    jQuery('#pg_edit_group_html_container').html('<div class="pg-edit-group-popup-loader"><div class="pm-loader"></div></div>');
     var pmDomColor = jQuery(".pmagic").find("a").css('color');
        jQuery(".pm-loader").css('border-top-color', pmDomColor);
       
    jQuery('#pm-edit-group-popup, .pm-popup-mask, .pg-blog-dialog-mask').toggle();
    var data = {action: 'pm_messenger_delete_threads_popup', 'tid':tid,'uid':uid,'mid':mid};
    jQuery.post(pg_msg_object.ajax_url, data, function (response) {
       if(response)
        {
            bgcolor = pmDomColor.replace(')', ',0.2)');
            jQuery('#pg_edit_group_html_container').html(response);
            jQuery("#pm-edit-group-popup .pg-users-send-box .pm-message-username").css('background-color', bgcolor);
            jQuery("#pm-edit-group-popup .pg-users-send-box .pm-message-username").css('border-color', pmDomColor);
            jQuery("#pm-edit-group-popup .pg-users-send-box .pm-message-username").css('color', pmDomColor);
            jQuery( ".pg-update-message svg" ).css('fill', pmDomColor); 
            jQuery('#pm-edit-group-popup .pm-popup-close, .pg-group-setting-close-btn ').on('click', function(e) {
                jQuery('.pm-popup-mask, #pm-edit-group-popup, .pg-blog-dialog-mask').hide();
            });
        }
    });
    
    
}

function pg_msg_read_messages(e,tid)
{
    var data = {action: 'pm_messages_mark_as_read', tid: tid};
    jQuery.post(pg_msg_object.ajax_url, data, function () {
       jQuery('#pg-msg-thread-'+tid+' .pg-unread-count' ).remove(); 
        pg_show_all_threads(tid);
    });
}

function pg_msg_unread_messages(e,tid)
{
    var data = {action: 'pm_messages_mark_as_unread', tid: tid};
    jQuery.post(pg_msg_object.ajax_url, data, function (result) {
        if(result)
        {
            pg_show_all_threads(tid); 
        }
    });
}

function pg_msg_edit(mid)
{
    jQuery('#chat_message_form .emojionearea-editor').focus();
    var msg = jQuery('#pg-msg_id_'+mid+' .pg-message-box').html();
    
    jQuery('#chat_message_form .emojionearea-editor').html(jQuery.trim(msg));
    jQuery('#chat_message_form #mid').val(mid);
}

function pg_msg_delete(mid)
{
    var tid = jQuery('#thread_hidden_field').val();
    var data = {action: 'pg_delete_msg', mid: mid,tid:tid};
    jQuery.post(pg_msg_object.ajax_url, data, function (result) {
        jQuery('#pg-msg_id_'+mid+' .pg-message-box').html(pg_msg_object.remove_msg);
    });
    
}
