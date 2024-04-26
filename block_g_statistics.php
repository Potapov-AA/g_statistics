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

use block_g_statistics\fetcher;
use block_g_statistics\rating;

/**
 * Form for editing HTML block instances.
 *
 * @package   block_g_statistics
 * @copyright 2024 Streje (san_sanih99@mail.ru)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_g_statistics extends block_base {
    function init() {
        $this->title = get_string('pluginname', 'block_g_statistics');
    }

    function has_config() {
        return true;
    }

    public function hide_header() {
        return true;
    }

    public function applicable_formats() {
        return [
            'admin' => false,
            'site-index' => false,
            'course-view' => true,
            'mod' => false,
            'my' => false
        ];
    }

    function get_content() {
        global $DB, $OUTPUT, $CFG;

        if ($this->content !== NULL) {
            return $this->content;
        }

        if (empty($this->config)) {
            $this->config = new stdClass();
        }

        $statistics = new fetcher();
        $rating = new rating();

        $config_mean_value = $this->config->meanvalue;
        $show_mean_value = (get_config('block_g_statistics', 'showmeanvalue') == 0 || $config_mean_value == 1) ? false : true;
        if ($show_mean_value) {
            $result = $statistics->get_mean_value($config_mean_value);
            if($result != -1) {
                $mean_value = $result . '/100';
            } else {
                $mean_value = "-//-";
            }
        } else {
            $mean_value = "-//-";
        }
        
        $config_current_balls = $this->config->currentballs;
        $show_current_balls = (get_config('block_g_statistics', 'showcurrentballs') == 0 || $config_current_balls == 1) ? false : true;
        if ($show_current_balls) {
            $current_balls = $statistics->get_balls($config_current_balls);
        }


        $show_rating_table = (get_config('block_g_statistics', 'showratingtable') == 0) ? false : true;
        if ($show_rating_table) {
            $usersInfo = $rating->get_rating();
        }

        $data_for_statistics = [
            // Статистика перевод блока
            "blockstatisticstitle" => get_string('blockstatisticstitle', 'block_g_statistics'),
            "blockstatisticsballs" => get_string('blockstatisticsballs', 'block_g_statistics'),
            "blockstatisticsmaingrade" => get_string('blockstatisticsmaingrade', 'block_g_statistics'),

            // Статистика
            "show_current_balls" => $show_current_balls,
            "current_balls" => $current_balls,

            "show_mean_value" => $show_mean_value,
            "mean_value" => $mean_value,

            // Таблица лидеров перевод блока
            "blockleaderboardtitle" => get_string('blockleaderboardtitle', 'block_g_statistics'),
            "blockleaderboardname" => get_string('blockleaderboardname', 'block_g_statistics'),
            "blockleaderboardballs" => get_string('blockleaderboardballs', 'block_g_statistics'),

            // Таблица лидеров
            "show_rating_table" => $show_rating_table,
            "users" => $usersInfo,
        ];


        $this->content = new stdClass;
        $this->content->text = $OUTPUT->render_from_template("block_g_statistics/statistics", $data_for_statistics);
        $this->content->footer = ''; 

        return $this->content;
    }
}


