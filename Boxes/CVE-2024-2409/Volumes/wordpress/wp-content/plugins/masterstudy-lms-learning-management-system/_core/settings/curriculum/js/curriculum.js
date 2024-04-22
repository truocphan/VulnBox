Vue.component('curriculum', {
    props: ['current_course_id'],
    components: [
        'section_items',
        'curriculum_add_item'
    ],
    data() {
        return {
            loaded : true,
            sections: [],
            onDrag : false,
            loading: false,
            search : false,
            addedItems : [],
            searchItems : [],
            course_id : null,
        };
    },
    mounted: function () {
        this.getSavedCurriculum()
    },
    created: function () {
        this.course_id = (typeof stm_lms_manage_course_id !== 'undefined') ? stm_lms_manage_course_id : this.current_course_id;
    },
    methods: {
        startDrag : function() {
            this.onDrag = true;
        },
        endDrag : function() {
            var _this = this;
            _this.onDrag = false;

            _this.sections.forEach(function (section) {
                _this.$set(section, 'hovered', false);
                section.materials.forEach(function (material) {
                    _this.$set(material, 'class', '');
                });
            });
        },
        getSavedCurriculum: function () {
            var vm = this;

            var url = stm_wpcfto_ajaxurl + '?action=stm_lms_get_curriculum_v2&nonce='
                + stm_lms_nonces['stm_lms_get_curriculum_v2']
                + '&course_id='
                + this.course_id;

            vm.isLoading(true);
            this.$http.get(url).then(function (response) {
                vm.$set(vm, 'sections', response.body);
                this.sections.forEach( (section) => {
                    section.title = decodeURIComponent(section.title);
                });
                vm.isLoading(false);
            });

        },
        isLoading(isLoading) {
            this.loading = isLoading;
        },
        addSection() {
            var vm = this;

            this.$http.post(
                `${ms_lms_resturl}/courses/${this.course_id}/curriculum/section`,
                {'title': 'Section'},
                {headers: {'X-WP-Nonce': ms_lms_nonce}}
            ).then(function (response) {
                vm.sections.push({
                    id: response.body.section.id,
                    title: '',
                    order: response.body.section.order,
                    materials: [],
                    opened: true,
                    touched: false,
                    editingSectionTitle: true,
                    activeTab: 'stm-lessons'
                });

                Vue.nextTick(() => {
                    vm.$refs['section_' + (vm.sections.length - 1)][0].focus();
                });
            });
        },
        addSectionTitle(section, section_key) {
            var vm = this;
            let data = {
                'id': section.id,
                'title': section.title,
            };
            this.$http.put(
                `${ms_lms_resturl}/courses/${this.course_id}/curriculum/section`,
                data,
                {headers: {'X-WP-Nonce': ms_lms_nonce}}
            ).then(function () {
                this.$set(section, 'editingSectionTitle', false);
                this.$set(section, 'opened', true);
                this.$set(section, 'touched', true);

                Vue.nextTick(function() {
                    vm.$refs['section_' + section_key][0].blur();
                });
            });
        },
        openSection(section) {
            this.$set(section, 'opened', !section.opened);
        },
        reorderSection(item) {
            let id = null,
                order = null;

            if (typeof item.moved !== 'undefined' && typeof item.moved.element !== 'undefined') {
                id = item.moved.element.id;
                order = item.moved.newIndex;
            }

            if (id) {
                let data = {
                    'id': id,
                    'order': ++order,
                };
                this.$http.put(
                    `${ms_lms_resturl}/courses/${this.course_id}/curriculum/section`,
                    data,
                    {headers: {'X-WP-Nonce': ms_lms_nonce}}
                );
            }
        },
        deleteSection(section_key, section_id, message) {
            if (!confirm(message)) return false;

            this.$http.delete(
                `${ms_lms_resturl}/courses/${this.course_id}/curriculum/section`,
                {headers: {'X-WP-Nonce': ms_lms_nonce}, body: {'id': section_id}},
            ).then(function () {
                this.sections.splice(section_key, 1);
                /*For deep watcher*/
                this.sections = this.sections;
            });
        },
        itemAdded(section, item) {
            let data = {
                'post_id': item.id,
                'section_id': section.id,
            };
            this.$http.post(
                `${ms_lms_resturl}/courses/${this.course_id}/curriculum/material`,
                data,
                {headers: {'X-WP-Nonce': ms_lms_nonce}}
            ).then(function () {
                section['materials'].push(item);
            });
        },
        itemChanged(item) {
            var _this = this;

            if(!item.title) return false;

            var url = stm_wpcfto_ajaxurl + '?action=stm_save_title&nonce=' + stm_lms_nonces['stm_save_title'] + '&title=' + item.title + '&id=' + item.post_id;

            this.$http.get(url);
        },
        itemReordered(item, section_id) {
            let id = null,
                order = null;

            if (typeof item.moved !== 'undefined' && typeof item.moved.element !== 'undefined') {
                id = item.moved.element.id;
                order = item.moved.newIndex;
            }
            if (typeof item.added !== 'undefined' && typeof item.added.element !== 'undefined') {
                id = item.added.element.id;
                order = item.added.newIndex;
            }

            if (id) {
                let data = {
                    'id': id,
                    'section_id': section_id,
                    'order': ++order,
                };
                this.$http.put(
                    `${ms_lms_resturl}/courses/${this.course_id}/curriculum/material`,
                    data,
                    {headers: {'X-WP-Nonce': ms_lms_nonce}}
                );
            }
        },
        emitMethod(item) {
            WPCFTO_EventBus.$emit('STM_LMS_Curriculum_item', item);
        },
    },
    watch: {
        sections: {
            deep: true,
            handler: function () {
                var value = [];
                this.sections.forEach(function (section) {
                    value.push(section.title);
                    section.materials.forEach(function (material) {
                        value.push(material.id);
                        material.title = decodeEntities(material.title);
                    });
                });

                this.$emit('curriculum_changed', value.join(','));
            }
        }
    },
});


Vue.component('section_items', {
    props: ['materials', 'current_course_id'],
    methods : {
        deleteItem(item_key, id, message) {
            if (!confirm(message)) return false;
            let course_id = (typeof stm_lms_manage_course_id !== 'undefined') ? stm_lms_manage_course_id : this.current_course_id;

            this.$http.delete(
                `${ms_lms_resturl}/courses/${course_id}/curriculum/material`,
                {headers: {'X-WP-Nonce': ms_lms_nonce}, body: {'id': id}},
            ).then(function () {
                this.materials.splice(item_key, 1);
            });
        },
    },
});

var decodeEntities = (function() {
    // this prevents any overhead from creating the object each time
    var element = document.createElement('div');

    function decodeHTMLEntities (str) {
        if(str && typeof str === 'string') {
            // strip script/html tags
            str = str.replace(/<script[^>]*>([\S\s]*?)<\/script>/gmi, '');
            str = str.replace(/<\/?\w(?:[^"'>]|"[^"]*"|'[^']*')*>/gmi, '');
            element.innerHTML = str;
            str = element.textContent;
            element.textContent = '';
        }

        return str;
    }

    return decodeHTMLEntities;
})();
