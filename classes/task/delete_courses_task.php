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
 * @package   tool_deletecourses
 * @copyright 2017 Lafayette College ITS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_deletecourses\task;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot.'/lib/coursecatlib.php');

class delete_courses_task extends \core\task\adhoc_task {
    public function get_component() {
        return 'tool_deletecourses';
    }

    public function execute() {
        global $CFG;
        $data = $this->get_custom_data();
        if ($data->disablerecyclebin && !array_key_exists('tool_recyclebin', $CFG->forced_plugin_settings)) {
            $CFG->forced_plugin_settings['tool_recyclebin'] = array('categorybinenable' => false);
        }

        // Finish if no category id specified.
        if (empty($data->category)) {
            mtrace("No category id");
            return;
        }

        // Finish if invalid category id specified.
        $category = \coursecat::get($data->category);
        if (!$category) {
            mtrace("Invalid category id");
            return;
        }

        // Finish if there are no courses.
        if (!$courses = $this->get_courses_in_category($category, $data->recursive)) {
            mtrace("No courses found");
            return;
        }

        // Delete all courses.
        $this->delete_courses_in_category($courses);
        fix_course_sortorder();
    }

    /**
     * Get all the courses to be deleted.
     *
     * @param object $category The category.
     * @param boolean $recursive Whether to delete courses in subcategories.
     * @return array
     */
    protected function get_courses_in_category($category, $recursive) {
        // Get all the courses.
        $courses = $category->get_courses(
            array(
                'recursive' => $recursive,
                'limit' => 0
            )
        );
        return $courses;
    }

    /**
     * @param array $courses The courses to be deleted.
     */
    protected function delete_courses_in_category($courses) {
        global $DB;

        $lockfactory = \core\lock\lock_config::get_lock_factory('tool_deletecourses_delete_course_task');
        foreach ($courses as $course) {
            $lockkey = "course{$course->id}";
            $lock = $lockfactory->get_lock($lockkey, 0);

            // Guard against multiple workers in cron.
            if ($lock !== false) {
                if ($coursedb = $DB->get_record('course', array('id' => $course->id))) {
                    if (!delete_course($coursedb, false)) {
                        mtrace("Failed to delete course {$course->id}");
                    }
                }
                $lock->release();
            }
        }
    }
}
