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
 * Language strings for tool_deletecourses.
 *
 * @package   tool_deletecourses
 * @copyright 2017 Lafayette College ITS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$string['deleteallcourses'] = 'Delete all courses';
$string['deleteconfirm'] = 'This page lets you delete all the courses in the category <strong>{$a}</strong> and its subcategories. This action cannot be undone.';
$string['deletequeued'] = 'An adhoc task has been queued to delete all the courses in the category <strong>{$a}</strong> and subcategories. It will run the next time cron executes.';
$string['disablerecyclebin'] = 'Disable recycle bin';
$string['pluginname'] = 'Delete courses';
$string['recursive'] = 'Recurse through subcategories?';
