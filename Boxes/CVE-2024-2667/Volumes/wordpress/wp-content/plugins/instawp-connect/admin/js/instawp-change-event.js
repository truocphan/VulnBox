jQuery(document).ready(function ($) {

    jQuery('.instawp_select2').select2({
        width: "180px",
    });

    //select2 for default user settings
    jQuery('.instawp_select2_ajax').select2({
        width: "180px",
        ajax: {
            dataType: 'json',
            delay: 100,
            processResults: function (res) {
                const $text = res.data.opt_col.text;
                const $id = res.data.opt_col.id;
                const results = res.data.results.map((element) => {
                    return {
                        text: $text != undefined ? element[$text] : element.text,
                        id: $text != undefined ? element[$id] : element.id,
                    }
                });
                return {
                    results: results
                };
            },
            cache: true
        }
    })

    //Syncing enabled disabled
    jQuery('.instawp_is_event_syncing input[type="checkbox"]').click(function () {
        let sync_status = 0;
        if (jQuery(this).prop("checked") === true) {
            sync_status = 1;
        } else {
            sync_status = 0;
        }
        instawp_is_event_syncing(sync_status);
    });


    //bulk sync btn...
    $(document).on('click', '.bulk-sync-popup-btn', function () {
        const site = $("#staging-site-sync").val();
        if (!site || site === '') {
            alert(instawp_tws.trans.create_staging_site_txt);
            return;
        }

        $('.bulk-sync-popup').show();
        $('.bulk-sync-popup').attr("data-sync-type", "bulk_sync");
        $('.bulk-events-info').show();
        $('.selected-events-info').hide();
        $('.sync_error_success_msg').html('');
        $('#sync_message').val('');

        //progress bar
        //$(".event-progress-text").html('')
        //$(".progress-wrapper").addClass('hidden');
        //$(".event-progress-bar>div").css('width', '0%');

        $("#destination-site").val($("#staging-site-sync").val());
        $(".sync_process .step-1").removeClass('process_inprogress').removeClass('process_complete');
        $(".sync_process .step-2").removeClass('process_inprogress').removeClass('process_complete');
        $(".sync_process .step-3").removeClass('process_inprogress').removeClass('process_complete');
        $(".bulk-sync-btn").html('<a class="changes-btn sync-changes-btn disabled" href="javascript:void(0);"><span>Sync</span></a>');

        get_events_summary();
    });

    $(document).on('click', '.bulk-sync-popup .close', function () {
        $('.bulk-sync-popup').hide();
    });

    //Get Selected sync...
    jQuery('.sync_events_form thead').on('click', 'input[type=checkbox]', function () {
        console.log(jQuery(this).val());
    });

    jQuery('.sync_events_form tbody').on('click', 'input[type=checkbox]', function () {
        getEventsID();
        // var event_slug = jQuery(this).parents('tr').find('.event_slug').text();
        // slug_arr.push(event_slug);
    });

    // $("#select-all-event").click(function(){
    //     $('.single-event-cb:checkbox').not(this).prop('checked', this.checked);
    // });

    display_event_action_dropdown = () => {
        if ($('.single-event-cb:checked').length) {
            $("#instawp-delete-events").removeClass('hidden');
            $(".bulk-sync-popup-btn > span").text('Sync Selected');
        } else {
            $("#instawp-delete-events").addClass('hidden');
            $(".bulk-sync-popup-btn > span").text('Sync All');
        }
    }

    $(document).on('click', '#select-all-event', function (e) {
        $("body").find('.single-event-cb:checkbox').not(this).prop('checked', this.checked);
        display_event_action_dropdown();
    });

    $(document).on('click', '.single-event-cb', function (e) {
        if ($('.single-event-cb:checked').length == $('.single-event-cb').length) {
            $("body").find('#select-all-event').prop('checked', true);
        } else {
            $("body").find('#select-all-event').prop('checked', false);
        }
        display_event_action_dropdown();
    });

    $(document).on('click', 'div#event-sync-pagination a.page-numbers', function (event) {
        event.preventDefault();
        const url = $(this).attr('href');
        const urlParams = new URLSearchParams(url);
        const page = urlParams.get('epage') != null ? urlParams.get('epage') : 1;
        get_site_events(page);
    });

    $(document).on('change', '#staging-site-sync, #filter-sync-events', function () {
        get_site_events();
    });

    $(document).on('click', '#instawp-delete-events', function () {
        const selectedEvents = [];
        $('.single-event-cb:checked').each(function () {
            selectedEvents.push($(this).val());
        });
        if (selectedEvents.length > 0) {
            if (confirm('Are you sure?')) {
                let formData = new FormData();
                let site_id = $("#staging-site-sync").val();
                formData.append('site_id', site_id);
                formData.append('action', 'instawp_delete_events');
                formData.append('ids', selectedEvents);
                baseCall(formData).then((response) => response.json()).then((data) => {
                    get_site_events();
                    display_event_action_dropdown();
                    $("body").find('#select-all-event').prop('checked', false);
                }).catch((error) => {

                });
            }
        }
    });

    $(document).on('click', '.instawp-refresh-events', function () {
        get_site_events();
    });

    // slug_counts = {};
    // jQuery.each(slug_arr, function(key,value) {
    //     if (!slug_counts.hasOwnProperty(value)) {
    //         slug_counts[value] = 1;
    //     } else {
    //         slug_counts[value]++;
    //     }
    // });

    $(document).on('click', '.selected-sync-popup-btn', function () {
        //console.log(slug_counts);
        $('.bulk-sync-popup').show();
        $('.bulk-sync-popup').attr("data-sync-type", "selected_sync");
        $('.bulk-events-info').hide();
        $('.selected-events-info').show();
    });


    //Single sync..
    $(document).on('click', '.btn-single-sync', function () {
        var sync_ids = $(this).attr('data-id');
        var sync_id = $(this).attr('id');
        const dest_connect_id = $("#staging-site-sync").val();
        const sync_message = 'This is a single sync.';
        $(this).attr('disabled', 'disabled');
        $(this).removeClass('two-way-sync-btn').addClass('loading')
        //Initiate Step 2
        packThings(sync_message, 'instawp_single_sync', sync_ids, dest_connect_id);
    });

    const baseCall = async (body) => {
        body.append('security', instawp_tws.security);
        return await fetch(instawp_tws.ajax_url, {
            method: "POST",
            credentials: 'same-origin',
            body
        });
    }

    const get_events_summary = async () => {
        const selectedEvents = [];
        $('.single-event-cb:checked').each(function () {
            selectedEvents.push($(this).val());
        });
        let formData = new FormData();
        formData.append('action', 'instawp_get_events_summary');
        formData.append('connect_id', $("#destination-site").val());
        formData.append('ids', selectedEvents);
        $(".sync-changes-btn").addClass('disabled');
        $("#event-type-list").addClass('instawp-box-loading').html('');
        $('.sync_error_success_msg').html(' ');
        baseCall(formData).then((response) => response.json()).then((response) => {
            $("#event-type-list").removeClass('instawp-box-loading').html(response.data.html);
            if ($('body').find('.event-type-count').length > 3) {
                $('body').find('.event-type-count:gt(2)').hide();
                $('body').find('.event-type-count-show-more').show();
            }
            $(".progress-wrapper").removeClass('hidden');
            $(".event-progress-text").html(response.data.progress_text);

            if (response.data.count == 0) {
                $(".sync-changes-btn").addClass('disabled');
                $('.sync_error_success_msg').html('<p class="error">' + response.data.message + '</p>');
            } else {
                $(".sync-changes-btn").removeClass('disabled');
            }
        }).catch((error) => {
            console.log("Error Occurred: ", error);
        });
    }


    const get_site_events = async (page = 1) => {
        let site_id = $("#staging-site-sync").val();
        let filter_status = $("#filter-sync-events").val();
        let current_page = $("#staging-site-sync").data('page');

        if (current_page == undefined) return;
        let formData = new FormData();
        formData.append('action', 'instawp_get_site_events');
        formData.append('epage', page);
        formData.append('connect_id', site_id);
        formData.append('filter_status', filter_status);

        $("#part-sync-results").html('<tr><td colspan="5" class="event-sync-cell loading"></td></tr>');

        baseCall(formData).then((response) => response.json()).then((data) => {
            $("#part-sync-results").html(data.data.results);
            if (data.data.pagination) {
                $("#event-sync-pagination").html(data.data.pagination);
                $("#event-sync-pagination-area").removeClass('hidden');
            } else {
                $("#event-sync-pagination").html('');
                $("#event-sync-pagination-area").addClass('hidden');
            }
        }).catch((error) => {
            console.log("Error Occurred: ", error);
        });
    }

    get_site_events();

    $(document).on('click', '.load-more-event-type', function () {
        $('body').find('.event-type-count:gt(2)').toggle();
        $(this).text() === 'Show more' ? $(this).text('Show less') : $(this).text('Show more');
    });

    const instawp_is_event_syncing = async (sync_status) => {
        let formData = new FormData(),
            el_sync_recording = $("#wp-toolbar").find('.instawp-sync-recording');

        formData.append('action', 'instawp_is_event_syncing');
        formData.append('sync_status', sync_status);
        baseCall(formData).then((response) => response.json()).then((data) => {
            if (data.sync_status === 1 || data.sync_status === '1') {
                el_sync_recording.addClass('recording-on');
            } else {
                el_sync_recording.removeClass('recording-on');
            }
        }).catch((error) => {
            console.log("Error Occurred: ", error);
        });
    }


    //Bulk&Selected sync process...
    $(document).on('click', '.sync-changes-btn', function () {
        const selectedEvents = [];
        $('.single-event-cb:checked').each(function () {
            selectedEvents.push($(this).val());
        });
        const sync_message = $("#sync_message").val();
        const sync_type = $('.bulk-sync-popup').attr("data-sync-type");
        const dest_connect_id = $("#destination-site").val();
        $(".sync_error_success_msg").html('');

        //Initiate Step 2
        $(this).addClass('disable-a loading');
        let formData = new FormData();
        formData.append('action', 'instawp_calculate_events');
        formData.append('connect_id', dest_connect_id);
        formData.append('ids', selectedEvents);
        baseCall(formData).then((response) => response.json()).then((data) => {
            if (data.success) {
                jQuery("#destination-site").attr("disabled", true)
                $(".progress-wrapper").removeClass('hidden');
                $(".event-progress-text").html(data.data.progress_text)
                packThings(sync_message, sync_type, dest_connect_id, page = 1, selectedEvents);
            } else {
                $(".sync-changes-btn").removeClass('disable-a loading');
                $('.sync_error_success_msg').html('<p class="error">' + data.message + '</p>');
            }

        }).catch((error) => {

        });
    });

    $(document).on('change', '#destination-site', function () {
        get_events_summary();
    });

    const packThings = async (sync_message, sync_type, dest_connect_id, batch_num, ids = []) => {
        let formData = new FormData();
        formData.append('action', 'instawp_pack_events');
        formData.append('sync_type', sync_type);
        formData.append('sync_message', sync_message);
        formData.append('dest_connect_id', dest_connect_id);
        formData.append('page', batch_num);
        formData.append('ids', ids);

        // $('.sync_error_success_msg').html('');
        $(".sync_process .step-1").removeClass('process_pending').addClass('process_inprogress');
        baseCall(formData).then((response) => response.json()).then((data) => {
            if (data.success === true) {
                //Complete Step 1
                $(".sync_process .step-1").removeClass('process_inprogress').addClass('process_complete');
                //Initiate Step 2
                $(".sync_process .step-2").removeClass('process_pending').addClass('process_inprogress');
                bulkSync(sync_message, data.data, sync_type, dest_connect_id, batch_num, ids);
            } else {
                $("#destination-site").attr("disabled", false);
                $(".sync-changes-btn").removeClass('disable-a loading');
                $('.sync_error_success_msg').html('<p class="error">' + data.message + '</p>');
            }
        }).catch((error) => {
            console.log("Error Occurred: ", error);
            $(".sync-changes-btn").removeClass('disable-a loading');
            $("#destination-site").attr("disabled", false);
        });

    }

    const bulkSync = (sync_message, data, sync_type, dest_connect_id, page, ids) => {
        let formData = new FormData();
        formData.append('action', 'instawp_sync_changes');
        formData.append('sync_message', sync_message);
        formData.append('sync_type', sync_type);
        formData.append('dest_connect_id', dest_connect_id);
        formData.append('sync_ids', '');
        formData.append('data', JSON.stringify(data));
        formData.append('page', page);
        formData.append('ids', ids);
        baseCall(formData).then((response) => response.json()).then((data) => {
            if (data.success === true) {
                const paging = data.data;

                $('.sync_error_success_msg').html(' ');
                $(".sync_process .step-2").removeClass('process_inprogress').addClass('process_complete');
                //Initiated Step3

                $(".event-progress-text").html(paging.progress_text)
                $(".event-progress-bar>div").css('width', paging.percent_completed + '%');
                $(".sync_process .step-3").removeClass('process_pending').addClass('process_inprogress');

                if (paging.current_batch < paging.total_batch) {
                    if (0 === (paging.total_completed % 30)) {
                        $('.sync_error_success_msg').html('<p class="warning">Cooling down..</p>');
                        setTimeout(function () {
                            packThings(sync_message, sync_type, dest_connect_id, paging.next_batch, ids);
                        }, 40000);
                    } else {
                        packThings(sync_message, sync_type, dest_connect_id, paging.next_batch, ids);
                    }
                }

                if (paging.percent_completed == 100 && paging.total_batch == paging.current_batch) {
                    $(".sync-changes-btn").removeClass('disable-a loading');
                    $(".sync_process .step-3").removeClass('process_inprogress').addClass('process_complete');
                    $('.bulk-sync-btn').html('<a class="sync-complete" href="javascript:void(0);">Sync Completed</a>');

                    setTimeout(function () {
                        $('.bulk-sync-popup').hide();
                        $(".event-progress-text").html('')
                        $(".progress-wrapper").addClass('hidden');
                        $(".event-progress-bar>div").css('width', '0%');
                        $("#destination-site").attr("disabled", false);
                        $("#staging-site-sync").val($("#destination-site").val());
                        $(".bulk-sync-popup-btn > span").text('Sync All');
                        get_site_events();
                    }, 2000);
                }
            } else {
                $("#destination-site").attr("disabled", false);
                $(".sync-changes-btn").removeClass('disable-a loading');
                $('.sync_error_success_msg').html('<p class="error">' + data.message + '</p>');
            }
        });
    }
});


function getEventsID() {
    var sync_selected_arr = [];
    jQuery(".sync_events_form tbody input[type=checkbox]:checked").each(function () {
        sync_selected_arr.push(jQuery(this).val());
    });
    if (sync_selected_arr.length > 0) {
        jQuery('.selected-sync-popup-btn').show();
    } else {
        jQuery('.selected-sync-popup-btn').hide();
    }
    var sync_selected_str = sync_selected_arr.toString();
    jQuery('#selected_events').val(sync_selected_str);
    var events_info = 'Total selected events: ' + sync_selected_arr.length
    jQuery('.selected-events-info').html(events_info);
}

