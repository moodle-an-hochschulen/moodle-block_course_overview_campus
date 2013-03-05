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

class block_course_overview_campus extends block_base {

	public function init() {
		$this->title = get_string('pluginname', 'block_course_overview_campus');
	}
	
	public function specialization() {
		$this->title = get_string('pluginname', 'block_course_overview');
	}

	public function get_content() {
		global $USER, $CFG, $DB, $PAGE, $OUTPUT;
	
		if($this->content !== NULL) {
			return $this->content;
		}
			

			
		/********************************************************************************/
		/***                              PREPROCESSING                               ***/
		/********************************************************************************/

		// Get block config
		$config = get_config('block_course_overview_campus');
		
		// Check if the configured term dates make sense, if not disable term filter
		if (!check_term_config($config)) {
			$config->termcoursefilter = false;
		}


		// Process GET parameters
		$hidecourse = optional_param('hidecourse', 0, PARAM_INT);
		$showcourse = optional_param('showcourse', 0, PARAM_INT);
		$hidenews = optional_param('hidenews', 0, PARAM_INT);
		$shownews = optional_param('shownews', 0, PARAM_INT);
		$manage = optional_param('manage', 0, PARAM_BOOL);
		$term = optional_param('term', null, PARAM_TEXT);
		$category = optional_param('category', null, PARAM_TEXT);
		$teacher = optional_param('teacher', null, PARAM_TEXT);
		

		// Set displaying preferences when set by GET parameters
		if ($hidecourse != 0) {
			set_user_preference('block_course_overview_campus-hidecourse-'.$hidecourse, 1);
		}
		if ($showcourse != 0) {
			set_user_preference('block_course_overview_campus-hidecourse-'.$showcourse, 0);
		}
		if ($hidenews != 0) {
			set_user_preference('block_course_overview_campus-hidenews-'.$hidenews, 1); 
		}
		if ($shownews != 0) {
			set_user_preference('block_course_overview_campus-hidenews-'.$shownews, 0);
		}

	
		// Set and remember term filter if GET parameter is present
		if ($term != null) {
			$selectedterm = $term;
			set_user_preference('block_course_overview_campus-selectedterm', $term);
		} 
		// Or set term filter based on user preference with default term fallback if activated
		elseif ($config->defaultterm == true) {
			$selectedterm = get_user_preferences('block_course_overview_campus-selectedterm', 'currentterm');
		} 
		// Or set term filter based on user preference with 'all' terms fallback
		else {
			$selectedterm = get_user_preferences('block_course_overview_campus-selectedterm', 'all');
		}
		

		// Set and remember category filter if GET parameter is present
		if ($category != null) {
			$selectedcategory = $category;
			set_user_preference('block_course_overview_campus-selectedcategory', $category);
		} 
		// Or set category filter based on user preference with 'all' categories fallback
		else {
			$selectedcategory = get_user_preferences('block_course_overview_campus-selectedcategory', 'all');
		}

	
		// Set and remember teacher filter if GET parameter is present
		if ($teacher != null) {
			$selectedteacher = $teacher;
			set_user_preference('block_course_overview_campus-selectedteacher', $teacher);
		} 
		// Or set teacher filter based on user preference with 'all' teachers fallback
		else {
			$selectedteacher = get_user_preferences('block_course_overview_campus-selectedteacher', 'all');
		}
		
	
		// Get my courses in alphabetical order
		$courses = enrol_get_my_courses('id, shortname, modinfo, sectioncache', 'fullname ASC');

		// Remove frontpage course, if enrolled, from courses list
		$site = get_site();
		if (array_key_exists($site->id, $courses)) {
			unset($courses[$site->id]);
		}
		

	
		/********************************************************************************/
		/***                             PROCESS MY COURSES                           ***/
		/********************************************************************************/

		// No, I don't have any courses -> content is only a placeholder message
		if (empty($courses)) {
			$content = get_string('nocourses','block_course_overview_campus');
		} 
		// Yes, I have courses
		else {
			// Start output buffer
			ob_start();


			// Get lastaccess of all courses to support course news
			foreach ($courses as $c) {
				if (isset($USER->lastcourseaccess[$c->id])) {
					$courses[$c->id]->lastaccess = $USER->lastcourseaccess[$c->id];
				} 
				else {
					$courses[$c->id]->lastaccess = 0;
				}
			}
			
			// Get course news from my courses
			$coursenews = array();
			if ($modules = $DB->get_records('modules')) {
				foreach ($modules as $mod) {
					if (file_exists($CFG->dirroot.'/mod/'.$mod->name.'/lib.php')) {
						include_once($CFG->dirroot.'/mod/'.$mod->name.'/lib.php');
						$fname = $mod->name.'_print_overview';
						if (function_exists($fname)) {
							$fname($courses, $coursenews);
						}
					}
				}
			}
	

			// Get course categories for later use
			$coursecategories = $DB->get_records('course_categories');
	
			// Get teacher roles for later use
			$CFG->coursecontact = trim($CFG->coursecontact);
			if (!empty($CFG->coursecontact)) {
				$teacherroles = explode(',', $CFG->coursecontact);
			}
			else {
				$teacherroles = array();
			}


			// Create empty filter for activated filters
			if ($config->termcoursefilter == true) {
				$filterterms = array();
			}
			if ($config->categorycoursefilter == true) {
				$filtercategories = array();
			}
			if ($config->teachercoursefilter == true) {
				$filterteachers = array();
			}

			// Create counter for hidden courses
			$hiddencourses = 0;
			
			// Create strings to remember courses and course news for YUI processing
			$yui_courseslist = ' ';
			$yui_coursenewslist = ' ';

			
			// Now iterate over courses and collect data about my courses
			foreach ($courses as $c) {
				// Populate filters with data about my courses
				// Term filter
				if ($config->termcoursefilter == true) {
					// Create object for bufferung course term information
					$courseterm = new stdClass();

					// If course start date is undefined, set course term to "other"
					if ($c->startdate == 0) {
						$courseterm->id = 'other';
						$courseterm->name = 'other';
					} 

					// If course start date is available, distinguish between term modes
					// "Academic year" mode
					elseif ($config->termmode == 1) {
						// If term starts on January 1st, set course term to course start date's year
						if ($config->term1startday == 1) {
							$courseterm->id = $courseterm->name = date('Y', $c->startdate);
						} 
						// If term doesn't start on January 1st and course start date's day comes on or after term start day, set course term to course start date's year + next year
						elseif (intval(date('z', $c->startdate)) >= intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term1startday)))) {
							$courseterm->id = $courseterm->name = date('Y', $c->startdate).'-'.(date('Y', $c->startdate)+1);
						} 
						// If term doesn't start on January 1st and course start date's day comes before term start day, set course term to course start date's year + former year
						else {
							$courseterm->id = $courseterm->name = (date('Y', $c->startdate)-1).'-'.date('Y', $c->startdate);
						}
					}
					// "Semester" mode
					elseif ($config->termmode == 2) {
						// If course start date's day comes before first term start day, set course term to second term of former year
						if (intval(date('z', $c->startdate)) < intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term1startday)))) {
							$courseterm->id = (date('Y', $c->startdate)-1).'-2';
							$courseterm->name = $config->term2name.' '.(date('Y', $c->startdate)-1).'/'.date('Y', $c->startdate);
						}
						// If course start date's day comes on or after first term start day but before second term start day, set course term to first term of current year
						elseif (intval(date('z', $c->startdate)) >= intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term1startday))) && 
								intval(date('z', $c->startdate)) < intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term2startday)))) {
							$courseterm->id = date('Y', $c->startdate).'-1';
							$courseterm->name = $config->term1name.' '.date('Y', $c->startdate);
						}
						// If course start date's day comes on or after second term start day, set course term to second term of current year
						else {
							$courseterm->id = date('Y', $c->startdate).'-2';
							// If first term does start on January 1st, suffix name with single year, otherwise suffix name with double year
							if ($config->term1startday == '1')
								$courseterm->name = $config->term2name.' '.date('Y', $c->startdate);
							else
								$courseterm->name = $config->term2name.' '.date('Y', $c->startdate).'/'.(date('Y', $c->startdate)+1);
						}
					}
					// "Tertial" mode
					elseif ($config->termmode == 3) {
						// If course start date's day comes before first term start day, set course term to third term of former year
						if (intval(date('z', $c->startdate)) < intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term1startday)))) {
							$courseterm->id = (date('Y', $c->startdate)-1).'-3';
							$courseterm->name = $config->term3name.' '.(date('Y', $c->startdate)-1).'/'.date('Y', $c->startdate);
						}
						// If course start date's day comes on or after first term start day but before second term start day, set course term to first term of current year
						elseif (intval(date('z', $c->startdate)) >= intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term1startday))) && 
								intval(date('z', $c->startdate)) < intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term2startday)))) {
							$courseterm->id = date('Y', $c->startdate).'-1';
							$courseterm->name = $config->term1name.' '.date('Y', $c->startdate);
						}
						// If course start date's day comes on or after second term start day but before third term start day, set course term to second term of current year
						elseif (intval(date('z', $c->startdate)) >= intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term2startday))) && 
								intval(date('z', $c->startdate)) < intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term3startday)))) {
							$courseterm->id = date('Y', $c->startdate).'-2';
							$courseterm->name = $config->term2name.' '.date('Y', $c->startdate);
						}
						// If course start date's day comes on or after third term start day, set course term to third term of current year
						else {
							$courseterm->id = date('Y', $c->startdate).'-3';
							// If first term does start on January 1st, suffix name with single year, otherwise suffix name with double year
							if ($config->term1startday == '1')
								$courseterm->name = $config->term3name.' '.date('Y', $c->startdate);
							else
								$courseterm->name = $config->term3name.' '.date('Y', $c->startdate).'/'.(date('Y', $c->startdate)+1);
						}
					}
					// "Trimester" mode
					elseif ($config->termmode == 4) {
						// If course start date's day comes before first term start day, set course term to fourth term of former year
						if (intval(date('z', $c->startdate)) < intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term1startday)))) {
							$courseterm->id = (date('Y', $c->startdate)-1).'-4';
							$courseterm->name = $config->term4name.' '.(date('Y', $c->startdate)-1).'/'.date('Y', $c->startdate);
						}
						// If course start date's day comes on or after first term start day but before second term start day, set course term to first term of current year
						elseif (intval(date('z', $c->startdate)) >= intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term1startday))) && 
								intval(date('z', $c->startdate)) < intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term2startday)))) {
							$courseterm->id = date('Y', $c->startdate).'-1';
							$courseterm->name = $config->term1name.' '.date('Y', $c->startdate);
						}
						// If course start date's day comes on or after second term start day but before third term start day, set course term to second term of current year
						elseif (intval(date('z', $c->startdate)) >= intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term2startday))) && 
								intval(date('z', $c->startdate)) < intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term3startday)))) {
							$courseterm->id = date('Y', $c->startdate).'-2';
							$courseterm->name = $config->term2name.' '.date('Y', $c->startdate);
						}
						// If course start date's day comes on or after third term start day but before fourth term start day, set course term to third term of current year
						elseif (intval(date('z', $c->startdate)) >= intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term3startday))) && 
								intval(date('z', $c->startdate)) < intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term4startday)))) {
							$courseterm->id = date('Y', $c->startdate).'-3';
							$courseterm->name = $config->term3name.' '.date('Y', $c->startdate);
						}
						// If course start date's day comes on or after fourth term start day, set course term to fourth term of current year
						else {
							$courseterm->id = date('Y', $c->startdate).'-4';
							// If first term does start on January 1st, suffix name with single year, otherwise suffix name with double year
							if ($config->term1startday == '1')
								$courseterm->name = $config->term4name.' '.date('Y', $c->startdate);
							else
								$courseterm->name = $config->term4name.' '.date('Y', $c->startdate).'/'.(date('Y', $c->startdate)+1);
						}
					}
					// This should never happen
					else {
						print_error('error');
					}

					// Remember course term for later use
					$c->term = $courseterm->id;

					// Add course term to filter list
					$filterterms[$courseterm->id] = $courseterm->name;

					// Cleanup
					unset ($courseterm);
				}
	
				// Category filter
				if ($config->categorycoursefilter == true) {
					// Get course category name from array of all category names
					$coursecategory = $coursecategories[$c->category];

					// Remember course category name for later use
					$c->categoryname = format_string($coursecategory->name);
					$c->categoryid = $coursecategory->id;

					// Add course category name and classname to filter list
					$filtercategories[$c->categoryid] = $c->categoryname;
				}
	
				// Teacher filter
				if ($config->teachercoursefilter == true) {
					// Get course context
					$context = context_course::instance($c->id);
					
					// Get course teachers based on global teacher roles
					$courseteachers = get_role_users($teacherroles, $context, true);

					// Remember course teachers for later use					
					$c->teachers = $courseteachers;

					// Add all course teacher's names to filter list
					foreach ($courseteachers as $ct) {
						$filterteachers[$ct->id] = $ct->lastname.', '.$ct->firstname;
					}
				}
				

				// Check if this course should be shown or not
				$courses[$c->id]->hidecourse = get_user_preferences('block_course_overview_campus-hidecourse-'.$c->id, 0);
				if ($courses[$c->id]->hidecourse == 1)
					$hiddencourses++;
	
				// Check if this course should show news or not
				$courses[$c->id]->hidenews = get_user_preferences('block_course_overview_campus-hidenews-'.$c->id, 0);
			}
	

			// Replace and remember currentterm placeholder with precise term based on my courses
			if ($config->termcoursefilter == true && $selectedterm == 'currentterm') {
				// Distinguish between term modes
				// "Academic year" mode
				if ($config->termmode == '1') {
					// If term starts on January 1st and there are courses this year, set selected term to this year
					if ($config->term1startday == '1' && isset($filterterms[date('Y')])) {
						$selectedterm = date('Y');
					} 
					// If term doesn't start on January 1st and current day comes on or after term start day and there are courses this term, set selected term to this year + next year
					elseif (intval(date('z')) >= intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term1startday))) && isset($filterterms[date('Y').'-'.(date('Y')+1)])) {
						$selectedterm = date('Y').'-'.(date('Y')+1); 
					} 
					// If term doesn't start on January 1st and current day comes before term start day and there are courses this term, set selected term to this year + former year
					elseif (isset($filterterms[(date('Y')-1).'-'.date('Y')])) {
						$selectedterm = (date('Y')-1).'-'.date('Y');
					} 
					// Otherwise set selected term to the latest (but not future) term possible
					else {
						$selectedterm = 'all';
						arsort($filterterms);
						foreach ($filterterms as $t) {
							if ($t != 'other' && intval(substr($t, 0, 4)) <= intval(date('Y'))) {
								$selectedterm = $t;
								break;
							}
						}
					}
				}
				// "Semester" mode
				elseif ($config->termmode == '2') {
					// If current day comes before first term start day and there are courses this term, set selected term to second term of former year
					if (intval(date('z')) < intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term1startday))) && isset($filterterms[(date('Y')-1).'-2'])) {
						$selectedterm = (date('Y')-1).'-2';
					}
					// If current day comes on or after first term start day but before second term start day and there are courses this term, set selected term to first term of current year
					elseif (intval(date('z', $c->startdate)) >= intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term1startday))) && 
							intval(date('z', $c->startdate)) < intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term2startday))) &&
							isset($filterterms[date('Y').'-1'])) {
						$selectedterm = date('Y').'-1'; 
					}
					// If course start date's day comes on or after second term start day and there are courses this term, set selected term to second term of current year
					elseif (isset($filterterms[date('Y').'-2'])) {
						$selectedterm = date('Y').'-2';
					}
					// Otherwise set selected term to the latest (but not future) term possible
					else {
						$selectedterm = 'all';
						krsort($filterterms);
						foreach ($filterterms as $t => $n) {
							if ($t != 'other' && intval(substr($t, 0, 4)) <= intval(date('Y'))) {
								$selectedterm = $t;
								break;
							}
						}
					}
				}
				// "Tertial" mode
				elseif ($config->termmode == '3') {
					// If current day comes before first term start day and there are courses this term, set selected term to third term of former year
					if (intval(date('z')) < intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term1startday))) && isset($filterterms[(date('Y')-1).'-3'])) {
						$selectedterm = (date('Y')-1).'-2';
					}
					// If current day comes on or after first term start day but before second term start day and there are courses this term, set selected term to first term of current year
					elseif (intval(date('z', $c->startdate)) >= intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term1startday))) && 
							intval(date('z', $c->startdate)) < intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term2startday))) &&
							isset($filterterms[date('Y').'-1'])) {
						$selectedterm = date('Y').'-1'; 
					}
					// If current day comes on or after second term start day but before third term start day and there are courses this term, set selected term to second term of current year
					elseif (intval(date('z', $c->startdate)) >= intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term2startday))) && 
							intval(date('z', $c->startdate)) < intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term3startday))) &&
							isset($filterterms[date('Y').'-2'])) {
						$selectedterm = date('Y').'-2'; 
					}
					// If course start date's day comes on or after third term start day and there are courses this term, set selected term to third term of current year
					elseif (isset($filterterms[date('Y').'-3'])) {
						$selectedterm = date('Y').'-3';
					}
					// Otherwise set selected term to the latest (but not future) term possible
					else {
						$selectedterm = 'all';
						krsort($filterterms);
						foreach ($filterterms as $t => $n) {
							if ($t != 'other' && intval(substr($t, 0, 4)) <= intval(date('Y'))) {
								$selectedterm = $t;
								break;
							}
						}
					}
				}
				// "Trimester" mode
				elseif ($config->termmode == '4') {
					// If current day comes before first term start day and there are courses this term, set selected term to fourth term of former year
					if (intval(date('z')) < intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term1startday))) && isset($filterterms[(date('Y')-1).'-4'])) {
						$selectedterm = (date('Y')-1).'-2';
					}
					// If current day comes on or after first term start day but before second term start day and there are courses this term, set selected term to first term of current year
					elseif (intval(date('z', $c->startdate)) >= intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term1startday))) && 
							intval(date('z', $c->startdate)) < intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term2startday))) &&
							isset($filterterms[date('Y').'-1'])) {
						$selectedterm = date('Y').'-1'; 
					}
					// If current day comes on or after second term start day but before third term start day and there are courses this term, set selected term to second term of current year
					elseif (intval(date('z', $c->startdate)) >= intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term2startday))) && 
							intval(date('z', $c->startdate)) < intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term3startday))) &&
							isset($filterterms[date('Y').'-2'])) {
						$selectedterm = date('Y').'-2'; 
					}
					// If current day comes on or after third term start day but before fourth term start day and there are courses this term, set selected term to third term of current year
					elseif (intval(date('z', $c->startdate)) >= intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term3startday))) && 
							intval(date('z', $c->startdate)) < intval(date('z', strtotime(date('Y', $c->startdate).'-'.$config->term4startday))) &&
							isset($filterterms[date('Y').'-3'])) {
						$selectedterm = date('Y').'-3'; 
					}
					// If course start date's day comes on or after fourth term start day and there are courses this term, set selected term to fourth term of current year
					elseif (isset($filterterms[date('Y').'-4'])) {
						$selectedterm = date('Y').'-4';
					}
					// Otherwise set selected term to the latest (but not future) term possible
					else {
						$selectedterm = 'all';
						krsort($filterterms);
						foreach ($filterterms as $t => $n) {
							if ($t != 'other' && intval(substr($t, 0, 4)) <= intval(date('Y'))) {
								$selectedterm = $t;
								break;
							}
						}
					}
				}
				// This should never happen
				else {
					print_error('error');
				}

				// Remember selected term
				set_user_preference('block_course_overview_campus-selectedterm', $selectedterm);
			}



			/********************************************************************************/
			/***                        GENERATE OUTPUT FOR FILTER                        ***/
			/********************************************************************************/

			// Show filter form if any filter is activated
			if ($config->categorycoursefilter == true || $config->termcoursefilter == true || $config->teachercoursefilter == true) {
				// Start section and form
				echo '<div id="coc-filterlist"><form method="post" action="">';
	
				// Show term filter			
				if($config->termcoursefilter == true) {
					echo '<div class="coc-filter">';
	
					// Show filter description
					echo format_string($config->termcoursefilterdisplayname);
					if ($config->termcoursefilterdisplayname != '')
						echo '<br />';

					echo '<select name="term" id="filterTerm">';
	
					// Remember in this variable if selected term was displayed or not
					$selectedtermdisplayed = false;

					// Sort term filter alphabetically in reverse order
					krsort($filterterms);

					// Print "All terms" option
					if ($selectedterm == 'all') {
						echo '<option value="all" selected>'.get_string('all', 'block_course_overview_campus').'</option> ';
						$selectedtermdisplayed = true;
					} 
					else {
						echo '<option value="all">'.get_string('all', 'block_course_overview_campus').'</option> ';
					}
					
					// Print each term in filter as an option item and select selected term
					foreach ($filterterms as $t => $n) {
						// If iterated term is selected term
						if ($selectedterm == $t) {
							// Handle "other" term option
							if ($selectedterm == 'other') {
								echo '<option selected value="other">'.get_string('other', 'block_course_overview_campus').'</option> ';
								$selectedtermdisplayed = true;
							} 
							else {
								echo '<option selected value="'.$t.'">'.format_string($n).'</option> ';
								$selectedtermdisplayed = true;
							}
						} 
						// If iterated term isn't selected term
						else {
							// Handle "other" term option
							if ($t == 'other') {
								echo '<option value="other">'.get_string('other', 'block_course_overview_campus').'</option> ';
							} 
							else {
								echo '<option value="'.$t.'">'.format_string($n).'</option> ';
							}
						}
					}

					echo '</select>';
	
					// If selected term couldn't be displayed, select all terms and save the new selection. In this case, no option item is marked as selected, but that's ok as the "all" item is at the top
					if (!$selectedtermdisplayed) {
						$selectedterm = 'all';
						set_user_preference('block_course_overview_campus-selectedterm', $selectedterm);
					}
	
					echo '</div>';
				}
	
				// Show category filter
				if($config->categorycoursefilter == true) {
					echo '<div class="coc-filter">';
	
					// Show filter description
					echo format_string($config->categorycoursefilterdisplayname);
					if ($config->categorycoursefilterdisplayname != '')
						echo '<br />';
	
					echo '<select name="category" id="filterCategory">';

					// Remember in this variable if selected category was displayed or not
					$selectedcategorydisplayed = false;

					// Sort category filter alphabetically
					natcasesort($filtercategories);
	
					// Print "All categories" option
					if ($selectedcategory == 'all') {
						echo '<option value="all" selected>'.get_string('all', 'block_course_overview_campus').'</option> ';
						$selectedcategorydisplayed = true;
					} 
					else {
						echo '<option value="all">'.get_string('all', 'block_course_overview_campus').'</option> ';
					}
					
					// Print each category in filter as an option item and select selected category
					foreach ($filtercategories as $value => $cat) {
						// If iterated category is selected category
						if ($selectedcategory == $value) {
							echo '<option selected value="'.$value.'">'.$cat.'</option> ';
							$selectedcategorydisplayed = true;
						} 
						// If iterated category isn't selected category
						else {
							echo '<option value="'.$value.'">'.$cat.'</option> ';
						}
					}

					echo '</select>';
	
					// If selected category couldn't be displayed, select all categories and save the new selection. In this case, no option item is marked as selected, but that's ok as the "all" item is at the top
					if (!$selectedcategorydisplayed) {
						$selectedcategory = 'all';
						set_user_preference('block_course_overview_campus-selectedcategory', $selectedcategory);
					}
	
					echo '</div>';
				}
				
				// Show teacher filter
				if($config->teachercoursefilter == true) {
					echo '<div class="coc-filter">';
	
					// Show filter description
					echo format_string($config->teachercoursefilterdisplayname);
					if ($config->teachercoursefilterdisplayname != '')
						echo '<br />';
	
					echo '<select name="teacher" id="filterTeacher">';

					// Remember in this variable if selected teacher was displayed or not
					$selectedteacherdisplayed = false;

					// Sort teacher filter alphabetically
					natcasesort($filterteachers);
	
					// Print "All teachers" option
					if ($selectedteacher == 'all') {
						echo '<option value="all" selected>'.get_string('all', 'block_course_overview_campus').'</option> ';
						$selectedteacherdisplayed = true;
					} 
					else {
						echo '<option value="all">'.get_string('all', 'block_course_overview_campus').'</option> ';
					}
					
					// Print each teacher in filter as an option item and select selected teacher
					foreach ($filterteachers as $id=>$t) {
						// If iterated teacher is selected teacher
						if ($selectedteacher == $id) {
							echo '<option selected value="'.$id.'">'.$t.'</option> ';
							$selectedteacherdisplayed = true;
						} 
						else {
							echo '<option value="'.$id.'">'.$t.'</option> ';
						}
					}

					echo '</select>';

					// If selected teacher couldn't be displayed, select all teachers and save the new selection. In this case, no option item is marked as selected, but that's ok as the "all" item is at the top
					if (!$selectedteacherdisplayed) {
						$selectedteacher = 'all';
						set_user_preference('block_course_overview_campus-selectedteacher', $selectedteacher);
					}
	
					echo '</div>';
				} 
	
				// Show submit button for Non-JavaScript interaction
				echo '<div id="coc-filtersubmit"><input type="submit" value="'.get_string('submitfilter', 'block_course_overview_campus').'" /></div>';
	
				// End section and form			
				echo '</form></div>';
			}
			

	
			/********************************************************************************/
			/***               GENERATE OUTPUT FOR HIDDEN COURSES MANAGEMENT              ***/
			/********************************************************************************/
	
			// I have hidden courses
			if ($hiddencourses > 0) {
				// And hidden courses managing is off
				if ($manage == false) {
					// Create footer with hidden courses information
					$footer = '<div id="coc-hiddencoursesmanagement">'.get_string('youhave', 'block_course_overview_campus').' <span id="coc-hiddencoursescount">'.$hiddencourses.'</span> '.get_string('hiddencourses', 'block_course_overview_campus').' | <a href="'.$PAGE->url->out_as_local_url(true, array('manage' => 1)).'">'.get_string('managehiddencourses', 'block_course_overview_campus').'</a></div>';
				} 
				// And hidden courses managing is on
				else {
					// Create toolbox with link for stopping management
					echo '<div class="coursebox toolbox"><a href="'.$PAGE->url->out_as_local_url(true, array('manage' => 0)).'">'.get_string('stopmanaginghiddencourses', 'block_course_overview_campus').'</a></div>';
	
					// Create footer with link for stopping management
					$footer = '<div id="coc-hiddencoursesmanagement"><a href="'.$PAGE->url->out_as_local_url(true, array('manage' => 0)).'">'.get_string('stopmanaginghiddencourses', 'block_course_overview_campus').'</a></div>';
				}
			}
			// I have no hidden courses
			else {
					// Prepare footer to appear as soon as a course is hidden
					$footer = '<div id="coc-hiddencoursesmanagement" class="coc-hidden">'.get_string('youhave', 'block_course_overview_campus').' <span id="coc-hiddencoursescount">'.$hiddencourses.'</span> '.get_string('hiddencourses', 'block_course_overview_campus').' | <a href="'.$PAGE->url->out_as_local_url(true, array('manage' => 1)).'">'.get_string('managehiddencourses', 'block_course_overview_campus').'</a></div>';
			}
			
		
	
			/********************************************************************************/
			/***                   GENERATE OUTPUT FOR COURSELIST                         ***/
			/********************************************************************************/

			// Start section
			echo '<div id="coc-courselist">';

			// Show courses
			foreach ($courses as $c) {
				// Remember course ID for YUI processing
				$yui_courseslist .= $c->id.' ';
				
				// Start course div as visible if it isn't hidden or if hidden courses are currently shwon
				if (($c->hidecourse == 0) || $manage == true) {
					echo '<div id="coc-course-'.$c->id.'" class="coc-course">';
				} 
				// Otherwise start course div as hidden
				else {
					echo '<div id="coc-course-'.$c->id.'" class="coc-course coc-hidden">';
				}
	
				// Start filter by term div - later we use this div to filter the course
				if ($config->termcoursefilter == true) {
					// Show course if it is within selected term or all terms are selected
					if ($c->term == $selectedterm || $selectedterm == 'all') {
						echo '<div class="termdiv coc-term-'.$c->term.'">';
					} 
					// Otherwise hide the course with CSS
					else {
						echo '<div class="termdiv coc-term-'.$c->term.' coc-hidden">';
					}
				}
	
				// Start filter by category div - later we use this div to filter the course
				if($config->categorycoursefilter == true) {
					// Show course if it is within selected category or all categories are selected
					if ($c->categoryid == $selectedcategory || $selectedcategory == 'all') {
						echo '<div class="categorydiv coc-category-'.$c->categoryid.'">';
					} 
					// Otherwise hide the course with CSS
					else {
						echo '<div class="categorydiv coc-category-'.$c->categoryid.' coc-hidden">';
					}
				}
	
				// Start filter by teacher div - later we use this div to filter the course
				if($config->teachercoursefilter == true) {
					// Start teacher div
					echo '<div class="teacherdiv';

					// Add all teachers 
					foreach ($c->teachers as $id=>$t) {
						echo ' coc-teacher-'.$id;
					}
					
					// Show course if it has the selected teacher or all teachers are selected
					if (isset($c->teachers[$selectedteacher]) || $selectedteacher=='all') {
						echo '">';
					} 
					// Otherwise hide the course with CSS
					else {
						echo ' coc-hidden">';
					}
				}
				

				// Start standard course overview coursebox
				echo $OUTPUT->box_start('coursebox');
	 
				// Output course news visibility control icons
				if (array_key_exists($c->id, $coursenews)) {
					// If course news are hidden
					if ($c->hidenews == 0) {
						echo '<div class="hidenewsicon">
								<a href="'.$PAGE->url->out_as_local_url(true, array('manage' => $manage, 'hidenews' => $c->id, 'shownews' => '')).'" id="coc-hidenews-'.$c->id.'" title="'.get_string('hidenews', 'block_course_overview_campus').'">
									<img src="'.$OUTPUT->pix_url('t/expanded').'" alt="'.get_string('hidenews', 'block_course_overview_campus').'" />
								</a>
								<a href="'.$PAGE->url->out_as_local_url(true, array('manage' => $manage, 'hidenews' => '', 'shownews' => $c->id)).'" id="coc-shownews-'.$c->id.'" class="coc-hidden" title="'.get_string('shownews', 'block_course_overview_campus').'">
									<img src="'.$OUTPUT->pix_url('t/collapsed').'" alt="'.get_string('shownews', 'block_course_overview_campus').'" />
								</a>
							</div>';
					} 
					// If course news are visible
					else {
						echo '<div class="hidenewsicon">
								<a href="'.$PAGE->url->out_as_local_url(true, array('manage' => $manage, 'hidenews' => $c->id, 'shownews' => '')).'" id="coc-hidenews-'.$c->id.'" class="coc-hidden" title="'.get_string('hidenews', 'block_course_overview_campus').'">
									<img src="'.$OUTPUT->pix_url('t/expanded').'" alt="'.get_string('hidenews', 'block_course_overview_campus').'" />
								</a>
								<a href="'.$PAGE->url->out_as_local_url(true, array('manage' => $manage, 'hidenews' => '', 'shownews' => $c->id)).'" id="coc-shownews-'.$c->id.'" title="'.get_string('shownews', 'block_course_overview_campus').'">
									<img src="'.$OUTPUT->pix_url('t/collapsed').'" alt="'.get_string('shownews', 'block_course_overview_campus').'" />
								</a>
							</div>';
					}
				}

				// Output course visibility control icons
				// If course is hidden
				if ($c->hidecourse == 0) {
					echo '<div class="hidecourseicon">
							<a href="'.$PAGE->url->out_as_local_url(true, array('manage' => $manage, 'hidecourse' => $c->id, 'showcourse' => '')).'" id="coc-hidecourse-'.$c->id.'" title="'.get_string('hidecourse', 'block_course_overview_campus').'">
								<img src="'.$OUTPUT->pix_url('t/hide').'" class="icon" alt="'.get_string('hidecourse', 'block_course_overview_campus').'" />
							</a>
							<a href="'.$PAGE->url->out_as_local_url(true, array('manage' => $manage, 'hidecourse' => '', 'showcourse' => $c->id)).'" id="coc-showcourse-'.$c->id.'" class="coc-hidden" title="'.get_string('showcourse', 'block_course_overview_campus').'">
								<img src="'.$OUTPUT->pix_url('t/show').'" class="icon" alt="'.get_string('showcourse', 'block_course_overview_campus').'" />
							</a>
						</div>';
				} 
				// If course is visible
				else {
					echo '<div class="hidecourseicon">
							<a href="'.$PAGE->url->out_as_local_url(true, array('manage' => $manage, 'hidecourse' => $c->id, 'showcourse' => '')).'" id="coc-hidecourse-'.$c->id.'" class="coc-hidden" title="'.get_string('hidecourse', 'block_course_overview_campus').'">
								<img src="'.$OUTPUT->pix_url('t/hide').'" class="icon" alt="'.get_string('hidecourse', 'block_course_overview_campus').'" />
							</a>
							<a href="'.$PAGE->url->out_as_local_url(true, array('manage' => $manage, 'hidecourse' => '', 'showcourse' => $c->id)).'" id="coc-showcourse-'.$c->id.'" title="'.get_string('showcourse', 'block_course_overview_campus').'">
								<img src="'.$OUTPUT->pix_url('t/show').'" class="icon" alt="'.get_string('showcourse', 'block_course_overview_campus').'" />
							</a>
						</div>';
				}
	
	
				// Get course attributes for use with course link
				$attributes = array('title' => s($c->fullname));
				if (empty($c->visible)) {
					$attributes['class'] = 'dimmed';
				}

				// Get teachers' names for use with course link
				$teachernames = get_teachername_string($c->teachers);


				// Output course link (show shortname and teacher name if configured)
				if ($config->showshortname == true && $config->showteachername == true && $teachernames != '')
					echo $OUTPUT->heading(html_writer::link(new moodle_url('/course/view.php', array('id' => $c->id)), format_string($c->fullname).'<br /><span class="coc-metainfo">('.$c->shortname.'&nbsp;&nbsp;|&nbsp;&nbsp;'.$teachernames.')</span>', $attributes), 3);
				elseif ($config->showshortname == true)
					echo $OUTPUT->heading(html_writer::link(new moodle_url('/course/view.php', array('id' => $c->id)), format_string($c->fullname).'<br /><span class="coc-metainfo">('.$c->shortname.')</span>', $attributes), 3);
				elseif ($config->showteachername == true && $teachernames != '')
					echo $OUTPUT->heading(html_writer::link(new moodle_url('/course/view.php', array('id' => $c->id)), format_string($c->fullname).'<br /><span class="coc-metainfo">('.$teachernames.')</span>', $attributes), 3);
				else
					echo $OUTPUT->heading(html_writer::link(new moodle_url('/course/view.php', array('id' => $c->id)), format_string($c->fullname), $attributes), 3);

				// Output course news
				if (array_key_exists($c->id, $coursenews)) {
					// Remember course ID for YUI processing
					$yui_coursenewslist .= $c->id.' ';

					// Start course news div as visible if the course's news aren't hidden
					if ($c->hidenews == 0) {
						echo '<div id="coc-coursenews-'.$c->id.'" class="coc-coursenews">';
					} 
					// Otherwise start course news div as hidden
					else {
						echo '<div id="coc-coursenews-'.$c->id.'" class="coc-coursenews coc-hidden">';
					}

					// Output the course's preformatted news HTML
					foreach ($coursenews[$c->id] as $modname => $html) {
						echo '<div class="coc-module">';
							echo $OUTPUT->pix_icon('icon', $modname, 'mod_'.$modname, array('class'=>'iconlarge'));
							echo $html;
						echo '</div>';
					}

					// End course news div
					echo '</div>';
				}

				// End standard course overview coursebox
				echo $OUTPUT->box_end();
			
				// End filter by term div
				if($config->termcoursefilter == true) {
					echo '</div>';
				}

				// End filter by category div
				if($config->categorycoursefilter == true) {
					echo '</div>';
				}

				// End filter by teacher div
				if($config->teachercoursefilter == true) {
					echo '</div>';
				}

				// End course div
				echo '</div>';
			}
			
			// End section
			echo '</div>';



			/********************************************************************************/
			/***                             OUTPUT CONTENT                               ***/
			/********************************************************************************/
	
			// Get and end output buffer
			$content = ob_get_contents();
			ob_end_clean();
		


			/********************************************************************************/
			/***                             AJAX MANAGEMENT                              ***/
			/********************************************************************************/
	
			// Verify that course displaying parameters are updatable by AJAX
			foreach ($courses as $c) {
				user_preference_allow_ajax_update('block_course_overview_campus-hidecourse-'.$c->id, PARAM_INT);
				user_preference_allow_ajax_update('block_course_overview_campus-hidenews-'.$c->id, PARAM_INT);
			}
	
			// Verify that filter parameters are updatable by AJAX
			if ($config->termcoursefilter == true) { 
				user_preference_allow_ajax_update('block_course_overview_campus-selectedterm', PARAM_TEXT);
			}
			if ($config->teachercoursefilter == true) {
				user_preference_allow_ajax_update('block_course_overview_campus-selectedteacher', PARAM_TEXT);
			}
			if ($config->categorycoursefilter == true) {
				user_preference_allow_ajax_update('block_course_overview_campus-selectedcategory', PARAM_TEXT);
			}
	
			// Include YUI for hiding courses and news with AJAX
			$PAGE->requires->yui_module('moodle-block_course_overview_campus-hidenews', 'M.block_course_overview_campus.initHideNews', array(array('courses'=>trim($yui_coursenewslist))));
			$PAGE->requires->yui_module('moodle-block_course_overview_campus-hidecourse', 'M.block_course_overview_campus.initHideCourse', array(array('courses'=>trim($yui_courseslist), 'editing'=>$manage)));
	
			// Include YUI for filtering courses with AJAX
			if ($config->teachercoursefilter == true || $config->termcoursefilter == true || $config->categorycoursefilter == true) { 
				$PAGE->requires->yui_module('moodle-block_course_overview_campus-filter', 'M.block_course_overview_campus.initFilter', array());
			}
		}



		/********************************************************************************/
		/***                             OUTPUT AND RETURN                            ***/
		/********************************************************************************/

		// Output content
		$this->content = new stdClass();

		if (!empty($content))
			$this->content->text = $content;
		else 
			$this->content->text = '';
		
		if (!empty($footer))
			$this->content->footer = $footer;
		else 
			$this->content->footer = '';
			
		return $this->content;
	}

	public function has_config() {
		return true;
	}

	public function applicable_formats() {
		return array('my-index' => true, 'site-index' => true);
	}

	public function instance_allow_multiple() {
		return false;
	}

	function instance_can_be_hidden() {
		return false;
	}
}
