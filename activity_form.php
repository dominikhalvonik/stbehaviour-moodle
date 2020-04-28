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
 * @package    local-mail
 * @author     Albert Gasset <albert.gasset@gmail.com>
 * @author     Marc Català <reskit@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// File: /local/pushmail/addjobs.php
require_once('../../config.php');
require_once("$CFG->libdir/formslib.php");

class activity_form extends moodleform
{

    /**
     * Add elements to form
     * @throws coding_exception
     * @throws dml_exception
     */
    public function definition()
    {
        $mform = $this->_form; // Don't forget the underscore!
        if(!empty($this->_customdata)) {
           $fid        = $this->_customdata['fid']; // this contains the data of this form
        }
       
       if(!empty($fid)) {
            $mform->addElement('hidden','fid',$fid );
            $mform->setType('fid', PARAM_INT);
        }

		$allcours = $this->get_allcourses();
		$allactivity = [];
 		$mform->addElement('select', 'coursename_act', get_string('coursename','local_stbehaviour'), $allcours, $attributes = []);
		$mform->addElement('select', 'activity_act', get_string('activityname','local_stbehaviour'), $allactivity, $attributes = []);
        $label = get_string('message', 'local_stbehaviour');
        $mform->addElement('editor', 'message', $label, null);
        $mform->setType('message', PARAM_RAW);
        
        $this->add_action_buttons();
          
    }

    /**
     * @return array
     * @throws dml_exception
     */
    public function get_allcourses()
    {
	    global $DB;
		$res = $DB->get_records('course');
		$liscours = [
		    0 => '--Select--'
        ];
		foreach($res as $coursname) {
			if($coursname->category == 0) {
				continue;
			} else {
				$liscours[$coursname->id] = $coursname->fullname;
			}
		}

		return $liscours;
	}

    /**
     * Custom validation should be added here
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files)
    {
        return array();
    }

    /**
     * @return object
     */
    public function get_data()
    {
        global $DB;
        $data = parent::get_data();

        if (!empty($data)) {
            $mform =& $this->_form;
            // Add the studentid properly to the $data object.
            if(!empty($mform->_submitValues['activity_act'])) {
                $data->activity_act = $mform->_submitValues['activity_act'];
            }
        }

        return $data;
    }
}
