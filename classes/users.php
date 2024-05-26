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

    /**
     * Получение роли пользователя
     * 
     * @param int $userid id пользователя (если переданно null, то будет проверка по текущему)
     * @param int $courseid id курса (если переданно null, то будет проверка по текущему)
     * 
     * @return int roleid индитификатор роли пользователя или -1, если пользователь на курсе не найден
     */
    public function get_user_roleid($userid=null, $courseid=null) {

        $user_info = $this->get_user_info_DB($userid, $courseid);

        foreach ($user_info as $item) {
            return $item->roleid;
        }

        return -1;
    }


    /**
     * Получение информации по пользователю
     * 
     * @param int $userid id пользователя (если переданно null, то будет проверка по текущему)
     * @param int $courseid id курса (если переданно null, то будет проверка по текущему)
     * 
     * @return array первый элемент ассоциативного массива с информацией о пользователе или пустой массив, если пользователь не найден
     */
    public function get_user_info($userid=null, $courseid=null) {

        $user_info = $this->get_user_info_DB($userid, $courseid);

        foreach ($user_info as $item) {
            return $item;
        }

        return [];
    }


    /**
     * Получение информации по всем пользователям
     * 
     * @param int $courseid id курса (если переданно null, то будет проверка по текущему)
     * 
     * @return array ассоциативный массив с информацией по пользователям
     */
    public function get_users_info($courseid=null) {

        $users_info = $this->get_users_info_DB($courseid);
        return $users_info;
    }



    // PRIVATE FUNCTIONS

    /**
     * Получение информации по пользователю
     * 
     * @param int $userid id пользователя (если переданно null, то будет проверка по текущему)
     * @param int $courseid id курса (если переданно null, то будет проверка по текущему)
     * 
     * @return array ассоциативный массив с информацией по пользователю
     */
    private function get_user_info_DB($userid, $courseid) {

        global $DB, $COURSE, $USER;

        if(is_null($userid)) $userid = $USER->id;
        if(is_null($courseid)) $courseid = $COURSE->id;

        $user_info = $DB->get_records_sql(
            "SELECT ra.id AS id, u.firstname AS firstname, u.lastname AS lastname, ra.roleid AS roleid, ra.userid AS userid, con.instanceid AS instanceid, con.contextlevel AS contextlevel
            FROM {role_assignments} AS ra
            JOIN {context} AS con ON ra.contextid = con.id
            JOIN {user} AS u ON ra.userid = u.id
            WHERE con.contextlevel = 50 AND instanceid=:instanceid AND userid=:userid",
            [
                'userid' => $userid,
                'instanceid' => $courseid
            ]
        );

        return $user_info;
    }


    /**
     * Получение информации по всем пользователям
     * 
     * @param int $courseid id курса (если переданно null, то будет проверка по текущему)
     * 
     * @return array ассоциативный массив с информацией по пользователям
     */
    private function get_users_info_DB($courseid) {

        global $DB, $COURSE;

        if(is_null($courseid)) $courseid = $COURSE->id;

        $users_info = $DB->get_records_sql(
            "SELECT ra.id AS id, ra.userid AS userid, u.firstname AS firstname, u.lastname AS lastname, ra.roleid AS roleid, con.instanceid AS instanceid, con.contextlevel AS contextlevel
            FROM {role_assignments} AS ra
            JOIN {context} AS con ON ra.contextid = con.id
            JOIN {user} AS u ON ra.userid = u.id
            WHERE roleid = 5 AND con.contextlevel = 50 AND instanceid=:instanceid",
            [
                'instanceid' => $courseid,
            ]
        );

        return $users_info;
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