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
require_once('lib.php');
require_once('form_helper.php');
require_login();
$header = get_string('activity_helpers', 'local_stbehaviour');
$pagetitle = get_string('activity_helpers', 'local_stbehaviour');
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/stbehaviour/add_helper.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_title($pagetitle);
$PAGE->set_heading($header);
$PAGE->requires->jquery();
$PAGE->requires->js('/local/stbehaviour/add_helper.js', true);
$args = array();
$data = array();
$action = optional_param('action', '', PARAM_RAW);
$id = optional_param('id', 0, PARAM_INT);
$fid = optional_param('fid', 0, PARAM_INT);
$confirm = optional_param('confirmdelete', 0, PARAM_INT);
$delete = optional_param('delete', 0, PARAM_INT);
$returnurl = new moodle_url('/local/stbehaviour/activityHelper.php');
if ($action == "delete") {
    if (!$delete) {
        echo $OUTPUT->header();
        $optionsyes = array('delete' => $id, 'confirmdelete' => md5($id), 'sesskey' => sesskey(), 'action' => 'delete', 'id' => $id);
        $deleteurl = new moodle_url('/local/stbehaviour/add_helper.php', $optionsyes);
        $deletebutton = new single_button($deleteurl, get_string('delete'), 'post');
        echo $OUTPUT->confirm('Do you really want to completely delete this rule can\'t be undone', $deletebutton, $returnurl);
        echo $OUTPUT->footer();
        die;
    } else if ($confirm == md5($delete)) {
        delete_activityhelper($id);
        redirect($returnurl);
    }
} else if ($action == "edit") {
    $toform = getdataactivityhelper($id);
    $data['coursename'] = $toform->courseid;
    $data['activityname'] = $toform->activyid;
    $data['message']['text'] = $toform->message;
    $args = array(
        'fid' => $id,
    );
}
$mform = new form_helper(null, $args);
if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/stbehaviour/activityHelper.php'));
} else if ($fromform = $mform->get_data()) {
    if (empty($fid)) {
        $insert = new stdClass();
        $insert->courseid = $fromform->coursename;
        $insert->activyid = $fromform->activityname;
        $insert->message = $fromform->message['text'];
        $DB->insert_record('stu_activity_helpers', $insert);
    } else {
        $update = new stdClass();
        $update->id = $fid;
        //$update->courseid   			=$fromform->coursename;
        //$update->activyid   			=$fromform->activityname;
        $update->message = $fromform->message['text'];
        //$update->timecreated		=	Time();
        $DB->update_record('stu_activity_helpers', $update);
    }

    redirect(new moodle_url('/local/stbehaviour/activityHelper.php'));
}
$mform->set_data($data);
echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();