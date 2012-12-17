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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	 See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

defined('MOODLE_INTERNAL') || die();

require_once(dirname(__FILE__) . '/lib.php');

if ($ADMIN->fulltree) {
	// Filter usage
	$settings->add(new admin_setting_heading('block_course_overview_campus_filtersettingheading', get_string('filtersettingheading', 'block_course_overview_campus'), ''));

	$settings->add(new admin_setting_configcheckbox('block_course_overview_campus_categorycoursefilter', get_string('categorycoursefilter', 'block_course_overview_campus'),
						get_string('categorycoursefilterdescription', 'block_course_overview_campus'), 0));

	$settings->add(new admin_setting_configcheckbox('block_course_overview_campus_teachercoursefilter', get_string('teachercoursefilter', 'block_course_overview_campus'),
						get_string('teachercoursefilterdescription', 'block_course_overview_campus'), 0));

	$settings->add(new admin_setting_configcheckbox('block_course_overview_campus_termcoursefilter', get_string('termcoursefilter', 'block_course_overview_campus'),
						get_string('termcoursefilterdescription', 'block_course_overview_campus'), 0));


	// Term definition
	// Check if the configured term dates make sense, if not show warning information
	if (isset($CFG->block_course_overview_campus_termcoursefilter) && $CFG->block_course_overview_campus_termcoursefilter == true && !check_term_config()) {
		$settings->add(new admin_setting_heading('block_course_overview_campus_termsettingheading', get_string('termsettingheading', 'block_course_overview_campus'), '<span class="errormessage">'.get_string('termsettingerror', 'block_course_overview_campus').'</span>'));
	}
	else {
		$settings->add(new admin_setting_heading('block_course_overview_campus_termsettingheading', get_string('termsettingheading', 'block_course_overview_campus'), ''));
	}

	// Possible term modes
	$termmodes[1] = get_string('academicyeardescription', 'block_course_overview_campus');
	$termmodes[2] = get_string('semesterdescription', 'block_course_overview_campus');
	$termmodes[3] = get_string('tertialdescription', 'block_course_overview_campus');
	$termmodes[4] = get_string('trimesterdescription', 'block_course_overview_campus');

	$settings->add(new admin_setting_configselect('block_course_overview_campus_termmode', get_string('termmode', 'block_course_overview_campus'), 
						get_string('termmodedescription', 'block_course_overview_campus'), $termmodes[1], $termmodes));

	// Get all calendar days
	$format = get_string('strftimedateshort', 'langconfig');
	for ($i = 1; $i <= 12; $i++) {
		for ($j = 1; $j <= cal_days_in_month(CAL_GREGORIAN, $i, 2003); $j++) { // Use no leap year to calculate days in month to avoid providing 29th february as an option
			// Create an intermediate timestamp with each day-month-combination and format it according to local date format for displaying purpose
			$daystring = userdate(gmmktime(12, 0, 0, $i, $j, 2003), $format);

			// Add the day as an option
			$days[sprintf('%02d', $i).'-'.sprintf('%02d', $j)] = $daystring;
		}
	}
	
	$settings->add(new admin_setting_configselect('block_course_overview_campus_term1startday', get_string('term1startday', 'block_course_overview_campus'), 
						get_string('term1startdaydescription', 'block_course_overview_campus'), $days['01-01'], $days));

	$settings->add(new admin_setting_configtext('block_course_overview_campus_term1name', get_string('term1name', 'block_course_overview_campus'), 
						get_string('term1namedescription', 'block_course_overview_campus'), get_string('term1', 'block_course_overview_campus'), PARAM_TEXT));

	$settings->add(new admin_setting_configselect('block_course_overview_campus_term2startday', get_string('term2startday', 'block_course_overview_campus'), 
						get_string('term2startdaydescription', 'block_course_overview_campus'), $days['01-01'], $days));

	$settings->add(new admin_setting_configtext('block_course_overview_campus_term2name', get_string('term2name', 'block_course_overview_campus'), 
						get_string('term2namedescription', 'block_course_overview_campus'), get_string('term2', 'block_course_overview_campus'), PARAM_TEXT));

	$settings->add(new admin_setting_configselect('block_course_overview_campus_term3startday', get_string('term3startday', 'block_course_overview_campus'), 
						get_string('term3startdaydescription', 'block_course_overview_campus'), $days['01-01'], $days));

	$settings->add(new admin_setting_configtext('block_course_overview_campus_term3name', get_string('term3name', 'block_course_overview_campus'), 
						get_string('term3namedescription', 'block_course_overview_campus'), get_string('term3', 'block_course_overview_campus'), PARAM_TEXT));

	$settings->add(new admin_setting_configselect('block_course_overview_campus_term4startday', get_string('term4startday', 'block_course_overview_campus'), 
						get_string('term4startdaydescription', 'block_course_overview_campus'), $days['01-01'], $days));

	$settings->add(new admin_setting_configtext('block_course_overview_campus_term4name', get_string('term4name', 'block_course_overview_campus'), 
						get_string('term4namedescription', 'block_course_overview_campus'), get_string('term4', 'block_course_overview_campus'), PARAM_TEXT));

	$settings->add(new admin_setting_configcheckbox('block_course_overview_campus_defaultterm', get_string('defaultterm', 'block_course_overview_campus'),
						get_string('defaulttermdescription', 'block_course_overview_campus'), 1));


	// Appearance
	$settings->add(new admin_setting_heading('block_course_overview_campus_appearancesettingheading', get_string('appearancesettingheading', 'block_course_overview_campus'), ''));

	$settings->add(new admin_setting_configcheckbox('block_course_overview_campus_showshortname', get_string('showshortname', 'block_course_overview_campus'),
						get_string('showshortnamedescription', 'block_course_overview_campus'), 0));

	$settings->add(new admin_setting_configtext('block_course_overview_campus_categorycoursefilterdisplayname', get_string('categorycoursefilterdisplayname', 'block_course_overview_campus'), 
						get_string('categorycoursefilterdisplaynamedescription', 'block_course_overview_campus'), get_string('category', 'block_course_overview_campus'), PARAM_TEXT));

	$settings->add(new admin_setting_configtext('block_course_overview_campus_teachercoursefilterdisplayname', get_string('teachercoursefilterdisplayname', 'block_course_overview_campus'), 
						get_string('teachercoursefilterdisplaynamedescription', 'block_course_overview_campus'), get_string('defaultcourseteacher'), PARAM_TEXT));

	$settings->add(new admin_setting_configtext('block_course_overview_campus_termcoursefilterdisplayname', get_string('termcoursefilterdisplayname', 'block_course_overview_campus'), 
						get_string('termcoursefilterdisplaynamedescription', 'block_course_overview_campus'), get_string('term', 'block_course_overview_campus'), PARAM_TEXT));
}