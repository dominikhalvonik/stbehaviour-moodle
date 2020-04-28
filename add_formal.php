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
require_once('add_formal_rule_form.php');
require_login();
$header = get_string('pluginname', 'local_stbehaviour');
$pagetitle = get_string('pluginname', 'local_stbehaviour');
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/stbehaviour/add_formal.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_title($pagetitle);
$PAGE->set_heading($header);
$args = array();
$data = array();
$action = optional_param('action', '', PARAM_RAW);
$id = optional_param('id', 0, PARAM_INT);
$fid = optional_param('fid', 0, PARAM_INT);
$confirm = optional_param('confirmdelete', 0, PARAM_INT);
$delete = optional_param('delete', 0, PARAM_INT);
$returnurl = new moodle_url('/local/stbehaviour/view.php');
if ($action == "delete") {
    if (!$delete) {
        echo $OUTPUT->header();
        $optionsyes = array('delete' => $id, 'confirmdelete' => md5($id), 'sesskey' => sesskey(), 'action' => 'delete', 'id' => $id);
        $deleteurl = new moodle_url('/local/stbehaviour/add_formal.php', $optionsyes);
        $deletebutton = new single_button($deleteurl, get_string('delete'), 'post');
        echo $OUTPUT->confirm('Do you really want to completely delete this rule can\'t be undone', $deletebutton, $returnurl);
        echo $OUTPUT->footer();
        die;
    } else if ($confirm == md5($delete)) {
        local_deleteformalchek($id);
        redirect($returnurl);
    }
} else if ($action == "edit") {
    $toform = get_dataformal_rule($id);

    $data['rulename'] = $toform->rulename;
    $data['ruletype'] = $toform->ruletype;
    $data['compvalue'] = $toform->comparationvalue;
    $data['points'] = $toform->points;
    $data['description']['text'] = $toform->description;
    $args = array(
        'fid' => $id,
    );
}
$mform = new add_formal_rule_form(null, $args);
if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/stbehaviour/view.php'));
} else if ($fromform = $mform->get_data()) {
    if (empty($fid)) {
        $insert = new stdClass();
        $insert->rulename = $fromform->rulename;
        $insert->ruletype = $fromform->ruletype;
        $insert->comparationvalue = $fromform->compvalue;
        $insert->points = $fromform->points;
        $insert->description = $fromform->description['text'];
        $insert->timecreated = Time();
        $DB->insert_record('stu_formal_rule', $insert);
    } else {
        $update = new stdClass();
        $update->id = $fid;
        $update->rulename = $fromform->rulename;
        $update->ruletype = $fromform->ruletype;
        $update->comparationvalue = $fromform->compvalue;
        $update->points = $fromform->points;
        $update->description = $fromform->description['text'];
        $update->timecreated = Time();
        $DB->update_record('stu_formal_rule', $update);
    }

    redirect(new moodle_url('/local/stbehaviour/view.php'));
}
$mform->set_data($data);
echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();