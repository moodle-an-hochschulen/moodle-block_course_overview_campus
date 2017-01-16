/*global define,M*/
define(['jquery'], function ($) {
    "use strict";

    function hideNewsCourse(e) {
        // Prevent the event from refreshing the page
        e.preventDefault();

        $('#coc-coursenews-' + e.data.course).addClass('coc-hidden');
        $('#coc-hidenews-' + e.data.course).addClass('coc-hidden');
        $('#coc-shownews-' + e.data.course).removeClass('coc-hidden');

        M.util.set_user_preference('block_course_overview_campus-hidenews-' + e.data.course, 1);
    }

    function showNews(e) {
        // Prevent the event from refreshing the page
        e.preventDefault();

        $('#coc-coursenews-' + e.data.course).removeClass('coc-hidden');
        $('#coc-hidenews-' + e.data.course).removeClass('coc-hidden');
        $('#coc-shownews-' + e.data.course).addClass('coc-hidden');

        M.util.set_user_preference('block_course_overview_campus-hidenews-' + e.data.course, 0);
    }

    return {
        initHideNews: function (params) {
            var i;

            var courses = params.courses.split(" ");
            for (i = 0; i<courses.length; i++) {
                $('#coc-hidenews-' + courses[i]).on('click', {course: courses[i]}, hideNewsCourse);
                $('#coc-shownews-' + courses[i]).on('click', {course: courses[i]}, showNews);
            }
        }
    };
});