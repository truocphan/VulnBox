(function ($) {

    $('body').addClass('stm-lms-manage-course');

    var stm_lms_i18n = stm_lms_manage_course['i18n'];
    var stm_lms_post_id = stm_lms_manage_course['post_id'];

    $(document).ready(function () {

        var getFields = (stm_lms_post_id) ? stm_lms_manage_course['post_data'] : JSON.parse(localStorage.getItem('manage_course'));

        startApp(getFields);

    });

    function startApp(fieldsSaved) {
        var fields_default = {
            title : '',
            category : '',
            image: '',
            content: '',
            price: '',
            sale_price: '',
            curriculum: '',
            scorm_package: '',
            faq: '',
            announcement: '',
            course_files_pack: '',
            save_as_draft : ''
        };

        var stm_lms_fields = fieldsSaved;
        stm_lms_fields = (stm_lms_fields != null) ? stm_lms_fields : fields_default;

        new Vue({
            el: '#stm_lms_manage_course',
            data: function () {
                return {
                    i18n: stm_lms_i18n,
                    selects : {},
                    fields : stm_lms_fields,
                    loading: false,
                    status: '',
                    message: '',
                    wizard: {},
                    new_category : ''
                }
            },
            mounted: function(){

                Vue.nextTick(function() {
                    $('.stm_lms_manage_course').on('click', function(event){
                        var $this = $(this);
                        $('.stm_lms_manage_course').removeClass('active');
                        $this.addClass('active');
                        setTimeout(function () {
                            $this.find('input:not(#new_category)').focus();
                        }, 100);
                    });
                    $(document).click(function(event) {
                        if(!$(event.target).closest('.stm_lms_manage_course').length) {
                            $('.stm_lms_manage_course').removeClass('active');
                        }
                    });
                });
            },
            methods: {
                getSelectedOption: function(value, options, name) {
                    var _this = this;
                    if(options.hasOwnProperty(value)) {
                        this.$set(this.selects, name, options[value]);
                    }
                },
                saveCourse: function() {
                    var _this = this;

                    if(_this.loading) return false;

                    _this.loading = true;

                    var data = new FormData();

                    data.append('action', 'stm_lms_pro_save_front_course');
                    data.append('nonce', stm_lms_pro_nonces['stm_lms_pro_save_front_course']);

                    Object.keys(_this.fields).map(function(objectKey) {
                        data.append(objectKey, _this.fields[objectKey]);
                    });

                    _this.$http.post(stm_lms_ajaxurl, data).then(function(response){
                        var res = response.body;

                        _this.$set(_this, 'status', res.status);
                        _this.$set(_this, 'message', res.message);

                        _this.loading = false;


                        if(!stm_lms_post_id) {
                            window.localStorage.removeItem("manage_course");
                        }

                        if (res.url) window.location.replace(res.url);

                    });
                },
                add_new_category() {
                    var vm = this;

                    vm.$set(vm.selects, 'category', vm.new_category);
                    vm.$set(vm.fields, 'category', vm.new_category);
                }
            },
            watch: {
                fields: {
                    handler: function(fields) {
                        if(!stm_lms_post_id) {
                            localStorage.setItem('manage_course', JSON.stringify(fields).escapeSpecialChars());
                        }
                    },
                    deep: true
                }
            }
        });
    }

})(jQuery);

String.prototype.escapeSpecialChars = function() {
    return this.replace(/\\n/g, "\\n")
        .replace(/\\'/g, "\\'")
        .replace(/\\"/g, '\\"')
        .replace(/\\&/g, "\\&")
        .replace(/\\r/g, "\\r")
        .replace(/\\t/g, "\\t")
        .replace(/\\b/g, "\\b")
        .replace(/\\f/g, "\\f");
};