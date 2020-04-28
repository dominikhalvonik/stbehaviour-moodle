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
require_once('add_formal_evaluation_criterion.php');
require_login();
$header = get_string('stecart', 'local_stbehaviour');
$pagetitle = get_string('stecart', 'local_stbehaviour');
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/stbehaviour/add_formalcriterion.php');
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
$returnurl = new moodle_url('/local/stbehaviour/formalcriterion.php');
if ($action == "delete") {
    if (!$delete) {
        echo $OUTPUT->header();
        $optionsyes = array('delete' => $id, 'confirmdelete' => md5($id), 'sesskey' => sesskey(), 'action' => 'delete', 'id' => $id);
        $deleteurl = new moodle_url('/local/stbehaviour/add_formalcriterion.php', $optionsyes);
        $deletebutton = new single_button($deleteurl, get_string('delete'), 'post');
        echo $OUTPUT->confirm('Do you really want to completely delete this rule can\'t be undone', $deletebutton, $returnurl);
        echo $OUTPUT->footer();
        die;
    } else if ($confirm == md5($delete)) {
        local_deletecriterion($id);
        redirect($returnurl);
    }
} else if ($action == "edit") {
    $toform = get_dataformal_criterion($id);

    $data['name'] = $toform->name;
    $data['min_points'] = $toform->min_points;
    $data['max_points'] = $toform->max_points;
    $data['description']['text'] = $toform->description;
    $args = array(
        'fid' => $id,
    );
}
$mform = new add_formal_evaluation_criterion(null, $args);
if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/stbehaviour/formalcriterion.php'));
} else if ($fromform = $mform->get_data()) {
    if (empty($fid)) {
        $insert = new stdClass();
        $insert->name = $fromform->name;
        $insert->min_points = $fromform->min_points;
        $insert->max_points = $fromform->max_points;
        $insert->description = $fromform->description['text'];
        //$insert->timecreated		=	Time();
        $DB->insert_record('stu_formal_criterion', $insert);
    } else {
        $update = new stdClass();
        $update->id = $fid;
        $update->name = $fromform->name;
        $update->min_points = $fromform->min_points;
        $update->max_points = $fromform->max_points;
        $update->description = $fromform->description['text'];
        //$update->timecreated		=	Time();
        $DB->update_record('stu_formal_criterion', $update);
    }

    redirect(new moodle_url('/local/stbehaviour/formalcriterion.php'));
}
$mform->set_data($data);
echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();