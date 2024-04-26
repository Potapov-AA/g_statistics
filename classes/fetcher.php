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
 * File containing onlineusers class.
 *
 * @package   block_g_statistics
 * @copyright 2024 Streje (san_sanih99@mail.ru)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_g_statistics;

defined('MOODLE_INTERNAL') || die();

class fetcher {
    /**
     * Getting the average score of the current user
     *
     * @param int $choise Selecting display method
     * @return int
     */
    function get_mean_value($choise) {
        $gradesarray = $this->get_grades_array();

        if(count($gradesarray) == 0) return -1;

        $rawgradeUserSumNormal = 0;
        $countCompletedTasks = 0;

        foreach($gradesarray as $item) {
            if(!is_null($item->rawgrade)) {
                $rawgradeUserSumNormal += $item->rawgrade / ($item->rawgrademax / 100);
                $countCompletedTasks++;
            }
        }
        
        switch($choise) {
            case 2: // Относительно количества пройденных заданий
                return round(($rawgradeUserSumNormal / $countCompletedTasks));
            case 3: // Относительно общего количества заданий
                return round(($rawgradeUserSumNormal / count($gradesarray)));
            default: 
                return -1;
        }
    }
    

    /**
     * Getting the total number of points
     *
     * @param int $choise Selecting display method
     * @return int
     */
    function get_balls($choise) {
        $gradesarray = $this->get_grades_array();

        if(count($gradesarray) == 0) return '-//-';

        $rawgradeUserSum = 0;
        $rawgradeMaxUserSum = 0;

        foreach($gradesarray as $item) {
            if(!is_null($item->rawgrade)) {
                $rawgradeUserSum += $item->rawgrade;
                $rawgradeMaxUserSum += $item->rawgrademax;
            }
        }

        switch($choise) {
            case 2: // Относительно количества пройденных заданий
                return round($rawgradeUserSum) . '/' . round($rawgradeMaxUserSum);
            case 3: // Относительно общего количества заданий
                return round($rawgradeUserSum) . '/' . $this->get_rewgrade_for_course();
            default: 
                return '-//-';
        }
    }


    /**
     * Getting an array of ratings
     *
     * @return int
     */
    private function get_grades_array() {
        global $DB, $USER, $COURSE;

        $gradesarray = $DB->get_records_sql(
            "SELECT gg.id AS id, gg.itemid AS itemid, gg.userid AS userid, gg.rawgrade AS rawgrade, gg.rawgrademax AS rawgrademax, gi.courseid AS courseid, gi.itemname AS itemname
            FROM {grade_grades} AS gg 
            JOIN {grade_items} AS gi ON gg.itemid = gi.id
            WHERE itemname IS NOT NULL AND userid = :userid AND courseid = :courseid",
            [
                'userid' => $USER->id,
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
    private function get_rewgrade_for_course() {
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
}