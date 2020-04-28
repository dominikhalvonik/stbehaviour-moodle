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
require_once('activity_form.php');
require_login();
$header = get_string('activity', 'local_stbehaviour');
$pagetitle = get_string('activity', 'local_stbehaviour');
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/stbehaviour/add_activity.php');
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
$returnurl = new moodle_url('/local/stbehaviour/activity.php');
if ($action == "delete") {
    if (!$delete) {
        echo $OUTPUT->header();
        $optionsyes = array('delete' => $id, 'confirmdelete' => md5($id), 'sesskey' => sesskey(), 'action' => 'delete', 'id' => $id);
        $deleteurl = new moodle_url('/local/stbehaviour/add_activity.php', $optionsyes);
        $deletebutton = new single_button($deleteurl, get_string('delete'), 'post');
        echo $OUTPUT->confirm('Do you really want to completely delete this rule can\'t be undone', $deletebutton, $returnurl);
        echo $OUTPUT->footer();
        die;
    } else if ($confirm == md5($delete)) {
        delete_activity($id);
        redirect($returnurl);
    }
} else if ($action == "edit") {
    $toform = get_activity_form($id);
    $data['coursename_act'] = $toform->courseid;
    $data['activity_act'] = $toform->moduleid;
    $data['message']['text'] = $toform->mesg;
    $args = array(
        'fid' => $id,
    );
}
$mform = new  activity_form(null, $args);
if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/stbehaviour/activity.php'));
} else if ($fromform = $mform->get_data()) {
    if (empty($fid)) {
        $insert = new stdClass();
        $insert->courseid = $fromform->coursename_act;
        $insert->activyid = 0;
        $insert->moduleid = $fromform->activity_act;
        $insert->mesg = $fromform->message['text'];;
        $DB->insert_record('stu_addactivity', $insert);
    }
    redirect(new moodle_url($CFG->wwwroot . '/local/stbehaviour/activity.php', array('moduleid' => $fromform->activity_act)));
}
$mform->set_data($data);
echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();