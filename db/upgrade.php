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
 * Block "course overview (campus)" - Upgrade script
 *
 * @package    block_course_overview_campus
 * @copyright  2019 Alexander Bias, Ulm University <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Function to upgrade block_course_overview_campus.
 * @param int $oldversion the version we are upgrading from
 * @return bool result
 */
function xmldb_block_course_overview_campus_upgrade($oldversion) {
    global $DB;

    if ($oldversion < 2019061200) {
        // After the course news functionality has been removed from the plugin, the user preferences have to be removed manually.
        // We remove them directly from the DB table and don't use unset_user_preference() as the cache is cleared anyway directly
        // after the plugin has been uninstalled.

        $like = $DB->sql_like('name', '?', true, true, false, '|');
        $params = array($DB->sql_like_escape('block_course_overview_campus-hidenews-', '|') . '%');
        $DB->delete_records_select('user_preferences', $like, $params);

        upgrade_plugin_savepoint(true, 2019061200, 'block', 'course_overview_campus');
    }

    return true;
}

