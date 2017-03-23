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
 * Delete courses in a category.
 *
 * @package   tool_deletecourses
 * @copyright 2017 Lafayette College ITS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->dirroot.'/lib/coursecatlib.php');

$categoryid = required_param('category', PARAM_INT);
$category = \coursecat::get($categoryid);
$context = \context_coursecat::instance($categoryid);

// Ensure the user can be here.
require_login(0, false);
require_capability('moodle/course:delete', $context);
$returnurl = new moodle_url('/course/management.php', array('categoryid' => $categoryid));

$PAGE->set_context($context);
$PAGE->set_pagelayout('admin');
$PAGE->set_url(new moodle_url('/admin/tool/deletecourses/delete.php'));
$PAGE->set_title(new lang_string('coursecatmanagement') . ': '. new lang_string('deleteallcourses', 'tool_deletecourses'));
$PAGE->set_heading($SITE->fullname);

// Confirmation form.
$mform = new tool_deletecourses_confirmation_form('delete.php', array('category' => $categoryid));
if ($mform->is_cancelled()) {
    redirect($returnurl);
} else if ($data = $mform->get_data()) {
    if (!isset($data->recursive)) {
        $data->recursive = 0;
    }
    if (!isset($data->disablerecyclebin)) {
        $data->disablerecyclebin = 0;
    }
    $task = new \tool_deletecourses\task\delete_courses_task();
    $task->set_custom_data(
        array(
            'category' => $data->category,
            'recursive' => $data->recursive,
            'disablerecyclebin' => $data->disablerecyclebin
        )
    );
    \core\task\manager::queue_adhoc_task($task);
    redirect($returnurl, get_string('deletequeued', 'tool_deletecourses', $category->name));
}

// Print page.
echo $OUTPUT->header();
echo $OUTPUT->heading(new lang_string('deleteallcourses', 'tool_deletecourses'));
echo $OUTPUT->box_start('generalbox', 'notice');
echo html_writer::tag('p', get_string('deleteconfirm', 'tool_deletecourses', $category->name));
$mform->display();
echo $OUTPUT->box_end();
echo $OUTPUT->footer();
