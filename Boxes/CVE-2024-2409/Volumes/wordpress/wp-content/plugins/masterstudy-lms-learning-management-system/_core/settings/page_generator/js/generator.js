Vue.component('stm_lms_page_generator', {
    props: ['field_data'],
    components: [],
    data() {
        return {
            loading : false
        };
    },
    mounted: function () {
        //console.log('mounted')
    },
    methods: {
        generatePages() {
            let vm = this;
            if(vm.loading) return false;
            vm.loading = true;
            this.$http.post(stm_wpcfto_ajaxurl + '?action=stm_generate_pages', JSON.stringify(vm.field_data)).then(function (data) {
                location.reload();
                vm.loading = false;
            });
        }
    },
});