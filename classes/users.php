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
 * File containing users class.
 * 
 * Class of rating tables
 *
 * @package   block_g_statistics
 * @copyright 2024 Streje
 * @author    Alexander Potapov <san_sanih99@mail.ru>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_g_statistics;

defined('MOODLE_INTERNAL') || die();

class users {


    // Получение роли текущего пользователя
    public function get_user_roleid() {
        global $DB, $COURSE, $USER;

        $user_role = $DB->get_records_sql(
            "SELECT ra.id AS id, ra.roleid AS roleid, ra.userid AS userid, con.instanceid AS instanceid, con.contextlevel AS contextlevel
            FROM {role_assignments} AS ra
            JOIN {context} AS con ON ra.contextid = con.id
            JOIN {course} AS c ON con.instanceid = c.id
            JOIN {user} AS u ON u.id = ra.userid
            WHERE con.contextlevel = 50 AND instanceid=:instanceid AND userid=:userid",
            [
                'userid' => $USER->id,
                'instanceid' => $COURSE->id
            ]
        );

        foreach ($user_role as $item) {
            return $item->roleid;
        } 
    }


    // Получить информацию по пользователю, студенту/участнику курса
    public function get_user_info($userid) {
        global $DB, $COURSE;

        $user = $DB->get_records_sql(
            "SELECT ra.id AS id, ra.userid AS userid, u.firstname AS firstname, u.lastname AS lastname
            FROM {user} AS u
            JOIN {role_assignments} AS ra ON ra.userid = u.id
            JOIN {role} AS r ON ra.roleid = r.id 
            JOIN {context} AS con ON ra.contextid = con.id
            JOIN {course} AS c ON con.instanceid = c.id
            WHERE r.shortname = 'student' AND con.contextlevel = 50 AND c.id = :courseid AND userid=:userid",
            [
                'courseid' => $COURSE->id,
                'userid' => $userid
            ]
        );

        return $user;
    }

    
    // Получение информации по всем пользователям
    public function get_users() {
        global $DB, $COURSE;

        $users = $DB->get_records_sql(
            "SELECT ra.id AS id, ra.userid AS userid, u.firstname AS firstname, u.lastname AS lastname
            FROM {user} AS u
            JOIN {role_assignments} AS ra ON ra.userid = u.id
            JOIN {role} AS r ON ra.roleid = r.id 
            JOIN {context} AS con ON ra.contextid = con.id
            JOIN {course} AS c ON con.instanceid = c.id
            WHERE r.shortname = 'student' AND con.contextlevel = 50 AND c.id = :courseid",
            [
                'courseid' => $COURSE->id,
            ]
        );

        return $users;
    }

    // Получение списка пользователей на курсе
    public function get_users_on_course() {
        global $DB, $COURSE;

        $user_role = $DB->get_records_sql(
            "SELECT ra.id AS id, ra.roleid AS roleid, ra.userid AS userid, con.instanceid AS instanceid, con.contextlevel AS contextlevel
            FROM {role_assignments} AS ra
            JOIN {context} AS con ON ra.contextid = con.id
            JOIN {course} AS c ON con.instanceid = c.id
            WHERE con.contextlevel = 50 AND roleid = 5 AND instanceid=:instanceid ",
            [
                'instanceid' => $COURSE->id
            ]
        );

        return $user_role;
    }

    // Получение id активных пользователей
    public function get_active_users() {
        global $DB, $COURSE;

        $active_users_array = $DB->get_records_sql(
            "SELECT  DISTINCT gg.userid AS userid, gi.courseid AS courseid, gi.gradetype AS gradetype
            FROM {grade_grades} AS gg 
            JOIN {grade_items} AS gi ON gg.itemid = gi.id 
            WHERE gradetype != 0 AND itemname IS NOT NULL AND gg.rawgrade IS NOT NULL AND courseid = :courseid",
            [
                'courseid' => $COURSE->id,
            ]
        );

        return $active_users_array;
    }
}