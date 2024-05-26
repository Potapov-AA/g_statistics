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

use block_g_statistics\users;

defined('MOODLE_INTERNAL') || die();

class rating {

    /**
     * Получение места в рейтинге пользователя
     * 
     * @param int $userid id пользователя
     * 
     * @return int  место в рейтинге пользователя, если пользователя нет в рейтинге возвращается -1
     */
    function get_user_rang($userid) {
        $rating = $this->get_rating();

        foreach($rating as $user) {

            if($user['id'] == $userid) return $user['rang'];
        }

        return -1;
    }
    

    /**
     * Формирование и получение таблицы лидеров 
     * 
     * @param int $min количество записей снизу рейтинга
     * @param int $max количество записей сверху рейтинга,
     * @param int $moduleid id модуля по которому формируется рейтинг
     * 
     * @return array  таблица лидеров
     */
    function get_rating($min = -1, $max = -1, $moduleid = -1) {
        global $USER;

        $users = new users();

        $users_info = $users->get_users_info();
        $rawgrade_users_array = $this->get_rawgrade_for_users();
        $active_users_array = $users->get_active_users();

        $active_usersid = [];
        foreach($active_users_array as $item) {
            array_push($active_usersid, $item->userid);
        }

        $rating = [];
        foreach($users_info as $user) {
            $status = ($USER->id == $user->userid) ? true : false;

            if(in_array($user->userid, $active_usersid)) {
                $balls_sum = 0;
                foreach($rawgrade_users_array as $item) {
                    if($item->userid == $user->userid) {
                        if ($moduleid == -1) {
                            $balls_sum += $item->rawgrade;
                        } else if ($item->moduleid == $moduleid) {
                            $balls_sum += $item->rawgrade;
                        }
                    }
                }
                array_push($rating, [
                    "rang" => 0,
                    "id" => $user->userid,
                    "firstname" => $user->firstname, 
                    "lastname" => $user->lastname,
                    "balls" => $balls_sum,
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
            $current_rang = 1;
            $current_balls = $rating[0]['balls'];

            for($i = 0; $i < count($rating); $i++) {
                if($rating[$i]['rang'] == '###') {
                    break;
                }

                if($rating[$i]['balls'] < $current_balls) {
                    $current_rang++;
                    $rating[$i]['rang'] = $current_rang;
                    $current_balls = $rating[$i]['balls'];
                } else {
                    $rating[$i]['rang'] = $current_rang;
                    $current_balls = $rating[$i]['balls'];
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
        
        $records_count = count($rating);

        $split_rating = [];
        $current_user_added = false;

        foreach($rating as $user) {
            if ($max > 0) {
                $max--;
                $records_count--;

                if ($user['status']) $current_user_added = true;

                array_push($split_rating, $user);

                continue;
            } 

            if ($records_count == $min) {
                
                array_push($split_rating, [
                    "rang" => null,
                    "id" => null,
                    "firstname" => null, 
                    "lastname" => null,
                    "balls" => null,
                    "status" => null,
                    "isuser" => false,
                ]);
            }
            if ($records_count <= $min && $records_count > 0) {
                $records_count--;

                array_push($split_rating, $user);
                continue;
            }
            
            if ($current_user_added) {
                $records_count--;
                continue;
            } else {
                $records_count--;
                if ($user['status']) {
                    $current_user_added = true;

                    array_push($split_rating, [
                        "rang" => null,
                        "id" => null,
                        "firstname" => null, 
                        "lastname" => null,
                        "balls" => null,
                        "status" => null,
                        "isuser" => false,
                    ]);

                    array_push($split_rating, $user);
                }
                continue;
            }
        }
        
        return $split_rating;
    }


    /**
     * Получение баллов пользователей
     * 
     * @return array баллы пользователей
     */
    private function get_rawgrade_for_users() {
        global $DB, $COURSE;

        $rawgrade_users_array = $DB->get_records_sql(
            "SELECT gg.id AS id, gg.userid AS userid, gg.rawgrade AS rawgrade, gi.courseid AS courseid, gi.gradetype AS gradetype, m.id AS moduleid
            FROM {grade_grades} AS gg 
            JOIN {grade_items} AS gi ON gg.itemid = gi.id 
            JOIN {modules} AS m ON m.name = gi.itemmodule
            WHERE gradetype != 0 AND itemname IS NOT NULL AND rawgrade IS NOT NULL AND courseid = :courseid",
            [
                'courseid' => $COURSE->id,
            ]
        );

        return $rawgrade_users_array;
    }


    /**
     * Функция сортировки для таблицы лидеров
     */
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