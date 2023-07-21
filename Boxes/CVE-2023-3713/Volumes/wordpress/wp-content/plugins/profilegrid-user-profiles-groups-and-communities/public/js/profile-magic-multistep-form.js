/* multistep form js */
(function( $ ) {
	'use strict';
       
       var curpage = 1;
        var id = null;
        var settings = null;
        $.fn.transitionPage = function(from,to) {

                if (settings.transitionFunction) {
                        settings.transitionFunction(from,to);
                } else {
                        $(from).hide();
                        $(to).show();
                }
                $(id + ' fieldset').removeClass('active');
                $(to).addClass('active');		
        }
        $.fn.showState = function(page) { 

                if (settings.stateFunction) { 
                        return settings.stateFunction(id+"_nav .multipage_state",page,settings.pages.length);
                }
                var state = '';
                for (x = 1; x <= settings.pages.length; x++) {
                        if(x==page) {
                                state = state + settings.activeDot;
                        } else {
                                state = state + settings.inactiveDot;
                        }
                }
                $(id+"_nav .multipage_state").html(state);	
        }
        $.fn.gotopage = function(page) {
                $(id + '_nav .multipage_next').html(pm_error_object.next);				

                if (isNaN(page)) { 
                        var q = page;
                        page = 1;
                        $(id+' fieldset').each(function(index) {
                                if ('#'+$(this).attr('id')==q) { 
                                        curpage = page = index+1;
                                }
                        });
                }

                var np = null;
                var cp = $(id+' fieldset.active');
                // show the appropriate page.
                $(id+' fieldset').each(function(index) {
                        index++;
                        if (index==page) {		
                                np = this;
                        }
                });

                $(this).transitionPage(cp,np);

                $(this).showState(page);

                $(id + '_nav .multipage_next').removeClass('submit');				

                // is there a legend tag for this fieldset?
                // if so, pull it out.
                var page_title = settings.pages[page-1].title;

                if (settings.stayLinkable) { 
                       var hashtag = '#' + settings.pages[page-1].id;
                        document.location.hash = hashtag;
                }
                if (page==1) {
                        // set up for first page
                        $(id + '_nav .multipage_back').hide();
                        $(id + '_nav .multipage_next').show();
                        if(page==settings.pages.length)
                        {
                            $(id + '_nav .multipage_next').addClass('submit');				
                            $(id + '_nav .multipage_next').html(settings.submitLabel);	
                        }
                        else
                        {
                        if (settings.pages[page].title) {
                                $(id + '_nav .multipage_next').html( pm_error_object.next + ': ' + settings.pages[page].title);
                        } else {
                                $(id + '_nav .multipage_next').html(pm_error_object.next);
                        }
                    }

                } else if (page==settings.pages.length) { 
                        // set up for last page
                        $(id + '_nav .multipage_back').show();
                        $(id + '_nav .multipage_next').show();

                        if (settings.pages[page-2].title) { 
                                $(id + '_nav .multipage_back').html(pm_error_object.back + ': ' + settings.pages[page-2].title);
                        } else {
                                $(id + '_nav .multipage_back').html(pm_error_object.back);				
                        }

                        $(id + '_nav .multipage_next').addClass('submit');				
                        $(id + '_nav .multipage_next').html(settings.submitLabel);				

                } else {
                        if (settings.pages[page-2].title) { 
                                $(id + '_nav .multipage_back').html(pm_error_object.back + ': ' + settings.pages[page-2].title);
                        } else {
                                $(id + '_nav .multipage_back').html(pm_error_object.back);				
                        }
                        if (settings.pages[page].title) {
                                $(id + '_nav .multipage_next').html(pm_error_object.next + ': ' + settings.pages[page].title);
                        } else {
                                $(id + '_nav .multipage_next').html(pm_error_object.next);
                        }

                        $(id + '_nav .multipage_back').show();
                        $(id + '_nav .multipage_next').show();				

                }

                $(id + ' fieldset.active input:first').focus();
                curpage=page;
                return false;

        }
	$.fn.validatePage = function(page) { return true;};
        $.fn.validateAll = function() { 
		for (var x = 1; x <= settings.pages.length; x++) {
			if (!$(this).validatePage(x)) {
				$(this).gotopage(x);
				return false;
			}
		}
		return true;
	};
        $.fn.gotofirst = function() {
		curpage = 1;
		$(this).gotopage(curpage);
		return false;
	}
	$.fn.gotolast = function() {
		curpage = settings.pages.length;
		$(this).gotopage(curpage);
		return false;
	}

	$.fn.nextpage = function() {
			// validate the current page
			var curfieldset = $(this).children("fieldset:nth-child("+ curpage+")");
			if(profile_magic_multistep_form_validation(curfieldset))
			{
				if ($(this).validatePage(curpage)) { 
					curpage++;
		
					if (curpage > settings.pages.length) {
                                            var payment_type = $("input[name='pm_payment_method']:checked").val();
                                             
                                           
                                            if(payment_type=='stripe')
                                            {
                                                 //var form = $(this).parents('form');
                                                 multistep_stripe_form(this);
                                            }
                                            else
                                            {
                                                // submit!
						$(this).submit();
                                            }
						
						 curpage = settings.pages.length;
						 return false;
					}
					$(this).gotopage(curpage);
				}
				return false;
			}
		
	}
	
	$.fn.getPages = function() {
		return settings.pages;
	};
		
	$.fn.prevpage = function() {

		curpage--;

		if (curpage < 1) {
			 curpage = 1;
		}
		$(this).gotopage(curpage);
		return false;
		
	}
	
	
	$.fn.multipage = function(options) { 
		
		settings = $.extend({stayLinkable:false,submitLabel:pm_error_object.submit,hideLegend:false,hideSubmit:true,generateNavigation:true,activeDot:'&nbsp;&#x25CF;',inactiveDot:'&nbsp;&middot;'},options);
		id = '#' + $(this).attr('id');
		var form = $(this);			
		
		form.addClass('multipage');
		
		form.submit(function(e) {
			if (!$(this).validateAll()) {
				e.preventDefault()
			};
		});
		
		// hide all the pages 
		$(id +' fieldset').hide();
			if (settings.hideSubmit) { 
				$(id+' input[type="submit"]').hide();
			}		
			
			if ($(id+' input[type="submit"]').val()!='') { 
				settings.submitLabel = $(id+' input[type="submit"]').val();
			}
			
			settings.pages = new Array();
			
			$(this).children('fieldset').each(function(index) { 
				var label = $(this).children('legend').html();
				settings.pages[index] = {number:index+1,title:label,id:$(this).attr('id')};
			});
			
			
			if (settings.hideLegend) { 
				// hide legend tags
				$(id+' fieldset legend').hide();
			}
			
			// show the first page.
			$(id+' fieldset:first').addClass('active');

			$(id+' fieldset:first').show();
									
			if (settings.generateNavigation) { 
				if (settings.navigationFunction) { 
					settings.navigationFunction($(this).getPages());
				} else {
					// insert navigation
                                        var id_name = $(this).attr('id');
                                        $('<div class="multipage_nav" id="'+id_name+'_nav"><a href="#" class="multipage_back" onclick="return  jQuery(\''+id+'\').prevpage();">' + pm_error_object.back + '</a><a href="#"  class="multipage_next" onclick="return jQuery(\''+id+'\').nextpage();">' + pm_error_object.next + '</a><span class="multipage_state"></span><div class="clearer"></div></div>').insertAfter(this);
				}
			}				
			
			if (document.location.hash) { 
				$(this).gotopage('#'+document.location.hash.substring(1,document.location.hash.length));
			} else {
				$(this).gotopage(1);			
			}	
			return false;
		
		}
        $('#multipage').multipage({transitionFunction:transition,stateFunction: textpages});
	$('form').submit(function(){return true;}); 
})(jQuery);
