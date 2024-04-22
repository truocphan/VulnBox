window.addEventListener('load', function () {

    var announcements = 'https://stylemixthemes.com/api/announcement.json';

    new Vue({
        el: '#pearl-announcement',
        data: {
            announcements:[]
        },
        mounted: function () {
            this.$http.get(announcements).then(function (response) {
                this.announcements = response.data;
                var title = this.announcements.announcement.title;
                jQuery('#pearl_dashboard_announcement h2 > span').text(title);
            }, function(){
                /*Error given*/
                jQuery('#pearl_dashboard_announcement').slideUp();
            });
        }
    });


    var news = 'https://stylemixthemes.com/wp/wp-json/wp/v2/posts?per_page=5';
    new Vue({
        el: '#pearl-changelog',
        data: {
            news:[]
        },
        mounted: function () {
            this.$http.get(news).then(function (response) {
                this.news = response.data;
            }, function(){
                /*Error given*/
                jQuery('#pearl_dashboard_announcement').slideUp();
            });
        }
    });


});