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
require_login();
$header = get_string('pluginname', 'local_stbehaviour');
$pagetitle = get_string('pluginname', 'local_stbehaviour');
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/stbehaviour/activity.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_title($pagetitle);
$PAGE->set_heading($header);
$moduleid =  optional_param('moduleid',0,PARAM_INT);
if($moduleid >0){
	evaluationcalut($moduleid);
}
$list = get_listactivity();
echo $OUTPUT->header();
$renderer = $PAGE->get_renderer('local_stbehaviour');
echo $renderer->view_tabactionhtml(4);
echo $renderer->add_action_activity();
echo $renderer->view_activity($list);

echo $OUTPUT->footer();