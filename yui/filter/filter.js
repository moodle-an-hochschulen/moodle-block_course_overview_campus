YUI.add('moodle-block_course_overview_campus-filter', function(Y) {

var Filter = function() {
    Filter.superclass.constructor.apply(this, arguments);
};
Filter.prototype = {
    initializer : function() {
        var i;
        var nodeFilterTerm = Y.one('#filterTerm')
        if(nodeFilterTerm != null) {
            nodeFilterTerm.on('change', this.filterTerm, this);
        }

        var nodeFilterTeacher = Y.one('#filterTeacher')
        if(nodeFilterTeacher != null) {
            nodeFilterTeacher.on('change', this.filterTeacher, this);
        }

        var nodeFilterCategory = Y.one('#filterCategory')
        if(nodeFilterCategory != null) {
            nodeFilterCategory.on('change', this.filterCategory, this);
        }
    },
    filterTerm : function(e) {
        // Prevent the event from refreshing the page
        e.preventDefault();
        var index = Y.one("#filterTerm").get('selectedIndex');
        var value = Y.one("#filterTerm").get("options").item(index).getAttribute('value');
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
        var index = Y.one("#filterTeacher").get('selectedIndex');
        var value = Y.one("#filterTeacher").get("options").item(index).getAttribute('value');
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
        var index = Y.one("#filterCategory").get('selectedIndex');
        var value = Y.one("#filterCategory").get("options").item(index).getAttribute('value');
        if(value=="all") {
            Y.all('div.categorydiv').removeClass('coc-hidden');
        } else {
            Y.all('div.categorydiv').addClass('coc-hidden');
            Y.all('div.coc-category-'+value).removeClass('coc-hidden');
        }
        // Store the users selection (Uses AJAX to save to the database)
        M.util.set_user_preference('block_course_overview_campus-selectedcategory', value);
    }

};
Y.extend(Filter, Y.Base, Filter.prototype, {
    NAME : 'Course Overview Campus Filter'
});
M.block_course_overview_campus = M.block_course_overview_campus || {};
// Initialisation function
M.block_course_overview_campus.initFilter = function() {
    return new Filter();
}

}, '@VERSION@', {requires:['base','node']});
