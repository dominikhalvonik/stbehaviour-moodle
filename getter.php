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

require_once(dirname(__FILE__) . '/../../config.php');
//this file is used as API for getting activities for course
global $DB;
// Get the parameter
$coursenameid = optional_param('coursenameid', 0, PARAM_INT);
$coursid = optional_param('coursid', 0, PARAM_INT);
// If departmentid exists
if ($coursid) {
    $modlueid = $DB->get_records('course_modules', array('course' => $coursid));
    if (!empty($modlueid)) {
        foreach ($modlueid as $mid) {
            if ($mid->module == 3) {
                $activitibook = $DB->get_record('book', array('id' => $mid->instance));
                echo "<option value=" . $mid->id . ">" . $activitibook->name . "</option>";
            } else if ($mid->module == 13) {
                $activitibook = $DB->get_record('lesson', array('id' => $mid->instance));
                echo "<option value=" . $mid->id . ">" . $activitibook->name . "</option>";
            } else if ($mid->module == 15) {
                $activitibook = $DB->get_record('page', array('id' => $mid->instance));
                echo "<option value=" . $mid->id . ">" . $activitibook->name . "</option>";
            } else if ($mid->module == 20) {
                $activitibook = $DB->get_record('url', array('id' => $mid->instance));
                echo "<option value=" . $mid->id . ">" . $activitibook->name . "</option>";
            }
        }
    }
}
if ($coursenameid) {
    $modlueid = $DB->get_records('course_modules', array('course' => $coursenameid));
    if (!empty($modlueid)) {
        foreach ($modlueid as $mid) {
            if ($mid->module == 3) {
                $activitibook = $DB->get_record('book', array('id' => $mid->instance));
                echo "<option value=" . $mid->id . ">" . $activitibook->name . "</option>";
            } else if ($mid->module == 13) {
                $activitibook = $DB->get_record('lesson', array('id' => $mid->instance));
                echo "<option value=" . $mid->id . ">" . $activitibook->name . "</option>";
            } else if ($mid->module == 15) {
                $activitibook = $DB->get_record('page', array('id' => $mid->instance));
                echo "<option value=" . $mid->id . ">" . $activitibook->name . "</option>";
            } else if ($mid->module == 20) {
                $activitibook = $DB->get_record('url', array('id' => $mid->instance));
                echo "<option value=" . $mid->id . ">" . $activitibook->name . "</option>";
            }
        }
    }
}