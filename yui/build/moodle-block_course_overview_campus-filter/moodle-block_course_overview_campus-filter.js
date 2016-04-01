YUI.add('moodle-block_course_overview_campus-filter', function (Y, NAME) {

/**
 * Block "course overview (campus)" - YUI code for filtering courses
 *
 * @package    block_course_overview_campus
 * @copyright  2013 Alexander Bias, University of Ulm <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

var Filter = function() {
    Filter.superclass.constructor.apply(this, arguments);
};
Filter.prototype = {
    initializer : function() {
        var nodeFilterTerm = Y.one('#coc-filterterm');
        if(nodeFilterTerm !== null) {
            nodeFilterTerm.on('change', this.filterTerm, this);
        }

        var nodeFilterTeacher = Y.one('#coc-filterteacher');
        if(nodeFilterTeacher !== null) {
            nodeFilterTeacher.on('change', this.filterTeacher, this);
        }

        var nodeFilterCategory = Y.one('#coc-filtercategory');
        if(nodeFilterCategory !== null) {
            nodeFilterCategory.on('change', this.filterCategory, this);
        }

        var nodeFilterTopLevelCategory = Y.one('#coc-filtertoplevelcategory');
        if(nodeFilterTopLevelCategory !== null) {
            nodeFilterTopLevelCategory.on('change', this.filterTopLevelCategory, this);
        }
    },
    filterTerm : function(e) {
        // Prevent the event from refreshing the page
        e.preventDefault();
        var index = Y.one("#coc-filterterm").get('selectedIndex');
        var value = Y.one("#coc-filterterm").get("options").item(index).getAttribute('value');
        if(value=="all") {
            Y.all('div.termdiv').removeClass('coc-hidden');
        } else {
            Y.all('div.termdiv').addClass('coc-hidden');
            Y.all('div.coc-term-'+value).removeClass('coc-hidden');
        }
        // Store the users selection (Uses AJAX to save to the database)
        M.util.set_user_preference('block_course_overview_campus-selectedterm', value);
    },
    filterTeacher : function(e) {
        // Prevent the event from refreshing the page
        e.preventDefault();
        var index = Y.one("#coc-filterteacher").get('selectedIndex');
        var value = Y.one("#coc-filterteacher").get("options").item(index).getAttribute('value');
        if(value=="all") {
            Y.all('div.teacherdiv').removeClass('coc-hidden');
        } else {
            Y.all('div.teacherdiv').addClass('coc-hidden');
            Y.all('div.coc-teacher-'+value).removeClass('coc-hidden');
        }
        // Store the users selection (Uses AJAX to save to the database)
        M.util.set_user_preference('block_course_overview_campus-selectedteacher', value);
    },
    filterCategory : function(e) {
        // Prevent the event from refreshing the page
        e.preventDefault();
        var index = Y.one("#coc-filtercategory").get('selectedIndex');
        var value = Y.one("#coc-filtercategory").get("options").item(index).getAttribute('value');
        if(value=="all") {
            Y.all('div.categorydiv').removeClass('coc-hidden');
        } else {
            Y.all('div.categorydiv').addClass('coc-hidden');
            Y.all('div.coc-category-'+value).removeClass('coc-hidden');
        }
        // Store the users selection (Uses AJAX to save to the database)
        M.util.set_user_preference('block_course_overview_campus-selectedcategory', value);
    },
    filterTopLevelCategory : function(e) {
        // Prevent the event from refreshing the page
        e.preventDefault();
        var index = Y.one("#coc-filtertoplevelcategory").get('selectedIndex');
        var value = Y.one("#coc-filtertoplevelcategory").get("options").item(index).getAttribute('value');
        if(value=="all") {
            Y.all('div.toplevelcategorydiv').removeClass('coc-hidden');
        } else {
            Y.all('div.toplevelcategorydiv').addClass('coc-hidden');
            Y.all('div.coc-toplevelcategory-'+value).removeClass('coc-hidden');
        }
        // Store the users selection (Uses AJAX to save to the database)
        M.util.set_user_preference('block_course_overview_campus-selectedtoplevelcategory', value);
    }

};
Y.extend(Filter, Y.Base, Filter.prototype, {
    NAME : 'Course Overview Campus Filter'
});
M.block_course_overview_campus = M.block_course_overview_campus || {};
// Initialisation function
M.block_course_overview_campus.initFilter = function() {
    return new Filter();
};


}, '@VERSION@', {"requires": ["base", "node"]});
