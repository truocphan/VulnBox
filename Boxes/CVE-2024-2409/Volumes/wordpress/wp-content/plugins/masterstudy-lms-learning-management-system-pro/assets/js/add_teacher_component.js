Vue.component('stm_lms_add_teacher', {
    props: ['instructor_id'],
    data: function () {
        return {
            users : stm_lms_add_teacher['users'],
            coInstructor: '',
        }
    },
    mounted: function () {
        if(this.instructor_id) {
            this.coInstructor = this.instructor_id;
        }
    },
    template: stm_lms_add_teacher['template'],
    methods: {},
    watch: {
        coInstructor: function (value) {
            value = (typeof value.ID !== 'undefined') ? value.ID : '';
            this.$emit('get_co_instructor', value);
        },

    }
});