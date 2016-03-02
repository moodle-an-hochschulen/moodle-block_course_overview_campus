/**
 * Block "course overview (campus)" - YUI code for hiding courses
 *
 * @package    block_course_overview_campus
 * @copyright  2013 Alexander Bias, University of Ulm <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

var HideCourse = function() {
    HideCourse.superclass.constructor.apply(this, arguments);
};
HideCourse.prototype = {
    initializer : function(params) {
        var i;

        var courses = params.courses.split(" ");
        for(i=0; i<courses.length; i++){
            Y.all('#coc-hidecourse-'+courses[i]).on('click', this.hideCourse, this, courses[i], params.editing);
            Y.all('#coc-showcourse-'+courses[i]).on('click', this.showCourse, this, courses[i]);
        }
    },
    hideCourse : function(e, course, editing) {
        // Prevent the event from refreshing the page
        e.preventDefault();

        Y.one('#coc-hidecourse-'+course).addClass('coc-hidden');
        Y.one('#coc-showcourse-'+course).removeClass('coc-hidden');
        if (editing===0) {
            Y.one('#coc-course-'+course).addClass('coc-hidden');
            Y.one('#coc-hiddencoursescount').setContent(parseInt(Y.one('#coc-hiddencoursescount').get("innerHTML"),10)+1);
            Y.one('#coc-hiddencoursesmanagement-bottom').removeClass('coc-hidden');
        }

        M.util.set_user_preference('block_course_overview_campus-hidecourse-'+course, 1);
    },
    showCourse : function(e, course, editing) {
        // Prevent the event from refreshing the page
        e.preventDefault();

        Y.one('#coc-showcourse-'+course).addClass('coc-hidden');
        Y.one('#coc-hidecourse-'+course).removeClass('coc-hidden');
        Y.one('#coc-course-'+course).removeClass('coc-hidden');

        M.util.set_user_preference('block_course_overview_campus-hidecourse-'+course, 0);
    }
};
Y.extend(HideCourse, Y.Base, HideCourse.prototype, {
    NAME : 'Course Overview Campus Hide Course'
});
M.block_course_overview_campus = M.block_course_overview_campus || {};
// Initialisation function
M.block_course_overview_campus.initHideCourse = function(params) {
    return new HideCourse(params);
};
