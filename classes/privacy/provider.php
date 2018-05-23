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
 * Block "course overview (campus)" - Privacy provider
 *
 * @package    block_course_overview_campus
 * @copyright  2018 Alexander Bias, Ulm University <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_course_overview_campus\privacy;

use \core_privacy\local\request\writer;
use \core_privacy\local\metadata\collection;
use \core_privacy\local\request\transform;

defined('MOODLE_INTERNAL') || die();

/**
 * Privacy Subsystem implementing provider.
 *
 * @package    block_course_overview_campus
 * @copyright  2018 Alexander Bias, Ulm University <alexander.bias@uni-ulm.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider implements \core_privacy\local\metadata\provider,
        \core_privacy\local\request\user_preference_provider {

    /**
     * Returns meta data about this system.
     *
     * @param collection $collection The initialised item collection to add items to.
     * @return collection A listing of user data stored through this system.
     */
    public static function get_metadata(collection $collection) : collection {
        $collection->add_user_preference('block_course_overview_campus-selectedterm',
                'privacy:metadata:preference:selectedterm');
        $collection->add_user_preference('block_course_overview_campus-selectedteacher',
                'privacy:metadata:preference:selectedteacher');
        $collection->add_user_preference('block_course_overview_campus-selectedcategory',
                'privacy:metadata:preference:selectedcategory');
        $collection->add_user_preference('block_course_overview_campus-selectedtoplevelcategory',
                'privacy:metadata:preference:selectedtoplevelcategory');
        $collection->add_user_preference('block_course_overview_campus-hidecourse-',
                'privacy:metadata:preference:hidecourse');
        $collection->add_user_preference('block_course_overview_campus-hidenews-',
                'privacy:metadata:preference:hidenews');
        $collection->add_user_preference('local_boostcoc-notshowncourses',
                'privacy:metadata:preference:local_boostcoc-notshowncourses');
        $collection->add_user_preference('local_boostcoc-activefilters',
                'privacy:metadata:preference:local_boostcoc-activefilters');

        return $collection;
    }

    /**
     * Store all user preferences for the plugin.
     *
     * @param int $userid The userid of the user whose data is to be exported.
     */
    public static function export_user_preferences(int $userid) {
        $preferences = get_user_preferences();
        foreach ($preferences as $name => $value) {
            $descriptionidentifier = null;

            // User preferences for filters.
            if (strpos($name, 'block_course_overview_campus-selected') === 0) {
                if ($name == 'block_course_overview_campus-selectedterm') {
                    $descriptionidentifier = 'privacy:request:preference:selectedterm';
                } else if ($name == 'block_course_overview_campus-selectedteacher') {
                    $descriptionidentifier = 'privacy:request:preference:selectedteacher';
                } else if ($name == 'block_course_overview_campus-selectedcategory') {
                    $descriptionidentifier = 'privacy:request:preference:selectedcategory';
                } else if ($name == 'block_course_overview_campus-selectedtoplevelcategory') {
                    $descriptionidentifier = 'privacy:request:preference:selectedtoplevelcategory';
                }

                if ($descriptionidentifier !== null) {
                    writer::export_user_preference(
                            'block_course_overview_campus',
                            $name,
                            $value,
                            get_string($descriptionidentifier, 'block_course_overview_campus', (object) [
                                    'value' => $value,
                            ])
                    );
                }

                // User preferences for hiding stuff.
            } else if (strpos($name, 'block_course_overview_campus-hide') === 0) {
                if (strpos($name, 'block_course_overview_campus-hidecourse-') === 0) {
                    $descriptionidentifier = 'privacy:request:preference:hidecourse';
                    $item = substr($name, strlen('block_course_overview_campus-hidecourse-'));
                } else if (strpos($name, 'block_course_overview_campus-hidenews-') === 0) {
                    $descriptionidentifier = 'privacy:request:preference:hidenews';
                    $item = substr($name, strlen('block_course_overview_campus-hidecourse-'));
                }

                if ($descriptionidentifier !== null) {
                    writer::export_user_preference(
                            'block_course_overview_campus',
                            $name,
                            $value,
                            get_string($descriptionidentifier, 'block_course_overview_campus', (object) [
                                    'item' => $item,
                                    'value' => $value,
                            ])
                    );
                }

                // User preferences for local_boostcoc.
            } else if (strpos($name, 'local_boostcoc-') === 0) {
                if ($name == 'local_boostcoc-notshowncourses') {
                    $descriptionidentifier = 'privacy:request:preference:local_boostcoc-notshowncourses';
                } else if ($name == 'local_boostcoc-activefilters') {
                    $descriptionidentifier = 'privacy:request:preference:local_boostcoc-activefilters';
                }

                if ($descriptionidentifier !== null) {
                    writer::export_user_preference(
                            'block_course_overview_campus',
                            $name,
                            $value,
                            get_string($descriptionidentifier, 'block_course_overview_campus', (object) [
                                    'value' => $value,
                            ])
                    );
                }
            }
        }
    }
}
