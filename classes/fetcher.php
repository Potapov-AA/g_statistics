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
     * @param int $currentgroup The group (if any) to filter on
     * @param int $now Time now
     * @param int $timetoshowusers Number of seconds to show online users
     * @param context $context Context object used to generate the sql for users enrolled in a specific course
     * @param bool $sitelevel Whether to check online users at site level.
     * @param int $courseid The course id to check
     * @return int
     */
    function get_mean_value($choise) {
        global $DB, $USER;

        $sizegradesarray = count($DB->get_records_sql(
            "SELECT * FROM {grade_grades} WHERE userid = :userid",
            [
                'userid' => $USER->id
            ]
        ));
        if($sizegradesarray == 0) return -1;

        if($choise == 2) {
            return 100;
        } else if($choise == 3) {
            return 50;
        }
        return -1;
    }
}



// $infogrades = $DB->get_records_sql(
        //     "SELECT  gg.id AS id, gg.userid AS userid, gi.itemname AS itemname, gi.grademax AS grademax, gg.rawgrade AS rawgrade, gi.courseid AS courseid
        //     FROM {grade_items} AS gi
        //     JOIN {grade_grades} AS gg ON gi.id = gg.itemid
        //     JOIN {course} AS c ON gi.courseid = c.id 
        //     WHERE gi.gradetype != 0 AND gi.itemtype!= 'course' AND c.id = :courseid AND gg.userid = :userid",
        //     [
        //         'courseid' => $courseid,
        //         'userid' => $userid,
        //     ]
        // );
