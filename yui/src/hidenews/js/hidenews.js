/**
 * Block "course overview (campus)" - YUI code for hiding course news
 *
 * @package    block_course_overview_campus
 * @copyright  2013 Alexander Bias, University of Ulm <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

var HideNews = function() {
    HideNews.superclass.constructor.apply(this, arguments);
};
HideNews.prototype = {
    initializer : function(params) {
        var i;

        var courses = params.courses.split(" ");
        for(i=0; i<courses.length; i++){
            Y.all('#coc-hidenews-'+courses[i]).on('click', this.hideNewsCourse, this, courses[i]);
            Y.all('#coc-shownews-'+courses[i]).on('click', this.showNews, this, courses[i]);
        }
    },
    hideNewsCourse : function(e, course) {
        // Prevent the event from refreshing the page
        e.preventDefault();

        Y.one('#coc-coursenews-'+course).addClass('coc-hidden');
        Y.one('#coc-hidenews-'+course).addClass('coc-hidden');
        Y.one('#coc-shownews-'+course).removeClass('coc-hidden');

        M.util.set_user_preference('block_course_overview_campus-hidenews-'+course, 1);
    },
    showNews : function(e, course) {
        // Prevent the event from refreshing the page
        e.preventDefault();

        Y.one('#coc-coursenews-'+course).removeClass('coc-hidden');
        Y.one('#coc-hidenews-'+course).removeClass('coc-hidden');
        Y.one('#coc-shownews-'+course).addClass('coc-hidden');

        M.util.set_user_preference('block_course_overview_campus-hidenews-'+course, 0);
    }
};
Y.extend(HideNews, Y.Base, HideNews.prototype, {
    NAME : 'Course Overview Campus Hide Course News'
});
M.block_course_overview_campus = M.block_course_overview_campus || {};
// Initialisation function
M.block_course_overview_campus.initHideNews = function(params) {
    return new HideNews(params);
};
