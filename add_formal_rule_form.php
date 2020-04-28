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

class add_formal_rule_form extends moodleform
{
    /**
     * Add elements to form
     * @throws coding_exception
     */
    public function definition()
    {
        global $CFG;
 
        $mform = $this->_form; // Don't forget the underscore!
       
        if(!empty($this->_customdata)) {
           $fid        = $this->_customdata['fid']; // this contains the data of this form
        }
       
       if(!empty($fid)) {
            $mform->addElement('hidden','fid',$fid );
            $mform->setType('fid', PARAM_INT);
        }
        
        $label = get_string('formalcheck', 'local_stbehaviour');
        $mform->addElement('header', 'general', $label);
 
        $mform->addElement('text', 'rulename', get_string('rulename','local_stbehaviour'));
		$mform->addRule('rulename', get_string('err_lettersonly','local_stbehaviour'), 'required', null, 'client');
		$mform->setType('rulename', PARAM_TEXT);
       
        $mform->addElement('select', 'ruletype', get_string('ruletype','local_stbehaviour'), array('1'=>1, '2'=>2,'3'=>3, '4'=>4,'5'=>5), $attributes=array());
		
		$mform->addElement('text', 'compvalue', get_string('compvalue','local_stbehaviour'));
		$mform->addRule('compvalue', get_string('err_alphanumeric','local_stbehaviour'), 'required', null, 'client');
        $mform->setType('compvalue', PARAM_TEXT); 
		
		$mform->addElement('text', 'points', get_string('points','local_stbehaviour'));
		$mform->addRule('points', get_string('err_numeric','local_stbehaviour'), 'required', null, 'client');
		$mform->addRule('points', get_string('numeric','local_stbehaviour'), 'numeric', null, 'client');
        $mform->setType('points', PARAM_INT); 
		
        $label = get_string('description', 'local_stbehaviour');
        $mform->addElement('editor', 'description', $label, null);
        $mform->setType('description', PARAM_TEXT);
        
        $this->add_action_buttons();
          
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
}
