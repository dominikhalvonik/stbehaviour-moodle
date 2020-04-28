<?php
// This file is part of the Local stopwatch plugin
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
 * This plugin sends users a stopwatch message after logging in
 * and notify a moderator a new user has been added
 * it has a settings page that allow you to configure the messages
 * send.
 *
 * @package    local
 * @subpackage stopwatch
 * @copyright  2017 Bas Brands, basbrands.nl, bas@sonsbeekmedia.nl
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../config.php');
//check if user is logged in or not
require_login();
//setup global variables
global $USER, $PAGE, $DB, $CFG;
//if the secodns key is in the POST request do all defined functionality
if (isset($_POST['seconds'])) {
    //get information about the activity stored in plugin database
    $ress = $DB->get_record('stu_moduleid', array('moduleid' => $_POST['moduleid'], 'pagenum' => $_POST['pageid']));
    //get information about the previouse user behavior
    $ressaduplic = $DB->get_record_sql('SELECT * FROM {stu_behavior_log} WHERE modulid = ' . $_POST['moduleid'] . ' AND pagenum = ' . $_POST['pageid'] . ' AND courseid = ' . $_POST['courseid'] . ' AND userid = ' . $USER->id . ' AND timeview >= ' . (time() - 600));
    if (empty($ressaduplic->id)) {
        //if there is no record in the database create new record object
        $record = new stdClass();
        $record->userid = $USER->id;
        $record->courseid = $_POST['courseid'];
        $record->component = $_POST['nameact'];
        $record->activid = $_POST['actid'];
        $record->modulid = $_POST['moduleid'];
        $record->pagenum = $_POST['pageid'];
        $record->totalesecond = 1;
        $record->numberofwords = $ress->numberofwords;
        $record->timeview = time();
        //save record to database
        $DB->insert_record('stu_behavior_log', $record);
    } else {
        //if there is a record which identifies this session than just update
        $record = new stdClass();
        $record->id = $ressaduplic->id;
        $record->totalesecond = $_POST['seconds'];
        if ($ressaduplic->totalesecond < $_POST['seconds']) {
            $DB->update_record('stu_behavior_log', $record);
        }
    }
}
