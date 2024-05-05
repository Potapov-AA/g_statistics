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

use block_g_statistics\statistics;
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

        $user_roleid = $this->get_user_roleid();
        if($user_roleid == 5) {
            $statistics = $this->get_statistics_for_user();
        } else if ($user_roleid == 3 || $user_roleid == 4) {
            $statistics = $this->get_statistics_for_admin();
        }

        
            


        $show_rating_table = (get_config('block_g_statistics', 'showratingtable') == 0) ? false : true;
        if ($show_rating_table) {
            $usersInfo = $rating->get_rating(); 
        }

        $translate_data = [
            // Статистика перевод блока
            "blockstatisticstitle" => get_string('blockstatisticstitle', 'block_g_statistics'),
            "blockstatisticsballs" => get_string('blockstatisticsballs', 'block_g_statistics'),
            "blockstatisticsmaingrade" => get_string('blockstatisticsmaingrade', 'block_g_statistics'),
            "blockstatisticscounttasks" => get_string('blockstatisticscounttasks', 'block_g_statistics'),

            "blockstatisticsmaingradeadmin" => get_string('blockstatisticsmaingradeadmin', 'block_g_statistics'),

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

        $data_for_statistics = array_merge($statistics, $translate_data);

        $this->content = new stdClass;
        $this->content->text = $OUTPUT->render_from_template("block_g_statistics/statistics", $data_for_statistics);
        $this->content->footer = ''; 

        return $this->content;
    }


    private function get_statistics_for_admin() {

        $statistics = new statistics();

        $show_statistics = get_config('block_g_statistics', 'showstatistics') == 0 ? true : false;
        if($show_statistics) {

            $config_mean_value_admin = $this->config->meanvalueadmin;
            $show_mean_value_admin = (get_config('block_g_statistics', 'showmeanvalue') == 0 || $config_mean_value_admin == 1) ? false : true;

            $mean_value_admin = [];
            if ($show_mean_value_admin) {

                $takeinactiveusers = $this->config->yesnounactiveusers == 1 ? true : false;

                switch ($config_mean_value_admin) {
                    case 2:
                        if ($takeinactiveusers) {
                            array_push($mean_value_admin, [
                                "value" => $statistics->get_mean_value_for_all_users(2, true) . '/100',
                                "description" => "Относительно количества пройденных заданий c учетом неактивных пользователей",
                            ]);
                        } else {
                            array_push($mean_value_admin, [
                                "value" => $statistics->get_mean_value_for_all_users(2, false) . '/100',
                                "description" => "Относительно количества пройденных заданий без учета неактивных пользователей"
                            ]);
                        }
                        break;
                    case 3:
                        if ($takeinactiveusers) {
                            array_push($mean_value_admin, [
                                "value" => $statistics->get_mean_value_for_all_users(3, true) . '/100',
                                "description" => "Относительно общего количества заданий с учетом неактивных пользователей"
                            ]);
                        } else {
                            array_push($mean_value_admin, [
                                "value" => $statistics->get_mean_value_for_all_users(3, false) . '/100',
                                "description" => "Относительно общего количества заданий без учета неактивных пользователей"
                            ]);
                        }
                        break;
                    case 4:
                        if ($takeinactiveusers) {
                            array_push($mean_value_admin, [
                                "value" => $statistics->get_mean_value_for_all_users(2, true) . '/100',
                                "description" => "Относительно количества пройденных заданий c учетом неактивных пользователей"
                            ]);
                            array_push($mean_value_admin, [
                                "value" => $statistics->get_mean_value_for_all_users(3, true) . '/100',
                                "description" => "Относительно общего количества заданий с учетом неактивных пользователей"
                            ]);
                        } else {
                            array_push($mean_value_admin, [
                                "value" => $statistics->get_mean_value_for_all_users(2, false) . '/100',
                                "description" => "Относительно количества пройденных заданий без учета неактивных пользователей",
                            ]);
                            array_push($mean_value_admin, [
                                "value" => $statistics->get_mean_value_for_all_users(3, false) . '/100',
                                "description" => "Относительно общего количества заданий без учета неактивных пользователей"
                            ]);
                        }
                        break;
                }
            }
        }

        return [
            "show_statistics" => $show_statistics, 
            "show_mean_value_admin" => $show_mean_value_admin,
            "mean_value_admin" => $mean_value_admin
        ];
    }


    // Метод сбора данных статистики для отображения статистики для пользователя
    private function get_statistics_for_user() {
        global $CFG;

        $statistics = new statistics();

        $show_statistics = get_config('block_g_statistics', 'showstatistics') == 0 ? true : false;
        if($show_statistics) {

            $config_mean_value = $this->config->meanvalue;
            $show_mean_value = (get_config('block_g_statistics', 'showmeanvalue') == 0 || $config_mean_value == 1) ? false : true;
            
            $mean_value = [];
            if ($show_mean_value) {
                switch ($config_mean_value) {
                    case 2:
                        array_push($mean_value, [
                            "value" => $statistics->get_mean_value($config_mean_value) . '/100',
                            "description" => get_string('selectcomplitetasks', 'block_g_statistics'),
                        ]);
                        break;
                    case 3:
                        array_push($mean_value, [
                            "value" => $statistics->get_mean_value($config_mean_value) . '/100',
                            "description" => get_string('selectalltasks', 'block_g_statistics'),
                        ]);
                        break;
                    case 4:
                        array_push($mean_value, [
                            "value" => $statistics->get_mean_value(2) . '/100',
                            "description" => get_string('selectcomplitetasks', 'block_g_statistics'),
                        ]);
                        array_push($mean_value, [
                            "value" => $statistics->get_mean_value(3) . '/100',
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

            $config_task_count = $this->config->taskcount;
            $show_task_count = (get_config('block_g_statistics', 'showtaskcountcomlpited') == 0 || $config_task_count == 1) ? false : true;

            $task_count = [];
            if ($show_task_count) {

                $tasks_type = [
                    -1 => 'allelements',
                    1 => 'assign',
                    14 => 'lesson',
                    16 => 'page',
                    17 => 'quiz',
                ];

                switch ($config_task_count) {
                    case 2:
                        array_push($task_count, [
                            "description" => get_string('allelements', 'block_g_statistics'),
                            "value" => $statistics->get_count_complited_tasks(),
                        ]);
                        break;

                    case 3: 
                        foreach ($tasks_type as $key => $value) {

                            array_push($task_count, [
                                "description" => get_string($value, 'block_g_statistics'),
                                "value" => $statistics->get_count_complited_tasks($key),
                            ]);
                        }
                        break;
                    case 4:

                        if ($this->config->allelements != 0) {

                            array_push($task_count, [
                                "description" => get_string('allelements', 'block_g_statistics'),
                                "value" => $statistics->get_count_complited_tasks(),
                            ]);
                        }

                        if ($this->config->assign != 0) {

                            array_push($task_count, [
                                "description" => get_string('assign', 'block_g_statistics'),
                                "value" => $statistics->get_count_complited_tasks($this->config->assign),
                            ]);
                        }

                        if ($this->config->lesson != 0) {

                            array_push($task_count, [
                                "description" => get_string('lesson', 'block_g_statistics'),
                                "value" => $statistics->get_count_complited_tasks($this->config->lesson),
                            ]);
                        }

                        if ($this->config->page != 0) {

                            array_push($task_count, [
                                "description" => get_string('page', 'block_g_statistics'),
                                "value" => $statistics->get_count_complited_tasks($this->config->page),
                            ]);
                        }

                        if ($this->config->quiz != 0) {

                            array_push($task_count, [
                                "description" => get_string('quiz', 'block_g_statistics'),
                                "value" => $statistics->get_count_complited_tasks($this->config->quiz),
                            ]);
                        }

                        break;
                }
            }

            return ["show_statistics" => $show_statistics, 
                    "show_mean_value" => $show_mean_value, 
                    "mean_value" => $mean_value, 
                    "show_current_balls" => $show_current_balls, 
                    "current_balls" => $current_balls,
                    "show_task_count" => $show_task_count,
                    "task_count" => $task_count,
                ];

        } else {

            return ["show_statistics" => $show_statistics, 
                    "show_mean_value" => null, 
                    "mean_value" => null, 
                    "show_current_balls" => null, 
                    "current_balls" => null,
                    "show_task_count" => null,
                    "task_count" => null,
                ];
        }
    }

    // Метод получения 
    private function get_user_roleid() {
        global $DB, $COURSE, $USER;

        $userrole = $DB->get_records_sql(
            "SELECT ra.id AS id, ra.roleid AS roleid, ra.userid AS userid, con.instanceid AS instanceid, con.contextlevel AS contextlevel
            FROM {role_assignments} AS ra
            JOIN {context} AS con ON ra.contextid = con.id
            JOIN {course} AS c ON con.instanceid = c.id
            WHERE con.contextlevel = 50 AND instanceid=:instanceid AND userid=:userid",
            [
                'userid' => $USER->id,
                'instanceid' => $COURSE->id
            ]
        );

        foreach ($userrole as $item) {
            return $item->roleid;
        } 
    }
}


