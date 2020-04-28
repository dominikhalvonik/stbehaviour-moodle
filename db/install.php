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

/**
 * Students Behavior installation and migration code.
 *
 * @package    local_stbehaviour
 * @copyright  (C)2019 University of Constantin the Philosopher <dominik.halvonik@ukf.sk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * This function install data to Moodle.
 * @return boolean - true if error does not occur.
 */
function xmldb_local_stbehaviour_install() {
    // Create formal rules and criteria
    migrate_assignment_data();

    return true;
}

/**
 * This function creates all the predefined formal rules and formal rules criteria
 * which need to be defined for activity evaluation from the formal point of view
 */
function migrate_assignment_data() {
    $formalRules = feed_formal_rules();
    $formalRulesCriteria = feed_formal_rules_criteria();

    foreach ($formalRules as $id => $attributes) {
        add_new_fornal_rule($attributes);
    }

    foreach ($formalRulesCriteria as $id => $attributes) {
        add_new_fornal_rule_criteria($attributes);
    }
}

/**
 * Adds a new instance of the formal rule
 *
 * @param object $formalRule
 * @return bool|int - id of the newly inserted record or false
 * @throws dml_exception
 */
function add_new_fornal_rule($formalRule) {
    global $DB;
    return $DB->insert_record('stu_formal_rule', $formalRule);
}

/**
 * Adds a new instance of the formal rule criteria
 *
 * @param object $formalRuleCriteria
 * @return bool|int - id of the newly inserted record or false
 * @throws dml_exception
 */
function add_new_fornal_rule_criteria($formalRuleCriteria) {
    global $DB;
    return $DB->insert_record('stu_formal_criterion', $formalRuleCriteria);
}

/**
 * Construct a formal rule object using parameters
 *
 * @param array $formalRule
 * @return object - formal rule object
 */
function create_new_formal_rule($formalRule) {

    $formalRuleObject = new stdClass();

    $formalRuleObject->id           = $formalRule['id'];
    $formalRuleObject->rulename             = $formalRule['rulename'];
    $formalRuleObject->ruletype            = $formalRule['ruletype'];
    $formalRuleObject->comparationvalue      = $formalRule['comparationvalue'];
    $formalRuleObject->points    = $formalRule['points'];
    $formalRuleObject->description          = $formalRule['description'];
    $formalRuleObject->timecreated      = time();

    return $formalRuleObject;
}

/**
 * Construct a formal rule criteria object using parameters
 *
 * @param array $formalRuleCriteria
 * @return object - formal rule criteria object
 */
function create_new_formal_rule_criteria($formalRuleCriteria) {

    $formalRuleCriteriaObject = new stdClass();

    $formalRuleCriteriaObject->id           = $formalRuleCriteria['id'];
    $formalRuleCriteriaObject->name             = $formalRuleCriteria['name'];
    $formalRuleCriteriaObject->min_points            = $formalRuleCriteria['min_points'];
    $formalRuleCriteriaObject->max_points      = $formalRuleCriteria['max_points'];
    $formalRuleCriteriaObject->description    = $formalRuleCriteria['description'];

    return $formalRuleCriteriaObject;
}

/**
 * Stores the data representing formal rules in array format
 * @return array
 */
function feed_formal_rules()
{
    $formalRules = [
        1 => [
            'id' => 1,
            'rulename' => 'Max words without multimedia content',
            'ruletype' => 1,
            'comparationvalue' => '150-250',
            'points' => 4,
            'description' => 'Max words without multimedia content on one page can be from 150 to 250'
        ],
        2 => [
            'id' => 2,
            'rulename' => 'Min words without multimedia content',
            'ruletype' => 1,
            'comparationvalue' => '20-30',
            'points' => 2,
            'description' => 'Min words without multimedia content on one page can be from 20 to 30'
        ],
        3 => [
            'id' => 3,
            'rulename' => 'Multimedia unit content on number of words',
            'ruletype' => 1,
            'comparationvalue' => '150-250',
            'points' => 3,
            'description' => 'Each 150-250 words it have to be one multimedia content'
        ],
        4 => [
            'id' => 4,
            'rulename' => 'Max words on page',
            'ruletype' => 2,
            'comparationvalue' => '1000',
            'points' => 3,
            'description' => 'Max words on page should not be more than 1000'
        ],
        5 => [
            'id' => 5,
            'rulename' => 'Image on page',
            'ruletype' => 1,
            'comparationvalue' => '1',
            'points' => 1,
            'description' => 'Image on page'
        ],
        6 => [
            'id' => 6,
            'rulename' => 'Video on page',
            'ruletype' => 1,
            'comparationvalue' => '1',
            'points' => 2,
            'description' => 'Video on page'
        ],
        7 => [
            'id' => 7,
            'rulename' => 'Multimedia unit on page',
            'ruletype' => 1,
            'comparationvalue' => '1',
            'points' => 3,
            'description' => 'Any multimedia unit on page like flash animations etc.'
        ],
        8 => [
            'id' => 8,
            'rulename' => 'Usage of bold formatting',
            'ruletype' => 2,
            'comparationvalue' => '1',
            'points' => 1,
            'description' => 'Usage of bold text formatting'
        ],
        9 => [
            'id' => 9,
            'rulename' => 'Do not use underline formatting',
            'ruletype' => 2,
            'comparationvalue' => '1',
            'points' => 1,
            'description' => 'Each 150-250 words it have to be one multimedia content'
        ],
        10 => [
            'id' => 10,
            'rulename' => 'Max cursive formatting',
            'ruletype' => 1,
            'comparationvalue' => '30',
            'points' => 3,
            'description' => 'Max number of words formatted via cursive method cannot be more than 30 in row'
        ],
        11 => [
            'id' => 11,
            'rulename' => 'Max fonts number',
            'ruletype' => 2,
            'comparationvalue' => '2',
            'points' => 3,
            'description' => 'Max number of used fonts on page can be 2'
        ],
        12 => [
            'id' => 12,
            'rulename' => 'Max word sizes',
            'ruletype' => 2,
            'comparationvalue' => '3',
            'points' => 3,
            'description' => 'Max number of used word sizes on page can be 3'
        ],
        13 => [
            'id' => 13,
            'rulename' => 'Paragraphs are used',
            'ruletype' => 2,
            'comparationvalue' => '1',
            'points' => 2,
            'description' => 'Paragraphs are used'
        ]
    ];

    return $formalRules;
}

/**
 * Stores the data representing formal rules criteria in array format
 * @return array
 */
function feed_formal_rules_criteria()
{
    $formalRulesCriteria = [
        1 => [
            'id' => 1,
            'name' => 'A',
            'min_points' => 40,
            'max_points' => 1000,
            'description' => 'Learning content has the highest quality'
        ],
        2 => [
            'id' => 2,
            'name' => 'B',
            'min_points' => 32,
            'max_points' => 39,
            'description' => 'Learning content has minor imperfections'
        ],
        3 => [
            'id' => 3,
            'name' => 'C',
            'min_points' => 25,
            'max_points' => 31,
            'description' => 'Learning content has some problems. We recommend small review'
        ],
        4 => [
            'id' => 4,
            'name' => 'D',
            'min_points' => 19,
            'max_points' => 24,
            'description' => 'Learning content has more problems that it should have. We recommend review'
        ],
        5 => [
            'id' => 5,
            'name' => 'E',
            'min_points' => 11,
            'max_points' => 18,
            'description' => 'Learning content has major problems. We recommend rebuilding the content'
        ],
        6 => [
            'id' => 6,
            'name' => 'FX',
            'min_points' => 0,
            'max_points' => 10,
            'description' => 'Learning content has bad quality. Please restructure the content'
        ]
    ];

    return $formalRulesCriteria;
}