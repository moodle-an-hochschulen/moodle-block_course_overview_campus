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

// Check if the configured term dates make sense
function check_term_config($config) {
	if ($config->termmode == 1)
		return true;
	elseif ($config->termmode == 2 && 
		intval(date('z', strtotime('2003-'.$config->term1startday))) < intval(date('z', strtotime('2003-'.$config->term2startday))))
			return true;
	elseif ($config->termmode == 3 && 
		intval(date('z', strtotime('2003-'.$config->term1startday))) < intval(date('z', strtotime('2003-'.$config->term2startday))) &&			
		intval(date('z', strtotime('2003-'.$config->term2startday))) < intval(date('z', strtotime('2003-'.$config->term3startday))))
			return true;
	elseif ($config->termmode == 4 && 
		intval(date('z', strtotime('2003-'.$config->term1startday))) < intval(date('z', strtotime('2003-'.$config->term2startday))) &&			
		intval(date('z', strtotime('2003-'.$config->term2startday))) < intval(date('z', strtotime('2003-'.$config->term3startday))) &&			
		intval(date('z', strtotime('2003-'.$config->term3startday))) < intval(date('z', strtotime('2003-'.$config->term4startday)))) {
			return true;
	}
	else {
		return false;
	}
}


// Take array of teacher objects and return a string of names, sorted by relevance and name
function get_teachername_string($teachers) {
	// Sort all teachers by relevance and name, return empty string when sorting fails
	$success = usort($teachers, "compare_teachers");
	if (!$success)
		return '';
	
	// Get all teachers' names as an array
	$teachernames = array_map(function($obj) { return $obj->lastname; }, $teachers);
	
	// Implode teachers' names to a single string
	$teachernames = implode(", ", $teachernames);

	return $teachernames;
}


// Helper function
function compare_teachers($a, $b) {
	// compare relevance of teachers' roles
	if ($a->sortorder < $b->sortorder) {
		return -1;
	}
	elseif ($a->sortorder > $b->sortorder) {
		return 1;
	}
	elseif ($a->sortorder == $b->sortorder) {
		// teachers' roles are equal, then compare lastnames
		return strcasecmp($a->lastname, $b->lastname);
	}
	else {
		// This should never happen
		return 0;
	}
}