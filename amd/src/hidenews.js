/**
 * Block "course overview (campus)" - JS code for hiding course news
 *
 * @package    block_course_overview_campus
 * @copyright  2013 Alexander Bias, Ulm University <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/* This comment is just there to keep grunt satisfied and won't be processed at runtime */
/* global define, M */

define(['jquery'], function ($) {
    "use strict";

    function hideNews(e) {
        // Prevent the event from refreshing the page.
        if (e !== undefined) {
            e.preventDefault();
        }

        $('#coc-coursenews-' + e.data.course).addClass('coc-hidden');
        $('#coc-hidenewsicon-' + e.data.course).addClass('coc-hidden');
        $('#coc-shownewsicon-' + e.data.course).removeClass('coc-hidden');

        // Store the course news status (Uses AJAX to save to the database).
        M.util.set_user_preference('block_course_overview_campus-hidenews-' + e.data.course, 1);
    }

    function showNews(e) {
        // Prevent the event from refreshing the page.
        if (e !== undefined) {
            e.preventDefault();
        }

        $('#coc-coursenews-' + e.data.course).removeClass('coc-hidden');
        $('#coc-hidenewsicon-' + e.data.course).removeClass('coc-hidden');
        $('#coc-shownewsicon-' + e.data.course).addClass('coc-hidden');

        // Store the course news status (Uses AJAX to save to the database).
        M.util.set_user_preference('block_course_overview_campus-hidenews-' + e.data.course, 0);
    }

    return {
        initHideNews: function (params) {
            var i;
            var courses = params.courses.split(" ");
            for (i = 0; i < courses.length; i++) {
                // Add change listener to hide course news widgets.
                $('#coc-hidenewsicon-' + courses[i]).on('click', {course: courses[i]}, hideNews);
                // Add change listener to show course news widgets.
                $('#coc-shownewsicon-' + courses[i]).on('click', {course: courses[i]}, showNews);
            }
        }
    };
});
