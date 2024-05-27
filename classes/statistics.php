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
 * File containing statistics class.
 * 
 * Statistics class
 *
 * @package   block_g_statistics
 * @copyright 2024 Streje
 * @author    Alexander Potapov <san_sanih99@mail.ru>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_g_statistics;

use block_g_statistics\users;

defined('MOODLE_INTERNAL') || die();

class statistics {

    /**
     * Получение средней оценки по курсу
     * 
     * @param int $choise (2 - расчет идет относительно количества пройденных заданий; 3 - относительно общего количества заданий)
     * @param boolean $consider_inactive_user (true - учитывать неактивных пользователей на курсе, false - не учитывать)
     * 
     * @return int средняя оценка по курсу
     */
    function get_mean_value_for_course($choise, $consider_inactive_user = true, $courseid = null) {

        $users = new users();
        $users_info = $users->get_users_info($courseid);
        $active_users_array = $users->get_active_users($courseid);

        $active_users_id = [];
        foreach($active_users_array as $item) {
            array_push($active_users_id, $item->userid);
        }
        if(count($active_users_id) == 0) return -1;

        $user_count = count($users_info);

        $rawgrade_sum = 0;

        foreach ($users_info as $user) {
            $userid = $user->userid;

            if (in_array($userid, $active_users_id)) {
                $rawgrade_sum += $this->get_mean_value($choise, $userid, $courseid);
            } else {
                if (!$consider_inactive_user) $user_count--;
            }
        }

        return round($rawgrade_sum / $user_count);
    }


    /**
     * Получение средней оценки пользователя
     * 
     * @param int $choise (2 - расчет идет относительно количества пройденных заданий; 3 - относительно общего количества заданий)
     * @param int $userid id - пользователя, если не указано или -1, то расчет по текущему пользователю
     * 
     * @return int средняя оценка по курсу для пользователя, -1 - если пользователь не найден или передан некорректный $choise
     */
    function get_mean_value($choise, $userid = null, $courseid = null) {

        $grades_array = $this->get_grades_array($userid, $courseid);

        if(count($grades_array) == 0) return -1;

        $rawgrade_user_sum_normal = 0;
        $count_completed_tasks = 0;

        foreach($grades_array as $item) {
            $rawgrade_user_sum_normal += $item->rawgrade / ($item->rawgrademax / 100);
            $count_completed_tasks++;
        }

        
        switch($choise) {
            case 2: // Относительно количества пройденных заданий
                return round(($rawgrade_user_sum_normal / $count_completed_tasks));
            case 3: // Относительно общего количества заданий
                return round(($rawgrade_user_sum_normal / $this->get_count_tasks_for_course($userid, $courseid)));
            default: 
                return -1;
        }
    }
    

    /**
     * Получение общего количества баллов пользователя
     * 
     * @param int $choise (2 - расчет идет относительно количества пройденных заданий; 3 - относительно общего количества заданий)
     * @param int $userid id - пользователя, если не указано или -1, то расчет по текущему пользователю
     * 
     * @return int общее количество баллов для пользователя, -1 - если пользователь не найден или передан некорректный $choise
     */
    function get_balls($choise, $userid = null, $courseid = null) {

        $grades_array = $this->get_grades_array($userid, $courseid);

        if (count($grades_array) == 0) return -1;

        $rawgrade_user_sum = 0;
        $rawgrade_max_user_sum = 0;

        foreach ($grades_array as $item) {
            $rawgrade_user_sum += $item->rawgrade;
            $rawgrade_max_user_sum += $item->rawgrademax;
        }

        switch($choise) {
            case 2: // Относительно количества пройденных заданий
                return round($rawgrade_user_sum) . '/' . round($rawgrade_max_user_sum);
            case 3: // Относительно общего количества заданий
                return round($rawgrade_user_sum) . '/' . $this->get_grademax_for_course($courseid);
            default: 
                return -1;
        }
    }


    /**
     * Получение количество выполненных заданий пользователя
     * 
     * @param int $type id учитываемых типов модулей, если -1 то учитываются все типы оцениваемых заданий
     * @param int $userid id - пользователя, если не указано или -1, то расчет по текущему пользователю
     * 
     * @return string количество заданий в формате x/y, где x - количество выполненных заданий, y - общее количество заданий
     */
    function get_count_complited_tasks($type=-1, $userid=null, $courseid=null) {
        return $this->get_count_complited_modules_for_user($type, $userid, $courseid) . '/' . $this->get_count_complited_modules_for_course($type, $courseid);
    }


    /**
     * Получение массива оценок
     * 
     * @param int $userid id - пользователя, если -1, то расчет по текущему пользователю
     * 
     * @return array ассоциативный массив в качестве ключа {grade_grades.id}
     */
    private function get_grades_array($userid, $courseid) {

        global $DB, $USER, $COURSE;

        if(is_null($userid)) $userid = $USER->id;
        if(is_null($courseid)) $courseid = $COURSE->id;


        $grades_array = $DB->get_records_sql(
            "SELECT gg.id AS id, gi.courseid AS courseid, gg.userid AS userid, gi.itemname AS itemname, gi.gradetype AS gradetype, gg.rawgrade AS rawgrade, gg.rawgrademax AS rawgrademax
            FROM {grade_grades} AS gg 
            JOIN {grade_items} AS gi ON gg.itemid = gi.id
            WHERE gradetype != 0 AND itemname IS NOT NULL AND rawgrade IS NOT NULL AND  userid = :userid AND courseid = :courseid",
            [
                'userid' => $userid,
                'courseid' => $courseid
            ]
        );

        return $grades_array;
    }


    /**
     * Получение общего количества баллов за курс
     * 
     * @return int общее количество баллов за курс
     */
    private function get_grademax_for_course($courseid) {
        global $DB, $COURSE;

        if(is_null($courseid)) $courseid = $COURSE->id;

        $rewgrade_sum_for_course = $DB->get_records_sql(
            "SELECT id, grademax, gradetype 
            FROM {grade_items} 
            WHERE itemmodule IS NULL AND courseid = :courseid",
            [
                'courseid' => $courseid
            ]
        );

        foreach($rewgrade_sum_for_course as $item) {
            return round($item->grademax);
        }
    }


    /**
     * Получение общего количества заданий на курсе
     * 
     * @param int $userid id - пользователя
     * 
     * @return int общее количество заданий на курсе
     */
    private function get_count_tasks_for_course($userid, $courseid) {
        global $DB, $USER, $COURSE;
        
        if(is_null($userid)) $userid = $USER->id;
        if(is_null($courseid)) $courseid = $COURSE->id;

        $grades_array = $DB->get_records_sql(
            "SELECT gg.id AS id
            FROM {grade_grades} AS gg 
            JOIN {grade_items} AS gi ON gg.itemid = gi.id
            WHERE gradetype != 0 AND itemname IS NOT NULL AND userid = :userid AND courseid = :courseid",
            [
                'userid' => $userid,
                'courseid' => $courseid
            ]
        );

        return count($grades_array);
    }


    /**
     * Получение количества заданий, которые можно пройти
     * 
     * @param int $type id учитываемых типов модулей, если -1 то учитываются все типы оцениваемых заданий
     * 
     * @return int количество заданий, которые можно пройти
     */
    private function get_count_complited_modules_for_course($type, $courseid) {
        global $DB, $COURSE;

        if(is_null($courseid)) $courseid = $COURSE->id;

        if ($type == -1) {
            $all_modules_count = count($DB->get_records_sql(
                                    "SELECT id, course, module, completion
                                    FROM {course_modules}
                                    WHERE completion != 0 AND course = :courseid",
                                    [
                                        'courseid' => $courseid
                                    ]
                                ));

            return $all_modules_count;
        }

        $modules_count = count($DB->get_records_sql(
                            "SELECT id, course, module, completion
                            FROM {course_modules}
                            WHERE completion != 0 AND course = :courseid AND module = :type",
                            [
                                'courseid' => $courseid,
                                'type' => $type
                            ]
                        ));
        
        return $modules_count;
    }


    /**
     * Получение количества пройденных заданий пользователем
     * 
     * @param int $type id учитываемых типов модулей, если -1 то учитываются все типы оцениваемых заданий
     * @param int $userid id - пользователя, если -1, то расчет по текущему пользователю
     * 
     * @return int количества пройденных заданий пользователем
     */
    private function get_count_complited_modules_for_user($type, $userid, $courseid) {
        global $DB, $COURSE, $USER;

        if(is_null($userid)) $userid = $USER->id;
        if(is_null($courseid)) $courseid = $COURSE->id;

        if($type == -1) {
            $all_modules_complited_count = count($DB->get_records_sql(
                                            "SELECT cmc.id AS id, cmc.coursemoduleid AS coursemoduleid, cmc.userid AS userid, cm.module AS module, cm.course AS course
                                            FROM {course_modules_completion} AS cmc
                                            JOIN {course_modules} AS cm ON cmc.coursemoduleid = cm.id
                                            WHERE course = :courseid AND userid = :userid",
                                            [
                                                'courseid' => $courseid,
                                                'userid' => $userid,
                                            ]
                                        ));
            
            return $all_modules_complited_count;
        }

        $modules_complited_count = count($DB->get_records_sql(
                                    "SELECT cmc.id AS id, cmc.coursemoduleid AS coursemoduleid, cmc.userid AS userid, cm.module AS module, cm.course AS course
                                    FROM {course_modules_completion} AS cmc
                                    JOIN {course_modules} AS cm ON cmc.coursemoduleid = cm.id
                                    WHERE course = :courseid AND userid = :userid AND module = :type",
                                    [
                                        'courseid' => $courseid,
                                        'userid' => $userid,
                                        'type' => $type,
                                    ]
                                ));

        return $modules_complited_count;
    }
}