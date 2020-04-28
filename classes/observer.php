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
// GNU General Public License for more details.local_stbehaviour

class local_stbehaviour_observer
{
    const ESTIMATED_TIME_ON_TASK = 65;
    /**
     * @param \core\event\course_viewed $event
     */
    public static function user_viewactivread(core\event\course_viewed $event)
    {
        /* course and section view user */
        $record = new stdClass();
        $record->userid = $event->userid;
        $record->courseid = $event->courseid;
        $record->component = $event->target;
        $record->contextid = $event->contextid;
        $record->activid = 0;
        $record->chapter = 0;
        $record->pagenum = 0;
        $record->subchapter = 0;
        $record->numberofwords = 0;
        $record->timeview = time();
    }

    /**
     * This method is triggered when user will enter activity of type book
     * @param \mod_book\event\chapter_viewed $event
     * @throws dml_exception
     */
    public static function book_chapterviewread(mod_book\event\chapter_viewed $event)
    {
        /* book and chapter view user */
        global $DB, $CFG;
        //get activity as object
        $res = $DB->get_record($event->objecttable, array('id' => $event->objectid));
        //get information about activity from plugin database
        $ress = $DB->get_record('stu_moduleid', array('courseid' => $event->courseid, 'activid' => $res->bookid, 'pagenum' => $res->id));
        //if the activity is set as an activity which should be monitored execute actions
        if (!empty($ress)) {
            //get calculated estimated time on this activity
            $estimatedTimeOnTask = local_stbehaviour_observer::getEstimatedTimeForActivity($ress);
            //get helper message for this activity
            $helpersArray = local_stbehaviour_observer::getHelperText($ress);
            $mesg = $helpersArray['mesg'];
            $helpers_text = $helpersArray['helpers_text'];
            //add JS/CSS and addition HTML to template header
            $CFG->additionalhtmlhead .= '<div id="mop_2" class="modal">
	                                        <div class="modal-content">
                                                <span id="close_2" class="close">&times;</span>
	                                            <div class="modal-body">
		                                            <div class="divlession">
			                                            <img src="' . $CFG->wwwroot . '/local/stbehaviour/img/helper.png" alt="Snow" style="width: 30%; height: 30%;">
			                                            <div class="centered"><p>' . $helpers_text . '</p></div>
		                                            </div>
	                                            </div>
		                                    </div>
	                                    </div>
	                                    <script>
                                        localStorage.setItem("mesg", ' . $mesg . ');
                                        localStorage.setItem("courseid", ' . $event->courseid . ');
                                        localStorage.setItem("pageid", ' . $res->id . ');
                                        localStorage.setItem("actid", ' . $res->bookid . ');
                                        localStorage.setItem("moduleid", ' . $ress->moduleid . ');
                                        localStorage.setItem("estimatedTime", ' . $estimatedTimeOnTask . ');
                                            function start() {
                                                 timer = setInterval(clockTick, 1000);
                                                localStorage.setItem("seconds", 0);
                                             }
                                              function clockTick() {
                                                  if(document.hasFocus()) {
                                                      seconds = parseInt(localStorage.getItem("seconds") || 0);
                                                      seconds++;
                                                      localStorage.setItem("seconds", seconds);
                                                      courseid = parseInt(localStorage.getItem("courseid") || 0);
                                                      actid = parseInt(localStorage.getItem("actid") || 0);
                                                      pageid = parseInt(localStorage.getItem("pageid") || 0);
                                                      moduleid = parseInt(localStorage.getItem("moduleid") || 0);
                                                      $.ajax({
                                                          type: "POST",
                                                          url: M.cfg.wwwroot + "/local/stbehaviour/countdontimer.php",
                                                          data: {
                                                              seconds: seconds,
                                                              pageid: pageid,
                                                              nameact: "book",
                                                              actid: actid,
                                                              courseid: courseid,
                                                              moduleid: moduleid
                                                          },
                                                          dataType: "text",
                                                          success: function (resultData) {
                                                              //stop();
                                                          }
                                                      });
                                                      mesg = parseInt(localStorage.getItem("mesg") || 0);
                                                      estimatedTime = parseInt(localStorage.getItem("estimatedTime") || 0);
                                                      if (mesg > 0) {
                                                          if(seconds === estimatedTime) {
                                                                var modal = $("#mop_2");
                                                                modal.fadeIn(1000);
                                                                var span = document.getElementById("close_2");
                                                                span.onclick = function() {
                                                                    modal.fadeOut(1000)
                                                                }
                                                                window.onclick = function(event) {
                                                                    var modalDiv = document.getElementById("mop_2");
                                                                    if (event.target === modalDiv) {
                                                                        modal.fadeOut(1000)
                                                                    }
                                                                }
                                                           }
                                                      }
                                                  }
                                              };
                                        start();
                                        </script>
                                        <style>
                                        .divlession {
                                          position: relative;
                                          text-align: center;
                                          color: white;
                                        }
                                        .centered {
                                          position: relative;
                                        }
                                        .modal {
                                          display: none;
                                          position: fixed;
                                          padding-top: 10px;
                                            padding-bottom: 10px;
                                          left: 0;
                                          top: 0;
                                          width: 100%;
                                          height: 100%;
                                          overflow: auto;
                                          background-color: rgb(0,0,0);
                                          background-color: rgba(0,0,0,0.4);
                                          margin-left:0%
                                        }
                                        .modal-content {
                                          position: relative;
                                          background-color: transparent;
                                          margin: auto;
                                          padding: 0;
                                          border: none;
                                          width: 50%!important;
                                          top: 12%;
                                          border-top-left-radius: 5px;
                                          border-bottom-left-radius: 5px;
                                          left: 0%;
                                          padding-left: 1%;
                                          padding-bottom: 1%;
                                        }
                                        .modal-body{
                                          text-align: center;
                                          box-sizing:border-box;
                                          padding:0%;
                                        
                                        }
                                        .close,
                                        .close:hover,
                                        .close:active{
                                          color: white;
                                          float: right;
                                          font-size: 28px;
                                          font-weight: bold;
                                          position:absolute;
                                          right:0%;
                                          z-index:2;
                                        }
                                        .modal-header {
                                          padding:0px;
                                          background-color: #fefefe;
                                          color: white;
                                          text-align: center;
                                        }
                                        </style>';
        }
    }

    /**
     * This method is called when user will enter lesson activity
     * @param \mod_lesson\event\content_page_viewed $event
     * @throws dml_exception
     */
    public static function contentpages_chapterviewread(mod_lesson\event\content_page_viewed $event)
    {
        /* book and chapter view user */
        global $DB, $CFG;
        //get activity as object
        $res = $DB->get_record($event->objecttable, array('id' => $event->objectid));
        //get information about activity from plugin database
        $ress = $DB->get_record('stu_moduleid', array('courseid' => $event->courseid, 'activid' => $res->lessonid, 'pagenum' => $res->id));
        if (!empty($ress)) {
            //get calculated estimated time on this activity
            $estimatedTimeOnTask = local_stbehaviour_observer::getEstimatedTimeForActivity($ress);
            //get helper message for this activity
            $helpersArray = local_stbehaviour_observer::getHelperText($ress);
            $mesg = $helpersArray['mesg'];
            $helpers_text = $helpersArray['helpers_text'];

            $CFG->additionalhtmlhead .= '<div id="mop_2" class="modal">
	                                        <div class="modal-content">
                                                <span id="close_2"  class="close">&times;</span>
	                                            <div class="modal-body">
		                                            <div class="divlession">
			                                            <img src="' . $CFG->wwwroot . '/local/stbehaviour/img/helper.png" alt="Snow" style="width: 30%; height: 30%;">
			                                            <div class="centered"><p>' . $helpers_text . '</p></div>
		                                            </div>
	                                            </div>
		                                    </div>
	                                    </div>
	                                    <script>
                                        localStorage.setItem("mesg", ' . $mesg . ');
                                        localStorage.setItem("courseid", ' . $event->courseid . ');
                                        localStorage.setItem("pageid", ' . $res->id . ');
                                        localStorage.setItem("actid", ' . $res->lessonid . ');
                                        localStorage.setItem("moduleid", ' . $ress->moduleid . ');
                                        localStorage.setItem("estimatedTime", ' . $estimatedTimeOnTask . ');
                                            function start() {
                                                 timer = setInterval(clockTick, 1000);
                                                localStorage.setItem("seconds", 0);
                                             }
                                              function clockTick() {
                                                  if(document.hasFocus()) {
                                                      seconds = parseInt(localStorage.getItem("seconds") || 0);
                                                      seconds++;
                                                      localStorage.setItem("seconds", seconds);
                                                      courseid = parseInt(localStorage.getItem("courseid") || 0);
                                                      actid = parseInt(localStorage.getItem("actid") || 0);
                                                      pageid = parseInt(localStorage.getItem("pageid") || 0);
                                                      moduleid = parseInt(localStorage.getItem("moduleid") || 0);
                                                      $.ajax({
                                                          type: "POST",
                                                          url: M.cfg.wwwroot + "/local/stbehaviour/countdontimer.php",
                                                          data: {
                                                              seconds: seconds,
                                                              pageid: pageid,
                                                              nameact: "lesson",
                                                              actid: actid,
                                                              courseid: courseid,
                                                              moduleid: moduleid
                                                          },
                                                          dataType: "text",
                                                          success: function (resultData) {
                                                              //stop();
                                                          }
                                                      });
                                                      mesg = parseInt(localStorage.getItem("mesg") || 0);
                                                      estimatedTime = parseInt(localStorage.getItem("estimatedTime") || 0);
                                                      if (mesg > 0) {
                                                          if(seconds === estimatedTime) {
                                                                var modal = $("#mop_2");
                                                                modal.fadeIn(1000);
                                                                var span = document.getElementById("close_2");
                                                                span.onclick = function() {
                                                                    modal.fadeOut(1000)
                                                                }
                                                                window.onclick = function(event) {
                                                                    var modalDiv = document.getElementById("mop_2");
                                                                    if (event.target === modalDiv) {
                                                                        modal.fadeOut(1000)
                                                                    }
                                                                }
                                                           }
                                                      }
                                                  }
                                              };
                                        start();
                                        </script>
                                        <style>
                                            .divlession {
                                              position: relative;
                                              text-align: center;
                                              color: white;
                                            }
                                            .centered {
                                              position: relative;
                                            }
                                            .modal {
                                              display: none;
                                              position: fixed;
                                              padding-top: 10px;
                                                padding-bottom: 10px;
                                              left: 0;
                                              top: 0;
                                              width: 100%;
                                              height: 100%;
                                              overflow: auto;
                                              background-color: rgb(0,0,0);
                                              background-color: rgba(0,0,0,0.4);
                                              margin-left:0%
                                            }
                                            .modal-content {
                                              position: relative;
                                              background-color: transparent;
                                              margin: auto;
                                              padding: 0;
                                              border: none;
                                              width: 50%!important;
                                              top: 12%;
                                              border-top-left-radius: 5px;
                                              border-bottom-left-radius: 5px;
                                              left: 0%;
                                              padding-left: 1%;
                                              padding-bottom: 1%;
                                            }
                                            .modal-body{
                                              text-align: center;
                                              box-sizing:border-box;
                                              padding:0%;
                                            
                                            }
                                            .close,
                                            .close:hover,
                                            .close:active{
                                              color: white;
                                              float: right;
                                              font-size: 28px;
                                              font-weight: bold;
                                              position:absolute;
                                              right:0%;
                                              z-index:2;
                                            }
                                            .modal-header {
                                              padding:0px;
                                              background-color: #fefefe;
                                              color: white;
                                              text-align: center;
                                            }
                                            </style>';
        }
    }

    /**
     * This method is called when user enter page activity
     * @param \mod_page\event\course_module_viewed $event
     * @throws dml_exception
     */
    public static function pages_chapterviewread(mod_page\event\course_module_viewed $event)
    {
        /* pages and chapter view user */
        global $DB, $CFG;
        //get activity as object
        $res = $DB->get_record($event->objecttable, array('id' => $event->objectid));
        //get information about activity from plugin database
        $ress = $DB->get_record('stu_moduleid', array('courseid' => $event->courseid, 'activid' => $res->id, 'pagenum' => $res->id));
        if (!empty($ress)) {
            //get calculated estimated time on this activity
            $activityEvaluation = $DB->get_record('stu_activity', ['pageid' => $ress->id, 'moduleid' => $ress->moduleid]);
            if (empty($activityEvaluation)) {
                $estimatedTimeOnTask  = 65;
            } else {
                $estimatedTimeOnTask  = $activityEvaluation->estimatedtime_task;
            }
            //get helper message for this activity
            $helpersArray = local_stbehaviour_observer::getHelperText($ress);
            $mesg = $helpersArray['mesg'];
            $helpers_text = $helpersArray['helpers_text'];

            $CFG->additionalhtmlhead .= '<div id="mop_2" class="modal">
	                                        <div class="modal-content">
                                                <span id="close_2"  class="close">&times;</span>
	                                            <div class="modal-body">
		                                            <div class="divlession">
			                                            <img src="' . $CFG->wwwroot . '/local/stbehaviour/img/helper.png" alt="Snow" style="width: 30%; height: 30%;">
			                                            <div class="centered"><p>' . $helpers_text . '</p></div>
		                                            </div>
	                                            </div>
	                                        </div>
	                                      </div>
	                                      <script>
                                            localStorage.setItem("mesg", ' . $mesg . ');
                                            localStorage.setItem("courseid", ' . $event->courseid . ');
                                            localStorage.setItem("pageid", ' . $res->id . ');
                                            localStorage.setItem("actid", ' . $res->id . ');
                                            localStorage.setItem("moduleid", ' . $ress->moduleid . ');
                                            localStorage.setItem("estimatedTime", ' . $estimatedTimeOnTask  . ');
                                                function start() {
                                                     timer = setInterval(clockTick, 1000);
                                                    localStorage.setItem("seconds", 0);
                                                 }
                                                  function clockTick() {
                                                    if(document.hasFocus()) {
                                                        seconds = parseInt(localStorage.getItem("seconds") || 0);
                                                        seconds++;
                                                        localStorage.setItem("seconds", seconds);
                                                        courseid = parseInt(localStorage.getItem("courseid") || 0);
                                                        actid = parseInt(localStorage.getItem("actid") || 0);
                                                        pageid = parseInt(localStorage.getItem("pageid") || 0);
                                                        moduleid = parseInt(localStorage.getItem("moduleid") || 0);
                                                        $.ajax({
                                                            type: "POST",
                                                            url: M.cfg.wwwroot +"/local/stbehaviour/countdontimer.php",
                                                            data: { 
                                                                seconds: seconds,
                                                                pageid: pageid,
                                                                nameact: "page",
                                                                actid: actid,
                                                                courseid: courseid,
                                                                moduleid: moduleid
                                                            },
                                                            dataType: "text",
                                                            success: function(resultData) {
                                                                //stop();
                                                            }
                                                        });
                                                        mesg = parseInt(localStorage.getItem("mesg") || 0);
                                                        estimatedTime = parseInt(localStorage.getItem("estimatedTime") || 0);
                                                        if(mesg > 0) { 
                                                            if(seconds === estimatedTime) {
                                                                var modal = $("#mop_2");
                                                                modal.fadeIn(1000);
                                                                var span = document.getElementById("close_2");
                                                                span.onclick = function() {
                                                                    modal.fadeOut(1000)
                                                                }
                                                                window.onclick = function(event) {
                                                                    var modalDiv = document.getElementById("mop_2");
                                                                    if (event.target === modalDiv) {
                                                                        modal.fadeOut(1000)
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                  };
                                            start();
                                            </script>
                                            <style>
                                                .divlession {
                                                  position: relative;
                                                  text-align: center;
                                                  color: white;
                                                }
                                                .centered {
                                                  position: relative;
                                                }
                                                .modal {
                                                  display: none;
                                                  position: fixed;
                                                  padding-top: 10px;
                                                    padding-bottom: 10px;
                                                  left: 0;
                                                  top: 0;
                                                  width: 100%;
                                                  height: 100%;
                                                  overflow: auto;
                                                  background-color: rgb(0,0,0);
                                                  background-color: rgba(0,0,0,0.4);
                                                  margin-left:0%
                                                }
                                                .modal-content {
                                                  position: relative;
                                                  background-color: transparent;
                                                  margin: auto;
                                                  padding: 0;
                                                  border: none;
                                                  width: 50%!important;
                                                  top: 12%;
                                                  border-top-left-radius: 5px;
                                                  border-bottom-left-radius: 5px;
                                                  left: 0%;
                                                  padding-left: 1%;
                                                  padding-bottom: 1%;
                                                }
                                                .modal-body{
                                                  text-align: center;
                                                  box-sizing:border-box;
                                                  padding:0%;
                                                
                                                }
                                                .close,
                                                .close:hover,
                                                .close:active{
                                                  color: white;
                                                  float: right;
                                                  font-size: 28px;
                                                  font-weight: bold;
                                                  position:absolute;
                                                  right:0%;
                                                  z-index:2;
                                                }
                                                .modal-header {
                                                  padding:0px;
                                                  background-color: #fefefe;
                                                  color: white;
                                                  text-align: center;
                                                }
                                            </style>';
        }
    }

    /**
     * @param object $ress
     * @return int
     * @throws dml_exception
     */
    private static function getEstimatedTimeForActivity($ress)
    {
        //global variable for DB object
        global $DB;
        //get the estimated time on this activity calculated from database
        $activityEvaluation = $DB->get_record('stu_activity', ['pageid' => $ress->id, 'moduleid' => $ress->moduleid]);
        if (empty($activityEvaluation)) {
            //if there is no calculation done set it to constant value
            $estimatedTimeOnTask = self::ESTIMATED_TIME_ON_TASK;
        } else {
            //if there is a calculated time - this should be always the case, set the variable to the correct value
            $estimatedTimeOnTask = $activityEvaluation->estimatedtime_task;
        }

        return $estimatedTimeOnTask;
    }

    /**
     * @param object $ress
     * @return array
     * @throws dml_exception
     */
    private static function getHelperText($ress)
    {
        //global variable for DB object
        global $DB;
        //get all helper messages for this activity
        $testhelpers = $DB->get_records('stu_activity_helpers', array('activyid' => $ress->moduleid));
        //select random number
        $randomKey = array_rand($testhelpers, 1);
        //select random helper text
        $testhelper = $testhelpers[$randomKey];
        //init variables for helper text and message number
        $mesg = 0;
        $helpers_text = '';
        //if there is any saved data select the message and helper text
        if (!empty($testhelper)) {
            $mesg = 3;
            $helpers_text = $testhelper->message;
        }

        return [
            'mesg' => $mesg,
            'helpers_text' => $helpers_text
        ];
    }
}

