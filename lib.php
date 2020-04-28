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

defined('MOODLE_INTERNAL') || die;

/**
 * @param global_navigation $nav
 * @throws coding_exception
 * @throws dml_exception
 * @throws moodle_exception
 */
function local_stbehaviour_extends_navigation(global_navigation $nav)
{
    local_stbehaviour_extend_navigation($nav);
}

/**
 * @param global_navigation $nav
 * @throws coding_exception
 * @throws dml_exception
 * @throws moodle_exception
 */
function local_stbehaviour_extend_navigation(global_navigation $nav)
{
    global $USER;
    if (isloggedin()) {
        $roles = get_employee_role();
        if ($roles->shortname == 'coursecreator' || $roles->shortname == 'manager') {
            $node = $nav->add(
                get_string('pluginname', 'local_stbehaviour'),
                new moodle_url('/local/stbehaviour/activity.php'),
                navigation_node::TYPE_CUSTOM,
                null,
                'stbehaviour'
            );

            $node->showinflatnavigation = true;
        } else if (is_siteadmin(@$USER->id)) {
            $node = $nav->add(
                get_string('pluginname', 'local_stbehaviour'),
                new moodle_url('/local/stbehaviour/view.php'),
                navigation_node::TYPE_CUSTOM,
                null,
                'stbehaviour'
            );
            $node->showinflatnavigation = true;
        }
    }
}

/**
 * @return mixed
 * @throws dml_exception
 */
function get_employee_role()
{
    global $DB, $USER;
    $sql = "SELECT r.id, r.name,r.shortname, r.description, u.firstname, u.lastname
          FROM {role} r
          JOIN {role_assignments} ra ON ra.roleid = r.id
          JOIN {context} c ON c.id = ra.contextid
          JOIN {user} u ON u.id = ra.userid
         WHERE c.contextlevel = 10 AND ra.userid = '" . $USER->id . "'";
    $roledetail = $DB->get_record_sql($sql);
    return $roledetail;
}

/**
 * @return array
 * @throws dml_exception
 */
function listformalrule()
{
    global $DB;
    $rec = $DB->get_records('stu_formal_rule');
    return $rec;
}

/**
 * @param $id
 * @return mixed
 * @throws dml_exception
 */
function get_dataformal_rule($id)
{
    global $DB;
    $rec = $DB->get_record('stu_formal_rule', array('id' => $id));
    return $rec;
}

/**
 * @param $id
 * @return bool
 * @throws dml_exception
 */
function local_deleteformalchek($id)
{
    global $DB;
    $rec = $DB->delete_records('stu_formal_rule', array('id' => $id));
    return $rec;
}

/**
 * @return array
 * @throws dml_exception
 */
function get_stubehaviourlog()
{
    global $DB;
    $rec = $DB->get_records('stu_behavior_log');
    return $rec;
}

/**
 * @return array
 * @throws dml_exception
 */
function listformalcriterion()
{
    global $DB;
    $rec = $DB->get_records('stu_formal_criterion');
    return $rec;
}

/**
 * @param $id
 * @return mixed
 * @throws dml_exception
 */
function get_dataformal_criterion($id)
{
    global $DB;
    $rec = $DB->get_record('stu_formal_criterion', array('id' => $id));
    return $rec;
}

/**
 * @param $id
 * @return bool
 * @throws dml_exception
 */
function local_deletecriterion($id)
{
    global $DB;
    $rec = $DB->delete_records('stu_formal_criterion', array('id' => $id));
    return $rec;
}

/**
 * @return array
 * @throws dml_exception
 */
function get_listactivity_evaluation()
{
    global $DB;
    $rec = $DB->get_records('stu_activity_evaluation');
    return $rec;
}

/**
 * @return array
 * @throws dml_exception
 */
function get_listactivity()
{
    global $DB;
    $rec = $DB->get_records('stu_addactivity');
    return $rec;
}

/**
 * @return array
 * @throws dml_exception
 */
function get_activityhelper()
{
    global $DB;
    $rec = $DB->get_records('stu_activity_helpers');
    return $rec;

}

/**
 * @param $id
 * @return bool
 * @throws dml_exception
 */
function delete_activityhelper($id)
{
    global $DB;
    $rec = $DB->delete_records('stu_activity_helpers', array('id' => $id));
    return $rec;
}

/**
 * @param $id
 * @return bool
 * @throws dml_exception
 */
function delete_activity($id)
{
    global $DB;
    $stuaddactiv = $DB->get_record('stu_addactivity', array('id' => $id));
    if (!empty($stuaddactiv)) {
        $DB->delete_records('stu_moduleid', array('moduleid' => $stuaddactiv->moduleid));
        $DB->delete_records('stu_activity', array('moduleid' => $stuaddactiv->moduleid));
        $DB->delete_records('stu_activity_evaluation', array('moduleid' => $stuaddactiv->moduleid));
    }
    $rec = $DB->delete_records('stu_addactivity', array('id' => $id));
    return $rec;
}

/**
 * @param $id
 * @return mixed
 * @throws dml_exception
 */
function getdataactivityhelper($id)
{
    global $DB;
    $rec = $DB->get_record('stu_activity_helpers', array('id' => $id));
    return $rec;

}

/**
 * @param $id
 * @return mixed
 * @throws dml_exception
 */
function get_activity_form($id)
{
    global $DB;
    $rec = $DB->get_record('stu_addactivity', array('id' => $id));
    return $rec;
}

/**
 * This method is for page evaluation
 * @param $numberofwords
 * @param $pageContentInHtml
 * @return int
 * @throws dml_exception
 */
function pageEvaluation($numberofwords, $pageContentInHtml)
{
    //number of points for this page
    $numberOfPoints = 0;
    //get all rules which are used for formal check
    $rules = listformalrule();
    //apply all rules on the content
    foreach ($rules as $rule) {
        if ($rule->rulename === 'Max words without multimedia content' || $rule->rulename === 'Min words without multimedia content') {
            $ruleValueInternval = explode($rule->comparationvalue, "-");
            $imageArray = explode('<img', $pageContentInHtml);
            $objectArray = explode('<object', $pageContentInHtml);
            $videoArray = explode('<video', $pageContentInHtml);
            if(count($imageArray) > 1) {
                foreach ($imageArray as $section) {
                    $numberOfWordsInSection = str_word_count($section, 0);
                    if ($numberOfWordsInSection >= $ruleValueInternval[0] && $numberOfWordsInSection <= $ruleValueInternval[1]) {
                        //add coll points which represents the number of points which this rule adds
                        $numberOfPoints += $rule->points;
                    }
                }
            }
            if(count($objectArray) > 1) {
                foreach ($objectArray as $section) {
                    $numberOfWordsInSection = str_word_count($section, 0);
                    if ($numberOfWordsInSection >= $ruleValueInternval[0] && $numberOfWordsInSection <= $ruleValueInternval[1]) {
                        //add coll points which represents the number of points which this rule adds
                        $numberOfPoints += $rule->points;
                    }
                }
            }
            if(count($videoArray) > 1) {
                foreach ($videoArray as $section) {
                    $numberOfWordsInSection = str_word_count($section, 0);
                    if ($numberOfWordsInSection >= $ruleValueInternval[0] && $numberOfWordsInSection <= $ruleValueInternval[1]) {
                        //add coll points which represents the number of points which this rule adds
                        $numberOfPoints += $rule->points;
                    }
                }
            }
        } else if ($rule->rulename === 'Multimedia unit content on number of words') {
            $ruleValueInternval = explode($rule->comparationvalue, "-");
            $imageArray = explode('<img', $pageContentInHtml);
            $numberOfImages = count($imageArray);
            $objectArray = explode('<object', $pageContentInHtml);
            $numberOfObjects = count($objectArray);
            $videoArray = explode('<video', $pageContentInHtml);
            $numberOfVideos = count($videoArray);
            $countOccurence = 0;
            $totalNumberOfWords = 0;
            if($numberOfImages > 1) {
                foreach ($imageArray as $section) {
                    $numberOfWordsInSection = str_word_count($section, 0);
                    $totalNumberOfWords += $numberOfWordsInSection;
                }
            }
            if($numberOfObjects > 1) {
                foreach ($objectArray as $section) {
                    $numberOfWordsInSection = str_word_count($section, 0);
                    $totalNumberOfWords += $numberOfWordsInSection;
                }
            }
            if($numberOfVideos > 1) {
                foreach ($videoArray as $section) {
                    $numberOfWordsInSection = str_word_count($section, 0);
                    $totalNumberOfWords += $numberOfWordsInSection;
                }
            }
            $countOccurence += $totalNumberOfWords / ($numberOfImages + $numberOfObjects + $numberOfVideos);
            if ($countOccurence >= $ruleValueInternval[0] && $countOccurence <= $ruleValueInternval[1]) {
                //add coll points which represents the number of points which this rule adds
                $numberOfPoints += $rule->points;
            }
        } else if ($rule->rulename === 'Max words on page') {
            if ($numberofwords <= $rule->comparationvalue) {
                //add coll points which represents the number of points which this rule adds
                $numberOfPoints += $rule->points;
            }
        } else if ($rule->rulename === 'Image on page') {
            $array = explode('<img', $pageContentInHtml);
            $numberOfElemets = count($array);
            if ($numberOfElemets > 1) {
                $numberOfPoints += ($rule->points * $numberOfElemets);
            }
        } else if ($rule->rulename === 'Video on page') {
            $array = explode('<video', $pageContentInHtml);
            $numberOfElemets = count($array);
            if ($numberOfElemets > 1) {
                $numberOfPoints += ($rule->points * $numberOfElemets);
            }
        }else if ($rule->rulename === 'Multimedia unit on page') {
            $array = explode('<object', $pageContentInHtml);
            $numberOfElemets = count($array);
            if ($numberOfElemets > 1) {
                $numberOfPoints += ($rule->points * $numberOfElemets);
            }
        } else if ($rule->rulename === 'Usage of bold formatting') {
            $isBoldUsed = strpos($pageContentInHtml, '<b>');
            if ($isBoldUsed !== false) {
                $numberOfPoints += $rule->points;
            }
        } else if ($rule->rulename === 'Do not use underline formatting') {
            $isUnderlineUsed = strpos($pageContentInHtml, '<u>');
            if ($isUnderlineUsed === false) {
                $numberOfPoints += $rule->points;
            } else {
                $numberOfPoints -= $rule->points;
            }
        } else if ($rule->rulename === 'Max cursive formatting') {
            if (strpos($pageContentInHtml, '<i>') !== false) {
                $pattern = "#<\s*?i\b[^>]*>(.*?)</i\b[^>]*>#s";
                preg_match($pattern, $pageContentInHtml, $matches);
                $numberOfWordsInSection = str_word_count($matches[1], 0);
                if ($numberOfWordsInSection <= $rule->comparationvalue) {
                    $numberOfPoints += $rule->points;
                } else {
                    $numberOfPoints -= $rule->points;
                }
            }
        } else if ($rule->rulename === 'Max fonts number') {
            //TODO improve the way how count multiple fonts
            if (strpos($pageContentInHtml, 'font-family:') !== false) {
                $numberOfFonts = substr_count($pageContentInHtml, 'font-family:');
                if($numberOfFonts > 2) {
                    $numberOfPoints -= $rule->points;
                } else {
                    $numberOfPoints += $rule->points;
                }
            } else {
                $numberOfPoints += $rule->points;
            }
        } else if ($rule->rulename === 'Max word sizes') {
            //TODO implement a rule which will take to account number of word sizes in paragraph
        } else if ($rule->rulename === 'Paragraphs are used') {
            if (strpos($pageContentInHtml, '<p>') !== false) {
                $numberOfPoints += $rule->points;
            }
        }
    }
    //return number of points for page
    return $numberOfPoints;

}

/**
 * @param $activityId
 * @return array|mixed
 * @throws dml_exception
 */
function activityEvaluation($activityId)
{
    global $DB;
    $moduleid = 0;
    $activityPages = $DB->get_records('stu_moduleid', array('activid' => $activityId));
    //number of points for course
    $numberOfPoints = 0;
    //loop all pages for provided activity - if the activity will be for example Page(which contains only 1 page) it will
    //be an array with 1 page in it
    $numberpages = 0;
    foreach ($activityPages as $activityresult) {
        $originalContent = getOriginalContent($activityresult->moduleid, $activityresult->pagenum);
        //get number of points for page base on the rules
        $pagePoints = pageEvaluation($activityresult->numberofwords, $originalContent);
        //add page points to total activity points
        $numberOfPoints += $pagePoints;
        ++$numberpages;
        $moduleid = $activityresult->moduleid;
    }
    //get the final score for activity
    $finalPoints = round($numberOfPoints / $numberpages, 0);
    //get the mark for this activity and return it to front-end which will display the name and the description
    $evaluationResult = activityCriteriaEvaluation($finalPoints);
    $insert = new stdClass();
    $insert->created_at = time();
    $insert->formal_evalcriteria_id = $evaluationResult->id;
    $insert->activities_id = $activityId;
    $insert->moduleid = $moduleid;
    $DB->insert_record('stu_activity_evaluation', $insert);
    //calculate estimated time on this activity for each page
    $pagesTimeOnTask = calculateTimeOnTask($activityPages);
    //save calculated time into database
    foreach ($pagesTimeOnTask as $pageId => $timeOnTaks) {
        $inserttime = new stdClass();
        $inserttime->estimatedtime_task = $timeOnTaks;
        $inserttime->pageid = $pageId;
        $inserttime->listenmoduleid = $activityId;
        $inserttime->moduleid = $moduleid;
        $DB->insert_record('stu_activity', $inserttime);
    }
    //return the evaluation result
    return $evaluationResult;
}

/**
 * @param $numberOfPoints
 * @return array|mixed
 * @throws dml_exception
 */
function activityCriteriaEvaluation($numberOfPoints)
{
    //get all criteria from database
    global $DB;
    $criteria = $DB->get_records('stu_formal_criterion');
    $criteriondata = [];
    //loop the criteria
    foreach ($criteria as $criterion) {
        //if number of points for the activity match the points interval for this criteria return it
        if ($criterion->min_points <= $numberOfPoints && $criterion->max_points >= $numberOfPoints) {
            $criteriondata['name'] = $criterion->name;
            $criteriondata['min_points'] = $criterion->min_points;
            $criteriondata['max_points'] = $criterion->max_points;
            $criteriondata['description'] = $criterion->description;

            return $criterion;
        }
    }

    return $criteriondata;
}

/**
 * This method will calculate the estimated time which should student spend on each page
 * while the formal check happens. The calculation is done based on research done at Constantine the Philosopher University in Nitra
 * @param array $activityPages
 * @return array
 * @throws dml_exception
 */
function calculateTimeOnTask($activityPages = [])
{
    //init response array
    $timeOnTaskResponse = [];
    //loop each activity page(for activity Page it will be only 1, for activity Book it will be multiple pages etc.)
    foreach ($activityPages as $activitypage) {
        //need to get number of graphical objects like images, etc.
        $numberOfGraphicalObjects = getNumberOfGraphicalObjects($activitypage->moduleid, $activitypage->pagenum);
        //calculate estimated time on page
        $timeOnTask = (0.04065806 * $activitypage->numberofwords) + (3.4554037 * $numberOfGraphicalObjects) + 65.00134174;
        //need to round the because we are working with seconds
        $finalTimeOnTask = round($timeOnTask, 0);
        //prepare response for each page
        $timeOnTaskResponse[$activitypage->id] = $finalTimeOnTask;
    }

    return $timeOnTaskResponse;
}

/**
 * @param $moduleid
 * @throws dml_exception
 */
function evaluationcalut($moduleid)
{
    global $DB;
    $modlueid = $DB->get_record('course_modules', array('id' => $moduleid));
    if (!empty($modlueid)) {
        if ($modlueid->module == 3) {
            //calculate book
            $activitibook = $DB->get_record('book', array('id' => $modlueid->instance));
            $allpages = $DB->get_records('book_chapters', array('bookid' => $activitibook->id));
            $duplicrecod = $DB->get_records('stu_moduleid', array('moduleid' => $moduleid));
            if (empty($duplicrecod)) {
                if (!empty($allpages)) {
                    foreach ($allpages as $pages) {
                        $rawText = str_replace('<', ' <', $pages->content);
                        $doubleSpace = strip_tags($rawText);
                        $text = str_replace('  ', ' ', $doubleSpace);
                        $numberOfWords = str_word_count($text, 0);
                        $recmodule = new stdClass();
                        $recmodule->courseid = $activitibook->course;
                        $recmodule->activid = $activitibook->id;
                        $recmodule->pagenum = $pages->id;
                        $recmodule->numberofwords = $numberOfWords;
                        $recmodule->moduleid = $moduleid;
                        $recmodule->moduletype = 'book';
                        $DB->insert_record('stu_moduleid', $recmodule);
                    }
                }
            }

        } else if ($modlueid->module == 13) {
            //calculate lesson
            $activitibook = $DB->get_record('lesson', array('id' => $modlueid->instance));
            $allpages = $DB->get_records('lesson_pages', array('lessonid' => $activitibook->id));
            $duplicrecod = $DB->get_records('stu_moduleid', array('moduleid' => $moduleid));
            if (empty($duplicrecod)) {
                if (!empty($allpages)) {
                    foreach ($allpages as $pages) {
                        $rawText = str_replace('<', ' <', $pages->contents);
                        $doubleSpace = strip_tags($rawText);
                        $text = str_replace('  ', ' ', $doubleSpace);
                        //$text = strip_tags($pages->contents);
                        $numberOfWords = str_word_count($text, 0);
                        $recmodule = new stdClass();
                        $recmodule->courseid = $activitibook->course;
                        $recmodule->activid = $activitibook->id;
                        $recmodule->pagenum = $pages->id;
                        $recmodule->numberofwords = $numberOfWords;
                        $recmodule->moduleid = $moduleid;
                        $recmodule->moduletype = 'lesson';
                        $DB->insert_record('stu_moduleid', $recmodule);
                    }
                }
            }
        } else if ($modlueid->module == 15) {
            //calculate page
            $activitibook = $DB->get_record('page', array('id' => $modlueid->instance));
            $duplicrecod = $DB->get_records('stu_moduleid', array('moduleid' => $moduleid));
            if (empty($duplicrecod)) {
                if (!empty($activitibook)) {
                    $rawText = str_replace('<', ' <', $activitibook->content);
                    $doubleSpace = strip_tags($rawText);
                    $text = str_replace('  ', ' ', $doubleSpace);
                    $numberOfWords = str_word_count($text, 0);
                    $recmodule = new stdClass();
                    $recmodule->courseid = $activitibook->course;
                    $recmodule->activid = $activitibook->id;
                    $recmodule->pagenum = $activitibook->id;
                    $recmodule->numberofwords = $numberOfWords;
                    $recmodule->moduleid = $moduleid;
                    $recmodule->moduletype = 'page';
                    $DB->insert_record('stu_moduleid', $recmodule);
                }
            }
        } else if ($modlueid->module == 20) {
            //TODO calculate URL
            $activitibook = $DB->get_record('url', array('id' => $modlueid->instance));
            $name = $activitibook->name;
        }
    }
    //if the activity is in list of activities that can be evaluated, evaluate
    if (!empty($activitibook)) {
        activityEvaluation($activitibook->id);
    }

}

/**
 * @param $moduleid
 * @param $pageNumber
 * @return string
 * @throws dml_exception
 */
function getOriginalContent($moduleid, $pageNumber)
{
    global $DB;
    $modlueid = $DB->get_record('course_modules', array('id' => $moduleid));
    if (!empty($modlueid)) {
        switch ($modlueid->module) {
            case 3:
                //book
                $activitibook = $DB->get_record('book', array('id' => $modlueid->instance));
                $allpages = $DB->get_records('book_chapters', array('bookid' => $activitibook->id));
                if (!empty($allpages)) {
                    foreach ($allpages as $pages) {
                        if ($pageNumber === $pages->id) {
                            return $pages->content;
                        }
                    }
                }
                break;
            case 13:
                //lesson
                $activitibook = $DB->get_record('lesson', array('id' => $modlueid->instance));
                $allpages = $DB->get_records('lesson_pages', array('lessonid' => $activitibook->id));
                if (!empty($allpages)) {
                    foreach ($allpages as $pages) {
                        if ($pageNumber === $pages->id) {
                            return $pages->content;
                        }
                    }
                }
                break;
            case 15:
                //page
                $activitibook = $DB->get_record('page', array('id' => $modlueid->instance));
                if (!empty($activitibook)) {
                    return $activitibook->content;
                }
                break;
        }
    }

    return "";
}

/**
 * @param $moduleid
 * @param $pageNumber
 * @return int
 * @throws dml_exception
 */
function getNumberOfGraphicalObjects($moduleid, $pageNumber)
{
    $originalContent = getOriginalContent($moduleid, $pageNumber);
    $imageArray = explode('<img', $originalContent);
    $numberOfImages = (count($imageArray) - 1);
    $objectArray = explode('<object', $originalContent);
    $numberOfObjects = (count($objectArray) - 1);
    $videoArray = explode('<video', $originalContent);
    $numberOfVideos = (count($videoArray) - 1);

    return $numberOfImages + $numberOfObjects + $numberOfVideos;
}




