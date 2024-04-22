"use strict";

(function ($) {
  $(document).ready(function () {
    new Vue({
      el: '#stm_lms_lesson_comments',
      data: function data() {
        return {
          loading: false,
          comments: [],
          myComments: [],
          comments_loading: false,
          openCommentForm: false,
          comment_text: '',
          message: '',
          status: '',
          offset: 0,
          has_comments: true,
          search: '',
          reply: [],
          addingComment: [],
          addQuestion: false
        };
      },
      mounted: function mounted() {
        this.getComments();
        this.my_comments();
      },
      methods: {
        getCommentsSearch: function getCommentsSearch() {
          var vm = this;
          vm.offset = 0;
          vm.comments = [];
          vm.getComments();
        },
        getComments: function getComments() {
          var vm = this;
          vm.comments_loading = true;
          var data = {
            'action': 'stm_lms_get_comments',
            'nonce': stm_lms_nonces['stm_lms_get_comments'],
            'post_id': stm_lms_lesson_id,
            'offset': vm.offset,
            'search': vm.search
          };
          var url = stm_lms_ajaxurl + '?' + this.encodeQueryData(data);
          vm.$http.get(url).then(function (response) {
            if (!response.body['posts'].length) vm.has_comments = false;
            response.body['posts'].forEach(function (comment) {
              vm.comments.push(comment);
            });
            vm.comments_loading = false;
            vm.offset++;
          });
        },
        my_comments: function my_comments() {
          var vm = this;
          var data = {
            'action': 'stm_lms_get_comments',
            'post_id': stm_lms_lesson_id,
            'nonce': stm_lms_nonces['stm_lms_get_comments'],
            'user_comments': true
          };
          var url = stm_lms_ajaxurl + '?' + this.encodeQueryData(data);
          vm.$http.get(url).then(function (response) {
            response.body['posts'].forEach(function (comment) {
              vm.myComments.push(comment);
            });
          });
        },
        addComment: function addComment(comment_key) {
          var vm = this;
          var isReply = typeof comment_key !== 'undefined';
          var comment = isReply ? vm.reply[comment_key] : vm.comment_text;
          if (typeof comment === 'undefined') return false;
          if (isReply) vm.$set(vm.addingComment, comment_key, true);
          if (comment.length) {
            vm.message = '';
            vm.loading = true;
            var data = {
              action: 'stm_lms_add_comment',
              nonce: stm_lms_nonces['stm_lms_add_comment'],
              course_id: stm_lms_course_id,
              post_id: stm_lms_lesson_id,
              comment: comment
            };
            if (isReply) data.parent = vm.comments[comment_key]['comment_ID'];
            var url = stm_lms_ajaxurl + '?' + this.encodeQueryData(data);
            vm.$http.get(url).then(function (response) {
              vm.message = response.body['message'];
              vm.status = response.body['status'];
              vm.loading = false;
              if (isReply) vm.addingComment[comment_key] = false;
              if (response.body['status'] === 'success') vm.openReview = false;
              if (response.body['comment']) {
                if (!isReply) {
                  vm.comments.unshift(response.body['comment']);
                  vm.myComments.unshift(response.body['comment']);
                  vm.add_question();
                } else {
                  vm.comments[comment_key]['replies'].unshift(response.body['comment']);
                  vm.reply[comment_key] = '';
                }
              }
              vm.comment_text = '';
              vm.comments[comment_key]['focused'] = false;
            });
          }
        },
        encodeQueryData: function encodeQueryData(data) {
          var ret = [];
          for (var d in data) ret.push(encodeURIComponent(d) + '=' + encodeURIComponent(data[d]));
          return ret.join('&');
        },
        textAreaFocused: function textAreaFocused(comment_key) {
          var $this = this;
          $this.$set($this.comments[comment_key], 'focused', true);
          if (typeof $this.myComments[comment_key] != 'undefined') {
            this.$set($this.myComments[comment_key], 'focused', true);
          }
        },
        textAreaUnFocused: function textAreaUnFocused(comment_key) {
          this.$set(this.comments[comment_key], 'focused', false);
        },
        expandComment: function expandComment(comment_key, my_comments) {
          var source = typeof my_comments === 'undefined' ? this.comments : this.myComments;
          var expanded = typeof source[comment_key]['expanded'] !== 'undefined' ? !source[comment_key]['expanded'] : true;
          this.$set(source[comment_key], 'expanded', expanded);
        },
        add_question: function add_question() {
          this.addQuestion = !this.addQuestion;
        }
      }
    });
  });
})(jQuery);