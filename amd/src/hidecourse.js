/*global define,M*/
define(['jquery'], function ($) {
    "use strict";

    function hideCourse(e) {
        var hiddenCount;
        // Prevent the event from refreshing the page
        e.preventDefault();

        $('#coc-hidecourse-' + e.data.course).addClass('coc-hidden');
        $('#coc-showcourse-' + e.data.course).removeClass('coc-hidden');
        if (e.data.editing === 0) {
            $('#coc-course-' + e.data.course).addClass('coc-hidden');
            hiddenCount = parseInt($('#coc-hiddencoursescount').html(), 10);
            $('#coc-hiddencoursescount').html(hiddenCount + 1);
            $('#coc-hiddencoursesmanagement-bottom').removeClass('coc-hidden');
        }

        M.util.set_user_preference('block_course_overview_campus-hidecourse-' + e.data.course, 1);
    }

    function showCourse(e) {
        // Prevent the event from refreshing the page
        e.preventDefault();

        $('#coc-showcourse-' + e.data.course).addClass('coc-hidden');
        $('#coc-hidecourse-' + e.data.course).removeClass('coc-hidden');
        $('#coc-course-' + e.data.course).removeClass('coc-hidden');

        M.util.set_user_preference('block_course_overview_campus-hidecourse-' + e.data.course, 0);
    }

    return {
        initHideCourse: function (params) {
            var i;

            var courses = params.courses.split(" ");
            for (i = 0; i<courses.length; i++) {
                $('#coc-hidecourse-' + courses[i]).on('click', {course: courses[i], editing: params.editing}, hideCourse);
                $('#coc-showcourse-' + courses[i]).on('click', {course: courses[i]}, showCourse);
            }
        }
    };
});

