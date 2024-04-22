"use strict";

/**
 * @var VueRouter
 * @var stm_lms_components
 */

new Vue({
  router: new VueRouter({
    routes: [{
      path: '',
      component: stm_lms_components['home'],
      redirect: '/courses'
    }, {
      path: '/courses',
      name: 'courses',
      component: stm_lms_components['courses']
    }, {
      path: '/course/:id',
      name: 'course',
      component: stm_lms_components['course'],
      props: true
    }, {
      path: '/course/:id/:user_id',
      name: 'course_user',
      component: stm_lms_components['course_user'],
      props: true
    }],
    scrollBehavior: function scrollBehavior() {
      return {
        x: 0,
        y: 0
      };
    }
  }),
  el: '#stm-lms-dashboard',
  data: {},
  components: {
    navigation: stm_lms_components['navigation']
  },
  mounted: function mounted() {}
});