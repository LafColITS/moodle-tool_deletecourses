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
 * Unit tests for tasks.
 *
 * @package    tool_deletecourses
 * @category   test
 * @copyright  2017 Lafayette College ITS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;

class tool_deletecourses_deletecourses_testcase extends advanced_testcase {
    public function test_delete_course() {
        global $DB;

        $this->setAdminUser();
        $this->resetAfterTest(true);

        $this->getDataGenerator()->create_user();
        $category1 = $this->getDataGenerator()->create_category();
        for ($categoryid = 2; $categoryid <= 60; $categoryid++) {
            $category = $this->getDataGenerator()->create_category(array('parent' => $category1->id));
            for ($course = 1; $course <= 16; $course++) {
                $this->getDataGenerator()->create_course(array('category' => $category->id));
            }
        }

        // Sanity check.
        $courses = $DB->count_records('course');
        $this->assertEquals(945, $courses);
        $courses = $DB->count_records('course', array('category' => $category1->id));
        $this->assertEquals(0, $courses);

        // Delete courses.
        $task = new \tool_deletecourses\task\delete_courses_task();
        $task->set_custom_data(
            array(
                'category' => $category1->id,
                'recursive' => true,
                'disablerecyclebin' => true
            )
        );
        \core\task\manager::queue_adhoc_task($task);
        $task = \core\task\manager::get_next_adhoc_task(time());
        $this->assertInstanceOf('\\tool_deletecourses\\task\\delete_courses_task', $task);
        $task->execute();
        \core\task\manager::adhoc_task_complete($task);

        // All courses should be deleted.
        $courses = $DB->count_records('course');
        $this->assertEquals(1, $courses);
        $courses = $DB->count_records('course', array('category' => $category1->id));
        $this->assertEquals(0, $courses);
    }
}
