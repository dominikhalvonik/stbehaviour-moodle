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

//namespace \core\event\course_viewed;\mod_book\event\chapter_viewed

defined('MOODLE_INTERNAL') || die();

$observers = [
    [
        'eventname' => '\core\event\course_viewed',
        'callback' => 'local_stbehaviour_observer::user_viewactivread',
    ],

    [
        'eventname' => '\mod_book\event\chapter_viewed',
        'callback' => 'local_stbehaviour_observer::book_chapterviewread',
    ],

    [
        'eventname' => '\mod_lesson\event\content_page_viewed',
        'callback' => 'local_stbehaviour_observer::contentpages_chapterviewread',
    ],
    [
        'eventname' => '\mod_page\event\course_module_viewed',
        'callback' => 'local_stbehaviour_observer::pages_chapterviewread',
    ],
];
	