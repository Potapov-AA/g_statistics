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
 * File containing rating class.
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

class rating {

    function get_user_rang($userId) {
        $rating = $this->get_rating();

        foreach($rating as $user) {

            if($user['id'] == $userId) return $user['rang'];
        }

        return -1;
    }
    
    function get_rating($min = -1, $max = -1, $moduleid = -1) {
        global $USER;

        $usersInfo = $this->get_user_info();
        $rawgradeUsersArray = $this->get_rawgrade_for_users();
        $activeUsersId = $this->get_active_users();

        $rating = [];
        foreach($usersInfo as $user) {
            $status = ($USER->id == $user->userid) ? true : false;

            if(in_array($user->userid, $activeUsersId)) {
                $ballsSum = 0;
                foreach($rawgradeUsersArray as $item) {
                    if($item->userid == $user->userid) {
                        if ($moduleid == -1) {
                            $ballsSum += $item->rawgrade;
                        } else if ($item->moduleid == $moduleid) {
                            $ballsSum += $item->rawgrade;
                        }
                    }
                }
                array_push($rating, [
                    "rang" => 0,
                    "id" => $user->userid,
                    "firstname" => $user->firstname, 
                    "lastname" => $user->lastname,
                    "balls" => $ballsSum,
                    "status" => $status,
                    "isuser" => true,
                ]);
            } else {
                array_push($rating, [
                    "rang" => '###',
                    "id" => $user->userid,
                    "firstname" => $user->firstname, 
                    "lastname" => $user->lastname,
                    "balls" => '###',
                    "status" => $status,
                    "isuser" => true,
                ]);
            }
        }

        usort($rating, array($this, 'rating_sort'));

        if($rating[0]['rang'] != '###') {
            $rating[0]['rang'] = 1;
            $currentRang = 1;
            $currentBalls = $rating[0]['balls'];

            for($i = 0; $i < count($rating); $i++) {
                if($rating[$i]['rang'] == '###') {
                    break;
                }

                if($rating[$i]['balls'] < $currentBalls) {
                    $currentRang++;
                    $rating[$i]['rang'] = $currentRang;
                    $currentBalls = $rating[$i]['balls'];
                } else {
                    $rating[$i]['rang'] = $currentRang;
                    $currentBalls = $rating[$i]['balls'];
                }
            }
        }

        $min = round($min);
        $max = round($max);

        if ($min <= 0 && $max <= 0) {
            return $rating;
        }

        if ($min > 0 && $max <= 0) $max = 1;

        if($min + $max >= count($rating)) return $rating;
        
        $recordsCount = count($rating);

        $splitRating = [];
        $currentUserAdded = false;

        foreach($rating as $user) {
            if ($max > 0) {
                $max--;
                $recordsCount--;

                if ($user['status']) $currentUserAdded = true;

                array_push($splitRating, $user);

                continue;
            } 

            if ($recordsCount == $min) {
                
                array_push($splitRating, [
                    "rang" => null,
                    "id" => null,
                    "firstname" => null, 
                    "lastname" => null,
                    "balls" => null,
                    "status" => null,
                    "isuser" => false,
                ]);
            }
            if ($recordsCount <= $min && $recordsCount > 0) {
                $recordsCount--;

                array_push($splitRating, $user);
                continue;
            }
            
            if ($currentUserAdded) {
                $recordsCount--;
                continue;
            } else {
                $recordsCount--;
                if ($user['status']) {
                    $currentUserAdded = true;

                    array_push($splitRating, [
                        "rang" => null,
                        "id" => null,
                        "firstname" => null, 
                        "lastname" => null,
                        "balls" => null,
                        "status" => null,
                        "isuser" => false,
                    ]);

                    array_push($splitRating, $user);
                }
                continue;
            }
        }
        

        return $splitRating;
    }

    private function get_user_info() {
        global $DB, $COURSE;

        $usersInfo = $DB->get_records_sql(
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

        return $usersInfo;
    }

    private function get_active_users() {
        global $DB, $COURSE;
        
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

        return $activeUsersId;
    }

    private function get_rawgrade_for_users() {
        global $DB, $COURSE;

        $rawgradeUsersArray = $DB->get_records_sql(
            "SELECT gg.id AS id, gg.userid AS userid, gg.rawgrade AS rawgrade, gi.courseid AS courseid, gi.gradetype AS gradetype, m.id AS moduleid
            FROM {grade_grades} AS gg 
            JOIN {grade_items} AS gi ON gg.itemid = gi.id 
            JOIN {modules} AS m ON m.name = gi.itemmodule
            WHERE gradetype != 0 AND itemname IS NOT NULL AND rawgrade IS NOT NULL AND courseid = :courseid",
            [
                'courseid' => $COURSE->id,
            ]
        );

        

        return $rawgradeUsersArray;
    }


    private function rating_sort($a, $b) {
        if ($a['balls'] == $b['balls']) {
            return 0;
        }
        
        if ($a['balls'] == '###' && is_numeric($b['balls'])) {
            return 1;
        }

        if ($b['balls'] == '###' && is_numeric($a['balls'])) {
            return -1;
        }

        return ($a['balls'] > $b['balls']) ? -1 : 1;
    }
}