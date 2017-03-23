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
 * Course deletion confirmation form.
 *
 * @package   tool_deletecourses
 * @copyright 2017 Lafayette College ITS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');

class tool_deletecourses_confirmation_form extends moodleform {
    public function definition() {
        $mform = $this->_form;

        // Get the category id.
        $category = $this->_customdata['category'];

        // Checkbox to recurse through subcategories.
        $mform->addElement('checkbox', 'recursive', get_string('recursive', 'tool_deletecourses'));
        $mform->setDefault('recursive', 1);

        // Checkbox to bypass recycle bin.
        $mform->addElement('checkbox', 'disablerecyclebin', get_string('disablerecyclebin', 'tool_deletecourses'));
        $mform->setDefault('disablerecyclebin', 1);

        // Metadata.
        $mform->addElement('hidden', 'category', $category);
        $mform->setType('category', PARAM_INT);

        $this->add_action_buttons(true, get_string('confirm'));
    }
}
