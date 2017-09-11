<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Block "course overview (campus)"
 *
 * @package    block_course_overview_campus
 * @copyright  2013 Alexander Bias, Ulm University <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// @codingStandardsIgnoreFile
// Let codechecker ignore this file. This legacy code is not fully compliant to Moodle coding style but working and well documented.

/**
 * Class block_course_overview_campus
 *
 * @package    block_course_overview_campus
 * @copyright  2013 Alexander Bias, Ulm University <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_course_overview_campus extends block_base {

    /**
     * init function
     * @return void
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_course_overview_campus');
    }

    /**
     * specialization function
     * @return void
     */
    public function specialization() {
        $this->title = format_string(get_config('block_course_overview_campus', 'blocktitle'));
    }

    /**
     * applicable_formats function
     * @return array
     */
    public function applicable_formats() {
        return array('my-index' => true, 'my' => true, 'site-index' => true);
    }

    /**
     * has_config function
     * @return bool
     */
    public function has_config() {
        return true;
    }

    /**
     * instance_allow_multiple function
     * @return bool
     */
    public function instance_allow_multiple() {
        return false;
    }

    /**
     * instance_can_be_hidden function
     * @return bool
     */
    public function instance_can_be_hidden() {
        return false;
    }

    /**
     * get_content function
     * @return string
     */
    public function get_content() {
        global $coc_config, $USER, $CFG, $DB, $PAGE, $OUTPUT;


        /********************************************************************************/
        /***                              PREPARE                                     ***/
        /********************************************************************************/

        // Don't run this function twice.
        if ($this->content !== null) {
            return $this->content;
        }

        // Include local library.
        require_once(__DIR__ . '/locallib.php');

        // Get plugin config.
        $coc_config = get_config('block_course_overview_campus');



        /********************************************************************************/
        /***                              PREPROCESSING                               ***/
        /********************************************************************************/

        // Include local library.
        require_once(__DIR__ . '/locallib.php');


        // Check if the configured term dates make sense, if not disable term filter.
        if (!block_course_overview_campus_check_term_config()) {
            $coc_config->termcoursefilter = false;
        }


        // Process GET parameters.
        $param_hidecourse = optional_param('coc-hidecourse', 0, PARAM_BOOL);
        $param_showcourse = optional_param('coc-showcourse', 0, PARAM_BOOL);
        $param_hidenews = optional_param('coc-hidenews', 0, PARAM_BOOL);
        $param_shownews = optional_param('coc-shownews', 0, PARAM_BOOL);
        $param_term = optional_param('coc-term', null, PARAM_ALPHANUMEXT);
        $param_category = optional_param('coc-category', null, PARAM_ALPHANUM);
        $param_toplevelcategory = optional_param('coc-toplevelcategory', null, PARAM_ALPHANUM);
        $param_teacher = optional_param('coc-teacher', null, PARAM_ALPHANUM);
        $param_manage = optional_param('coc-manage', 0, PARAM_BOOL);


        // Set displaying preferences when set by GET parameters.
        if ($coc_config->enablehidecourses) {
            if ($param_hidecourse != 0) {
                set_user_preference('block_course_overview_campus-hidecourse-'.$param_hidecourse, 1);
            }
            if ($param_showcourse != 0) {
                set_user_preference('block_course_overview_campus-hidecourse-'.$param_showcourse, 0);
            }
        }
        if ($coc_config->enablecoursenews) {
            if ($param_hidenews != 0) {
                set_user_preference('block_course_overview_campus-hidenews-'.$param_hidenews, 1);
            }
            if ($param_shownews != 0) {
                set_user_preference('block_course_overview_campus-hidenews-'.$param_shownews, 0);
            }
        }


        // Set and remember term filter if GET parameter is present.
        if ($coc_config->termcoursefilter == true) {
            if ($param_term != null) {
                $selectedterm = $param_term;
                set_user_preference('block_course_overview_campus-selectedterm', $param_term);
            }
            // Or set term filter based on user preference with default term fallback if activated.
            else if ($coc_config->defaultterm == true) {
                $selectedterm = get_user_preferences('block_course_overview_campus-selectedterm', 'currentterm');
            }
            // Or set term filter based on user preference with 'all' terms fallback.
            else {
                $selectedterm = get_user_preferences('block_course_overview_campus-selectedterm', 'all');
            }
        }


        // Set and remember parent category filter if GET parameter is present.
        if ($coc_config->categorycoursefilter == true) {
            if ($param_category != null) {
                $selectedcategory = $param_category;
                set_user_preference('block_course_overview_campus-selectedcategory', $param_category);
            }
            // Or set parent category filter based on user preference with 'all' categories fallback.
            else {
                $selectedcategory = get_user_preferences('block_course_overview_campus-selectedcategory', 'all');
            }
        }


        // Set and remember top level category filter if GET parameter is present.
        if ($coc_config->toplevelcategorycoursefilter == true) {
            if ($param_toplevelcategory != null) {
                $selectedtoplevelcategory = $param_toplevelcategory;
                set_user_preference('block_course_overview_campus-selectedtoplevelcategory', $param_toplevelcategory);
            }
            // Or set top level category filter based on user preference with 'all' categories fallback.
            else {
                $selectedtoplevelcategory = get_user_preferences('block_course_overview_campus-selectedtoplevelcategory', 'all');
            }
        }


        // Set and remember teacher filter if GET parameter is present.
        if ($coc_config->teachercoursefilter == true) {
            if ($param_teacher != null) {
                $selectedteacher = $param_teacher;
                set_user_preference('block_course_overview_campus-selectedteacher', $param_teacher);
            }
            // Or set teacher filter based on user preference with 'all' teachers fallback.
            else {
                $selectedteacher = get_user_preferences('block_course_overview_campus-selectedteacher', 'all');
            }
        }


        // Get my courses.
        $courses = block_course_overview_campus_get_my_courses();



        /********************************************************************************/
        /***                             PROCESS MY COURSES                           ***/
        /********************************************************************************/

        // No, I don't have any courses -> content is only a placeholder message.
        if (empty($courses)) {
            $content = get_string('nocourses', 'block_course_overview_campus');
        }

        // Yes, I have courses.
        else {
            // Start output buffer.
            ob_start();


            // Get course news from my courses for later use.
            if ($coc_config->enablecoursenews && $param_manage == 0) {
                // Get lastaccess of my courses to support course news.
                foreach ($courses as $c) {
                    if (isset($USER->lastcourseaccess[$c->id])) {
                        $courses[$c->id]->lastaccess = $USER->lastcourseaccess[$c->id];
                    } else {
                        $courses[$c->id]->lastaccess = 0;
                    }
                }
                // Get course news html array.
                $coursenews = block_course_overview_campus_get_overviews($courses, $coc_config->skipcoursenews);
            }

            // Get all course categories for later use.
            $coursecategories = $DB->get_records('course_categories');

            // Get teacher roles for later use.
            if (!empty($coc_config->teacherroles)) {
                $teacherroles = explode(',', $coc_config->teacherroles);
            }
            else {
                $teacherroles = array();
            }


            // Create empty filter for activated filters.
            if ($coc_config->termcoursefilter == true) {
                $filterterms = array();
            }
            if ($coc_config->categorycoursefilter == true) {
                $filtercategories = array();
            }
            if ($coc_config->toplevelcategorycoursefilter == true) {
                $filtertoplevelcategories = array();
            }
            if ($coc_config->teachercoursefilter == true) {
                $filterteachers = array();
            }

            // Create counter for hidden courses.
            if ($coc_config->enablehidecourses) {
                $hiddencoursescounter = 0;
            }

            // Create string to remember courses for JS processing.
            $js_courseslist = ' ';

            // Create string to remember course news for JS processing.
            if ($coc_config->enablecoursenews && $param_manage == 0) {
                $js_coursenewslist = ' ';
            }


            // Now iterate over my courses and collect data about them.
            foreach ($courses as $c) {
                // Get course context.
                $context = context_course::instance($c->id);

                // Collect information about my courses and populate filters with data about my courses.
                // Term information.
                if ($coc_config->termcoursefilter == true || $coc_config->secondrowshowtermname == true) {
                    // Create object for bufferung course term information.
                    $courseterm = new stdClass();

                    // If course start date is undefined, set course term to "other".
                    if ($c->startdate == 0) {
                        $courseterm->id = 'other';
                        $courseterm->name = get_string('other', 'block_course_overview_campus');
                    }

                    // If course start date is available, if timeless courses are enabled and if course start date is before.
                    // timeless course threshold, set course term to "timeless".
                    else if ($coc_config->timelesscourses == true && date('Y', $c->startdate) < $coc_config->timelesscoursesthreshold) {
                        $courseterm->id = 'timeless';
                        $courseterm->name = format_string($coc_config->timelesscoursesname);
                    }

                    // If course start date is available, distinguish between term modes.
                    // "Academic year" mode.
                    else if ($coc_config->termmode == 1) {
                        // Prepare date information.
                        $coursestartyday = usergetdate($c->startdate)['yday'];
                        $coursestartyear = usergetdate($c->startdate)['year'];
                        $term1startyday = usergetdate(make_timestamp($coursestartyear, explode('-', $coc_config->term1startday)[0], explode('-', $coc_config->term1startday)[1]))['yday'];

                        // If term starts on January 1st, set course term to course start date's year.
                        if ($coc_config->term1startday == '01-01') {
                            $courseterm->id = $coursestartyear;
                            $courseterm->name = block_course_overview_campus_get_term_displayname($coc_config->term1name, $coursestartyear);
                        }
                        // If term doesn't start on January 1st and course start date's day comes on or after term start day,
                        // set course term to course start date's year + next year.
                        else if ($coursestartyday >= $term1startyday) {
                            $courseterm->id = $coursestartyear.'-'.($coursestartyear + 1);
                            $courseterm->name = block_course_overview_campus_get_term_displayname($coc_config->term1name, $coursestartyear, ($coursestartyear + 1));
                        }
                        // If term doesn't start on January 1st and course start date's day comes before term start day,
                        // set course term to course start date's year + former year.
                        else {
                            $courseterm->id = ($coursestartyear - 1).'-'.$coursestartyear;
                            $courseterm->name = block_course_overview_campus_get_term_displayname($coc_config->term1name, ($coursestartyear - 1), $coursestartyear);
                        }
                    }
                    // "Semester" mode.
                    else if ($coc_config->termmode == 2) {
                        // Prepare date information.
                        $coursestartyday = usergetdate($c->startdate)['yday'];
                        $coursestartyear = usergetdate($c->startdate)['year'];
                        $term1startyday = usergetdate(make_timestamp($coursestartyear, explode('-', $coc_config->term1startday)[0], explode('-', $coc_config->term1startday)[1]))['yday'];
                        $term2startyday = usergetdate(make_timestamp($coursestartyear, explode('-', $coc_config->term2startday)[0], explode('-', $coc_config->term2startday)[1]))['yday'];

                        // If course start date's day comes before first term start day,
                        // set course term to second term of former year.
                        if ($coursestartyday < $term1startyday) {
                            $courseterm->id = ($coursestartyear - 1).'-2';
                            $courseterm->name = block_course_overview_campus_get_term_displayname($coc_config->term2name, ($coursestartyear - 1), $coursestartyear);
                        }
                        // If course start date's day comes on or after first term start day but before second term start day,
                        // set course term to first term of current year.
                        else if ($coursestartyday >= $term1startyday && $coursestartyday < $term2startyday) {
                            $courseterm->id = $coursestartyear.'-1';
                            $courseterm->name = block_course_overview_campus_get_term_displayname($coc_config->term1name, $coursestartyear);
                        }
                        // If course start date's day comes on or after second term start day,
                        // set course term to second term of current year.
                        else {
                            $courseterm->id = $coursestartyear.'-2';
                            // If first term does start on January 1st, suffix name with single year,
                            // otherwise suffix name with double year.
                            if ($coc_config->term1startday == '01-01') {
                                $courseterm->name = block_course_overview_campus_get_term_displayname($coc_config->term2name, $coursestartyear);
                            } else {
                                $courseterm->name = block_course_overview_campus_get_term_displayname($coc_config->term2name, $coursestartyear, ($coursestartyear + 1));
                            }
                        }
                    }
                    // "Tertial" mode.
                    else if ($coc_config->termmode == 3) {
                        // Prepare date information.
                        $coursestartyday = usergetdate($c->startdate)['yday'];
                        $coursestartyear = usergetdate($c->startdate)['year'];
                        $term1startyday = usergetdate(make_timestamp($coursestartyear, explode('-', $coc_config->term1startday)[0], explode('-', $coc_config->term1startday)[1]))['yday'];
                        $term2startyday = usergetdate(make_timestamp($coursestartyear, explode('-', $coc_config->term2startday)[0], explode('-', $coc_config->term2startday)[1]))['yday'];
                        $term3startyday = usergetdate(make_timestamp($coursestartyear, explode('-', $coc_config->term3startday)[0], explode('-', $coc_config->term3startday)[1]))['yday'];

                        // If course start date's day comes before first term start day,
                        // set course term to third term of former year.
                        if ($coursestartyday < $term1startyday) {
                            $courseterm->id = ($coursestartyear - 1).'-3';
                            $courseterm->name = block_course_overview_campus_get_term_displayname($coc_config->term3name, ($coursestartyear - 1), $coursestartyear);
                        }
                        // If course start date's day comes on or after first term start day but before second term start day,
                        // set course term to first term of current year.
                        else if ($coursestartyday >= $term1startyday && $coursestartyday < $term2startyday) {
                            $courseterm->id = $coursestartyear.'-1';
                            $courseterm->name = block_course_overview_campus_get_term_displayname($coc_config->term1name, $coursestartyear);
                        }
                        // If course start date's day comes on or after second term start day but before third term start day,
                        // set course term to second term of current year.
                        else if ($coursestartyday >= $term2startyday && $coursestartyday < $term3startyday) {
                            $courseterm->id = $coursestartyear.'-2';
                            $courseterm->name = block_course_overview_campus_get_term_displayname($coc_config->term2name, $coursestartyear);
                        }
                        // If course start date's day comes on or after third term start day,
                        // set course term to third term of current year.
                        else {
                            $courseterm->id = $coursestartyear.'-3';
                            // If first term does start on January 1st, suffix name with single year,
                            // otherwise suffix name with double year.
                            if ($coc_config->term1startday == '01-01') {
                                $courseterm->name = block_course_overview_campus_get_term_displayname($coc_config->term3name, $coursestartyear);
                            } else {
                                $courseterm->name = block_course_overview_campus_get_term_displayname($coc_config->term3name, $coursestartyear, ($coursestartyear + 1));
                            }
                        }
                    }
                    // "Trimester" mode.
                    else if ($coc_config->termmode == 4) {
                        // Prepare date information.
                        $coursestartyday = usergetdate($c->startdate)['yday'];
                        $coursestartyear = usergetdate($c->startdate)['year'];
                        $term1startyday = usergetdate(make_timestamp($coursestartyear, explode('-', $coc_config->term1startday)[0], explode('-', $coc_config->term1startday)[1]))['yday'];
                        $term2startyday = usergetdate(make_timestamp($coursestartyear, explode('-', $coc_config->term2startday)[0], explode('-', $coc_config->term2startday)[1]))['yday'];
                        $term3startyday = usergetdate(make_timestamp($coursestartyear, explode('-', $coc_config->term3startday)[0], explode('-', $coc_config->term3startday)[1]))['yday'];
                        $term4startyday = usergetdate(make_timestamp($coursestartyear, explode('-', $coc_config->term4startday)[0], explode('-', $coc_config->term4startday)[1]))['yday'];

                        // If course start date's day comes before first term start day,
                        // set course term to fourth term of former year.
                        if ($coursestartyday < $term1startyday) {
                            $courseterm->id = ($coursestartyear - 1).'-4';
                            $courseterm->name = block_course_overview_campus_get_term_displayname($coc_config->term4name, ($coursestartyear - 1), $coursestartyear);
                        }
                        // If course start date's day comes on or after first term start day but before second term start day,
                        // set course term to first term of current year.
                        else if ($coursestartyday >= $term1startyday && $coursestartyday < $term2startyday) {
                            $courseterm->id = $coursestartyear.'-1';
                            $courseterm->name = block_course_overview_campus_get_term_displayname($coc_config->term1name, $coursestartyear);
                        }
                        // If course start date's day comes on or after second term start day but before third term start day,
                        // set course term to second term of current year.
                        else if ($coursestartyday >= $term2startyday && $coursestartyday < $term3startyday) {
                            $courseterm->id = $coursestartyear.'-2';
                            $courseterm->name = block_course_overview_campus_get_term_displayname($coc_config->term2name, $coursestartyear);
                        }
                        // If course start date's day comes on or after third term start day but before fourth term start day,
                        // set course term to third term of current year.
                        else if ($coursestartyday >= $term3startyday && $coursestartyday < $term4startyday) {
                            $courseterm->id = $coursestartyear.'-3';
                            $courseterm->name = block_course_overview_campus_get_term_displayname($coc_config->term3name, $coursestartyear);
                        }
                        // If course start date's day comes on or after fourth term start day,
                        // set course term to fourth term of current year.
                        else {
                            $courseterm->id = $coursestartyear.'-4';
                            // If first term does start on January 1st, suffix name with single year,
                            // otherwise suffix name with double year.
                            if ($coc_config->term1startday == '01-01') {
                                $courseterm->name = block_course_overview_campus_get_term_displayname($coc_config->term4name, $coursestartyear);
                            } else {
                                $courseterm->name = block_course_overview_campus_get_term_displayname($coc_config->term4name, $coursestartyear, ($coursestartyear + 1));
                            }
                        }
                    }
                    // This should never happen.
                    else {
                        print_error('error');
                    }

                    // Remember course term for later use.
                    $c->term = $courseterm->id;
                    $c->termname = format_string($courseterm->name);
                }
                // Term filter.
                if ($coc_config->termcoursefilter == true) {
                    // Add course term to filter list.
                    $filterterms[$courseterm->id] = $courseterm->name;
                }

                // Parent category information.
                if ($coc_config->categorycoursefilter == true ||
                        $coc_config->secondrowshowcategoryname == true ||
                        $coc_config->toplevelcategorycoursefilter == true ||
                        $coc_config->secondrowshowtoplevelcategoryname == true) {
                    // Get course parent category name from array of all category names.
                    $coursecategory = $coursecategories[$c->category];

                    // Remember course parent category name for later use.
                    $c->categoryname = format_string($coursecategory->name);
                    $c->categoryid = $coursecategory->id;

                    // Get course top level category name from array of all category names.
                    $coursecategorypath = explode('/', $coursecategory->path);
                    $coursetoplevelcategoryid = $coursecategorypath[1];
                    $coursetoplevelcategory = $coursecategories[$coursetoplevelcategoryid];

                    // Remember course top level category name for later use.
                    $c->toplevelcategoryname = format_string($coursetoplevelcategory->name);
                    $c->toplevelcategoryid = $coursetoplevelcategory->id;
                }
                // Parent category filter.
                if ($coc_config->categorycoursefilter == true) {
                    // Merge homonymous categories into one category if configured.
                    if ($coc_config->mergehomonymouscategories == true) {
                        // Check if course category name is already present in the category filter array.
                        if ($othercategoryid = array_search($c->categoryname, $filtercategories)) {
                            // If yes and if course category is different than the already present category (same name,
                            // but different id), modify course category id to equal the already present category id
                            // (poor hack, but functional).
                            if ($othercategoryid != $c->categoryid) {
                                $c->categoryid = $othercategoryid;
                            }
                        }
                    }

                    // Add course parent category name to filter list.
                    $filtercategories[$c->categoryid] = $c->categoryname;
                }
                // Top level category filter.
                if ($coc_config->toplevelcategorycoursefilter == true) {
                    // Add course top level category name to filter list.
                    $filtertoplevelcategories[$c->toplevelcategoryid] = $c->toplevelcategoryname;
                }

                // Teacher information.
                if ($coc_config->teachercoursefilter == true || $coc_config->secondrowshowteachername == true) {

                    // Get course teachers based on global teacher roles.
                    if (count($teacherroles) > 0) {

                        // Get all user name fields for SQL query in a proper way.
                        $allnames = get_all_user_name_fields(true, 'u');
                        $teacherfields = 'ra.id AS raid, u.id, '.$allnames.', r.sortorder'; // Moodle would complain about two columns called id with a "Did you remember to make the first column something unique in your call to get_records? Duplicate value 'xxx' found in column 'id'." debug message. That's why we alias one column to a name different than id.
                        $teachersortfields = 'u.lastname, u.firstname';

                        // Check if we have to check for suspended teachers.
                        if ($coc_config->teacherroleshidesuspended == 1) {
                            // Build extra where clause for SQL query.
                            $now = round(time(), -2); // Improves db caching.
                            $extrawhere = 'ue.status = '.ENROL_USER_ACTIVE.' AND e.status = '.ENROL_INSTANCE_ENABLED.' AND ue.timestart < '.$now.' AND (ue.timeend = 0 OR ue.timeend > '.$now.')';
                        } else {
                            $extrawhere = '';
                        }

                        // Check if we have to include teacher roles from parent contexts.
                        // If yes.
                        if ($coc_config->teacherrolesparent == 1) {
                            // If we have to check for suspended teachers.
                            if ($coc_config->teacherroleshidesuspended == 1) {
                                $courseteachers = get_role_users($teacherroles, $context, true,
                                        $teacherfields, $teachersortfields, false, '', '', '', $extrawhere);
                            } else {
                                $courseteachers = get_role_users($teacherroles, $context, true,
                                        $teacherfields, $teachersortfields);
                            }
                        }
                        // If no.
                        else if ($coc_config->teacherrolesparent == 2) {
                            // If we have to check for suspended teachers.
                            if ($coc_config->teacherroleshidesuspended == 1) {
                                $courseteachers = get_role_users($teacherroles, $context, false,
                                        $teacherfields, $teachersortfields, false, '', '', '', $extrawhere);
                            } else {
                                $courseteachers = get_role_users($teacherroles, $context, false,
                                        $teacherfields, $teachersortfields);
                            }
                        }
                        // If depending on moodle/course:reviewotherusers capability.
                        else if ($coc_config->teacherrolesparent == 3) {
                            // If we have to check for suspended teachers.
                            $hasreviewotherscapability = has_capability('moodle/course:reviewotherusers', $context);
                            if ($coc_config->teacherroleshidesuspended == 1) {
                                $courseteachers = get_role_users($teacherroles, $context, $hasreviewotherscapability,
                                        $teacherfields, $teachersortfields, false, '', '', '', $extrawhere);
                            } else {
                                $courseteachers = get_role_users($teacherroles, $context, $hasreviewotherscapability,
                                        $teacherfields, $teachersortfields);
                            }
                        }
                        // Should not happen.
                        else {
                            $courseteachers = get_role_users($teacherroles, $context, true, $teacherfields,
                                    $teachersortfields);
                        }
                    } else {
                        $courseteachers = array();
                    }

                    // The way we use get_role_users(), the teachers array may now contain duplicates as a teacher might have more
                    // than one role in a course and is indexed by ra.id instead of u.id (which we expect later).
                    // We will rewrite the array in reverse order indexed by userid, this way existing teachers will be eliminated
                    // by their own duplicate with higher relevance.
                    $courseteacherstmp = $courseteachers;
                    $courseteachers = [];
                    foreach (array_reverse($courseteacherstmp) as $teacher) {
                        $courseteachers[$teacher->id] = $teacher;
                    }

                    // Remember course teachers for later use.
                    $c->teachers = $courseteachers;
                }
                // Teacher filter.
                if ($coc_config->teachercoursefilter == true) {
                    // Add all course teacher's names to filter list.
                    if ($coc_config->teachercoursefilter == true) {
                        foreach ($courseteachers as $ct) {
                            $filterteachers[$ct->id] = $ct->lastname.', '.$ct->firstname;
                        }
                    }
                }


                // Check if this course is hidden according to the hide courses feature.
                if ($coc_config->enablehidecourses == true) {
                    $courses[$c->id]->hidecourse = block_course_overview_campus_course_hidden_by_hidecourses($c);
                    // Increase counter for hidden courses management.
                    if ($courses[$c->id]->hidecourse == true) {
                        $hiddencoursescounter++;
                    }
                }

                // Check if this course is hidden according to the term course filter.
                if ($coc_config->termcoursefilter == true) {
                    $courses[$c->id]->termcoursefiltered = block_course_overview_campus_course_hidden_by_termcoursefilter($c, $selectedterm);
                }

                // Check if this course is hidden according to the parent category course filter.
                if ($coc_config->categorycoursefilter == true) {
                    $courses[$c->id]->categorycoursefiltered = block_course_overview_campus_course_hidden_by_categorycoursefilter($c, $selectedcategory);
                }

                // Check if this course is hidden according to the top level category course filter.
                if ($coc_config->toplevelcategorycoursefilter == true) {
                    $courses[$c->id]->toplevelcategorycoursefiltered = block_course_overview_campus_course_hidden_by_toplevelcategorycoursefilter($c, $selectedtoplevelcategory);
                }

                // Check if this course is hidden according to the teacher course filter.
                if ($coc_config->teachercoursefilter == true) {
                    $courses[$c->id]->teachercoursefiltered = block_course_overview_campus_course_hidden_by_teachercoursefilter($c, $selectedteacher);
                }

                // Check if this course should show news or not.
                if ($coc_config->enablecoursenews == true && $param_manage == 0) {
                    $courses[$c->id]->hidenews = block_course_overview_campus_coursenews_hidden($c);
                }


                // Re-sort courses to list courses in which I have a teacher role first if configured:
                // First step: Removing the courses.
                if ($coc_config->prioritizemyteachedcourses) {
                    // Check if user is teacher in this course.
                    if (array_key_exists($USER->id, $courseteachers)) {
                        // Remember the course.
                        $myteachercourses[] = $c;
                        // Remove the course from the courses array.
                        unset($courses[$c->id]);
                    }
                }
            }


            // Re-sort courses to list courses in which I have a teacher role first if configured:
            // Last step: Adding the courses again.
            if ($coc_config->prioritizemyteachedcourses && isset ($myteachercourses) && count($myteachercourses) > 0) {
                // Add the courses again at the beginning of the courses array.
                $courses = $myteachercourses + $courses;
            }


            // Replace and remember currentterm placeholder with precise term based on my courses.
            if ($coc_config->termcoursefilter == true && $selectedterm == 'currentterm') {
                // Distinguish between term modes.
                // "Academic year" mode.
                if ($coc_config->termmode == '1') {
                    // If term starts on January 1st and there are courses this year,
                    // set selected term to this year.
                    if ($coc_config->term1startday == '1' && isset($filterterms[date('Y')])) {
                        $selectedterm = date('Y');
                    }
                    // If term doesn't start on January 1st and current day comes on or after term start day and there are courses
                    // this term, set selected term to this year + next year.
                    else if (intval(date('z')) >= intval(date('z', strtotime(date('Y', $c->startdate).'-'.$coc_config->term1startday))) &&
                            isset($filterterms[date('Y').'-'.(date('Y') + 1)])) {
                        $selectedterm = date('Y').'-'.(date('Y') + 1);
                    }
                    // If term doesn't start on January 1st and current day comes before term start day and there are courses
                    // this term, set selected term to this year + former year.
                    else if (isset($filterterms[(date('Y') - 1).'-'.date('Y')])) {
                        $selectedterm = (date('Y') - 1).'-'.date('Y');
                    }
                    // Otherwise set selected term to the latest (but not future) term possible.
                    else {
                        $selectedterm = 'all';
                        arsort($filterterms);
                        foreach ($filterterms as $t) {
                            if ($t != 'other' && $t != 'timeless' && intval(substr($t, 0, 4)) <= intval(date('Y'))) {
                                $selectedterm = $t;
                                break;
                            }
                        }
                    }
                }
                // "Semester" mode.
                else if ($coc_config->termmode == '2') {
                    // If current day comes before first term start day and there are courses this term,
                    // set selected term to second term of former year.
                    if (intval(date('z')) < intval(date('z', strtotime(date('Y', $c->startdate).'-'.$coc_config->term1startday))) && isset($filterterms[(date('Y') - 1).'-2'])) {
                        $selectedterm = (date('Y') - 1).'-2';
                    }
                    // If current day comes on or after first term start day but before second term start day and there are courses
                    // this term, set selected term to first term of current year.
                    else if (intval(date('z', $c->startdate)) >= intval(date('z', strtotime(date('Y', $c->startdate).'-'.$coc_config->term1startday))) &&
                            intval(date('z', $c->startdate)) < intval(date('z', strtotime(date('Y', $c->startdate).'-'.$coc_config->term2startday))) &&
                            isset($filterterms[date('Y').'-1'])) {
                        $selectedterm = date('Y').'-1';
                    }
                    // If course start date's day comes on or after second term start day and there are courses this term,
                    // set selected term to second term of current year.
                    else if (isset($filterterms[date('Y').'-2'])) {
                        $selectedterm = date('Y').'-2';
                    }
                    // Otherwise set selected term to the latest (but not future) term possible.
                    else {
                        $selectedterm = 'all';
                        krsort($filterterms);
                        foreach ($filterterms as $t => $n) {
                            if ($t != 'other' && $t != 'timeless' && intval(substr($t, 0, 4)) <= intval(date('Y'))) {
                                $selectedterm = $t;
                                break;
                            }
                        }
                    }
                }
                // "Tertial" mode.
                else if ($coc_config->termmode == '3') {
                    // If current day comes before first term start day and there are courses this term,
                    // set selected term to third term of former year.
                    if (intval(date('z')) < intval(date('z', strtotime(date('Y', $c->startdate).'-'.$coc_config->term1startday))) &&
                            isset($filterterms[(date('Y') - 1).'-3'])) {
                        $selectedterm = (date('Y') - 1).'-2';
                    }
                    // If current day comes on or after first term start day but before second term start day and there are courses
                    // this term, set selected term to first term of current year.
                    else if (intval(date('z', $c->startdate)) >= intval(date('z', strtotime(date('Y', $c->startdate).'-'.$coc_config->term1startday))) &&
                            intval(date('z', $c->startdate)) < intval(date('z', strtotime(date('Y', $c->startdate).'-'.$coc_config->term2startday))) &&
                            isset($filterterms[date('Y').'-1'])) {
                        $selectedterm = date('Y').'-1';
                    }
                    // If current day comes on or after second term start day but before third term start day and there are courses
                    // this term, set selected term to second term of current year.
                    else if (intval(date('z', $c->startdate)) >= intval(date('z', strtotime(date('Y', $c->startdate).'-'.$coc_config->term2startday))) &&
                            intval(date('z', $c->startdate)) < intval(date('z', strtotime(date('Y', $c->startdate).'-'.$coc_config->term3startday))) &&
                            isset($filterterms[date('Y').'-2'])) {
                        $selectedterm = date('Y').'-2';
                    }
                    // If course start date's day comes on or after third term start day and there are courses this term,
                    // set selected term to third term of current year.
                    else if (isset($filterterms[date('Y').'-3'])) {
                        $selectedterm = date('Y').'-3';
                    }
                    // Otherwise set selected term to the latest (but not future) term possible.
                    else {
                        $selectedterm = 'all';
                        krsort($filterterms);
                        foreach ($filterterms as $t => $n) {
                            if ($t != 'other' && $t != 'timeless' && intval(substr($t, 0, 4)) <= intval(date('Y'))) {
                                $selectedterm = $t;
                                break;
                            }
                        }
                    }
                }
                // "Trimester" mode.
                else if ($coc_config->termmode == '4') {
                    // If current day comes before first term start day and there are courses this term,
                    // set selected term to fourth term of former year.
                    if (intval(date('z')) < intval(date('z', strtotime(date('Y', $c->startdate).'-'.$coc_config->term1startday))) &&
                            isset($filterterms[(date('Y') - 1).'-4'])) {
                        $selectedterm = (date('Y') - 1).'-2';
                    }
                    // If current day comes on or after first term start day but before second term start day and there are courses
                    // this term, set selected term to first term of current year.
                    else if (intval(date('z', $c->startdate)) >= intval(date('z', strtotime(date('Y', $c->startdate).'-'.$coc_config->term1startday))) &&
                            intval(date('z', $c->startdate)) < intval(date('z', strtotime(date('Y', $c->startdate).'-'.$coc_config->term2startday))) &&
                            isset($filterterms[date('Y').'-1'])) {
                        $selectedterm = date('Y').'-1';
                    }
                    // If current day comes on or after second term start day but before third term start day and there are courses
                    // this term, set selected term to second term of current year.
                    else if (intval(date('z', $c->startdate)) >= intval(date('z', strtotime(date('Y', $c->startdate).'-'.$coc_config->term2startday))) &&
                            intval(date('z', $c->startdate)) < intval(date('z', strtotime(date('Y', $c->startdate).'-'.$coc_config->term3startday))) &&
                            isset($filterterms[date('Y').'-2'])) {
                        $selectedterm = date('Y').'-2';
                    }
                    // If current day comes on or after third term start day but before fourth term start day and there are courses
                    // this term, set selected term to third term of current year.
                    else if (intval(date('z', $c->startdate)) >= intval(date('z', strtotime(date('Y', $c->startdate).'-'.$coc_config->term3startday))) &&
                            intval(date('z', $c->startdate)) < intval(date('z', strtotime(date('Y', $c->startdate).'-'.$coc_config->term4startday))) &&
                            isset($filterterms[date('Y').'-3'])) {
                        $selectedterm = date('Y').'-3';
                    }
                    // If course start date's day comes on or after fourth term start day and there are courses this term,
                    // set selected term to fourth term of current year.
                    else if (isset($filterterms[date('Y').'-4'])) {
                        $selectedterm = date('Y').'-4';
                    }
                    // Otherwise set selected term to the latest (but not future) term possible.
                    else {
                        $selectedterm = 'all';
                        krsort($filterterms);
                        foreach ($filterterms as $t => $n) {
                            if ($t != 'other' && $t != 'timeless' && intval(substr($t, 0, 4)) <= intval(date('Y'))) {
                                $selectedterm = $t;
                                break;
                            }
                        }
                    }
                }
                // This should never happen.
                else {
                    print_error('error');
                }

                // Remember selected term.
                set_user_preference('block_course_overview_campus-selectedterm', $selectedterm);
            }



            /********************************************************************************/
            /***                        GENERATE OUTPUT FOR FILTER                        ***/
            /********************************************************************************/

            // Show filter form if any filter is activated and if hidden courses management isn't active.
            if ((!$coc_config->enablehidecourses || $param_manage == 0) && ($coc_config->categorycoursefilter == true || $coc_config->toplevelcategorycoursefilter == true || $coc_config->termcoursefilter == true || $coc_config->teachercoursefilter == true)) {
                // Calculate CSS class for filter divs.
                $filtercount = 0;
                if ($coc_config->termcoursefilter == true) {
                    $filtercount++;
                }
                if ($coc_config->teachercoursefilter == true) {
                    $filtercount++;
                }
                if ($coc_config->categorycoursefilter == true) {
                    $filtercount++;
                }
                if ($coc_config->toplevelcategorycoursefilter == true) {
                    $filtercount++;
                }
                if ($filtercount == 1) {
                    $filterwidth = 'span12 col-md-12'; // Class 'span12' is used for Bootstrapbase and will be ignored by Boost.
                } else if ($filtercount == 2) {
                    $filterwidth = 'span6 col-md-6'; // Class 'span6' is used for Bootstrapbase and will be ignored by Boost.
                } else if ($filtercount == 3) {
                    $filterwidth = 'span4 col-md-4'; // Class 'span4' is used for Bootstrapbase and will be ignored by Boost.
                } else if ($filtercount == 4) {
                    $filterwidth = 'span3 col-md-6 col-lg-3'; // Class 'span3' is used for Bootstrapbase and will be ignored by Boost.
                } else {
                    $filterwidth = 'span12 col-md-12'; // Class 'span12' is used for Bootstrapbase and will be ignored by Boost.
                }

                // Start form.
                echo '<form method="post" action="">';

                // Start section.
                echo '<div id="coc-filterlist" class="container-fluid"><div class="row">';

                // Show term filter.
                if ($coc_config->termcoursefilter == true) {
                    echo '<div class="coc-filter '.$filterwidth.' m-b-1">';

                    // Show filter description.
                    if ($coc_config->termcoursefilterdisplayname != '') {
                        echo '<label for="coc-filterterm">'.format_string($coc_config->termcoursefilterdisplayname).'</label>';
                    }

                    // Show filter widget.
                    echo '<select name="coc-term" id="coc-filterterm" class="input-block-level form-control">'; // Class 'input-block-level' is used for Bootstrapbase and will be ignored by Boost.

                    // Remember in this variable if selected term was displayed or not.
                    $selectedtermdisplayed = false;

                    // Sort term filter alphabetically in reverse order.
                    krsort($filterterms);

                    // Print "All terms" option.
                    if ($selectedterm == 'all') {
                        echo '<option value="all" selected>'.get_string('all', 'block_course_overview_campus').'</option> ';
                        $selectedtermdisplayed = true;
                    } else {
                        echo '<option value="all">'.get_string('all', 'block_course_overview_campus').'</option> ';
                    }

                    // Print each term in filter as an option item and select selected term.
                    foreach ($filterterms as $t => $n) {
                        // If iterated term is selected term.
                        if ($selectedterm == $t) {
                            // Handle "other" term option.
                            if ($selectedterm == 'other') {
                                echo '<option selected value="other">'.get_string('other', 'block_course_overview_campus').'</option> ';
                                $selectedtermdisplayed = true;
                            }
                            // Handle "timeless" term option.
                            else if ($selectedterm == 'timeless') {
                                echo '<option selected value="timeless">'.format_string($coc_config->timelesscoursesname).'</option> ';
                                $selectedtermdisplayed = true;
                            } else {
                                echo '<option selected value="'.$t.'">'.format_string($n).'</option> ';
                                $selectedtermdisplayed = true;
                            }
                        }
                        // If iterated term isn't selected term.
                        else {
                            // Handle "other" term option.
                            if ($t == 'other') {
                                echo '<option value="other">'.get_string('other', 'block_course_overview_campus').'</option> ';
                            }
                            // Handle "timeless" term option.
                            else if ($t == 'timeless') {
                                echo '<option value="timeless">'.format_string($coc_config->timelesscoursesname).'</option> ';
                            } else {
                                echo '<option value="'.$t.'">'.format_string($n).'</option> ';
                            }
                        }
                    }

                    echo '</select>';

                    // If selected term couldn't be displayed, select all terms and save the new selection.
                    // In this case, no option item is marked as selected, but that's ok as the "all" item is at the top.
                    if (!$selectedtermdisplayed) {
                        $selectedterm = 'all';
                        set_user_preference('block_course_overview_campus-selectedterm', $selectedterm);
                    }

                    echo '</div>';
                }

                // Show top level category filter.
                if ($coc_config->toplevelcategorycoursefilter == true) {
                    echo '<div class="coc-filter '.$filterwidth.' m-b-1">';

                    // Show filter description.
                    if ($coc_config->toplevelcategorycoursefilterdisplayname != '') {
                        echo '<label for="coc-filtertoplevelcategory">'.format_string($coc_config->toplevelcategorycoursefilterdisplayname).'</label>';
                    }

                    // Show filter widget.
                    echo '<select name="coc-toplevelcategory" id="coc-filtertoplevelcategory" class="input-block-level form-control">'; // class 'input-block-level' is used for Bootstrapbase and will be ignored by Boost.

                    // Remember in this variable if selected top level category was displayed or not.
                    $selectedtoplevelcategorydisplayed = false;

                    // Sort top level category filter by category sort order.
                    // Create empty array for sorted categories.
                    $filtertoplevelcategoriessortorder = array();
                    // Fetch full category information for each category.
                    foreach ($filtertoplevelcategories as $ftl_key => $ftl_value) {
                        $filtertoplevelcategoriesfullinfo[] = $coursecategories[$ftl_key];
                    }
                    // Sort full category information array by sortorder.
                    $success = usort($filtertoplevelcategoriesfullinfo, "block_course_overview_campus_compare_categories");
                    // If sorting was successful, create new array with same data structure like the old one.
                    // Otherwise just leave the old array as it is (should not happen).
                    if ($success) {
                        $filtertoplevelcategories = array();
                        foreach ($filtertoplevelcategoriesfullinfo as $ftl) {
                            $filtertoplevelcategories[$ftl->id] = format_string($ftl->name);
                        }
                    }

                    // Print "All categories" option.
                    if ($selectedtoplevelcategory == 'all') {
                        echo '<option value="all" selected>'.get_string('all', 'block_course_overview_campus').'</option> ';
                        $selectedtoplevelcategorydisplayed = true;
                    } else {
                        echo '<option value="all">'.get_string('all', 'block_course_overview_campus').'</option> ';
                    }

                    // Print each top level category in filter as an option item and select selected top level category.
                    foreach ($filtertoplevelcategories as $value => $cat) {
                        // If iterated top level category is selected top level category.
                        if ($selectedtoplevelcategory == $value) {
                            echo '<option selected value="'.$value.'">'.$cat.'</option> ';
                            $selectedtoplevelcategorydisplayed = true;
                        }
                        // If iterated top level category isn't selected top level category.
                        else {
                            echo '<option value="'.$value.'">'.$cat.'</option> ';
                        }
                    }

                    echo '</select>';

                    // If selected top level category couldn't be displayed, select all categories and save the new selection.
                    // In this case, no option item is marked as selected, but that's ok as the "all" item is at the top.
                    if (!$selectedtoplevelcategorydisplayed) {
                        $selectedtoplevelcategory = 'all';
                        set_user_preference('block_course_overview_campus-selectedtoplevelcategory', $selectedtoplevelcategory);
                    }

                    echo '</div>';
                }

                // Show parent category filter.
                if ($coc_config->categorycoursefilter == true) {
                    echo '<div class="coc-filter '.$filterwidth.' m-b-1">';

                    // Show filter description.
                    if ($coc_config->categorycoursefilterdisplayname != '') {
                        echo '<label for="coc-filtercategory">'.format_string($coc_config->categorycoursefilterdisplayname).'</label>';
                    }

                    // Show filter widget.
                    echo '<select name="coc-category" id="coc-filtercategory" class="input-block-level form-control">'; // Class 'input-block-level' is used for Bootstrapbase and will be ignored by Boost.

                    // Remember in this variable if selected parent category was displayed or not.
                    $selectedcategorydisplayed = false;

                    // Sort parent category filter alphabetically.
                    natcasesort($filtercategories);

                    // Print "All categories" option.
                    if ($selectedcategory == 'all') {
                        echo '<option value="all" selected>'.get_string('all', 'block_course_overview_campus').'</option> ';
                        $selectedcategorydisplayed = true;
                    } else {
                        echo '<option value="all">'.get_string('all', 'block_course_overview_campus').'</option> ';
                    }

                    // Print each parent category in filter as an option item and select selected parent category.
                    foreach ($filtercategories as $value => $cat) {
                        // If iterated parent category is selected parent category.
                        if ($selectedcategory == $value) {
                            echo '<option selected value="'.$value.'">'.$cat.'</option> ';
                            $selectedcategorydisplayed = true;
                        }
                        // If iterated parent category isn't selected parent category.
                        else {
                            echo '<option value="'.$value.'">'.$cat.'</option> ';
                        }
                    }

                    echo '</select>';

                    // If selected parent category couldn't be displayed, select all categories and save the new selection.
                    // In this case, no option item is marked as selected, but that's ok as the "all" item is at the top.
                    if (!$selectedcategorydisplayed) {
                        $selectedcategory = 'all';
                        set_user_preference('block_course_overview_campus-selectedcategory', $selectedcategory);
                    }

                    echo '</div>';
                }

                // Show teacher filter.
                if ($coc_config->teachercoursefilter == true) {
                    echo '<div class="coc-filter '.$filterwidth.' m-b-1">';

                    // Show filter description.
                    if ($coc_config->teachercoursefilterdisplayname != '') {
                        echo '<label for="coc-filterteacher">'.format_string($coc_config->teachercoursefilterdisplayname).'</label>';
                    }

                    // Show filter widget.
                    echo '<select name="coc-teacher" id="coc-filterteacher" class="input-block-level form-control">'; // Class 'input-block-level' is used for Bootstrapbase and will be ignored by Boost.

                    // Remember in this variable if selected teacher was displayed or not.
                    $selectedteacherdisplayed = false;

                    // Sort teacher filter alphabetically.
                    natcasesort($filterteachers);

                    // Print "All teachers" option.
                    if ($selectedteacher == 'all') {
                        echo '<option value="all" selected>'.get_string('all', 'block_course_overview_campus').'</option> ';
                        $selectedteacherdisplayed = true;
                    } else {
                        echo '<option value="all">'.get_string('all', 'block_course_overview_campus').'</option> ';
                    }

                    // Print each teacher in filter as an option item and select selected teacher.
                    foreach ($filterteachers as $id => $t) {
                        // If iterated teacher is selected teacher.
                        if ($selectedteacher == $id) {
                            echo '<option selected value="'.$id.'">'.$t.'</option> ';
                            $selectedteacherdisplayed = true;
                        } else {
                            echo '<option value="'.$id.'">'.$t.'</option> ';
                        }
                    }

                    echo '</select>';

                    // If selected teacher couldn't be displayed, select all teachers and save the new selection.
                    // In this case, no option item is marked as selected, but that's ok as the "all" item is at the top.
                    if (!$selectedteacherdisplayed) {
                        $selectedteacher = 'all';
                        set_user_preference('block_course_overview_campus-selectedteacher', $selectedteacher);
                    }

                    echo '</div>';
                }

                // End section.
                echo '</div></div>';

                // Show submit button for Non-JavaScript interaction.
                echo '<div id="coc-filtersubmit" class="container-fluid m-b-1"><div class="row"><input type="submit" value="'.get_string('submitfilter', 'block_course_overview_campus').'" class="btn btn-primary" /></div></div>';

                // End form.
                echo '</form>';
            }



            /********************************************************************************/
            /***            GENERATE OUTPUT FOR HIDDEN COURSES MANAGEMENT TOP BOX         ***/
            /********************************************************************************/

            // Do only if course hiding is enabled.
            if ($coc_config->enablehidecourses) {
                // If hidden courses managing is active, output hidden courses management top box as visible.
                if ($param_manage == 1) {
                    echo '<div id="coc-hiddencoursesmanagement-top" class="container-fluid"><div class="row"><a href="'.$CFG->wwwroot.$PAGE->url->out_as_local_url(true, array('coc-manage' => 0)).'">'.get_string('stopmanaginghiddencourses', 'block_course_overview_campus').'</a></div></div>';
                }
            }



            /********************************************************************************/
            /***                   GENERATE OUTPUT FOR COURSELIST                         ***/
            /********************************************************************************/

            // Start section.
            echo '<div id="coc-courselist" class="container-fluid m-b-1">';

            // Show courses.
            foreach ($courses as $c) {
                // Remember course ID for JS processing.
                $js_courseslist .= $c->id.' ';

                // Start course div.
                echo '<div id="coc-course-'.$c->id.'" class="row coc-course">';

                // Start hide course div - later we use this div to filter the course.
                if ($coc_config->enablehidecourses == true && $param_manage == 0) {
                    // Show course if it is visible according to the hide courses feature.
                    if ($c->hidecourse == false) {
                        echo '<div class="hidecoursediv coc-hidecourse-'.$c->id.'">';
                    }
                    // Otherwise hide the course with CSS.
                    else {
                        echo '<div class="hidecoursediv coc-hidecourse-'.$c->id.' coc-hidden">';
                    }
                }

                // Start filter by term div - later we use this div to filter the course.
                if ($coc_config->termcoursefilter == true && $param_manage == 0) {
                    // Show course if it is visible according to the term course filter.
                    if ($c->termcoursefiltered == false) {
                        echo '<div class="termdiv coc-term-'.$c->term.'">';
                    }
                    // Otherwise hide the course with CSS.
                    else {
                        echo '<div class="termdiv coc-term-'.$c->term.' coc-hidden">';
                    }
                }

                // Start filter by parent category div - later we use this div to filter the course.
                if ($coc_config->categorycoursefilter == true && $param_manage == 0) {
                    // Show course if it is visible according to the parent category course filter.
                    if ($c->categorycoursefiltered == false) {
                        echo '<div class="categorydiv coc-category-'.$c->categoryid.'">';
                    }
                    // Otherwise hide the course with CSS.
                    else {
                        echo '<div class="categorydiv coc-category-'.$c->categoryid.' coc-hidden">';
                    }
                }

                // Start filter by top level category div - later we use this div to filter the course.
                if ($coc_config->toplevelcategorycoursefilter == true && $param_manage == 0) {
                    // Show course if it is visible according to the top level category course filter.
                    if ($c->toplevelcategorycoursefiltered == false) {
                        echo '<div class="toplevelcategorydiv coc-toplevelcategory-'.$c->toplevelcategoryid.'">';
                    }
                    // Otherwise hide the course with CSS.
                    else {
                        echo '<div class="toplevelcategorydiv coc-toplevelcategory-'.$c->toplevelcategoryid.' coc-hidden">';
                    }
                }

                // Start filter by teacher div - later we use this div to filter the course.
                if ($coc_config->teachercoursefilter == true && $param_manage == 0) {
                    // Start teacher div.
                    echo '<div class="teacherdiv';

                    // Add all teachers.
                    foreach ($c->teachers as $id => $t) {
                        echo ' coc-teacher-'.$id;
                    }

                    // Show course if it is visible according to the teacher course filter.
                    if ($c->teachercoursefiltered == false) {
                        echo '">';
                    }
                    // Otherwise hide the course with CSS.
                    else {
                        echo ' coc-hidden">';
                    }
                }


                // Start standard course overview coursebox.
                echo $OUTPUT->box_start('coursebox');

                // Output course news visibility control icons.
                if ($coc_config->enablecoursenews && $param_manage == 0) {
                    if (array_key_exists($c->id, $coursenews)) {
                        // If course news are hidden.
                        if ($c->hidenews == false) {
                            echo '<div class="hidenewsicon">
                                    <a href="'.$CFG->wwwroot.$PAGE->url->out_as_local_url(true, array('coc-manage' => $param_manage, 'coc-hidenews' => $c->id, 'coc-shownews' => '')).'" id="coc-hidenewsicon-'.$c->id.'" title="'.get_string('hidenews', 'block_course_overview_campus').'">
                                        <img src="'.$OUTPUT->pix_url('t/expanded').'" alt="'.get_string('hidenews', 'block_course_overview_campus').'" />
                                    </a>
                                    <a href="'.$CFG->wwwroot.$PAGE->url->out_as_local_url(true, array('coc-manage' => $param_manage, 'coc-hidenews' => '', 'coc-shownews' => $c->id)).'" id="coc-shownewsicon-'.$c->id.'" class="coc-hidden" title="'.get_string('shownews', 'block_course_overview_campus').'">
                                        <img src="'.$OUTPUT->pix_url('t/collapsed').'" alt="'.get_string('shownews', 'block_course_overview_campus').'" />
                                    </a>
                                </div>';
                        }
                        // If course news are visible.
                        else {
                            echo '<div class="hidenewsicon">
                                    <a href="'.$CFG->wwwroot.$PAGE->url->out_as_local_url(true, array('coc-manage' => $param_manage, 'coc-hidenews' => $c->id, 'coc-shownews' => '')).'" id="coc-hidenewsicon-'.$c->id.'" class="coc-hidden" title="'.get_string('hidenews', 'block_course_overview_campus').'">
                                        <img src="'.$OUTPUT->pix_url('t/expanded').'" alt="'.get_string('hidenews', 'block_course_overview_campus').'" />
                                    </a>
                                    <a href="'.$CFG->wwwroot.$PAGE->url->out_as_local_url(true, array('coc-manage' => $param_manage, 'coc-hidenews' => '', 'coc-shownews' => $c->id)).'" id="coc-shownewsicon-'.$c->id.'" title="'.get_string('shownews', 'block_course_overview_campus').'">
                                        <img src="'.$OUTPUT->pix_url('t/collapsed').'" alt="'.get_string('shownews', 'block_course_overview_campus').'" />
                                    </a>
                                </div>';
                        }
                    }
                }

                // Output course visibility control icons.
                if ($coc_config->enablehidecourses) {
                    // If course is hidden.
                    if (block_course_overview_campus_course_hidden_by_hidecourses($c, 0) == false) { // We can't rely on $c->hidecourse here because otherwise the icon would always be t/show.
                        echo '<div class="hidecourseicon">
                                <a href="'.$CFG->wwwroot.$PAGE->url->out_as_local_url(true, array('coc-manage' => $param_manage, 'coc-hidecourse' => $c->id, 'coc-showcourse' => '')).'" id="coc-hidecourseicon-'.$c->id.'" title="'.get_string('hidecourse', 'block_course_overview_campus').'">
                                    <img src="'.$OUTPUT->pix_url('t/hide').'" class="icon" alt="'.get_string('hidecourse', 'block_course_overview_campus').'" />
                                </a>
                                <a href="'.$CFG->wwwroot.$PAGE->url->out_as_local_url(true, array('coc-manage' => $param_manage, 'coc-hidecourse' => '', 'coc-showcourse' => $c->id)).'" id="coc-showcourseicon-'.$c->id.'" class="coc-hidden" title="'.get_string('showcourse', 'block_course_overview_campus').'">
                                    <img src="'.$OUTPUT->pix_url('t/show').'" class="icon" alt="'.get_string('showcourse', 'block_course_overview_campus').'" />
                                </a>
                            </div>';
                    }
                    // If course is visible.
                    else {
                        echo '<div class="hidecourseicon">
                                <a href="'.$CFG->wwwroot.$PAGE->url->out_as_local_url(true, array('coc-manage' => $param_manage, 'coc-hidecourse' => $c->id, 'coc-showcourse' => '')).'" id="coc-hidecourseicon-'.$c->id.'" class="coc-hidden" title="'.get_string('hidecourse', 'block_course_overview_campus').'">
                                    <img src="'.$OUTPUT->pix_url('t/hide').'" class="icon" alt="'.get_string('hidecourse', 'block_course_overview_campus').'" />
                                </a>
                                <a href="'.$CFG->wwwroot.$PAGE->url->out_as_local_url(true, array('coc-manage' => $param_manage, 'coc-hidecourse' => '', 'coc-showcourse' => $c->id)).'" id="coc-showcourseicon-'.$c->id.'" title="'.get_string('showcourse', 'block_course_overview_campus').'">
                                    <img src="'.$OUTPUT->pix_url('t/show').'" class="icon" alt="'.get_string('showcourse', 'block_course_overview_campus').'" />
                                </a>
                            </div>';
                    }
                }

                // Get course attributes for use with course link.
                $attributes = array('title' => format_string($c->fullname));
                if (empty($c->visible)) {
                    $attributes['class'] = 'dimmed';
                }

                // Check if some meta info has to be displayed in addition to the course name.
                if ($coc_config->secondrowshowshortname == true ||
                        $coc_config->secondrowshowtermname == true ||
                        $coc_config->secondrowshowcategoryname == true ||
                        $coc_config->secondrowshowtoplevelcategoryname == true ||
                        ($coc_config->secondrowshowteachername == true && count($c->teachers) > 0)) {
                    $meta = array();
                    if ($coc_config->secondrowshowshortname == true) {
                        $meta[] = $c->shortname;
                    }
                    if ($coc_config->secondrowshowtermname == true) {
                        $meta[] = $c->termname;
                    }
                    if ($coc_config->secondrowshowcategoryname == true) {
                        $meta[] = $c->categoryname;
                    }
                    if ($coc_config->secondrowshowtoplevelcategoryname == true) {
                        $meta[] = $c->toplevelcategoryname;
                    }
                    if ($coc_config->secondrowshowteachername == true) {
                        // Get teachers' names for use with course link.
                        if (count($c->teachers) > 0) {
                            $teachernames = block_course_overview_campus_get_teachername_string($c->teachers);
                            $meta[] = $teachernames;
                        } else if (strlen(trim($coc_config->noteachertext)) > 0) {
                            $teachernames = format_string($coc_config->noteachertext);
                            $meta[] = $teachernames;
                        }
                    }

                    // Create meta info code.
                    // Hide metainfo on phones if configured.
                    if ($coc_config->secondrowhideonphones == true) {
                        $metainfo = '<br /><span class="coc-metainfo hidden-phone hidden-sm-down">('.implode($meta, '  |  ').')</span>'; // Class 'hidden-phone' is used for Bootstrapbase and will be ignored by Boost.
                    }
                    // Otherwise.
                    else {
                        $metainfo = '<br /><span class="coc-metainfo">('.implode($meta, '  |  ').')</span>';
                    }
                }
                else {
                    $metainfo = '';
                }

                // Output course link.
                if ($coc_config->firstrowcoursename == 2) {
                    echo $OUTPUT->heading(html_writer::link(new moodle_url('/course/view.php', array('id' => $c->id)), $c->shortname.$metainfo, $attributes), 3);
                }
                else {
                    echo $OUTPUT->heading(html_writer::link(new moodle_url('/course/view.php', array('id' => $c->id)), format_string($c->fullname).$metainfo, $attributes), 3);
                }


                // Output course news.
                if ($coc_config->enablecoursenews && $param_manage == 0) {
                    if (array_key_exists($c->id, $coursenews)) {
                        // Remember course ID for JS processing.
                        $js_coursenewslist .= $c->id.' ';

                        // Start course news div as visible if the course's news aren't hidden.
                        if ($c->hidenews == false) {
                            echo '<div id="coc-coursenews-'.$c->id.'" class="coc-coursenews">';
                        }
                        // Otherwise start course news div as hidden.
                        else {
                            echo '<div id="coc-coursenews-'.$c->id.'" class="coc-coursenews coc-hidden">';
                        }

                        // Output the course's preformatted news HTML.
                        foreach ($coursenews[$c->id] as $modname => $html) {
                            echo '<div class="coc-module">';
                                // Output activity icon.
                                echo $OUTPUT->pix_icon('icon', $modname, 'mod_'.$modname, array('class' => 'iconlarge activityicon'));

                                // Output activity introduction string.
                                if (get_string_manager()->string_exists("activityoverview", $modname)) {
                                    echo '<div class="overview">'.get_string("activityoverview", $modname).'</div>';
                                } else {
                                    echo '<div class="overview">'.get_string("activityoverview", 'block_course_overview_campus', get_string('modulename', $modname)).'</div>';
                                }

                                // Output activity news.
                                echo $html;
                            echo '</div>';
                        }

                        // End course news div.
                        echo '</div>';
                    }
                }

                // End standard course overview coursebox.
                echo $OUTPUT->box_end();

                // End filter by term div.
                if ($coc_config->termcoursefilter == true && $param_manage == 0) {
                    echo '</div>';
                }

                // End filter by parent category div.
                if ($coc_config->categorycoursefilter == true && $param_manage == 0) {
                    echo '</div>';
                }

                // End filter by top level category div.
                if ($coc_config->toplevelcategorycoursefilter == true && $param_manage == 0) {
                    echo '</div>';
                }

                // End filter by teacher div.
                if ($coc_config->teachercoursefilter == true && $param_manage == 0) {
                    echo '</div>';
                }

                // End hide course div.
                if ($coc_config->enablehidecourses == true && $param_manage == 0) {
                    echo '</div>';
                }

                // End course div.
                echo '</div>';
            }

            // End section.
            echo '</div>';



            /********************************************************************************/
            /***           GENERATE OUTPUT FOR HIDDEN COURSES MANAGEMENT BOTTOM BOX       ***/
            /********************************************************************************/

            // Do only if course hiding is enabled.
            if ($coc_config->enablehidecourses) {
                // If hidden courses managing is active, output the box as visible.
                if ($param_manage == 1) {
                    echo '<div id="coc-hiddencoursesmanagement-bottom" class="container-fluid"><div class="row"><a href="'.$CFG->wwwroot.$PAGE->url->out_as_local_url(true, array('coc-manage' => 0)).'">'.get_string('stopmanaginghiddencourses', 'block_course_overview_campus').'</a></div></div>';
                }
                // If hidden courses managing is not active, but I have hidden courses, output the box as visible.
                else if ($param_manage == 0 && $hiddencoursescounter > 0) {
                    echo '<div id="coc-hiddencoursesmanagement-bottom" class="container-fluid"><div class="row">'.get_string('youhave', 'block_course_overview_campus').' <span id="coc-hiddencoursescount">'.$hiddencoursescounter.'</span> '.get_string('hiddencourses', 'block_course_overview_campus').' | <a href="'.$CFG->wwwroot.$PAGE->url->out_as_local_url(true, array('coc-manage' => 1)).'">'.get_string('managehiddencourses', 'block_course_overview_campus').'</a></div></div>';
                }
                // Otherwise output the box as hidden to appear via JS as soon as a course is hidden.
                else {
                    echo '<div id="coc-hiddencoursesmanagement-bottom" class="container-fluid"><div class="row coc-hidden">'.get_string('youhave', 'block_course_overview_campus').' <span id="coc-hiddencoursescount">'.$hiddencoursescounter.'</span> '.get_string('hiddencourses', 'block_course_overview_campus').' | <a href="'.$CFG->wwwroot.$PAGE->url->out_as_local_url(true, array('coc-manage' => 1)).'">'.get_string('managehiddencourses', 'block_course_overview_campus').'</a></div></div>';
                }
            }



            /********************************************************************************/
            /***                             OUTPUT CONTENT                               ***/
            /********************************************************************************/

            // Get and end output buffer.
            $content = ob_get_contents();
            ob_end_clean();



            /********************************************************************************/
            /***                             AJAX MANAGEMENT                              ***/
            /********************************************************************************/

            // Verify that course displaying parameters are updatable by AJAX.
            foreach ($courses as $c) {
                if ($coc_config->enablehidecourses) {
                    user_preference_allow_ajax_update('block_course_overview_campus-hidecourse-'.$c->id, PARAM_BOOL);
                }
                if ($coc_config->enablecoursenews && $param_manage == 0) {
                    user_preference_allow_ajax_update('block_course_overview_campus-hidenews-'.$c->id, PARAM_BOOL);
                }
            }

            // Verify that filter parameters are updatable by AJAX.
            if ($coc_config->termcoursefilter == true) {
                user_preference_allow_ajax_update('block_course_overview_campus-selectedterm', PARAM_ALPHANUMEXT);
            }
            if ($coc_config->teachercoursefilter == true) {
                user_preference_allow_ajax_update('block_course_overview_campus-selectedteacher', PARAM_ALPHANUM);
            }
            if ($coc_config->categorycoursefilter == true) {
                user_preference_allow_ajax_update('block_course_overview_campus-selectedcategory', PARAM_ALPHANUM);
            }
            if ($coc_config->toplevelcategorycoursefilter == true) {
                user_preference_allow_ajax_update('block_course_overview_campus-selectedtoplevelcategory', PARAM_ALPHANUM);
            }

            // Include JS for hiding courses with AJAX.
            if ($coc_config->enablehidecourses) {
                $js_hidecoursesoptions = [
                    'local_boostcoc' => block_course_overview_campus_check_local_boostcoc(),
                    'courses' => trim($js_courseslist),
                    'manage' => $param_manage,
                    ];
                $PAGE->requires->js_call_amd('block_course_overview_campus/hidecourse', 'initHideCourse', [$js_hidecoursesoptions]);
            }

            // Include JS for hiding course news with AJAX.
            if ($coc_config->enablecoursenews && $param_manage == 0) {
                $js_coursenewsoptions = [
                    'courses' => trim($js_coursenewslist),
                    ];

                $PAGE->requires->js_call_amd('block_course_overview_campus/hidenews', 'initHideNews', [$js_coursenewsoptions]);
            }

            // Include JS for filtering courses with AJAX.
            if ($coc_config->teachercoursefilter == true || $coc_config->termcoursefilter == true || $coc_config->categorycoursefilter == true || $coc_config->toplevelcategorycoursefilter == true) {
                $js_filteroptions = [
                    'local_boostcoc' => block_course_overview_campus_check_local_boostcoc(),
                    'initialsettings' => [
                        'term' => (isset($selectedterm)) ? $selectedterm : '',
                        'teacher' => (isset($selectedteacher)) ? $selectedteacher : '',
                        'category' => (isset($selectedcategory)) ? $selectedcategory : '',
                        'toplevelcategory' => (isset($selectedtoplevelcategory)) ? $selectedtoplevelcategory : '',
                    ],
                ]; // Passing the initialsettings to the JS code is necessary for filtering the course list again when using browser 'back' button.
                $PAGE->requires->js_call_amd('block_course_overview_campus/filter', 'initFilter', [$js_filteroptions]);
            }



            /********************************************************************************/
            /***                             LOCAL_BOOSTCOC                               ***/
            /********************************************************************************/

            // Do only if local_boostcoc is installed.
            if (block_course_overview_campus_check_local_boostcoc() == true) {
                // Remember the not shown courses for local_boostcoc.
                block_course_overview_campus_remember_notshowncourses_for_local_boostcoc($courses);

                // Verify that we can also remember the not shown courses for local_boostcoc by AJAX.
                user_preference_allow_ajax_update('local_boostcoc-notshowncourses', PARAM_RAW);

                // Remember the active filters for local_boostcoc.
                block_course_overview_campus_remember_activefilters_for_local_boostcoc($hiddencoursescounter);

                // Verify that we can also remember the active filters for local_boostcoc by AJAX.
                user_preference_allow_ajax_update('local_boostcoc-activefilters', PARAM_RAW);
            }
        }



        /********************************************************************************/
        /***                             OUTPUT AND RETURN                            ***/
        /********************************************************************************/

        // Output content.
        $this->content = new stdClass();

        if (!empty($content)) {
            $this->content->text = $content;
        } else {
            $this->content->text = '';
        }

        return $this->content;
    }
}
