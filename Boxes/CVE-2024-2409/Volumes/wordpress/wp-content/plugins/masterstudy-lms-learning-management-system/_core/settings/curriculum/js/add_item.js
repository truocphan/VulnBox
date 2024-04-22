Vue.component('curriculum_add_item', {
    props: ['type'],
    data() {
        return {
            itemTitle : '',
            loading: false
        };
    },
    mounted: function () {

    },
    methods: {
        addItem() {
            var vm = this;

            if (this.itemTitle === '') return false;

            vm.loading = true;

            var isFront = typeof stm_lms_manage_course_id !== 'undefined';

            var subrequest = '?action=stm_curriculum_create_item&nonce=' +
                stm_lms_nonces['stm_curriculum_create_item'] +
                '&is_front=' + isFront +
                '&post_type=' + vm.type +
                '&title=' + encodeURIComponent(this.itemTitle);

            var url = stm_wpcfto_ajaxurl + subrequest;
            vm.$http.get(url).then(function (response) {

                vm.itemAdded(response.body);

                this.itemTitle = '';

                vm.loading = false;
            });
        },
        itemAdded(item) {
            this.$emit('curriculum_item_added', item);
        }
    }
});