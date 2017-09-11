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
 * @package    block_course_overview_campus
 * @copyright  2013 Alexander Bias, Ulm University <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// @codingStandardsIgnoreFile
// Let codechecker ignore this file. This legacy code is not fully compliant to Moodle coding style but working and well documented.

if ($hassiteconfig) {
    // Empty $settings to prevent a single settings page from being created by lib/classes/plugininfo/block.php
    // because we will create several settings pages now.
    $settings = null;

    // Create admin settings category.
    $ADMIN->add('blocksettings', new admin_category('block_course_overview_campus',
            get_string('pluginname', 'block_course_overview_campus', null, true)));



    // Create empty settings page structure to make the site administration work on non-admin pages.
    if (!$ADMIN->fulltree) {
        // Settings page: General.
        $settingspage = new admin_settingpage('block_course_overview_campus_general',
                get_string('settingspage_general', 'block_course_overview_campus', null, true));
        $ADMIN->add('block_course_overview_campus', $settingspage);

        // Settings page: Course overview list.
        $settingspage = new admin_settingpage('block_course_overview_campus_courseoverviewlist',
                get_string('settingspage_courseoverviewlist', 'block_course_overview_campus', null, true));
        $ADMIN->add('block_course_overview_campus', $settingspage);

        // Settings page: Hide courses.
        $settingspage = new admin_settingpage('block_course_overview_campus_hidecourses',
                get_string('settingspage_hidecourses', 'block_course_overview_campus', null, true));
        $ADMIN->add('block_course_overview_campus', $settingspage);

        // Settings page: Course news.
        $settingspage = new admin_settingpage('block_course_overview_campus_coursenews',
                get_string('settingspage_coursenews', 'block_course_overview_campus', null, true));
        $ADMIN->add('block_course_overview_campus', $settingspage);

        // Settings page: Teacher roles.
        $settingspage = new admin_settingpage('block_course_overview_campus_teacherroles',
                get_string('settingspage_teacherroles', 'block_course_overview_campus', null, true));
        $ADMIN->add('block_course_overview_campus', $settingspage);

        // Settings page: Parent category filter.
        $settingspage = new admin_settingpage('block_course_overview_campus_categoryfilter',
                get_string('settingspage_categoryfilter', 'block_course_overview_campus', null, true));
        $ADMIN->add('block_course_overview_campus', $settingspage);

        // Settings page: Top level category filter.
        $settingspage = new admin_settingpage('block_course_overview_campus_toplevelcategoryfilter',
                get_string('settingspage_toplevelcategoryfilter', 'block_course_overview_campus', null, true));
        $ADMIN->add('block_course_overview_campus', $settingspage);

        // Settings page: Teacher filter.
        $settingspage = new admin_settingpage('block_course_overview_campus_teacherfilter',
                get_string('settingspage_teacherfilter', 'block_course_overview_campus', null, true));
        $ADMIN->add('block_course_overview_campus', $settingspage);

        // Settings page: Term filter.
        $settingspage = new admin_settingpage('block_course_overview_campus_termfilter',
                get_string('settingspage_termfilter', 'block_course_overview_campus', null, true));
        $ADMIN->add('block_course_overview_campus', $settingspage);
    }


    // Create full settings page structure.
    else if ($ADMIN->fulltree) {
        // Include local library.
        require_once(__DIR__ . '/locallib.php');


        // Settings page: General.
        $settingspage = new admin_settingpage('block_course_overview_campus_general',
                get_string('settingspage_general', 'block_course_overview_campus', null, true));

        // Appearance.
        $settingspage->add(new admin_setting_heading('block_course_overview_campus/appearancesettingheading',
                get_string('appearancesettingheading', 'block_course_overview_campus', null, true),
                ''));

        $settingspage->add(new admin_setting_configtext('block_course_overview_campus/blocktitle',
                get_string('blocktitle', 'block_course_overview_campus', null, true),
                get_string('blocktitle_desc', 'block_course_overview_campus', null, true),
                get_string('pluginname', 'block_course_overview', null, true),
                PARAM_TEXT));

        // Add settings page to the admin settings category.
        $ADMIN->add('block_course_overview_campus', $settingspage);



        // Settings page: Course overview list.
        $settingspage = new admin_settingpage('block_course_overview_campus_courseoverviewlist',
                get_string('settingspage_courseoverviewlist', 'block_course_overview_campus', null, true));

        // Course overview list entries.
        $settingspage->add(new admin_setting_heading('block_course_overview_campus/listentriessettingheading',
                get_string('listentriessettingheading', 'block_course_overview_campus', null, true),
                ''));

        // Possible course name modes.
        $coursenamemodes[1] = get_string('fullnamecourse', 'core', null, false); // Don't use string lazy loading here because the string will be directly used and would produce a PHP warning otherwise.
        $coursenamemodes[2] = get_string('shortnamecourse', 'core', null, true);

        $settingspage->add(new admin_setting_configselect('block_course_overview_campus/firstrowcoursename',
                get_string('firstrowcoursename', 'block_course_overview_campus', null, true),
                get_string('firstrowcoursename_desc', 'block_course_overview_campus', null, true),
                $coursenamemodes[1],
                $coursenamemodes));

        $settingspage->add(new admin_setting_configcheckbox('block_course_overview_campus/secondrowshowshortname',
                get_string('secondrowshowshortname', 'block_course_overview_campus', null, true),
                get_string('secondrowshowshortname_desc', 'block_course_overview_campus', null, true),
                0));

        $settingspage->add(new admin_setting_configcheckbox('block_course_overview_campus/secondrowshowtermname',
                get_string('secondrowshowtermname', 'block_course_overview_campus', null, true),
                get_string('secondrowshowtermname_desc', 'block_course_overview_campus', null, true),
                0));

        $settingspage->add(new admin_setting_configcheckbox('block_course_overview_campus/secondrowshowcategoryname',
                get_string('secondrowshowcategoryname', 'block_course_overview_campus', null, true),
                get_string('secondrowshowcategoryname_desc', 'block_course_overview_campus', null, true),
                0));

        $settingspage->add(new admin_setting_configcheckbox('block_course_overview_campus/secondrowshowtoplevelcategoryname',
                get_string('secondrowshowtoplevelcategoryname', 'block_course_overview_campus', null, true),
                get_string('secondrowshowtoplevelcategoryname_desc', 'block_course_overview_campus', null, true),
                0));

        $settingspage->add(new admin_setting_configcheckbox('block_course_overview_campus/secondrowshowteachername',
                get_string('secondrowshowteachername', 'block_course_overview_campus', null, true),
                get_string('secondrowshowteachername_desc', 'block_course_overview_campus', null, true),
                0));

        // Possible teacher name styles.
        $teachernamestylemodes[1] = get_string('teachernamestylefullname', 'block_course_overview_campus', null, true);
        $teachernamestylemodes[2] = get_string('teachernamestylelastname', 'block_course_overview_campus', null, false); // Don't use string lazy loading here because the string will be directly used and would produce a PHP warning otherwise.
        $teachernamestylemodes[3] = get_string('teachernamestylefirstname', 'block_course_overview_campus', null, true);
        $teachernamestylemodes[4] = get_string('teachernamestylefullnamedisplay', 'block_course_overview_campus', get_config('core', 'fullnamedisplay'), true);

        $settingspage->add(new admin_setting_configselect('block_course_overview_campus/secondrowshowteachernamestyle',
                get_string('secondrowshowteachernamestyle', 'block_course_overview_campus', null, true),
                get_string('secondrowshowteachernamestyle_desc', 'block_course_overview_campus', null, true),
                $teachernamestylemodes[2],
                $teachernamestylemodes));

        $settingspage->add(new admin_setting_configcheckbox('block_course_overview_campus/secondrowhideonphones',
                get_string('secondrowhideonphones', 'block_course_overview_campus', null, true),
                get_string('secondrowhideonphones_desc', 'block_course_overview_campus', null, true),
                0));


        // Course order.
        $settingspage->add(new admin_setting_heading('block_course_overview_campus/ordersettingheading',
                get_string('ordersettingheading', 'block_course_overview_campus', null, true),
                ''));

        $settingspage->add(new admin_setting_configcheckbox('block_course_overview_campus/prioritizemyteachedcourses',
                get_string('prioritizemyteachedcourses', 'block_course_overview_campus', null, true),
                get_string('prioritizemyteachedcourses_desc', 'block_course_overview_campus', null, true),
                0));

        // Add settings page to the admin settings category.
        $ADMIN->add('block_course_overview_campus', $settingspage);



        // Settings page: Hide courses.
        $settingspage = new admin_settingpage('block_course_overview_campus_hidecourses',
                get_string('settingspage_hidecourses', 'block_course_overview_campus', null, true));

        // Course overview list hidden courses management.
        $settingspage->add(new admin_setting_heading('block_course_overview_campus/hidecoursessettingheading',
                get_string('hidecoursessettingheading', 'block_course_overview_campus', null, true),
                ''));

        $settingspage->add(new admin_setting_configcheckbox('block_course_overview_campus/enablehidecourses',
                get_string('enablehidecourses', 'block_course_overview_campus', null, true),
                get_string('enablehidecourses_desc', 'block_course_overview_campus', null, true),
                1));

        // Add settings page to the admin settings category.
        $ADMIN->add('block_course_overview_campus', $settingspage);



        // Settings page: Course news.
        $settingspage = new admin_settingpage('block_course_overview_campus_coursenews',
                get_string('settingspage_coursenews', 'block_course_overview_campus', null, true));

        // Course news.
        $settingspage->add(new admin_setting_heading('block_course_overview_campus/coursenewsheading',
                get_string('coursenewsheading', 'block_course_overview_campus', null, true),
                ''));

        $settingspage->add(new admin_setting_configcheckbox('block_course_overview_campus/enablecoursenews',
                get_string('enablecoursenews', 'block_course_overview_campus', null, true),
                get_string('enablecoursenews_desc', 'block_course_overview_campus', null, true),
                1));

        $settingspage->add(new admin_setting_configcheckbox('block_course_overview_campus/coursenewsdefault',
                get_string('coursenewsdefault', 'block_course_overview_campus', null, true),
                get_string('coursenewsdefault_desc', 'block_course_overview_campus', null, true),
                0));

        // Get activities which provide course news.
        $modules = get_plugin_list_with_function('mod', 'print_overview');
        $modchoices = array();
        foreach ($modules as $m => $f) {
            $modchoices[$m] = get_string('pluginname', $m);
        }

        $settingspage->add(new admin_setting_configmultiselect('block_course_overview_campus/skipcoursenews',
                get_string('skipcoursenews', 'block_course_overview_campus', null, true),
                get_string('skipcoursenews_desc', 'block_course_overview_campus', null, true),
                array(),
                $modchoices));

        // Add settings page to the admin settings category.
        $ADMIN->add('block_course_overview_campus', $settingspage);



        // Settings page: Teacher roles.
        $settingspage = new admin_settingpage('block_course_overview_campus_teacherroles',
                get_string('settingspage_teacherroles', 'block_course_overview_campus', null, true));

        // Teacher roles.
        $settingspage->add(new admin_setting_heading('block_course_overview_campus/teacherrolessettingheading',
                get_string('teacherrolessettingheading', 'block_course_overview_campus', null, true),
                ''));

        $settingspage->add(new admin_setting_pickroles('block_course_overview_campus/teacherroles',
                get_string('teacherroles', 'block_course_overview_campus', null, true),
                get_string('teacherroles_desc', 'block_course_overview_campus', null, true),
                array('editingteacher')));

        $settingspage->add(new admin_setting_configtext('block_course_overview_campus/noteachertext',
                get_string('noteachertext', 'block_course_overview_campus', null, true),
                get_string('noteachertext_desc', 'block_course_overview_campus', null, true),
                get_string('noteacher', 'block_course_overview_campus', null, true),
                PARAM_TEXT));

        // Possible settings for parent teacher roles.
        $teacherrolesparentmodes[1] = get_string('yes', 'core', null, false); // Don't use string lazy loading here because the string will be directly used and would produce a PHP warning otherwise.
        $teacherrolesparentmodes[2] = get_string('no', 'core', null, true);
        $teacherrolesparentmodes[3] = get_string('teacherrolesparentcapability', 'block_course_overview_campus', null, true);

        $settingspage->add(new admin_setting_configselect('block_course_overview_campus/teacherrolesparent',
                get_string('teacherrolesparent', 'block_course_overview_campus', null, true),
                get_string('teacherrolesparent_desc', 'block_course_overview_campus', null, true),
                $teacherrolesparentmodes[1],
                $teacherrolesparentmodes));

        $settingspage->add(new admin_setting_configcheckbox('block_course_overview_campus/teacherroleshidesuspended',
                get_string('teacherroleshidesuspended', 'block_course_overview_campus', null, true),
                get_string('teacherroleshidesuspended_desc', 'block_course_overview_campus', null, true),
                0));

        // Add settings page to the admin settings category.
        $ADMIN->add('block_course_overview_campus', $settingspage);



        // Settings page: Parent category filter.
        $settingspage = new admin_settingpage('block_course_overview_campus_categoryfilter',
                get_string('settingspage_categoryfilter', 'block_course_overview_campus', null, true));

        // Parent category filter: Activation.
        $settingspage->add(new admin_setting_heading('block_course_overview_campus/categorycoursefiltersettingheading',
                get_string('categorycoursefiltersettingheading', 'block_course_overview_campus', null, true),
                ''));

        $settingspage->add(new admin_setting_configcheckbox('block_course_overview_campus/categorycoursefilter',
                get_string('categorycoursefilter', 'block_course_overview_campus', null, true),
                get_string('categorycoursefilter_desc', 'block_course_overview_campus', null, true),
                0));

        $settingspage->add(new admin_setting_configtext('block_course_overview_campus/categorycoursefilterdisplayname',
                get_string('categorycoursefilterdisplayname', 'block_course_overview_campus', null, true),
                get_string('categorycoursefilterdisplayname_desc', 'block_course_overview_campus', null, true),
                get_string('category', 'block_course_overview_campus', null, true),
                PARAM_TEXT));


        // Parent category filter: Merge homonymous categories.
        $settingspage->add(new admin_setting_heading('block_course_overview_campus/mergehomonymouscategoriessettingheading',
                get_string('mergehomonymouscategoriessettingheading', 'block_course_overview_campus', null, true),
                ''));

        $settingspage->add(new admin_setting_configcheckbox('block_course_overview_campus/mergehomonymouscategories',
                get_string('mergehomonymouscategories', 'block_course_overview_campus', null, true),
                get_string('mergehomonymouscategories_desc', 'block_course_overview_campus', null, true),
                0));

        // Add settings page to the admin settings category.
        $ADMIN->add('block_course_overview_campus', $settingspage);



        // Settings page: Top level category filter.
        $settingspage = new admin_settingpage('block_course_overview_campus_toplevelcategoryfilter',
                get_string('settingspage_toplevelcategoryfilter', 'block_course_overview_campus', null, true));

        // Top level category filter: Activation.
        $settingspage->add(new admin_setting_heading('block_course_overview_campus/toplevelcategorycoursefiltersettingheading',
                get_string('toplevelcategorycoursefiltersettingheading', 'block_course_overview_campus', null, true),
                ''));

        $settingspage->add(new admin_setting_configcheckbox('block_course_overview_campus/toplevelcategorycoursefilter',
                get_string('toplevelcategorycoursefilter', 'block_course_overview_campus', null, true),
                get_string('toplevelcategorycoursefilter_desc', 'block_course_overview_campus', null, true),
                0));

        $settingspage->add(new admin_setting_configtext('block_course_overview_campus/toplevelcategorycoursefilterdisplayname',
                get_string('toplevelcategorycoursefilterdisplayname', 'block_course_overview_campus', null, true),
                get_string('toplevelcategorycoursefilterdisplayname_desc', 'block_course_overview_campus', null, true),
                get_string('toplevelcategory', 'block_course_overview_campus', null, true),
                PARAM_TEXT));

        // Add settings page to the admin settings category.
        $ADMIN->add('block_course_overview_campus', $settingspage);



        // Settings page: Teacher filter.
        $settingspage = new admin_settingpage('block_course_overview_campus_teacherfilter',
                get_string('settingspage_teacherfilter', 'block_course_overview_campus', null, true));

        // Teacher filter: Activation.
        $settingspage->add(new admin_setting_heading('block_course_overview_campus/teachercoursefiltersettingheading',
                get_string('teachercoursefiltersettingheading', 'block_course_overview_campus', null, true),
                ''));

        $settingspage->add(new admin_setting_configcheckbox('block_course_overview_campus/teachercoursefilter',
                get_string('teachercoursefilter', 'block_course_overview_campus', null, true),
                get_string('teachercoursefilter_desc', 'block_course_overview_campus', null, true),
                0));

        $settingspage->add(new admin_setting_configtext('block_course_overview_campus/teachercoursefilterdisplayname',
                get_string('teachercoursefilterdisplayname', 'block_course_overview_campus', null, true),
                get_string('teachercoursefilterdisplayname_desc', 'block_course_overview_campus', null, true),
                get_string('defaultcourseteacher'),
                PARAM_TEXT));

        // Add settings page to the admin settings category.
        $ADMIN->add('block_course_overview_campus', $settingspage);



        // Settings page: Term filter.
        $settingspage = new admin_settingpage('block_course_overview_campus_termfilter',
                get_string('settingspage_termfilter', 'block_course_overview_campus', null, true));

        // Term filter: Activation.
        $settingspage->add(new admin_setting_heading('block_course_overview_campus/termcoursefiltersettingheading',
                get_string('termcoursefiltersettingheading', 'block_course_overview_campus', null, true),
                ''));

        $settingspage->add(new admin_setting_configcheckbox('block_course_overview_campus/termcoursefilter',
                get_string('termcoursefilter', 'block_course_overview_campus', null, true),
                get_string('termcoursefilter_desc', 'block_course_overview_campus', null, true),
                0));

        $settingspage->add(new admin_setting_configtext('block_course_overview_campus/termcoursefilterdisplayname',
                get_string('termcoursefilterdisplayname', 'block_course_overview_campus', null, true),
                get_string('termcoursefilterdisplayname_desc', 'block_course_overview_campus', null, true),
                get_string('term', 'block_course_overview_campus', null, true),
                PARAM_TEXT));


        // Term filter: Term definition.
        // Check if the configured term dates make sense, if not show warning information.
        $coc_config = get_config('block_course_overview_campus');
        if (isset($coc_config->termcoursefilter) &&
                $coc_config->termcoursefilter == true &&
                !block_course_overview_campus_check_term_config($coc_config)) {
            $settingspage->add(new admin_setting_heading('block_course_overview_campus/termsettingheading',
                    get_string('termsettingheading', 'block_course_overview_campus'),
                    '<span class="errormessage">'.get_string('termsettingerror', 'block_course_overview_campus', null, true).'</span>'));
        } else {
            $settingspage->add(new admin_setting_heading('block_course_overview_campus/termsettingheading',
                    get_string('termsettingheading', 'block_course_overview_campus', null, true), ''));
        }

        // Possible term modes.
        $termmodes[1] = get_string('academicyear_desc', 'block_course_overview_campus', null, false); // Don't use string lazy loading here because the string will be directly used and would produce a PHP warning otherwise.
        $termmodes[2] = get_string('semester_desc', 'block_course_overview_campus', null, true);
        $termmodes[3] = get_string('tertial_desc', 'block_course_overview_campus', null, true);
        $termmodes[4] = get_string('trimester_desc', 'block_course_overview_campus', null, true);

        $settingspage->add(new admin_setting_configselect('block_course_overview_campus/termmode',
                get_string('termmode', 'block_course_overview_campus', null, true),
                get_string('termmode_desc', 'block_course_overview_campus', null, true),
                $termmodes[1],
                $termmodes));


        // Get all calendar days for later use.
        $format = get_string('strftimedateshort', 'langconfig');
        for ($i = 1; $i <= 12; $i++) {
            for ($j = 1; $j <= date('t', mktime(0, 0, 0, $i, 1, 2003)); $j++) { // Use no leap year to calculate days in month to avoid providing 29th february as an option.
                // Create an intermediate timestamp with each day-month-combination and format it
                // according to local date format for displaying purpose.
                $daystring = userdate(gmmktime(12, 0, 0, $i, $j, 2003), $format);

                // Add the day as an option.
                $days[sprintf('%02d', $i).'-'.sprintf('%02d', $j)] = $daystring;
            }
        }

        $settingspage->add(new admin_setting_configselect('block_course_overview_campus/term1startday',
                get_string('term1startday', 'block_course_overview_campus', null, true),
                get_string('term1startday_desc', 'block_course_overview_campus', null, true),
                $days['01-01'],
                $days));

        $settingspage->add(new admin_setting_configselect('block_course_overview_campus/term2startday',
                get_string('term2startday', 'block_course_overview_campus', null, true),
                get_string('term2startday_desc', 'block_course_overview_campus', null, true),
                $days['01-01'],
                $days));

        $settingspage->add(new admin_setting_configselect('block_course_overview_campus/term3startday',
                get_string('term3startday', 'block_course_overview_campus', null, true),
                get_string('term3startday_desc', 'block_course_overview_campus', null, true),
                $days['01-01'],
                $days));

        $settingspage->add(new admin_setting_configselect('block_course_overview_campus/term4startday',
                get_string('term4startday', 'block_course_overview_campus', null, true),
                get_string('term4startday_desc', 'block_course_overview_campus', null, true),
                $days['01-01'],
                $days));


        // Term filter: Term names.
        $settingspage->add(new admin_setting_heading('block_course_overview_campus/termnamesettingheading',
                get_string('termnamesettingheading', 'block_course_overview_campus', null, true),
                ''));

        $settingspage->add(new admin_setting_configtext('block_course_overview_campus/term1name',
                get_string('term1name', 'block_course_overview_campus', null, true),
                get_string('term1name_desc', 'block_course_overview_campus', null, true),
                get_string('term1', 'block_course_overview_campus'),
                PARAM_TEXT));

        $settingspage->add(new admin_setting_configtext('block_course_overview_campus/term2name',
                get_string('term2name', 'block_course_overview_campus', null, true),
                get_string('term2name_desc', 'block_course_overview_campus', null, true),
                get_string('term2', 'block_course_overview_campus'),
                PARAM_TEXT));

        $settingspage->add(new admin_setting_configtext('block_course_overview_campus/term3name',
                get_string('term3name', 'block_course_overview_campus', null, true),
                get_string('term3name_desc', 'block_course_overview_campus', null, true),
                get_string('term3', 'block_course_overview_campus'),
                PARAM_TEXT));

        $settingspage->add(new admin_setting_configtext('block_course_overview_campus/term4name',
                get_string('term4name', 'block_course_overview_campus', null, true),
                get_string('term4name_desc', 'block_course_overview_campus', null, true),
                get_string('term4', 'block_course_overview_campus'),
                PARAM_TEXT));

        // Possible year positions for later use.
        $termyearpos[1] = get_string('termyearposprefixspace_desc', 'block_course_overview_campus', null, true);
        $termyearpos[2] = get_string('termyearposprefixnospace_desc', 'block_course_overview_campus', null, true);
        $termyearpos[3] = get_string('termyearpossuffixspace_desc', 'block_course_overview_campus', null, false); // Don't use string lazy loading here because the string will be directly used and would produce a PHP warning otherwise.
        $termyearpos[4] = get_string('termyearpossuffixnospace_desc', 'block_course_overview_campus', null, true);

        $settingspage->add(new admin_setting_configselect('block_course_overview_campus/termyearpos',
                get_string('termyearpos', 'block_course_overview_campus', null, true),
                get_string('termyearpos_desc', 'block_course_overview_campus', null, true),
                $termyearpos[3],
                $termyearpos));

        // Possible year separators for later use.
        $termyearseparation[1] = get_string('termyearseparationhyphen_desc', 'block_course_overview_campus', null, true);
        $termyearseparation[2] = get_string('termyearseparationslash_desc', 'block_course_overview_campus', null, false); // Don't use string lazy loading here because the string will be directly used and would produce a PHP warning otherwise.
        $termyearseparation[3] = get_string('termyearseparationunderscore_desc', 'block_course_overview_campus', null, true);
        $termyearseparation[4] = get_string('termyearseparationnosecondyear_desc', 'block_course_overview_campus', null, true);

        $settingspage->add(new admin_setting_configselect('block_course_overview_campus/termyearseparation',
                get_string('termyearseparation', 'block_course_overview_campus', null, true),
                get_string('termyearseparation_desc', 'block_course_overview_campus', null, true),
                $termyearseparation[2],
                $termyearseparation));


        // Term filter: Term behaviour.
        $settingspage->add(new admin_setting_heading('block_course_overview_campus/termbehavioursettingheading',
                get_string('termbehavioursettingheading', 'block_course_overview_campus', null, true),
                ''));

        $settingspage->add(new admin_setting_configcheckbox('block_course_overview_campus/defaultterm',
                get_string('defaultterm', 'block_course_overview_campus', null, true),
                get_string('defaultterm_desc', 'block_course_overview_campus', null, true),
                1));


        // Term filter: Timeless courses.
        $settingspage->add(new admin_setting_heading('block_course_overview_campus/timelesscoursessettingheading',
                get_string('timelesscoursessettingheading', 'block_course_overview_campus', null, true),
                ''));

        $settingspage->add(new admin_setting_configcheckbox('block_course_overview_campus/timelesscourses',
                get_string('timelesscourses', 'block_course_overview_campus', null, true),
                get_string('timelesscourses_desc', 'block_course_overview_campus', null, true),
                1));

        $settingspage->add(new admin_setting_configtext('block_course_overview_campus/timelesscoursesname',
                get_string('timelesscoursesname', 'block_course_overview_campus', null, true),
                get_string('timelesscoursesname_desc', 'block_course_overview_campus', null, true),
                get_string('timelesscourses', 'block_course_overview_campus', null, true),
                PARAM_TEXT));

        // Get all years from 1970.
        for ($i = 1971; $i <= date('Y'); $i++) {
            // Add the year as an option.
            $years[$i] = $i;
        }

        $settingspage->add(new admin_setting_configselect('block_course_overview_campus/timelesscoursesthreshold',
                get_string('timelesscoursesthreshold', 'block_course_overview_campus', null, true),
                get_string('timelesscoursesthreshold_desc', 'block_course_overview_campus', null, true),
                $years[date('Y') - 1],
                $years));

        // Add settings page to the admin settings category.
        $ADMIN->add('block_course_overview_campus', $settingspage);
    }
}
