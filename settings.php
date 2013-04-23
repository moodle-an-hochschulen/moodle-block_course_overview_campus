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
 * Block "course overview (campus)" - Settings
 *
 * @package     block
 * @subpackage  block_course_overview_campus
 * @copyright   2013 Alexander Bias, University of Ulm <alexander.bias@uni-ulm.de>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(dirname(__FILE__) . '/lib.php');

if ($ADMIN->fulltree) {
    // Filter usage
    $settings->add(new admin_setting_heading('block_course_overview_campus/filtersettingheading', get_string('filtersettingheading', 'block_course_overview_campus'), ''));

    $settings->add(new admin_setting_configcheckbox('block_course_overview_campus/categorycoursefilter', get_string('categorycoursefilter', 'block_course_overview_campus'),
                        get_string('categorycoursefilter_desc', 'block_course_overview_campus'), 0));

    $settings->add(new admin_setting_configcheckbox('block_course_overview_campus/teachercoursefilter', get_string('teachercoursefilter', 'block_course_overview_campus'),
                        get_string('teachercoursefilter_desc', 'block_course_overview_campus'), 0));

    $settings->add(new admin_setting_configcheckbox('block_course_overview_campus/termcoursefilter', get_string('termcoursefilter', 'block_course_overview_campus'),
                        get_string('termcoursefilter_desc', 'block_course_overview_campus'), 0));


    // Term definition
    // Check if the configured term dates make sense, if not show warning information
    if (isset($CFG->block_course_overview_campus_termcoursefilter) && $CFG->block_course_overview_campus_termcoursefilter == true && !check_term_config()) {
        $settings->add(new admin_setting_heading('block_course_overview_campus/termsettingheading', get_string('termsettingheading', 'block_course_overview_campus'), '<span class="errormessage">'.get_string('termsettingerror', 'block_course_overview_campus').'</span>'));
    }
    else {
        $settings->add(new admin_setting_heading('block_course_overview_campus/termsettingheading', get_string('termsettingheading', 'block_course_overview_campus'), ''));
    }

    // Possible term modes
    $termmodes[1] = get_string('academicyear_desc', 'block_course_overview_campus');
    $termmodes[2] = get_string('semester_desc', 'block_course_overview_campus');
    $termmodes[3] = get_string('tertial_desc', 'block_course_overview_campus');
    $termmodes[4] = get_string('trimester_desc', 'block_course_overview_campus');

    $settings->add(new admin_setting_configselect('block_course_overview_campus/termmode', get_string('termmode', 'block_course_overview_campus'),
                        get_string('termmode_desc', 'block_course_overview_campus'), $termmodes[1], $termmodes));

    // Get all calendar days
    $format = get_string('strftimedateshort', 'langconfig');
    for ($i = 1; $i <= 12; $i++) {
        for ($j = 1; $j <= date('t', mktime(0, 0, 0, $i, 1, 2003)); $j++) { // Use no leap year to calculate days in month to avoid providing 29th february as an option
            // Create an intermediate timestamp with each day-month-combination and format it according to local date format for displaying purpose
            $daystring = userdate(gmmktime(12, 0, 0, $i, $j, 2003), $format);

            // Add the day as an option
            $days[sprintf('%02d', $i).'-'.sprintf('%02d', $j)] = $daystring;
        }
    }

    $settings->add(new admin_setting_configselect('block_course_overview_campus/term1startday', get_string('term1startday', 'block_course_overview_campus'),
                        get_string('term1startday_desc', 'block_course_overview_campus'), $days['01-01'], $days));

    $settings->add(new admin_setting_configtext('block_course_overview_campus/term1name', get_string('term1name', 'block_course_overview_campus'),
                        get_string('term1name_desc', 'block_course_overview_campus'), get_string('term1', 'block_course_overview_campus'), PARAM_TEXT));

    $settings->add(new admin_setting_configselect('block_course_overview_campus/term2startday', get_string('term2startday', 'block_course_overview_campus'),
                        get_string('term2startday_desc', 'block_course_overview_campus'), $days['01-01'], $days));

    $settings->add(new admin_setting_configtext('block_course_overview_campus/term2name', get_string('term2name', 'block_course_overview_campus'),
                        get_string('term2name_desc', 'block_course_overview_campus'), get_string('term2', 'block_course_overview_campus'), PARAM_TEXT));

    $settings->add(new admin_setting_configselect('block_course_overview_campus/term3startday', get_string('term3startday', 'block_course_overview_campus'),
                        get_string('term3startday_desc', 'block_course_overview_campus'), $days['01-01'], $days));

    $settings->add(new admin_setting_configtext('block_course_overview_campus/term3name', get_string('term3name', 'block_course_overview_campus'),
                        get_string('term3name_desc', 'block_course_overview_campus'), get_string('term3', 'block_course_overview_campus'), PARAM_TEXT));

    $settings->add(new admin_setting_configselect('block_course_overview_campus/term4startday', get_string('term4startday', 'block_course_overview_campus'),
                        get_string('term4startday_desc', 'block_course_overview_campus'), $days['01-01'], $days));

    $settings->add(new admin_setting_configtext('block_course_overview_campus/term4name', get_string('term4name', 'block_course_overview_campus'),
                        get_string('term4name_desc', 'block_course_overview_campus'), get_string('term4', 'block_course_overview_campus'), PARAM_TEXT));



    // Term behaviour
    $settings->add(new admin_setting_heading('block_course_overview_campus/termbehavioursettingheading', get_string('termbehavioursettingheading', 'block_course_overview_campus'), ''));

    $settings->add(new admin_setting_configcheckbox('block_course_overview_campus/defaultterm', get_string('defaultterm', 'block_course_overview_campus'),
                        get_string('defaultterm_desc', 'block_course_overview_campus'), 1));


    // Timeless courses
    $settings->add(new admin_setting_heading('block_course_overview_campus/timelesscoursessettingheading', get_string('timelesscoursessettingheading', 'block_course_overview_campus'), ''));

    $settings->add(new admin_setting_configcheckbox('block_course_overview_campus/timelesscourses', get_string('timelesscourses', 'block_course_overview_campus'),
                        get_string('timelesscourses_desc', 'block_course_overview_campus'), 1));

    $settings->add(new admin_setting_configtext('block_course_overview_campus/timelesscoursesname', get_string('timelesscoursesname', 'block_course_overview_campus'),
                        get_string('timelesscoursesname_desc', 'block_course_overview_campus'), get_string('timelesscourses', 'block_course_overview_campus'), PARAM_TEXT));

    // Get all years from 1970
    for ($i = 1971; $i <= date('Y'); $i++) {
        // Add the year as an option
        $years[$i] = $i;
    }

    $settings->add(new admin_setting_configselect('block_course_overview_campus/timelesscoursesthreshold', get_string('timelesscoursesthreshold', 'block_course_overview_campus'),
                        get_string('timelesscoursesthreshold_desc', 'block_course_overview_campus'), $years[date('Y')-1], $years));


    // Appearance
    $settings->add(new admin_setting_heading('block_course_overview_campus/appearancesettingheading', get_string('appearancesettingheading', 'block_course_overview_campus'), ''));

    $settings->add(new admin_setting_configcheckbox('block_course_overview_campus/showshortname', get_string('showshortname', 'block_course_overview_campus'),
                        get_string('showshortname_desc', 'block_course_overview_campus'), 0));

    $settings->add(new admin_setting_configcheckbox('block_course_overview_campus/showteachername', get_string('showteachername', 'block_course_overview_campus'),
                        get_string('showteachername_desc', 'block_course_overview_campus'), 0));

    $settings->add(new admin_setting_configtext('block_course_overview_campus/categorycoursefilterdisplayname', get_string('categorycoursefilterdisplayname', 'block_course_overview_campus'),
                        get_string('categorycoursefilterdisplayname_desc', 'block_course_overview_campus'), get_string('category', 'block_course_overview_campus'), PARAM_TEXT));

    $settings->add(new admin_setting_configtext('block_course_overview_campus/teachercoursefilterdisplayname', get_string('teachercoursefilterdisplayname', 'block_course_overview_campus'),
                        get_string('teachercoursefilterdisplayname_desc', 'block_course_overview_campus'), get_string('defaultcourseteacher'), PARAM_TEXT));

    $settings->add(new admin_setting_configtext('block_course_overview_campus/termcoursefilterdisplayname', get_string('termcoursefilterdisplayname', 'block_course_overview_campus'),
                        get_string('termcoursefilterdisplayname_desc', 'block_course_overview_campus'), get_string('term', 'block_course_overview_campus'), PARAM_TEXT));
}
