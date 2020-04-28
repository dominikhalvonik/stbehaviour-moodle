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


class local_stbehaviour_renderer extends plugin_renderer_base
{
    /**
     * @param array $lfr
     * @return string
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function view_formal_rule($lfr)
    {
        $table = new html_table();
        $table->head = array(get_string('srno', 'local_stbehaviour'), get_string('rulename', 'local_stbehaviour'), get_string('ruletype',
            'local_stbehaviour'), get_string('compvalue', 'local_stbehaviour'), get_string('points', 'local_stbehaviour'), get_string('description', 'local_stbehaviour'),
            get_string('action', 'local_stbehaviour'));
        $i = 1;
        foreach ($lfr as $value) {
            $editurl = new moodle_url('/local/stbehaviour/add_formal.php?action=edit&id=' . $value->id);
            $deleteurl = new moodle_url('/local/stbehaviour/add_formal.php?action=delete&id=' . $value->id);

            $edit = html_writer::link($editurl, '<i class="icon fa fa-cog fa-fw " aria-hidden="true" title="Edit" aria-label="Edit"></i>');
            $delete = html_writer::link($deleteurl, '<i class="icon fa fa-trash fa-fw " aria-hidden="true" title="Delete" aria-label="Delete"></i>');
            $table->data[] = array($i, $value->rulename, $value->ruletype, $value->comparationvalue, $value->points, $value->description, $edit . ' ' . $delete);
            $i++;
        }

        return html_writer::table($table);
    }

    /**
     * @return string
     * @throws coding_exception
     */
    public function add_action_formal()
    {
        global $CFG;
        $content = '<div class="searchform" style="float:right;">';
        $content .= '<a class="btn btn-primary" href="' . $CFG->wwwroot . '/local/stbehaviour/add_formal.php"><i class="fa fa-sign-in">' . get_string('addformalrul', 'local_stbehaviour') . '</i></a>';
        $content .= '</div>';

        return $content;
    }

    /**
     * @return string
     * @throws coding_exception
     */
    public function add_action_Criterion()
    {
        global $CFG;
        $content = '<div class="searchform" style="float:right;">';
        $content .= '<a class="btn btn-primary" href="' . $CFG->wwwroot . '/local/stbehaviour/add_formalcriterion.php"><i class="fa fa-sign-in">' . get_string('addevaluationcert', 'local_stbehaviour') . '</i></a>';
        $content .= '</div>';
        return $content;
    }

    /**
     * @return string
     * @throws coding_exception
     */
    public function add_action_activity()
    {
        global $CFG;
        $content = '<div class="searchform" style="float:right;">';
        $content .= '<a class="btn btn-primary" href="' . $CFG->wwwroot . '/local/stbehaviour/add_activity.php"><i class="fa fa-sign-in">' . get_string('addactivity', 'local_stbehaviour') . '</i></a>';
        $content .= '</div>';

        return $content;
    }

    /**
     * @param array $stlog
     * @return string
     * @throws coding_exception
     * @throws dml_exception
     */
    public function view_stubehaviourlog($stlog)
    {
        global $DB;
        $table = new html_table();
        $table->head = array(get_string('srno', 'local_stbehaviour'), get_string('fullname', 'local_stbehaviour'), get_string('coursename',
            'local_stbehaviour'), get_string('component', 'local_stbehaviour'), get_string('numberofwords', 'local_stbehaviour'), get_string('time', 'local_stbehaviour'),
            get_string('time', 'local_stbehaviour'));
        $i = 1;
        foreach ($stlog as $value) {
            $userfullname = $DB->get_record('user', array('id' => $value->userid));
            $uname = "";
            if (!empty($userfullname)) {
                $uname = $userfullname->firstname . ' ' . $userfullname->lastname;
            }
            $coursename = $DB->get_record('course', array('id' => $value->courseid));
            $cname = "";
            if (!empty($coursename)) {
                $cname = $coursename->fullname;
            }
            $modlueid = $DB->get_record('course_modules', array('id' => $value->modulid));
            if (!empty($modlueid)) {
                if ($modlueid->module == 3) {
                    $activitibook = $DB->get_record('book', array('id' => $modlueid->instance));
                    $name = $activitibook->name;
                }
                if ($modlueid->module == 13) {
                    $activitibook = $DB->get_record('lesson', array('id' => $modlueid->instance));
                    $name = $activitibook->name;
                }
                if ($modlueid->module == 15) {
                    $activitibook = $DB->get_record('page', array('id' => $modlueid->instance));
                    $name = $activitibook->name;
                }
                if ($modlueid->module == 20) {
                    $activitibook = $DB->get_record('url', array('id' => $modlueid->instance));
                    $name = $activitibook->name;
                }
            }

            $table->data[] = array($i, $uname, $cname, $name, $value->numberofwords, $value->totalesecond, date("F j, Y, g:i a", $value->timeview));
            $i++;
        }

        return html_writer::table($table);
    }

    /**
     * @param array $stfc
     * @return string
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function view_formal_Criterion($stfc)
    {
        $table = new html_table();
        $table->head = array(get_string('srno', 'local_stbehaviour'), get_string('name', 'local_stbehaviour'), get_string('min_points',
            'local_stbehaviour'), get_string('max_points', 'local_stbehaviour'), get_string('description', 'local_stbehaviour'), get_string('action', 'local_stbehaviour'));
        $i = 1;
        foreach ($stfc as $value) {
            $editurl = new moodle_url('/local/stbehaviour/add_formalcriterion.php?action=edit&id=' . $value->id);
            $deleteurl = new moodle_url('/local/stbehaviour/add_formalcriterion.php?action=delete&id=' . $value->id);

            $edit = html_writer::link($editurl, '<i class="icon fa fa-cog fa-fw " aria-hidden="true" title="Edit" aria-label="Edit"></i>');
            $delete = html_writer::link($deleteurl, '<i class="icon fa fa-trash fa-fw " aria-hidden="true" title="Delete" aria-label="Delete"></i>');
            $table->data[] = array($i, $value->name, $value->min_points, $value->max_points, $value->description, $edit . ' ' . $delete);
            $i++;
        }

        return html_writer::table($table);
    }

    /**
     * @param array $list
     * @return string
     * @throws coding_exception
     * @throws dml_exception
     */
    public function view_activity_evaluation($list)
    {
        global $DB;
        $table = new html_table();
        $table->head = array(get_string('srno', 'local_stbehaviour'), get_string('created_at', 'local_stbehaviour'), get_string('formal_evalcriteria_id',
            'local_stbehaviour'), get_string('activities_id', 'local_stbehaviour'));

        $i = 1;
        foreach ($list as $value) {
            $modlueid = $DB->get_record('course_modules', array('id' => $value->moduleid));
            $formalcrename = $DB->get_record('stu_formal_criterion', array('id' => $value->formal_evalcriteria_id));
            if (!empty($modlueid)) {
                if ($modlueid->module == 3) {
                    $activitibook = $DB->get_record('book', array('id' => $modlueid->instance));
                    $name = $activitibook->name;
                }
                if ($modlueid->module == 13) {
                    $activitibook = $DB->get_record('lesson', array('id' => $modlueid->instance));
                    $name = $activitibook->name;
                }
                if ($modlueid->module == 15) {
                    $activitibook = $DB->get_record('page', array('id' => $modlueid->instance));
                    $name = $activitibook->name;
                }
                if ($modlueid->module == 20) {
                    $activitibook = $DB->get_record('url', array('id' => $modlueid->instance));
                    $name = $activitibook->name;
                }
            }
            $table->data[] = array($i, date("F j, Y, g:i a", $value->created_at), $formalcrename->name, $name);
            $i++;
        }

        return html_writer::table($table);
    }

    /**
     * @param $list $list
     * @return string
     * @throws coding_exception
     * @throws dml_exception
     * @throws moodle_exception
     */
    public function view_activity($list)
    {
        global $DB;
        $table = new html_table();
        $table->head = array(get_string('srno', 'local_stbehaviour'), get_string('coursename', 'local_stbehaviour'), get_string('activityname',
            'local_stbehaviour'), get_string('message', 'local_stbehaviour'), get_string('action', 'local_stbehaviour'));
        $i = 1;
        foreach ($list as $value) {
            //$editurl    =   new moodle_url('/local/stbehaviour/add_activity.php?action=edit&id='.$value->id);
            $deleteurl = new moodle_url('/local/stbehaviour/add_activity.php?action=delete&id=' . $value->id);

            //$edit = html_writer::link($editurl,'<i class="icon fa fa-cog fa-fw " aria-hidden="true" title="Edit" aria-label="Edit"></i>');
            $delete = html_writer::link($deleteurl, '<i class="icon fa fa-trash fa-fw " aria-hidden="true" title="Delete" aria-label="Delete"></i>');
            $modlueid = $DB->get_record('course_modules', array('id' => $value->moduleid));
            if (!empty($modlueid)) {
                if ($modlueid->module == 3) {
                    $activitibook = $DB->get_record('book', array('id' => $modlueid->instance));
                    $name = $activitibook->name;
                }
                if ($modlueid->module == 13) {
                    $activitibook = $DB->get_record('lesson', array('id' => $modlueid->instance));
                    $name = $activitibook->name;
                }
                if ($modlueid->module == 15) {
                    $activitibook = $DB->get_record('page', array('id' => $modlueid->instance));
                    $name = $activitibook->name;
                }
                if ($modlueid->module == 20) {
                    $activitibook = $DB->get_record('url', array('id' => $modlueid->instance));
                    $name = $activitibook->name;
                }
            }
            $rec = $DB->get_record('course', array('id' => $value->courseid));
            $table->data[] = array($i, $rec->fullname, $name, $value->mesg, $delete);
            $i++;
        }

        return html_writer::table($table);
    }

    /**
     * @param int $i
     * @return string
     * @throws coding_exception
     * @throws moodle_exception
     */
    public function view_tabactionhtml($i)
    {
        $clas1 = '';
        $clas2 = '';
        $clas3 = '';
        $clas4 = '';
        $clas5 = '';
        $clas6 = '';
        $clas7 = '';
        if ($i == 1) {
            $clas1 = 'active';
        } else if ($i == 2) {
            $clas2 = 'active';
        } else if ($i == 3) {
            $clas3 = 'active';
        } else if ($i == 4) {
            $clas4 = 'active';
        } else if ($i == 5) {
            $clas5 = 'active';
        } else if ($i == 6) {
            $clas6 = 'active';
        } else if ($i == 7) {
            $clas7 = 'active';
        }
        $html = '';
        $roles = get_employee_role();
        if ($roles->shortname == 'coursecreator') {
            $html .= '<ul class="nav nav-pills nav-fill">
						  <li class="nav-item">
								<a class="nav-link ' . $clas4 . '" href="' . new moodle_url('/local/stbehaviour/activity.php') . '">' . get_string('activity', 'local_stbehaviour') . '</a>
						  </li>
						  <li class="nav-item">
								<a class="nav-link ' . $clas6 . '" href="' . new moodle_url('/local/stbehaviour/activityHelper.php') . '">' . get_string('activity_helpers', 'local_stbehaviour') . '</a>
						  </li>
						  <li class="nav-item">
								<a class="nav-link ' . $clas5 . '" href="' . new moodle_url('/local/stbehaviour/actevaluation.php') . '">' . get_string('activity_evaluation', 'local_stbehaviour') . '</a>
						  </li>
						  
						  <li class="nav-item">
								<a class="nav-link ' . $clas3 . '" href="' . new moodle_url('/local/stbehaviour/userlog.php') . '">' . get_string('stbl', 'local_stbehaviour') . '</a>
						  </li>
				</ul><br><br><br>';
        } else {
            $html .= '<ul class="nav nav-pills nav-fill">
						  <li class="nav-item">
								<a class="nav-link  ' . $clas1 . '" href="' . new moodle_url('/local/stbehaviour/view.php') . '">' . get_string('formalcheck', 'local_stbehaviour') . '</a>
						  </li>
						  <li class="nav-item">
								<a class="nav-link ' . $clas2 . '" href="' . new moodle_url('/local/stbehaviour/formalcriterion.php') . '">' . get_string('stecart', 'local_stbehaviour') . '</a>
						  </li>
						  <li class="nav-item">
								<a class="nav-link ' . $clas4 . '" href="' . new moodle_url('/local/stbehaviour/activity.php') . '">' . get_string('activity', 'local_stbehaviour') . '</a>
						  </li>
						  <li class="nav-item">
								<a class="nav-link ' . $clas6 . '" href="' . new moodle_url('/local/stbehaviour/activityHelper.php') . '">' . get_string('activity_helpers', 'local_stbehaviour') . '</a>
						  </li>
						  <li class="nav-item">
								<a class="nav-link ' . $clas5 . '" href="' . new moodle_url('/local/stbehaviour/actevaluation.php') . '">' . get_string('activity_evaluation', 'local_stbehaviour') . '</a>
						  </li>
						  
						  <li class="nav-item">
								<a class="nav-link ' . $clas3 . '" href="' . new moodle_url('/local/stbehaviour/userlog.php') . '">' . get_string('stbl', 'local_stbehaviour') . '</a>
						  </li>
				</ul><br><br><br>';
        }

        return $html;
    }

    /**
     * @param array $data
     * @return string
     * @throws coding_exception
     * @throws dml_exception
     * @throws moodle_exception
     */
    public function view_activityhelper($data)
    {
        global $DB;
        $table = new html_table();
        $table->head = array(get_string('srno', 'local_stbehaviour'), get_string('coursename', 'local_stbehaviour'), get_string('activityname',
            'local_stbehaviour'), get_string('message', 'local_stbehaviour'), get_string('action', 'local_stbehaviour'));
        $i = 1;
        foreach ($data as $value) {
            $editurl = new moodle_url('/local/stbehaviour/add_helper.php?action=edit&id=' . $value->id);
            $deleteurl = new moodle_url('/local/stbehaviour/add_helper.php?action=delete&id=' . $value->id);

            $edit = html_writer::link($editurl, '<i class="icon fa fa-cog fa-fw " aria-hidden="true" title="Edit" aria-label="Edit"></i>');
            $delete = html_writer::link($deleteurl, '<i class="icon fa fa-trash fa-fw " aria-hidden="true" title="Delete" aria-label="Delete"></i>');
            $modlueid = $DB->get_record('course_modules', array('id' => $value->activyid));
            if (!empty($modlueid)) {
                if ($modlueid->module == 3) {
                    $activitibook = $DB->get_record('book', array('id' => $modlueid->instance));
                    $name = $activitibook->name;
                }
                if ($modlueid->module == 13) {
                    $activitibook = $DB->get_record('lesson', array('id' => $modlueid->instance));
                    $name = $activitibook->name;
                }
                if ($modlueid->module == 15) {
                    $activitibook = $DB->get_record('page', array('id' => $modlueid->instance));
                    $name = $activitibook->name;
                }
                if ($modlueid->module == 20) {
                    $activitibook = $DB->get_record('url', array('id' => $modlueid->instance));
                    $name = $activitibook->name;
                }
            }
            $rec = $DB->get_record('course', array('id' => $value->courseid));
            $table->data[] = array($i, $rec->fullname, $name, $value->message, $edit . ' ' . $delete);
            $i++;
        }

        return html_writer::table($table);
    }

    /**
     * @return string
     * @throws coding_exception
     */
    public function add_activityhelper()
    {
        global $CFG;
        $content = '<div class="searchform" style="float:right;">';
        $content .= '<a class="btn btn-primary" href="' . $CFG->wwwroot . '/local/stbehaviour/add_helper.php"><i class="fa fa-sign-in">' . get_string('addhelper', 'local_stbehaviour') . '</i></a>';
        $content .= '</div>';

        return $content;
    }

}
