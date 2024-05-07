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

defined('MOODLE_INTERNAL') || die();

class statistics {

    function get_mean_value_for_all_users($choise, $consideruserunactive = true) {
        global $DB, $COURSE;

        // Получение списка пользователей на курсе
        $userrole = $DB->get_records_sql(
            "SELECT ra.id AS id, ra.roleid AS roleid, ra.userid AS userid, con.instanceid AS instanceid, con.contextlevel AS contextlevel
            FROM {role_assignments} AS ra
            JOIN {context} AS con ON ra.contextid = con.id
            JOIN {course} AS c ON con.instanceid = c.id
            WHERE con.contextlevel = 50 AND roleid = 5 AND instanceid=:instanceid ",
            [
                'instanceid' => $COURSE->id
            ]
        );

        // Получение id активных пользователей
        $activeUsersArray = $DB->get_records_sql(
            "SELECT  DISTINCT gg.userid AS userid, gi.courseid AS courseid, gi.gradetype AS gradetype
            FROM {grade_grades} AS gg 
            JOIN {grade_items} AS gi ON gg.itemid = gi.id 
            WHERE gradetype != 0 AND itemname IS NOT NULL AND gg.rawgrade IS NOT NULL AND courseid = :courseid",
            [
                'courseid' => $COURSE->id,
            ]
        );

        $activeUsersId = [];
        foreach($activeUsersArray as $item) {
            array_push($activeUsersId, $item->userid);
        }
        if(count($activeUsersId) == 0) return '-//-';

        $usercount = count($userrole);

        $rawgradesum = 0;

        foreach ($userrole as $user) {
            $userid = $user->userid;

            if (in_array($userid, $activeUsersId)) {
                $rawgradesum += $this->get_mean_value($choise, $userid);
            } else {
                if (!$consideruserunactive) $usercount--;
            }
        }

        return round($rawgradesum / $usercount);
    }

    /**
     * Getting the average score of the current user
     *
     * @param int $choise Selecting display method
     * @return int
     */
    function get_mean_value($choise, $userid = -1) {
        $gradesarray = $this->get_grades_array($userid);

        if(count($gradesarray) == 0) return '-//-';

        $rawgradeUserSumNormal = 0;
        $countCompletedTasks = 0;

        foreach($gradesarray as $item) {
            $rawgradeUserSumNormal += $item->rawgrade / ($item->rawgrademax / 100);
            $countCompletedTasks++;
        }

        
        switch($choise) {
            case 2: // Относительно количества пройденных заданий
                return round(($rawgradeUserSumNormal / $countCompletedTasks));
            case 3: // Относительно общего количества заданий
                return round(($rawgradeUserSumNormal / $this->get_count_tasks_for_course($userid)));
            default: 
                return '-//-';
        }
    }
    

    /**
     * Getting the total number of points
     *
     * @param int $choise Selecting display method
     * @return int
     */
    function get_balls($choise, $userid = -1) {
        $gradesarray = $this->get_grades_array($userid);

        if (count($gradesarray) == 0) return '-//-';

        $rawgradeUserSum = 0;
        $rawgradeMaxUserSum = 0;

        foreach ($gradesarray as $item) {
            $rawgradeUserSum += $item->rawgrade;
            $rawgradeMaxUserSum += $item->rawgrademax;
        }

        switch($choise) {
            case 2: // Относительно количества пройденных заданий
                return round($rawgradeUserSum) . '/' . round($rawgradeMaxUserSum);
            case 3: // Относительно общего количества заданий
                return round($rawgradeUserSum) . '/' . $this->get_grademax_for_course();
            default: 
                return '-//-';
        }
    }


    function get_count_complited_tasks($type=-1, $userid=-1) {
        return $this->get_count_complited_modules_for_user($type, $userid) . '/' . $this->get_count_complited_modules_for_course($type);
    }



    /**
     * Getting an array of ratings
     *
     * @return int
     */
    private function get_grades_array($userid) {
        global $DB, $USER, $COURSE;

        if($userid == -1) {
            $userid = $USER->id;
        } 

        $gradesarray = $DB->get_records_sql(
            "SELECT gg.id AS id, gi.courseid AS courseid, gg.userid AS userid, gi.itemname AS itemname, gi.gradetype AS gradetype, gg.rawgrade AS rawgrade, gg.rawgrademax AS rawgrademax
            FROM {grade_grades} AS gg 
            JOIN {grade_items} AS gi ON gg.itemid = gi.id
            WHERE gradetype != 0 AND itemname IS NOT NULL AND rawgrade IS NOT NULL AND  userid = :userid AND courseid = :courseid",
            [
                'userid' => $userid,
                'courseid' => $COURSE->id
            ]
        );

        return $gradesarray;
    }


    /**
     * Getting the total number of points for course
     *
     * @return int
     */
    private function get_grademax_for_course() {
        global $DB, $COURSE;

        $rewgradeSumForCourse = array_key_first($DB->get_records_sql(
            "SELECT grademax 
            FROM {grade_items} 
            WHERE itemnumber IS NULL AND courseid = :courseid",
            [
                'courseid' => $COURSE->id
            ]
        ));

        return round($rewgradeSumForCourse);
    }


    private function get_count_tasks_for_course($userid) {
        global $DB, $USER, $COURSE;
        
        if($userid == -1) {
            $userid = $USER->id;
        } 

        $gradesarray = $DB->get_records_sql(
            "SELECT gg.id AS id
            FROM {grade_grades} AS gg 
            JOIN {grade_items} AS gi ON gg.itemid = gi.id
            WHERE gradetype != 0 AND itemname IS NOT NULL AND  userid = :userid AND courseid = :courseid",
            [
                'userid' => $userid,
                'courseid' => $COURSE->id
            ]
        );

        return count($gradesarray);
    }


    private function get_count_complited_modules_for_course($type) {
        global $DB, $COURSE;

        if ($type == -1) {
            $allModulesCount = count($DB->get_records_sql(
                                    "SELECT id, course, module, completion
                                    FROM {course_modules}
                                    WHERE completion != 0 AND course = :courseid",
                                    [
                                        'courseid' => $COURSE->id
                                    ]
                                ));

            return $allModulesCount;
        }

        $modulesCount = count($DB->get_records_sql(
                            "SELECT id, course, module, completion
                            FROM {course_modules}
                            WHERE completion != 0 AND course = :courseid AND module = :type",
                            [
                                'courseid' => $COURSE->id,
                                'type' => $type
                            ]
                        ));
        
        return $modulesCount;
    }


    private function get_count_complited_modules_for_user($type, $userid) {
        global $DB, $COURSE, $USER;

        if ($userid == -1) {
            $userid = $USER->id;
        }

        if($type == -1) {
            $allModulesComplitedCount = count($DB->get_records_sql(
                                            "SELECT cmc.id AS id, cmc.coursemoduleid AS coursemoduleid, cmc.userid AS userid, cm.module AS module, cm.course AS course
                                            FROM {course_modules_completion} AS cmc
                                            JOIN {course_modules} AS cm ON cmc.coursemoduleid = cm.id
                                            WHERE course = :courseid AND userid = :userid",
                                            [
                                                'courseid' => $COURSE->id,
                                                'userid' => $userid,
                                            ]
                                        ));
            
            return $allModulesComplitedCount;
        }

        $modulesComplitedCount = count($DB->get_records_sql(
                                    "SELECT cmc.id AS id, cmc.coursemoduleid AS coursemoduleid, cmc.userid AS userid, cm.module AS module, cm.course AS course
                                    FROM {course_modules_completion} AS cmc
                                    JOIN {course_modules} AS cm ON cmc.coursemoduleid = cm.id
                                    WHERE course = :courseid AND userid = :userid AND module = :type",
                                    [
                                        'courseid' => $COURSE->id,
                                        'userid' => $userid,
                                        'type' => $type,
                                    ]
                                ));

        return $modulesComplitedCount;
    }
}