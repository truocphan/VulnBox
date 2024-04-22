/**
 * Vars appended in php by wp_localize_script
 * @var stm_lms_questions_data - { stm_lms_questions, stm_lms_question_choices, stm_lms_is_allow_create_new_question_category }
 */

Vue.component('stm_questions_v2', {
    props: ['posts', 'stored_ids'],
    components: [],
    data() {
        return {
            allowNewQuestionCategories: false,
            categories: {},
            checkedItem: {},
            choices: {
                'single_choice': 'Single choice',
                'multi_choice': 'Multi choice',
                'true_false': 'True or false',
                'item_match': 'Item Match',
                'image_match': 'Item Match',
                'keywords': 'Keywords',
                'fill_the_gap': 'Fill the Gap',
            },
            isAddQuestionCategory: false,
            isSearch: false,
            items: [],
            itemCategoriesEdit: {},
            loaded: true,
            loading: false,
            mediaLibrary: false,
            options: [],
            question: {
                categories: [],
                title: '',
                type: 'single_choice',
                post_type: 'stm-questions',
            },
            new_question_category: '',
            search: '',
            searching: false,
            searchTimeout: '',
            showQuestionCategories: false,
            tab: 'new_quiz',
            tabs: [''],
            question_bank_title: '',
            question_bank: stm_lms_questions_data.stm_lms_questions,
            question_bank_category: {},
            question_bank_num: 5,
            question_type: 'single_choice',
            question_types: [],
        };
    },
    computed: {
        /** category list for new question **/
        question_categories() {

            if ( ( Object.keys(this.categories).length === 0 && this.categories.constructor === Object ) ) {
                return {};
            }

            var ids = this.question.categories.map(q_category => q_category.id );
            return this.categories.map(category => {
                if ( true === ids.includes(category.id) ){
                    category.show = false;
                }else{
                    category.show = true;
                }
                return category;
            });
        },
    },
    mounted: function () {
        var _this = this;

        if( typeof stm_lms_questions_data.stm_lms_question_choices !== 'undefined' ) {
            _this.$set(_this, 'choices', stm_lms_questions_data.stm_lms_question_choices);
        }

        if( typeof stm_lms_questions_data.stm_lms_is_allow_create_new_question_category !== 'undefined' ) {
            this.allowNewQuestionCategories = Boolean( parseInt( stm_lms_questions_data.stm_lms_is_allow_create_new_question_category ) );
        }

        _this.initComponent();

        if (typeof WPCFTO_EventBus !== 'undefined') {
            WPCFTO_EventBus.$on('STM_LMS_Questions_Update', function (item) {
                _this.stored_ids = item;
                _this.initComponent();
            });
        }
    },
    destroyed() {
        document.removeEventListener('click', this.hideEditCategoryList);
    },
    methods: {
        /** open/close question category drop down, add event listener if opened to close by click outside **/
        updateQuestionCategory( item ){
            this.$set( item, 'is_edit', !item.is_edit );

            if ( true === item.is_edit ){
                document.addEventListener('click', this.hideEditCategoryList);
            }else{
                document.removeEventListener('click', this.hideEditCategoryList);
            }
        },

        /**
         * @param child Html element
         * @param classList array of class names
         * @returns {boolean|*}
         */
        hasParentClass(child, classList){
            for (var i = 0; i < classList.length; i ++ ) {
                if(child.className.split(' ').indexOf(classList[i]) >= 0) return true;
            }
            //Throws TypeError if no parent
            try{
                return child.parentNode && this.hasParentClass(child.parentNode, classList);
            }catch(TypeError){
                return false;
            }
        },

        /** event to hide category list drop down **/
        hideEditCategoryList( event ) {
            if ( false === this.hasParentClass(event.target, ['question_category_input']) ){
                this.items = this.items.map( item => {
                    item.is_edit = false;
                    return item;
                });
                document.removeEventListener('click', this.hideEditCategoryList);
            }
        },

        closeModal() {
            this.mediaLibrary = false
        },

        openMedia(item) {
            this.checkedItem = item
            this.showMediaLibrary();
        },

        checkedImage(image) {
            this.checkedItem.image = {
                id: image.id,
                url: image.url
            }
            this.mediaLibrary = false
        },

        showMediaLibrary() {
            this.mediaLibrary = !this.mediaLibrary
        },

        initComponent() {
            /** clean question data **/
            this.question.categories = [];
            this.question.title = '';

            /** get question categories **/
            this.getTerms('stm_lms_question_taxonomy', 'categories');

            if (this.stored_ids) {
                this.getQuestions( 'items' );
            } else {
                this.items = [];
                this.isLoading(false);
            }
        },

        getQuestions( varaibleKey ){
            var vm   = this;

            if ( !vm.hasOwnProperty( varaibleKey ) ){
                return;
            }

            var data = {
                action: 'stm_lms_questions',
                nonce: stm_lms_nonces['stm_lms_questions'],
                posts_per_page: -1,
                orderby: 'post__in',
                ids: this.stored_ids,
                post_types: this.posts.join(','),
            };

            this.$http.get( stm_wpcfto_ajaxurl, { params:  data } ).then(function (response) {
                if ( 200 == response.status ) {
                    vm[varaibleKey] = response.body;
                }
            });
        },

        /**
         * @param {string} taxonomy
         * @param {string} varaibleKey - data property to append result
         */
        getTerms( taxonomy, varaibleKey ){
            var vm   = this;

            if ( !vm.hasOwnProperty( varaibleKey ) ){
                return;
            }

            var data = {
                action: 'stm_lms_terms',
                nonce: stm_lms_nonces['stm_lms_terms'],
                taxonomy: taxonomy,
            };

            this.$http.get( stm_wpcfto_ajaxurl, { params:  data } ).then(function (response) {
                if ( 200 == response.status && response.body !== null ) {
                    vm[varaibleKey] = response.body;
                }
            });
        },

        /** create new question category **/
        createQuestionCategory() {
            if ( !this.isAddQuestionCategory || !this.allowNewQuestionCategories
                || this.new_question_category.length <= 1 ){
                return;
            }

            var vm   = this;
            var data = {
                action: 'stm_lms_create_term',
                nonce: stm_lms_nonces['stm_lms_create_term'],
                taxonomy: 'stm_lms_question_taxonomy',
                name: vm.new_question_category,
            };

            vm.isLoading(true);

            this.$http.get( stm_wpcfto_ajaxurl, { params:  data } ).then(function (response) {
                if ( 200 == response.status ) {

                    vm.question.categories.push( {id: response.body.term_id, name: response.body.name} );

                    vm.new_question_category  = '';
                    vm.showQuestionCategories = false;
                    vm.categories             = response.body.list;
                    vm.question_bank          = response.body.list;

                    vm.isLoading(false);
                }
            });
        },

        addQuestionCategory( category ) {
            this.question.categories.push( {id: category.id, name: category.name} );
            this.showQuestionCategories = false;
            this.new_question_category  = '';
        },

        /** remove category connection from new question **/
        removeQuestionCategory( category_id ) {
            this.question.categories = this.question.categories.filter(q_category => {
                return q_category.id != category_id;
            });
        },

        /**
         *
         * @param category
         * @param question_id
         * @param update_action (add|remove)
         * @returns {*[]} categories list for question item
         */
        getUpdatedCategories( category, question_id, update_action = 'add' ){
            var question_item  = this.items.find(question => parseInt(question.id) === parseInt(question_id));
            if ( question_item === undefined ){ return; }

            var categories = [...question_item.categories];

            if ( update_action === 'add' ){
                categories.push({term_id: category.id, name: category.name });
            }

            if ( update_action === 'remove' ){
                var category_index = question_item.categories.findIndex(cat => parseInt(cat.term_id) === parseInt(category.term_id));
                if ( category_index === -1 ){ return; }
                categories.splice(category_index, 1);
            }

            return categories;
        },

        getPosts(url, variable) {
            var vm = this;
            vm.isLoading(true);
            this.$http.get(url).then(function (response) {

                vm[variable] = response.body;
                response.body.forEach(function (question) {
                    question.title = decodeEntities(question.title);
                });
                vm.isLoading(false);
            });
        },

        isLoading(isLoading) {
            this.loading = isLoading;
        },

        getQuestionCategoriesNames( questionCategories ) {
            if( typeof questionCategories === 'undefined' ) {
                return '';
            }
            var categories = questionCategories.map(q_category => {
                return q_category.name;
            });
            return categories.join(', ');
        },

        createQuestion() {
            var vm = this;

            if ( vm.question.title === '' ) {
                return false;
            }

            vm.isLoading(true);

            var isFront     = typeof stm_lms_manage_course_id !== 'undefined';
            var categoryIds = vm.question.categories.map(q_category => {
                return q_category.id;
            });

            var data = {
                action: 'stm_curriculum_create_item',
                nonce: stm_lms_nonces['stm_curriculum_create_item'],
                is_front: isFront,
                post_type: vm.question.post_type,
                title: encodeURIComponent(vm.question.title),
                category_ids: categoryIds.join(','),
            };

            this.$http.get( stm_wpcfto_ajaxurl, { params:  data } ).then(function (response) {

                if ( 200 == response.status ) {
                    var item  = response.body;
                    item.type = vm.question.type;
                    this.items.push(response.body);

                    /** clean question **/
                    vm.question.title        = '';
                    vm.question.categories   = [];
                    vm.question.type         = 'single_choice';
                }

                vm.isLoading(false);
            });
        },

        deleteQuestion(item_key, message) {
            if (!confirm(message)) return false;
            this.items.splice(item_key, 1);

            /*For deep watcher*/
            this.items = this.items;
        },
        saveQuestions() {
            var vm = this;

            var $ = jQuery;
            var $publish_button = $('#publishing-action input[name="save"]');

            $publish_button.attr('disabled', 1);

            this.$http.post(stm_wpcfto_ajaxurl + '?action=stm_save_questions&nonce=' + stm_lms_nonces['stm_save_questions'], JSON.stringify(vm.items)).then(function () {
                $publish_button.removeAttr('disabled');
            }, function () {
                $publish_button.removeAttr('disabled');
            });
        },
        updateIds() {
            var vm = this;
            vm.ids = [];
            this.items.forEach(function (value, key) {
                if (typeof value !== 'undefined') {
                    vm.ids.push(value.id);
                }
            });
            vm.$emit('get-questions', vm.ids);
        },
        changeTitle(post_id, title, itemKey) {
            if (isNaN(post_id)) {
                this.items[itemKey]['id'] = title;
                this.updateIds();
            } else {
                this.$http.get(stm_wpcfto_ajaxurl + '?action=stm_save_title&nonce=' + stm_lms_nonces['stm_save_title'] + '&title=' + encodeURIComponent(title) + '&id=' + post_id);
            }
        },
        addToBank(term) {
            var _this = this;

            if (typeof (_this.question_bank_category[term.slug]) === 'undefined') {

                _this.$set(_this.question_bank_category, term.slug, term);

            } else {

                _this.$set(_this.question_bank_category, term.slug);

            }
        },
        addQB() {
            var vm = this;
            if (vm.question_bank_title === '') return false;
            vm.isLoading(true);

            /*Rebuild categories to array without keys*/
            let categories = [];
            Object.keys(vm.question_bank_category).forEach(key => {
                if (typeof vm.question_bank_category[key] !== 'undefined') {
                    categories.push(vm.question_bank_category[key]);
                }
            });

            var data = {
                action: 'stm_curriculum_create_item',
                nonce: stm_lms_nonces['stm_curriculum_create_item'],
                post_type: this.posts.join(','),
                title: vm.question_bank_title,
            };

            this.$http.get( stm_wpcfto_ajaxurl, { params:  data } ).then(function (response) {
                var item  = response.body;
                item.type = 'question_bank';
                var answer = [{
                    'categories': categories,
                    'number': vm.question_bank_num
                }];

                vm.$set(item, 'answers', answer);

                vm.items.push(response.body);
                vm.question_bank_title = '';

                vm.$set(vm, 'question_bank_category', {});
                vm.isLoading(false);
            });
        }
    },
    watch: {
        items: {
            handler: function () {
                var vm = this;

                vm.updateIds();

                clearTimeout(vm.timer);
                vm.timer = setTimeout(function () {
                    vm.saveQuestions();
                }, 500);
            },
            deep: true
        },
        'new_question_category': function ( value ) {
            /** filter categories for autocomplete result **/
            var hidden_count = 0;

            if ( value.length <= 1 ) {
                this.isAddQuestionCategory = false;
                return;
            }

            /** If no question categories , show add btn and return**/
            if ( (Object.keys(this.categories).length === 0 && this.categories.constructor === Object) ) {
                this.isAddQuestionCategory = true;
                return;
            }

            this.question_categories = this.question_categories.map(category => {
                if ( value.length > 0 && category.name.toLowerCase().indexOf( value.toLowerCase() ) <= -1 ) {
                    category.show = false;
                    hidden_count++;
                } else {
                    category.show = true;
                }
                return category;
            });

            /** if no possible value, show icon to add new one **/
            if ( parseInt( this.question_categories.length ) === parseInt( hidden_count ) ) {
                this.isAddQuestionCategory = true;
            }else{
                this.isAddQuestionCategory = false;
            }
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
