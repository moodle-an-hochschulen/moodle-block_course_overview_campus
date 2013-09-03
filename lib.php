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
 * Block "course overview (campus)" - Library
 *
 * @package     block
 * @subpackage  block_course_overview_campus
 * @copyright   2013 Alexander Bias, University of Ulm <alexander.bias@uni-ulm.de>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Check if the configured term dates make sense
 *
 * @param object $config The config object
 * @return bool
 */
function check_term_config($config) {
    if ($config->termmode == 1) {
        return true;
    }
    else if ($config->termmode == 2 &&
        intval(date('z', strtotime('2003-'.$config->term1startday))) < intval(date('z', strtotime('2003-'.$config->term2startday)))) {
            return true;
    }
    else if ($config->termmode == 3 &&
        intval(date('z', strtotime('2003-'.$config->term1startday))) < intval(date('z', strtotime('2003-'.$config->term2startday))) &&
        intval(date('z', strtotime('2003-'.$config->term2startday))) < intval(date('z', strtotime('2003-'.$config->term3startday)))) {
            return true;
    }
    else if ($config->termmode == 4 &&
        intval(date('z', strtotime('2003-'.$config->term1startday))) < intval(date('z', strtotime('2003-'.$config->term2startday))) &&
        intval(date('z', strtotime('2003-'.$config->term2startday))) < intval(date('z', strtotime('2003-'.$config->term3startday))) &&
        intval(date('z', strtotime('2003-'.$config->term3startday))) < intval(date('z', strtotime('2003-'.$config->term4startday)))) {
            return true;
    }
    else {
        return false;
    }
}


/**
 * Take array of teacher objects and return a string of names, sorted by relevance and name
 *
 * @param array $teachers Array of teachers
 * @return string String with concatenated teacher names
 */
function get_teachername_string($teachers) {
    // If given array is empty, return empty string
    if (empty($teachers))
        return '';

    // Sort all teachers by relevance and name, return empty string when sorting fails
    $success = usort($teachers, "compare_teachers");
    if (!$success) {
        return '';
    }

    // Get all teachers' names as an array
    $teachernames = array_map(function($obj) {
        return $obj->lastname;
    }, $teachers);

    // Implode teachers' names to a single string
    $teachernames = implode(", ", $teachernames);

    return $teachernames;
}


/**
 * Take term name and year(s) and return displayname for term filter based on plugin configuration
 *
 * @param string $termname The term's name
 * @param string $year The term's year
 * @param string $year2 The term's second year (optional)(
 * @return string String with the term's displayname
 */
function get_term_displayname($termname, $year, $year2='') {
    global $config;

    // Build the first year - second year combination
    $displayname = $year;
    if ($year2 != '') {
        // Hyphen separation
        if ($config->termyearseparation == 1) {
            $displayname = $year.'-'.$year2;
        }
        // Slash separation
        else if ($config->termyearseparation == 2) {
            $displayname = $year.'/'.$year2;
        }
        // Underscore separation
        else if ($config->termyearseparation == 3) {
            $displayname = $year.'_'.$year2;
        }
        // No second year
        else if ($config->termyearseparation == 4) {
            $displayname = $year;
        }
        // This shouldn't happen
        else {
            $displayname = $year.'/'.$year2;
        }
    }

    // Add the term name
    // Prefix with space
    if ($config->termyearpos == 1) {
        $displayname = $displayname.' '.$termname;
    }
    // Prefix without space
    else if ($config->termyearpos == 2) {
        $displayname = $displayname.$termname;
    }
    // Suffix with space
    else if ($config->termyearpos == 3) {
        $displayname = $termname.' '.$displayname;
    }
    // Suffix without space
    else if ($config->termyearpos == 4) {
        $displayname = $termname.$displayname;
    }
    // This shouldn't happen
    else {
        $displayname = $termname. ' '.$termname;
    }

    return $displayname;
}


/**
 * Compare helper function
 *
 * @param object $a Teacher A
 * @param object $b Teacher B
 * @return int
 */
function compare_teachers($a, $b) {
    // compare relevance of teachers' roles
    if ($a->sortorder < $b->sortorder) {
        return -1;
    }
    else if ($a->sortorder > $b->sortorder) {
        return 1;
    }
    else if ($a->sortorder == $b->sortorder) {
        // teachers' roles are equal, then compare lastnames
        return strcasecmp($a->lastname, $b->lastname);
    }
    else {
        // This should never happen
        return 0;
    }
}
