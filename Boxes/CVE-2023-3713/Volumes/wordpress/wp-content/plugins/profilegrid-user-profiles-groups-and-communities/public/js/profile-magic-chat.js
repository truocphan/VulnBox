
function start_messenger()
{
    
var autocomplete_request = null;

jQuery("#receipent_field").autocomplete({
     appendTo: "#pm-autocomplete",
     minLength: 3,
    source: function (request, response) 
            {
                    if (autocomplete_request != null) 
                    {
                        autocomplete_request.abort();
                    }

                    var name = jQuery("#receipent_field").val();
                    if(name.charAt(0)=="@")
                    {
                        name = name.substr(1);
                    }

                    var data = {'action': 'pm_autocomplete_user_search', 'name': name};
                    autocomplete_request = jQuery.post(pm_chat_object.ajax_url, data, function (resp) {
                            if (resp) 
                            {
                                var x = jQuery.parseJSON(resp);
                                response(x);
                                jQuery("#pm-autocomplete ul li").attr("tabindex",'0');
                            }
                            else
                            {
                               // console.log("err in autocomplete field");
                            }
                        });

            },
    select: function (event, ui) 
            {
                event.preventDefault();
                //jQuery("#receipent_field").attr("value", "@"+ui.item.label);
                jQuery("#receipent_field_rid").val(ui.item.id);
                pg_activate_new_thread(ui.item.id);
                //activate_thread_with_uid(ui.item.id,0);

            }

});

jQuery('#message_display_area').scroll(function() 
{
    var tid = get_active_thread_id();
    
    if(jQuery('#load_more_message').length)
    {
        console.log(jQuery('#load_more_message').length);
            if (jQuery('#message_display_area').offset().top - 100 <= jQuery('#load_more_message').offset().top)
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

jQuery(function() {
        // Initializes and creates emoji set from sprite sheet
        window.emojiPicker = new EmojiPicker({
          emojiable_selector: '[data-emojiable=true]',
          assetsPath: pm_chat_object.plugin_emoji_url,
          popupButtonClasses: 'fa fa-smile-o'
        });
         window.emojiPicker.discover();
      });
      
    jQuery(".emoji-wysiwyg-editor").focusin(function() {
            var tid = get_active_thread_id();
            var activity = 'typing';
            pm_get_messenger_notification('', activity);
        });

    jQuery(".emoji-wysiwyg-editor").focusout(function() {
            var tid = get_active_thread_id();
            var activity = 'nottyping';
            pm_get_messenger_notification('', activity);
        });
    
   jQuery(document).ready(function(){
   
       var pmDomColor = jQuery(".pmagic").find("a").css('color');
        jQuery(".pm-loader").css('border-top-color', pmDomColor);
        jQuery(".pmagic .pm-blog-time").css('color', pmDomColor);
        jQuery(".pmagic .pm-user-conversations-counter").css('color', pmDomColor);
        jQuery(".pmagic #unread_thread_count").css('background-color', pmDomColor);
        jQuery(".pmagic #unread_notification_count").css('background-color', pmDomColor);
        jQuery(".pmagic .pm-blog-desc-wrap #chat_message_form input#receipent_field").css('color', pmDomColor);
        jQuery(".pmagic .pm-new-message-area button").css('color', pmDomColor);
        jQuery(".pmagic .pm-messenger-button svg").css('fill', pmDomColor);
        jQuery(".pmagic .pm-thread-active .pm-conversations-container .pm-thread-user").css('color', pmDomColor);
        jQuery(".pm-color").css('color', pmDomColor);
        jQuery("#pg-friends .pm-selected-image svg").css('fill', pmDomColor);
        jQuery( ".pmagic .page-numbers .page-numbers.current" ).addClass( "pm-bg" ).css('background', pmDomColor);
        jQuery( ".pm-group-view.pg-theme-seven .pg-profile-area-wrap" ) .css('background', pmDomColor); 

    
        
     
        jQuery('.pmagic .pm-profile-tab-wrap .pm-profile-tab').hover(
               function() {
                   jQuery(this).css('border-bottom-color',pmDomColor);
               },
               
               function() {
                   jQuery(this).css('border-bottom-color','transparent');
                   jQuery('.pm-section-nav-horizental .pm-profile-tab.ui-state-active').css('border-bottom-color',pmDomColor); 
               }
                         
       );
     
   }); 

}


function update_thread() {

    //console.log("updating thread");
    pg_show_all_threads();
    var tid = get_active_thread_id();
    
    show_thread_messages(tid,1);
    //show_threads(tid);
}

function pm_messenger_send_chat_message(event) {
    event.preventDefault();
    if( jQuery("#messenger_textarea").val()===''){
        alert(pm_chat_object.empty_chat_message);
        return false;
    }
    if(jQuery("#receipent_field_rid").val()===''){
        alert("Enter a valid receipent");
        return false;
    }
    var form = jQuery("#chat_message_form");
    var form_values = form.serializeArray();
    pm_messenger_send_message(form_values);
    var content = jQuery.trim(jQuery(".emoji-wysiwyg-editor").html());
    jQuery(".emoji-wysiwyg-editor").html('');
     jQuery("#messenger_textarea").val('');
     var img = jQuery('.pm-messenger-user-profile-pic').html();
     var html = '<div id="" class="pm_msg_rf  pm-sending-msg" >'+img+'<div class="pm-user-description-row pm-dbfl pm-border">'+content+'</div>'+ pm_chat_object.seding_text +'</div>';
     jQuery("#message_display_area").append(html);
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
    
    jQuery.post(pm_chat_object.ajax_url, data, function (resp) {
        show_thread_messages(tid,1);
        jQuery("#message_display_area").scrollTop( jQuery("#message_display_area div:last").offset().top)
    });
}

function get_active_thread_id() {
    var cur_thread = jQuery("#threads_ul [active='true']");
    var id = jQuery(cur_thread).attr("id");
    if (id === undefined){
        id='';
    }else{
    var tid = id.replace('t_id_', '');
    }
    return tid;

}

function pg_activate_new_thread(uid)
{
    var data = {action:'pm_activate_new_thread',uid: uid};
    jQuery.post(pm_chat_object.ajax_url, data, function (resp) 
    {
        console.log(resp.tid);
        jQuery('#threads_ul').html(resp.threads);
        pm_get_active_thread_header(resp.rid);
        show_thread_messages(resp.tid,1); 
        //show_message_pane(resp.tid,resp.rid);
    },'JSON');
}

function pm_get_rid_by_uname(uname)
{ 
    
}

function show_thread_messages(tid,loadnum) 
{
    
    //var tid = id.replace('t_id_', '');
    //console.log("showing thread  message of tid : "+tid);
   var offset = new Date().getTimezoneOffset();
   //console.log("offset is "+offset);
    var data = {'action': 'pm_messenger_show_messages', 'tid': tid,'loadnum': loadnum,'timezone':offset};
   console.log(data);
    jQuery.post(pm_chat_object.ajax_url, data, function (resp) {
       
        //console.log(resp);
        if(jQuery('#thread_pane').length)
        {
            if(loadnum == "1" )
            {
                jQuery("#message_display_area").html(resp);
                 if (jQuery("#message_display_area div:last").length)
                {
                jQuery("#message_display_area").scrollTop( jQuery("#message_display_area div:last").offset().top);
                }
            }
            else
            {
                jQuery("#message_display_area").prepend(resp);
                jQuery("#message_display_area").scrollTop( jQuery("#load_more_message").offset().top+500);
            }
        
        }
    });

}

function show_message_pane(tid,rid) {
    
        //jQuery("#receipent_field").prop("disabled",true);
         //jQuery("#receipent_field").addClass("pm-recipent-disable");
        jQuery('#pm-username-error').html('');
        show_pg_section_right_panel();
      
        jQuery("#threads_ul li").attr("active", "false");
        jQuery("#t_id_"+tid).attr("active", "true");
        jQuery("#t_id_"+tid+" #unread_msg_count").html(" ");
        var uid = jQuery("#t_id_"+tid).attr("uid");
        pm_get_active_thread_header(rid);
        show_thread_messages(tid,1); 
        
    //console.log("showing message pane of tid : "+tid+" and uid : "+uid);
    jQuery("#receipent_field_rid").attr('value', uid);
    jQuery("#thread_hidden_field").attr("value", tid);
    
    pm_messages_mark_as_read(tid);
    //activate_thread_with_uid(uid,mid);

}

function pm_get_active_thread_header(uid)
{
    var data = {action: 'pm_get_active_thread_header', uid: uid};
    jQuery.post(pm_chat_object.ajax_url, data, function (resp) {
        jQuery('#userSection').html(resp);
    });
}

function pm_messenger_notification_extra_data(x){
  //console.log(x);
    //console.log("extra data working");
    var data = {'action': 'pm_messenger_notification_extra_data'};

    jQuery.get(pm_chat_object.ajax_url, data, function (response)
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
                if(jQuery('#thread_pane').length)
                {
                    pg_activate_new_thread(obj.rid);
                }
                if(x<obj.unread_threads)
                {
                    jQuery("#msg_tone")[0].play();
                }
                
                   
            }else{
                   jQuery("#unread_thread_count").html('');   
                    jQuery("#unread_thread_count").removeClass("thread-count-show"); 
            
                
            }
          
        }

    });
}

function refresh_messenger()
{
    start_messenger();
    pm_messenger_notification_extra_data(); 
    var tid = get_active_thread_id();
    show_thread_messages(tid,1);


}
 var notification_request = null;
function pm_get_messenger_notification(timestamp, activity)
{
   
    if (activity === undefined)activity = '';
    var tid = get_active_thread_id();
    var data = {'action': 'pm_get_messenger_notification',
        'timestamp': timestamp,
        'activity': activity,
        'tid': tid
    };
    if(notification_request !== null){
        notification_request.abort();

    }

    notification_request = jQuery.get(pm_chat_object.ajax_url, data, function (response)
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
                }
                if (obj.activity == 'nottyping') {
                    jQuery("#typing_on .pm-typing-inner").hide();
                }
                if (obj.data_changed === true)
                {

                    update_thread();
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

function pm_messages_mark_as_read(tid)
{
    var data = {action: 'pm_messages_mark_as_read', tid: tid};
    jQuery.post(pm_chat_object.ajax_url, data, function () {
        jQuery("#unread_thread_count").html('');   
        jQuery("#unread_thread_count").removeClass("thread-count-show"); 
    });
}

function pm_messenger_delete_thread(a,tid){
 
    if (tid == undefined){   
        return false;
    }else{
   // console.log("Deleting thread with  tid :" + tid);
   jQuery(a).parent('div').parent('li').remove();
    }
    var data = {action: 'pm_messenger_delete_threads', 'tid': tid};
    jQuery.post(pm_chat_object.ajax_url, data, function (resp) {
        jQuery('.pm-message-thread-section').show();
       // console.log(resp);
        var obj = jQuery.parseJSON(resp);
        console.log(obj);
         //pg_activate_new_thread(obj.uid);
         pm_get_active_thread_header(obj.uid);
         show_thread_messages(obj.tid,1);
    });

}

function pg_show_all_threads()
{
     var data = {action: 'pg_show_all_threads'};
    jQuery.post(pm_chat_object.ajax_url, data, function (resp) {
        jQuery('#threads_ul').html(resp);
     });
    
}
