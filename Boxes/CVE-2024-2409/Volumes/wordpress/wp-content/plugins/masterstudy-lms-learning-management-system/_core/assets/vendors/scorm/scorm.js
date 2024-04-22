var SCORM = pipwerks.SCORM;

var SCORM_API = {
    CourseId: null,
    LastError: 0,
    Initialized: false,
    Data: {},
    LMSProgress: {},
    Initialize: function () {
        this.ModuleRunning = true;
        this.Initialized = true;
        this.LMSProgress = {};
        return true;
    },
    Terminate: function () {
        this.ModuleRunning = false;
        this.Initialized = false;
        this.CourseId = null;
        this.Data = {};
        SCORM.connection.isActive = false;
        if (this.LMSProgress.hasOwnProperty("lesson_status") && this.LMSProgress && this.LMSProgress.hasOwnProperty("completion_status") && this.LMSProgress.hasOwnProperty("success_status")) {
            if (this.LMSProgress.lesson_status == "passed" || (this.LMSProgress.completion_status == "completed" && this.LMSProgress.success_status == "passed")) {
                location.reload(true);
            }
        }
        this.LMSProgress = {};
        return 'true';
    },
    GetValue: function (key, value) {
        this.LastError = 0;
        if (!this.Initialized) {
            this.LastError = scormErrors.GetValueBeforeInit;
            return '';
        }
        if (this.Data[key] !== undefined) {
            return this.Data[key];
        } else {
            return '';
        }
    },
    SetValue: function (key, value) {
        this.LastError = 0;
        if (!this.Initialized) {
            this.LastError = scormErrors.SetValueBeforeInit;
            return '';
        }
        this.Data[key] = value;
        return 'true';
    },
    Commit: function () {
        this.LastError = 0;
        if (this.CourseId) {
            var url = stm_lms_resturl + '/scorm_course_progress/' + this.CourseId;
            var data = this.Data;
            var scorm_api = this;
            jQuery.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data : JSON.stringify(data),
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', stm_lms_vars.wp_rest_nonce);
                },
                success: function(response) {
                    scorm_api.LMSProgress = response;
                }
            });
            return 'true';
        } else {
            return 'false';
        }
    },
    GetLastError: function () {
        var error = this.LastError;
        this.LastError = 0;
        return error;
    },
    GetErrorString: function (error) {
        return 'Error: ' + error;
    },
    GetDiagnostic: function () {
        var message = 'Diagnostic: ' + this.LastError;
        this.LastError = 0;
        return message;
    },
    LMSInitialize: function () {
        return this.Initialize();
    },
    LMSFinish: function () {
        return this.Terminate();
    },
    LMSGetValue: function (key) {
        return this.GetValue(key);
    },
    LMSSetValue: function (key, value) {
        return this.SetValue(key, value);
    },
    LMSCommit: function () {
        return this.Commit();
    },
    LMSGetLastError: function () {
        return this.GetLastError();
    },
    LMSGetErrorString: function () {
        return this.GetErrorString();
    },
    LMSGetDiagnostic: function () {
        return this.GetDiagnostic();
    },
};

var API_1484_11 = null;
var API = null;

async function initLms(CourseId, scorm_version, data_src) {
    var url = stm_lms_resturl + '/scorm_course_progress/' + CourseId;
    await jQuery.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        contentType: 'application/json',
        beforeSend: function(xhr) {
            xhr.setRequestHeader('X-WP-Nonce', stm_lms_vars.wp_rest_nonce);
        },
        success: function(response) {
            SCORM.version = scorm_version;
            if (SCORM.version == "1.2") {
                API_1484_11 = null;
                API = SCORM_API;
            } else {
                API = null;
                API_1484_11 = SCORM_API;
            }

            jQuery('iframe#stm-lms-scorm-iframe').attr('src', data_src);
            var ScormConnected = SCORM.init();
            var ScormApi = SCORM.API.get();
            ScormApi.Data = {};
            ScormApi.CourseId = CourseId;

            if (response.hasOwnProperty('cmi.suspend_data') && response['cmi.suspend_data'] != '') {
                for (var key in response) {
                    if (response[key]) {
                        ScormApi.Data[key] = response[key];
                    }
                }
            }
        }
    });
}


(function ($) {
    if($('#stm-lms-scorm-iframe').length > 0) {
    var CourseId = $('iframe#stm-lms-scorm-iframe').data('course-id');
    scorm_version = $('iframe#stm-lms-scorm-iframe').data('scorm-version').toString();
    data_src = $('iframe#stm-lms-scorm-iframe').data('src');

    initLms(CourseId, scorm_version, data_src);
    }
})(jQuery);