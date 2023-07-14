;jQuery(document).ready(function($) {
    "use strict";

    const
            $window = $(window),
            $body = $('body'),

            // Project Search
            htwptSearchSection = $('#htwpt-search-section'),
            htwptDemos = $('#htwpt-demos'),
            htwptBuilder = $('#htwpt-builder'),
            htwptSearchField = $('#htwpt-search-field'),
            htwptType = $('#htwpt-type'),

            // Project
            htwptProjectSection = $('#htwpt-project-section'),
            htwptProjectGrid = $('#htwpt-project-grid'),
            htwptProjectLoadMore = $('#htwpt-load-more-project'),

            // Project Count
            htwptInitProjectStartCount = 0,
            htwptInitProjectEndCount = 8,
            htwptProjectLoadCount = 4,

            // Project Loading/Load more
            htwptLoaderHtml = '<span id="htwpt-loader"></span>',
            htwptLoaderSelector = '#htwpt-loader',
            htwptLoadingText = '<span class="htwpt-pro-loading"></span>',
            htwptLoadedText = HTTM.message.allload,
            htwptNothingFoundText = HTTM.message.notfound,

            // Group Project 
            htwptGroupProjectSection = $('#htwpt-group-section'),
            htwptGroupProjectGrid = $('#htwpt-group-grid'),
            htwptGroupProjectBack = $('#htwpt-group-close'),
            htwptGroupProjectTitle = $('#htwpt-group-name');

        let
            // Project Data
            htwptProjectData = HTTM.alldata,

            // Project Count
            htwptProjectStartCount = htwptInitProjectStartCount,
            htwptProjectEndCount = htwptInitProjectEndCount,

            // Project Options Value
            htwptDemosValue = htwptDemos.val(),
            htwptBuilderValue = htwptBuilder.val(),
            htwptSearchFieldValue = htwptSearchField.val(),
            htwptTypeValue = htwptType.val(),

            // Project Start End Count Fnction for Options
            htwptProjectStartEndCount,

            // Project Print Function
            htwptProjectPirnt,

            // Check Image Load Function
            imageLoad,

            // Scroll Magic Infinity & Reveal Function
            htwptInfinityLoad,
            htwptElementReveal,

            // Ajax Fail Message
            failMessage,
            msg = '';
        // Project Start End Count Fnction for Options
        htwptProjectStartEndCount = () => {
            htwptProjectStartCount = htwptInitProjectStartCount;
            htwptProjectEndCount = htwptInitProjectEndCount;
        };
        // Delete transient fourcely 
        $('body').on( 'click','.htmega-template-library-page-sync i', function(e){
            $(this).addClass("eicon-animation-spin");
            var t = {
                data: {},
                success: function (t) {
                    htwptProjectData =  t.templates;
                    $('.htmega-template-library-page-sync i').removeClass("eicon-animation-spin");
                },
            };
            t.data.sync = true, 
            elementorCommon.ajax.addRequest("get_htmega_library_data", t);
           
        }); 

        // Projects Demo Type Select
        htwptDemos.selectric({
            onChange: (e) => {
                htwptDemosValue = $(e).val();
                htwptSearchFieldValue = '';
                htwptSearchField.val('');
                htwptProjectStartEndCount();
                htwptProjectPirnt(htwptProjectData);
            },
        });

        // Projects Builder Type Select
        htwptBuilder.selectric({
            onChange: (e) => {
                htwptBuilderValue = $(e).val();
                htwptProjectStartEndCount();
                htwptProjectPirnt(htwptProjectData);
            },
        });

        // Projects Pro/Free Type Select
        htwptType.selectric({
            onChange: (e) => {
                htwptTypeValue = $(e).val();
                htwptProjectStartEndCount();
                htwptProjectPirnt(htwptProjectData);
            },
        });

        // Projects Search
        htwptSearchField.on('input', () => {
            if (!htwptSearchField.val()) {
                htwptSearchFieldValue = htwptSearchField.val().toLowerCase();
                htwptProjectStartEndCount();
                htwptProjectPirnt(htwptProjectData);
            }
        });
        htwptSearchField.on('keyup', (e) => {
            if (e.keyCode == 13) {
                htwptSearchFieldValue = htwptSearchField.val().toLowerCase();
                htwptProjectStartEndCount();
                htwptProjectPirnt(htwptProjectData);
            }
        });

        // Check Image Load Function
        imageLoad = () => {
            $('.htwpt-image img').each((i, e) => $(e).on('load', () => $(e).addClass('finish')));
        };

        // Projects Print/Append on HTML Dom Function
        htwptProjectPirnt = function (htwptProjectData, types = 'push') {
            // Projects Data Filter for Template/Blocks
            htwptProjectData = htwptProjectData.filter(i => i.demoType == htwptDemosValue)
            // Projects Data Filter for Builder Support
            if (htwptBuilderValue != "all") {
                htwptProjectData = htwptProjectData.filter(i => i.builder.filter(j => j == htwptBuilderValue)[0])
            }
            // Projects Data Filter for Free/Pro
            if (htwptTypeValue != "all") {
                // htwptProjectData = htwptProjectData.filter(i => i.isPro == htwptTypeValue)
                htwptProjectData = htwptProjectData.filter(i => i.tmpType == htwptTypeValue)
            }
            // Projects Data Filter by Search
            if (htwptSearchFieldValue != "") {
                htwptProjectData = htwptProjectData.filter(i => i.tags.filter(j => j == htwptSearchFieldValue)[0])
            }

            let htwptPrintDataArray = Array.from(new Set(htwptProjectData.map(i => i.shareId))).map(j => htwptProjectData.find(a => a.shareId === j)),
                htwptPrintData = htwptPrintDataArray.slice(htwptProjectStartCount, htwptProjectEndCount),
                html = '';
            for (let i = 0; i < htwptPrintData.length; i++) {
                let {
                    thumbnail,
                    id,
                    url,
                    shareId,
                    title
                } = htwptPrintData[i],
                    totalItem = htwptProjectData.filter(i => i.shareId == shareId).length,
                    singleItem = totalItem == 1 ? 'htwpt-project-item-signle' : '';
                html += `<div class="${singleItem} col-xl-4 col-md-6 col-12">
                            <div class="htwpt-project-item ${singleItem}" data-group="${shareId}">
                                <div class="htwpt-project-thumb">
                                    <div class="htwpt-image">
                                        <img src="${thumbnail}" alt="${title}" />
                                        <span class="img-loader"></span>
                                    </div>
                                </div>
                                <div class="htwpt-project-info">
                                    <h5 class="title">${shareId}</h5>
                                    <h6 class="sub-title">${totalItem} ${htwpUcfirst(htwptDemosValue)} ${HTTM.message.packagedesc}</h6>
                                </div>
                            </div>
                        </div>`;
            }
            if (types == "append") {
                htwptProjectGrid.append(html);
            } else {
                htwptProjectGrid.html(html);
            }
            if (htwptPrintDataArray.length == 0) {
                htwptProjectGrid.html(`<h2 class="htwpt-project-message text-danger">${htwptNothingFoundText}</h2>`);
                $(htwptLoaderSelector).addClass('finish').html('');
            } else {
                if (htwptPrintDataArray.length <= htwptProjectEndCount) {
                    $(htwptLoaderSelector).addClass('finish').html(htwptLoadedText);
                } else {
                    $(htwptLoaderSelector).removeClass('finish').html(htwptLoadingText);
                }
            }
            imageLoad();
        }

        // Scroll Magic for Infinity Load Function
        htwptInfinityLoad = () => {
            setTimeout(() => {
                let htwptInfinityController = new ScrollMagic.Controller(),
                    htwptInfinityscene = new ScrollMagic.Scene({
                        triggerElement: '#htwpt-loader',
                        triggerHook: 'onEnter',
                        offset: 0
                    })
                    .addTo(htwptInfinityController)
                    .on('enter', (e) => {
                        if (!$(htwptLoaderSelector).hasClass('finish')) {
                            htwptProjectStartCount = htwptProjectEndCount;
                            htwptProjectEndCount += htwptProjectLoadCount;
                            setTimeout(() => {
                                htwptProjectPirnt(htwptProjectData, 'append')
                            }, 200);
                        }
                    });
            });
        }

        // Scroll Magic for Reveal Element Function
        htwptElementReveal = () => {
            let htwptInfinityController = new ScrollMagic.Controller();
            $('.htwpt-group-item').each(function () {
                new ScrollMagic.Scene({
                        triggerElement: this,
                        triggerHook: 'onEnter',
                        offset: 50
                    })
                    .setClassToggle(this, "visible")
                    .addTo(htwptInfinityController);
            })
        }

        if(htwptProjectData.length) {
            htwptProjectLoadMore.append(htwptLoaderHtml);
            htwptProjectPirnt(htwptProjectData);
            htwptInfinityLoad();
        }

        function htwpUcfirst(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        // Group Project Open Function
        htwptProjectGrid.on('click', '.htwpt-project-item', function (e) {
            e.preventDefault();
            let htwptProjectGroupData = htwptProjectData;
            // Projects Data Filter for Template/Blocks
            htwptProjectGroupData = htwptProjectGroupData.filter(i => i.demoType == htwptDemosValue)
            // Projects Data Filter for Builder Support
            if (htwptBuilderValue != "all") {
                htwptProjectGroupData = htwptProjectGroupData.filter(i => i.builder.filter(j => j == htwptBuilderValue)[0])
            }
            // Projects Data Filter for Free/Pro
            if (htwptTypeValue != "all") {
                htwptProjectGroupData = htwptProjectGroupData.filter(i => i.tmpType == htwptTypeValue)
            }
            // Projects Data Filter by Search
            if (htwptSearchFieldValue != "") {
                htwptProjectGroupData = htwptProjectGroupData.filter(i => i.tags.filter(j => j == htwptSearchFieldValue)[0])
            }

            let $this = $(this),
                $group = $this.data('group'),
                htwptPrintGroupData = htwptProjectGroupData.filter(i => i.shareId == $group),
                htwptGroupHTML = '',
                $impbutton = '',
                $tmptitle = '';
            for (let i = 0; i < htwptPrintGroupData.length; i++) {
                let {
                    thumbnail,
                    id,
                    url,
                    shareId,
                    title,
                    isPro,
                    freePlugins,
                    proPlugins,
                    requiredtheme,
                } = htwptPrintGroupData[i];
                if(isPro == '1' ){
                    $impbutton = `<a href="${HTTM.prolink}" target="_blank">${HTTM.buttontxt.buynow}</a>`;
                    $tmptitle = `<h5 class="title">${title} <span>(${HTTM.prolabel})</span></h5>`;
                }else{
                    $impbutton = `<a href="#" class="htwpttemplateimp button" data-templpateopt='{"parentid":"${shareId}","templpateid":"${id}","templpattitle":"${title}","message":"Successfully ${htwpUcfirst(shareId)+ ' -> ' + title} has been imported.","thumbnail":"${thumbnail}","freePlugins":"${freePlugins}", "proPlugins":"${proPlugins}","requiredtheme":"${requiredtheme}" }'>${HTTM.buttontxt.import}</a>`;
                    $tmptitle = `<h5 class="title">${title}</h5>`;
                }
                htwptGroupHTML += `<div class="htwpt-group-item col-xl-4 col-md-6 col-12">
                            <div class="htwpt-project-item">
                                <div class="htwpt-project-thumb">
                                    <a href="${thumbnail}" class="htwpt-image htwpt-image-popup">
                                        <img src="${thumbnail}" data-preview='{"templpateid":"${id}","templpattitle":"${title}","parentid":"${shareId}","fullimage":"${thumbnail}"}' alt="${title}" />
                                        <span class="img-loader"></span>
                                    </a>
                                    <div class="htwpt-actions">
                                        <a href="${url}" target="_blank">${HTTM.buttontxt.preview}</a>
                                        ${$impbutton}
                                    </div>
                                </div>
                                <div class="htwpt-project-info">
                                    ${$tmptitle}
                                    <h6 class="sub-title">${shareId}</h6>
                                </div>
                            </div>
                            <div id="htwpt-popup-prev-${id}" style="display: none;"><img src="${thumbnail}" alt="${title}" style="width:100%;"/></div>
                        </div>`;
            }
            if (!$(htwptLoaderSelector).hasClass('finish')) {
                $(htwptLoaderSelector).addClass('finish group-loaded');
            }
            htwptProjectSection.addClass('group-project-open');
            htwptSearchSection.addClass('group-project-open');
            let topPotision;
            
            htwptSearchSection.offset().top > 32 && $(window).scrollTop() < htwptSearchSection.offset().top ? topPotision = htwptSearchSection.offset().top - $(window).scrollTop() : topPotision = 32;

            htwptGroupProjectSection.fadeIn().css({
                "top": topPotision + 'px',
                "left": htwptSearchSection.offset().left + 'px'
            });
            $body.css('overflow-y', 'hidden');
            htwptGroupProjectTitle.html($group);
            htwptGroupProjectGrid.html(htwptGroupHTML);
            htwptElementReveal();
            imageLoad();
        });

        // Group Project Close Function
        htwptGroupProjectBack.on('click', function (e) {
            e.preventDefault();
            htwptGroupProjectSection.fadeOut('fast');
            htwptGroupProjectTitle.html('');
            htwptGroupProjectGrid.html('');
            htwptProjectSection.removeClass('group-project-open');
            htwptSearchSection.removeClass('group-project-open');
            $body.css('overflow-y', 'auto');
            imageLoad();
            if ($(htwptLoaderSelector).hasClass('group-loaded')) {
                $(htwptLoaderSelector).removeClass('finish group-loaded');
            }
        });

        // Scroll To Top
        let $htwptScrollToTop = $(".htwpt-scrollToTop"),
            $htwptGroupScrollToTop = $(".htwpt-groupScrollToTop");
        $window.on('scroll', function () {
            if ($window.scrollTop() > 100) {
                $htwptScrollToTop.addClass('show');
            } else {
                $htwptScrollToTop.removeClass('show');
            }
        });
        $htwptScrollToTop.on('click', function (e) {
            e.preventDefault();
            $("html, body").animate({
                scrollTop: 0
            });
        });
        htwptGroupProjectSection.on('scroll', function () {
            if (htwptGroupProjectSection.scrollTop() > 100) {
                $htwptGroupScrollToTop.addClass('show');
            } else {
                $htwptGroupScrollToTop.removeClass('show');
            }
        });
        $htwptGroupScrollToTop.on('click', function (e) {
            e.preventDefault();
            htwptGroupProjectSection.animate({
                scrollTop: 0
            });
        });


    /*
    * PopUp button
    * Preview PopUp
    * Data Import Request
    */
    $('body').on('click', 'a.htwpttemplateimp', function(e) {
        e.preventDefault();

        var $this = $(this),
            template_opt = $this.data('templpateopt');

        $('.htwpt-edit').html('');
        $('#htwptpagetitle').val('');
        $(".htwptpopupcontent").show();
        $(".htwptmessage").hide();
        $(".htwptmessage p").html( template_opt.message );

        // dialog header
        $("#htwpt-popup-area").attr( "title", htwpUcfirst(template_opt.parentid) + ' → ' +template_opt.templpattitle );

        var htbtnMarkuplibrary = `<a href="#" class="wptemplataimpbtn" data-btnattr='{"templateid":"${template_opt.templpateid}","parentid":"${template_opt.parentid}","templpattitle":"${template_opt.templpattitle}"}'>${HTTM.buttontxt.tmplibrary}</a>`;
        var htbtnMarkuppage = `<a href="#" class="wptemplataimpbtn htwptdisabled" data-btnattr='{"templateid":"${template_opt.templpateid}","parentid":"${template_opt.parentid}","templpattitle":"${template_opt.templpattitle}"}'>${HTTM.buttontxt.tmppage}</a>`;

        // Enter page title then enable button
        $('#htwptpagetitle').on('input', function () {
            if( !$('#htwptpagetitle').val() == '' ){
                $(".htwptimport-button-dynamic-page .wptemplataimpbtn").removeClass('htwptdisabled');
            } else {
                $(".htwptimport-button-dynamic-page .wptemplataimpbtn").addClass('htwptdisabled');
            }
        });

        // button Dynamic content
        $( ".htwptimport-button-dynamic" ).html( htbtnMarkuplibrary );
        $( ".htwptimport-button-dynamic-page" ).html( htbtnMarkuppage );
        $( ".ui-dialog-title" ).html( htwpUcfirst( template_opt.parentid ) + ' &#8594; ' +template_opt.templpattitle );

        $this.addClass( 'updating-message' );
        // call dialog
        function OpenPopup(){
            $( "#htwpt-popup-area" ).dialog({
                modal: true,
                minWidth: 500,
                minHeight:300,
                buttons: {
                    Close: function() {
                      $( this ).dialog( "close" );
                    }
                }
            });
        }

        $.ajax( {
            url: HTTM.ajaxurl,
            type: 'POST',
            data: {
                action: 'htmega_ajax_get_required_plugin',
                freeplugins: template_opt.freePlugins,
                proplugins: template_opt.proPlugins,
                requiredtheme: template_opt.requiredtheme,
            },
            complete: function( data ) {
                $( ".htwptemplata-requiredplugins" ).html( data.responseText );
                OpenPopup();
                $this.removeClass( 'updating-message' );
            }
        });


    });

    // Preview PopUp
    $('body').on( 'click','.htwpt-image-popup img', function(e){
        e.preventDefault();

        var $this = $(this),
            preview_opt = $this.data('preview');

        // dialog header
        $( "#htwpt-popup-prev-"+preview_opt.templpateid ).attr( "title", htwpUcfirst(preview_opt.parentid) + ' → ' + preview_opt.templpattitle );
        $( ".ui-dialog-title" ).html( htwpUcfirst( preview_opt.parentid ) + ' &#8594; ' +preview_opt.templpattitle );

        $( "#htwpt-popup-prev-"+preview_opt.templpateid ).dialog({
            modal: true,
            width: 'auto',
            maxHeight: ( $(window).height()-50 ),
            buttons: {
                Close: function() {
                  $( this ).dialog( "close" );
                }
            }
        });

    });
    // Import data request
    $('body').on('click', 'a.wptemplataimpbtn', function(e) {
        e.preventDefault();

        var $this = $(this),
            pagetitle = ( $('#htwptpagetitle').val() ) ? ( $('#htwptpagetitle').val() ) : '',
            databtnattr = $this.data('btnattr');
        $.ajax({
            url: HTTM.ajaxurl,
            data: {
                'action'       : 'htmega_ajax_request',
                'httemplateid' : databtnattr.templateid,
                'htparentid'   : databtnattr.parentid,
                'httitle'      : databtnattr.templpattitle,
                'pagetitle'    : pagetitle,
            },
            dataType: 'JSON',
            beforeSend: function(){
                $(".htwptspinner").addClass('loading');
                $(".htwptpopupcontent").hide();
            },
            success:function(data) {
                $(".htwptmessage").show();
                var tmediturl = HTTM.adminURL+"post.php?post="+ data.id +"&action=elementor";
                $('.htwpt-edit').html('<a href="'+ tmediturl +'" target="_blank">'+ data.edittxt +'</a>');
            },
            complete:function(data){
                $(".htwptspinner").removeClass('loading');
                $(".htwptmessage").css( "display","block" );
            },
            error: function(errorThrown){
            }
        });

    });

});
