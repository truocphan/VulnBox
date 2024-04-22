"use strict";

(function ($) {
  $(document).ready(function () {
    new Vue({
      el: '#stm-lms-reviews',
      data: function data() {
        return {
          loading: true,
          reviews: [],
          offset: 0,
          total: false
        };
      },
      created: function created() {
        this.getReviews();
      },
      methods: {
        getReviews: function getReviews() {
          var getReviewsUrl = "".concat(stm_lms_ajaxurl, "?action=stm_lms_get_reviews&nonce=").concat(stm_lms_nonces['stm_lms_get_reviews'], "&offset=").concat(this.offset, "&post_id=").concat(stm_lms_post_id);
          this.loading = true;
          this.$http.get(getReviewsUrl).then(function (response) {
            var reviews_temp = [];
            response.body['posts'].forEach(function (review) {
              reviews_temp.push(review);
            });
            this.reviews = reviews_temp;
            this.total = response.body['total'];
            this.loading = false;
            this.offset++;
          });
        }
      }
    });
    new Vue({
      el: '#stm_lms_add_review',
      data: function data() {
        return {
          is_rtl: false,
          loading: false,
          review_text: '',
          openReview: false,
          rating: 0,
          ratingWidth: 75,
          singleRating: 75 / 5,
          message: '',
          status: '',
          ratingClasses: []
        };
      },
      mounted: function mounted() {
        var vm = this;
        Vue.nextTick(function () {
          if ($('body').hasClass('rtl-demo')) {
            vm.is_rtl = true;
          }
        });
      },
      methods: {
        ratingW: function ratingW(rating) {
          var rWidth = rating * 20;
          //if(this.is_rtl) rWidth = 100 - rWidth;
          return rWidth + '%';
        },
        addReview: function addReview() {
          var vm = this;
          if (this.openReview) {
            vm.message = '';
            vm.loading = true;
            var url = stm_lms_ajaxurl + '?action=stm_lms_add_review&nonce=' + stm_lms_nonces['stm_lms_add_review'];
            vm.loading = true;
            vm.$http.post(url, {
              post_id: stm_lms_post_id,
              mark: this.rating,
              review: this.review_text
            }, {
              emulateJSON: true
            }).then(function (response) {
              vm.message = response.body['message'];
              vm.status = response.body['status'];
              vm.loading = false;
              if (response.body['status'] === 'success') vm.openReview = false;
            });
          }
          this.openReview = true;
        },
        ratingHover: function ratingHover($event) {
          this.rating = parseInt($event.offsetX / this.singleRating) + 1;
        }
      }
    });
  });
})(jQuery);