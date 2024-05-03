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
    
    // Инициализация
    function init() {
        $this->title = get_string('pluginname', 'block_g_statistics');
    }

    // Включить возможность добавления несокльких блоков
    public function instance_allow_multiple() {
        return true;
    }

    // Включить глобальную настройку плагина
    function has_config() {
        return true;
    }

    // Сокрытие шапки плагина
    public function hide_header() {
        return true;
    }

    // Определение возможных мест добавления блока
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
        global $OUTPUT, $CFG, $COURSE;

        if ($this->content !== NULL) {
            return $this->content;
        }

        if (empty($this->config)) {
            $this->config = new stdClass();
        }

        $rating = new rating();

        $statistics = $this->get_statistics();


        $show_rating_table = (get_config('block_g_statistics', 'showratingtable') == 0) ? false : true;
        if ($show_rating_table) {
            $usersInfo = $rating->get_rating();
        }

        $data_for_statistics = [
            // Статистика
            "show_statistics" => $statistics["show_statistics"],

            "show_current_balls" => $statistics["show_current_balls"],
            "current_balls" => $statistics["current_balls"],

            "show_mean_value" => $statistics["show_mean_value"],
            "mean_value" => $statistics["mean_value"],

            // Статистика перевод блока
            "blockstatisticstitle" => get_string('blockstatisticstitle', 'block_g_statistics'),
            "blockstatisticsballs" => get_string('blockstatisticsballs', 'block_g_statistics'),
            "blockstatisticsmaingrade" => get_string('blockstatisticsmaingrade', 'block_g_statistics'),

            

            // Таблица лидеров перевод блока
            "blockleaderboardtitle" => get_string('blockleaderboardtitle', 'block_g_statistics'),
            "blockleaderboardname" => get_string('blockleaderboardname', 'block_g_statistics'),
            "blockleaderboardballs" => get_string('blockleaderboardballs', 'block_g_statistics'),

            // Таблица лидеров
            "show_rating_table" => $show_rating_table,
            "users" => $usersInfo,
            "wwwroot" => $CFG->wwwroot,
            "courseid" => $COURSE->id,
        ];

        $this->content = new stdClass;
        $this->content->text = $OUTPUT->render_from_template("block_g_statistics/statistics", $data_for_statistics);
        $this->content->footer = ''; 

        return $this->content;
    }


    // Метод сбора данных статистики для отображения статистики
    private function get_statistics() {
        global $CFG;

        $statistics = new fetcher();

        $show_statistics = get_config('block_g_statistics', 'showstatistics') == 0 ? true : false;
        if($show_statistics) {

            $config_mean_value = $this->config->meanvalue;
            $show_mean_value = (get_config('block_g_statistics', 'showmeanvalue') == 0 || $config_mean_value == 1) ? false : true;
            
            $mean_value = [];
            if ($show_mean_value) {
                switch ($config_mean_value) {
                    case 2:
                        array_push($mean_value, [
                            "value" => $statistics->get_mean_value($config_mean_value),
                            "description" => get_string('selectcomplitetasks', 'block_g_statistics'),
                        ]);
                        break;
                    case 3:
                        array_push($mean_value, [
                            "value" => $statistics->get_mean_value($config_mean_value),
                            "description" => get_string('selectalltasks', 'block_g_statistics'),
                        ]);
                        break;
                    case 4:
                        array_push($mean_value, [
                            "value" => $statistics->get_mean_value(2),
                            "description" => get_string('selectcomplitetasks', 'block_g_statistics'),
                        ]);
                        array_push($mean_value, [
                            "value" => $statistics->get_mean_value(3),
                            "description" => get_string('selectalltasks', 'block_g_statistics'),
                        ]);
                        break;
                }
            }
            
            $config_current_balls = $this->config->currentballs;
            $show_current_balls = (get_config('block_g_statistics', 'showcurrentballs') == 0 || $config_current_balls == 1) ? false : true;

            $current_balls = [];
            if ($show_current_balls) {
                
                switch ($config_current_balls) {
                    case 2:
                        array_push($current_balls, [
                            "value" => $statistics->get_balls($config_current_balls),
                            "description" => get_string('selectcomplitetasks', 'block_g_statistics'),
                        ]);
                        break;
                    case 3:
                        array_push($current_balls, [
                            "value" => $statistics->get_balls($config_current_balls),
                            "description" => get_string('selectalltasks', 'block_g_statistics'),
                        ]);
                        break;
                    case 4:
                        array_push($current_balls, [
                            "value" => $statistics->get_balls(2),
                            "description" => get_string('selectcomplitetasks', 'block_g_statistics'),
                        ]);
                        array_push($current_balls, [
                            "value" => $statistics->get_balls(3),
                            "description" => get_string('selectalltasks', 'block_g_statistics'),
                        ]);
                        break;
                }
            }

            return ["show_statistics" => $show_statistics, 
                    "show_mean_value" => $show_mean_value, 
                    "mean_value" => $mean_value, 
                    "show_current_balls" => $show_current_balls, 
                    "current_balls" => $current_balls];

        } else {

            return ["show_statistics" => $show_statistics, 
                    "show_mean_value" => null, 
                    "mean_value" => null, 
                    "show_current_balls" => null, 
                    "current_balls" => null];
        }
    }
}


