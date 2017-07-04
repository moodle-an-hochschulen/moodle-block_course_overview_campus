/**
 * Block "course overview (campus)" - JS code for filtering courses
 *
 * @package    block_course_overview_campus
 * @copyright  2013 Alexander Bias, Ulm University <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery'], function($) {
    "use strict";

    /**
     * Function to filter the shown courses by term.
     */
    function filterTerm(e) {
        // Prevent the event from refreshing the page.
        if (e !== undefined) {
            e.preventDefault();
        }

        var value = $('#coc-filterterm').val();
        if (value === "all") {
            $('.termdiv').removeClass('coc-hidden');
        } else {
            $('.termdiv').addClass('coc-hidden');
            $('.coc-term-' + value).removeClass('coc-hidden');
        }

        // Store the users selection (Uses AJAX to save to the database).
        M.util.set_user_preference('block_course_overview_campus-selectedterm', value);
    }

    /**
     * Function to filter the shown courses by term teacher.
     */
    function filterTeacher(e) {
        // Prevent the event from refreshing the page.
        if (e !== undefined) {
            e.preventDefault();
        }

        var value = $("#coc-filterteacher").val();
        if (value === "all") {
            $('.teacherdiv').removeClass('coc-hidden');
        } else {
            $('.teacherdiv').addClass('coc-hidden');
            $('.coc-teacher-' + value).removeClass('coc-hidden');
        }

        // Store the users selection (Uses AJAX to save to the database).
        M.util.set_user_preference('block_course_overview_campus-selectedteacher', value);
    }

    /**
     * Function to filter the shown courses by parent category.
     */
    function filterCategory(e) {
        // Prevent the event from refreshing the page.
        if (e !== undefined) {
            e.preventDefault();
        }

        var value = $("#coc-filtercategory").val();
        if (value === "all") {
            $('.categorydiv').removeClass('coc-hidden');
        } else {
            $('.categorydiv').addClass('coc-hidden');
            $('.coc-category-' + value).removeClass('coc-hidden');
        }

        // Store the users selection (Uses AJAX to save to the database).
        M.util.set_user_preference('block_course_overview_campus-selectedcategory', value);
    }

    /**
     * Function to filter the shown courses by top level category.
     */
    function filterTopLevelCategory(e) {
        // Prevent the event from refreshing the page.
        if (e !== undefined) {
            e.preventDefault();
        }

        var value = $("#coc-filtertoplevelcategory").val();
        if (value === "all") {
            $('.toplevelcategorydiv').removeClass('coc-hidden');
        } else {
            $('.toplevelcategorydiv').addClass('coc-hidden');
            $('.coc-toplevelcategory-' + value).removeClass('coc-hidden');
        }

        // Store the users selection (Uses AJAX to save to the database).
        M.util.set_user_preference('block_course_overview_campus-selectedtoplevelcategory', value);
    }

    /**
     * Function to apply all filters again (used when the user has pushed the back button).
     */
    function applyAllFilters(initialSettings) {
        /* eslint-disable max-depth */
        var setting, value, $element, elementValue;
        for (setting in initialSettings) {
            if (initialSettings.hasOwnProperty(setting)) {
                value = initialSettings[setting];
                $element = $('#coc-filter' + setting);
                if ($element.length) {
                    elementValue = $element.val();
                    if (elementValue !== value) {
                        switch (setting) {
                            case 'term':
                                filterTerm();
                                break;
                            case 'teacher':
                                filterTeacher();
                                break;
                            case 'category':
                                filterCategory();
                                break;
                            case 'toplevelcategory':
                                filterTopLevelCategory();
                                break;
                        }
                    }
                }
            }
        }
        /* eslint-enable max-depth */
    }

    /**
     * Function to remember the not shown courses for local_boostcoc.
     */
    function localBoostCOCRememberNotShownCourses() {
        // Get all course nodes which are not shown (= invisible = their height is 0) and store their IDs in an array.
        var notshowncourses = new Array();
        $('.coc-course').each(function(index, element) {
            if ($(element).height() == 0) {
                notshowncourses.push(element.id.slice(11)); // This will remove "coc-course-" from the id's string.
            }
        });

        // Convert not shown courses array to JSON.
        var jsonstring = JSON.stringify(notshowncourses);

        // Store the current status of not shown courses (Uses AJAX to save to the database).
        M.util.set_user_preference('local_boostcoc-notshowncourses', jsonstring);
    }

    /**
     * Function to remember the active filters for local_boostcoc.
     */
    function localBoostCOCRememberActiveFilters() {
        // Get all active filters (value != all) and the fact that hidden courses are present and store them in an array.
        var activefilters = new Array();
        $('#coc-filterterm, #coc-filtercategory, #coc-filtertoplevelcategory, #coc-filterteacher').each(function(index, element) {
            if ($(element).val() !== "all") {
                activefilters.push(element.id.slice(4)); // This will remove "coc-" from the id's string.
            }
        });
        var hiddenCount = parseInt($('#coc-hiddencoursescount').html(), 10);
        if (hiddenCount > 0) {
            activefilters.push('hidecourses');
        }

        // Convert not shown courses array to JSON.
        var jsonstring = JSON.stringify(activefilters);

        // Store the current status of active filters (Uses AJAX to save to the database).
        M.util.set_user_preference('local_boostcoc-activefilters', jsonstring);
    }

    return {
        initFilter: function(params) {
            // Add change listener to filter widgets.
            $('#coc-filterterm').on('change', filterTerm);
            $('#coc-filterteacher').on('change', filterTeacher);
            $('#coc-filtercategory').on('change', filterCategory);
            $('#coc-filtertoplevelcategory').on('change', filterTopLevelCategory);

            // Add change listener to filter widgets for local_boostcoc.
            if (params.local_boostcoc == true) {
                $('#coc-filterterm, #coc-filterteacher, #coc-filtercategory, #coc-filtertoplevelcategory').on('change',
                        localBoostCOCRememberNotShownCourses).on('change', localBoostCOCRememberActiveFilters);
            }

            // Make sure any initial filter settings are applied (may be needed if the user
            // has used the browser 'back' button).
            applyAllFilters(params.initialsettings);
        }
    };
});
