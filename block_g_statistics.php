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
 * Block G-statistics.
 *
 * @package   block_g_statistics
 * @copyright 2024 Streje
 * @author    Alexander Potapov <san_sanih99@mail.ru>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use block_g_statistics\statistics;
use block_g_statistics\rating;
use block_g_statistics\users;

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
        $users = new users();

        $user_roleid = $users->get_user_roleid();
        if($user_roleid == 5) {
            $statistics = $this->get_statistics_for_user();

            $show_leaderboard_for_user = $this->config->show_leaderboard_for_user;
            $show_rating_table = (get_config('block_g_statistics', 'settings_show_leaderboard') == 0 || $show_leaderboard_for_user == 0) ? false : true;
            if ($show_rating_table) {
                $max_bot_user = $this->config->max_bot_user;
                $max_top_user = $this->config->max_top_user;

                $module_type = $this->config->rang_type;

                $users_info = $rating->get_rating($max_bot_user, $max_top_user, $module_type); 
            }

        } else if ($user_roleid == 3 || $user_roleid == 4) {
            $statistics = $this->get_statistics_for_admin();

            $show_leaderboard_for_admin = $this->config->show_leaderboard_for_admin;
            $show_rating_table = (get_config('block_g_statistics', 'settings_show_leaderboard') == 0 || $show_leaderboard_for_admin == 0) ? false : true;
            if ($show_rating_table) {
                $max_bot_admin = $this->config->max_bot_admin;
                $max_top_admin = $this->config->max_top_admin;

                $module_type = $this->config->rang_type;

                $users_info = $rating->get_rating($max_bot_admin, $max_top_admin, $module_type); 
            }

        }

        $translate_data = [
            // Статистика перевод блока
            "block_statistics_title" => get_string('block_statistics_title', 'block_g_statistics'),
            "block_statistics_balls" => get_string('block_statistics_balls', 'block_g_statistics'),
            "block_statistics_mean_grade" => get_string('block_statistics_mean_grade', 'block_g_statistics'),
            "block_statistics_count_complited_tasks" => get_string('block_statistics_count_complited_tasks', 'block_g_statistics'),

            "block_statistics_mean_grade_for_course" => get_string('block_statistics_mean_grade_for_course', 'block_g_statistics'),

            // Таблица лидеров перевод блока
            "block_leaderboard_title" => get_string('block_leaderboard_title', 'block_g_statistics'),
            "block_leaderboard_username" => get_string('block_leaderboard_username', 'block_g_statistics'),
            "block_leaderboard_balls" => get_string('block_leaderboard_balls', 'block_g_statistics'),
            "block_statistics_title_for_user" => get_string('block_statistics_title_for_user', 'block_g_statistics'),
            "block_statirang_for_admin" => get_string('block_statirang_for_admin', 'block_g_statistics'),

            // Таблица лидеров
            "show_rating_table" => $show_rating_table,
            "users" => $users_info,
            "wwwroot" => $CFG->wwwroot,
            "courseid" => $COURSE->id,
        ];

        $data_for_statistics = array_merge($statistics, $translate_data);

        $this->content = new stdClass;
        $this->content->text = $OUTPUT->render_from_template("block_g_statistics/statistics", $data_for_statistics);
        $this->content->footer = ''; 

        return $this->content;
    }


    /**
     * Метод получения данных для отображения статистики для админа/учителя курса
     *
     * @return array - массив статистики
     */
    private function get_statistics_for_admin() {

        $statistics = new statistics();
        $users = new users();

        $result = [];

        $show_statistics = get_config('block_g_statistics', 'settings_show_statistics') == 0 ? true : false;
        $result["show_statistics"] = $show_statistics;
        if($show_statistics) {

            // Получение средней оценки по курсу
            $config_mean_value_for_course = $this->config->mean_value_for_course;
            $show_mean_value_for_course = (get_config('block_g_statistics', 'settings_show_mean_grade_for_course') == 0 || $config_mean_value_for_course == 1) ? false : true;
            $result["show_mean_value_for_course"] = $show_mean_value_for_course;
            $mean_value_for_course_array = [];
            if ($show_mean_value_for_course) {

                $takeinactiveusers = $this->config->yes_no_unactive_users == 1 ? true : false;

                switch ($config_mean_value_for_course) {
                    case 2:
                        if ($takeinactiveusers) {
                            array_push($mean_value_for_course_array, [
                                "value" => $statistics->get_mean_value_for_course(2, true) . '/100',
                                "description" => get_string('description_count_tasks_with_inactive_users', 'block_g_statistics'),
                            ]);
                        } else {
                            array_push($mean_value_for_course_array, [
                                "value" => $statistics->get_mean_value_for_course(2, false) . '/100',
                                "description" => get_string('description_count_tasks_without_inactive_users', 'block_g_statistics')
                            ]);
                        }
                        break;
                    case 3:
                        if ($takeinactiveusers) {
                            array_push($mean_value_for_course_array, [
                                "value" => $statistics->get_mean_value_for_course(3, true) . '/100',
                                "description" => get_string('description_max_count_tasks_with_inactive_users', 'block_g_statistics'),
                            ]);
                        } else {
                            array_push($mean_value_for_course_array, [
                                "value" => $statistics->get_mean_value_for_course(3, false) . '/100',
                                "description" => get_string('description_max_count_tasks_withpout_inactive_users', 'block_g_statistics'),
                            ]);
                        }
                        break;
                    case 4:
                        if ($takeinactiveusers) {
                            array_push($mean_value_for_course_array, [
                                "value" => $statistics->get_mean_value_for_course(2, true) . '/100',
                                "description" => get_string('description_count_tasks_with_inactive_users', 'block_g_statistics'),
                            ]);
                            array_push($mean_value_for_course_array, [
                                "value" => $statistics->get_mean_value_for_course(3, true) . '/100',
                                "description" => get_string('description_max_count_tasks_with_inactive_users', 'block_g_statistics'),
                            ]);
                        } else {
                            array_push($mean_value_for_course_array, [
                                "value" => $statistics->get_mean_value_for_course(2, false) . '/100',
                                "description" => get_string('description_count_tasks_without_inactive_users', 'block_g_statistics')
                            ]);
                            array_push($mean_value_for_course_array, [
                                "value" => $statistics->get_mean_value_for_course(3, false) . '/100',
                                "description" => get_string('description_max_count_tasks_withpout_inactive_users', 'block_g_statistics'),
                            ]);
                        }
                        break;
                }
            }
            $result["mean_value_for_course_array"] = $mean_value_for_course_array;


            // Получение статистики по пользователю
            $config_user_statistics = $this->config->user_statistics;
            $show_user_statistics = (get_config('block_g_statistics', 'settings_show_user_statistics') == 0 || $config_user_statistics == 1) ? false : true;
            $result["show_user_statistics"] = $show_user_statistics;
            $user_statistics_array = [];
            if($show_user_statistics) {

                $user_info = $users->get_user_info($userid=$config_user_statistics);
                $username = $user_info->firstname . ' ' . $user_info->lastname;
                $user_statistics_array["user_name"] = $username;
                
                
                // Получение средней оценки для пользователя
                $config_show_user_maen_value = $this->config->show_user_mean_value == 1 ? true : false;
                $result["show_user_maen_value"] = $config_show_user_maen_value;
                if ($config_show_user_maen_value) {

                    $user_statistics_mean_value = [];
                    array_push($user_statistics_mean_value, [
                        "value" => $statistics->get_mean_value(2, $config_user_statistics) . '/100',
                        "description" => get_string('config_select_complite_tasks', 'block_g_statistics'),
                    ]);
                    array_push($user_statistics_mean_value, [
                        "value" => $statistics->get_mean_value(3, $config_user_statistics) . '/100',
                        "description" => get_string('config_select_all_tasks', 'block_g_statistics'),
                    ]);

                    $user_statistics_array["user_statistics_mean_value"] = $user_statistics_mean_value;
                }
                

                // Получение количества баллов для пользователя
                $config_show_user_balls = $this->config->show_user_balls == 1 ? true : false;
                $result["show_user_balls"] = $config_show_user_balls;
                if ($config_show_user_balls) {

                    $user_statistics_balls = [];
                    array_push($user_statistics_balls, [
                        "value" => $statistics->get_balls(2, $config_user_statistics),
                        "description" => get_string('config_select_complite_tasks', 'block_g_statistics'),
                    ]);
                    array_push($user_statistics_balls, [
                        "value" => $statistics->get_balls(3, $config_user_statistics),
                        "description" => get_string('config_select_all_tasks', 'block_g_statistics'),
                    ]);

                    $user_statistics_array["user_statistics_balls"] = $user_statistics_balls;
                }


                // Получение колиества выполненных заданий для пользователя
                $config_show_user_count_task = $this->config->show_user_count_tasks == 1 ? true : false;
                $result["show_user_count_task"] = $config_show_user_count_task;
                if ($config_show_user_count_task) {

                    $user_task_count = [];

                    $tasks_type = [
                        -1 => 'allelements',
                        1 => 'assign',
                        14 => 'lesson',
                        16 => 'page',
                        17 => 'quiz',
                    ];

                    foreach ($tasks_type as $key => $value) {

                        array_push($user_task_count, [
                            "description" => get_string($value, 'block_g_statistics'),
                            "value" => $statistics->get_count_complited_tasks($key, $config_user_statistics),
                        ]);
                    }

                    $user_statistics_array["user_task_count"] = $user_task_count;
                }


                // Получение места в рейтинге для пользователя
                $config_show_user_rang = $this->config->show_user_rang == 1 ? true : false;
                $result["show_user_rang"] = $config_show_user_rang;
                if ($config_show_user_rang) {
                    $rating = new rating();

                    $user_statistics_array["user_rang"] = $rating->get_user_rang($config_user_statistics);
                }
                
            }

            $result["user_statistics_array"] = $user_statistics_array;
        }  
        return $result;
    }


    /**
     * Метод получения данных для отображения статистики пользователя
     *
     * @return array - массив статистики
     */
    private function get_statistics_for_user() {
        global $CFG;

        $statistics = new statistics();

        $show_statistics = get_config('block_g_statistics', 'settings_show_statistics') == 0 ? true : false;
        if($show_statistics) {

            // Получение средней оценки
            $config_mean_value = $this->config->mean_value;
            $show_mean_value = (get_config('block_g_statistics', 'settings_show_mean_value') == 0 || $config_mean_value == 1) ? false : true;
            $mean_value_array = [];
            if ($show_mean_value) {
                switch ($config_mean_value) {
                    case 2:
                        array_push($mean_value_array, [
                            "value" => $statistics->get_mean_value($config_mean_value) . '/100',
                            "description" => get_string('config_select_complite_tasks', 'block_g_statistics'),
                        ]);
                        break;
                    case 3:
                        array_push($mean_value_array, [
                            "value" => $statistics->get_mean_value($config_mean_value) . '/100',
                            "description" => get_string('config_select_all_tasks', 'block_g_statistics'),
                        ]);
                        break;
                    case 4:
                        array_push($mean_value_array, [
                            "value" => $statistics->get_mean_value(2) . '/100',
                            "description" => get_string('config_select_complite_tasks', 'block_g_statistics'),
                        ]);
                        array_push($mean_value_array, [
                            "value" => $statistics->get_mean_value(3) . '/100',
                            "description" => get_string('config_select_all_tasks', 'block_g_statistics'),
                        ]);
                        break;
                }
            }
            
            // Получение общего количества баллов
            $config_sum_balls = $this->config->sum_balls;
            $show_sum_balls= (get_config('block_g_statistics', 'settings_show_sum_balls') == 0 || $config_sum_balls == 1) ? false : true;
            $sum_balls_array = [];
            if ($show_sum_balls) {
                switch ($config_sum_balls) {
                    case 2:
                        array_push($sum_balls_array, [
                            "value" => $statistics->get_balls($config_sum_balls),
                            "description" => get_string('config_select_complite_tasks', 'block_g_statistics'),
                        ]);
                        break;
                    case 3:
                        array_push($sum_balls_array, [
                            "value" => $statistics->get_balls($config_sum_balls),
                            "description" => get_string('config_select_all_tasks', 'block_g_statistics'),
                        ]);
                        break;
                    case 4:
                        array_push($sum_balls_array, [
                            "value" => $statistics->get_balls(2),
                            "description" => get_string('config_select_complite_tasks', 'block_g_statistics'),
                        ]);
                        array_push($sum_balls_array, [
                            "value" => $statistics->get_balls(3),
                            "description" => get_string('config_select_all_tasks', 'block_g_statistics'),
                        ]);
                        break;
                }
            }

            // Получение количества решеных заданий
            $config_task_count_comlpited = $this->config->task_count_comlpited;
            $show_task_count_comlpited = (get_config('block_g_statistics', 'settings_show_task_count_comlpited') == 0 || $config_task_count_comlpited == 1) ? false : true;
            $task_count_comlpited_array = [];
            if ($show_task_count_comlpited) {

                $tasks_type = [
                    -1 => 'allelements',
                    1 => 'assign',
                    14 => 'lesson',
                    16 => 'page',
                    17 => 'quiz',
                ];

                switch ($config_task_count_comlpited) {
                    case 2:
                        array_push($task_count_comlpited_array, [
                            "description" => get_string('allelements', 'block_g_statistics'),
                            "value" => $statistics->get_count_complited_tasks(),
                        ]);
                        break;
                    case 3: 
                        foreach ($tasks_type as $key => $value) {

                            array_push($task_count_comlpited_array, [
                                "description" => get_string($value, 'block_g_statistics'),
                                "value" => $statistics->get_count_complited_tasks($key),
                            ]);
                        }
                        break;
                    case 4:
                        // TODO: УЛУЧШИТЬ С ПОМОЩЬЮ СЛОВАРЯ И ПРОХОДКИ ПО ЦИКЛУ
                        if ($this->config->allelements != 0) {

                            array_push($task_count_comlpited_array, [
                                "description" => get_string('allelements', 'block_g_statistics'),
                                "value" => $statistics->get_count_complited_tasks(),
                            ]);
                        }

                        if ($this->config->assign != 0) {

                            array_push($task_count_comlpited_array, [
                                "description" => get_string('assign', 'block_g_statistics'),
                                "value" => $statistics->get_count_complited_tasks($this->config->assign),
                            ]);
                        }

                        if ($this->config->lesson != 0) {

                            array_push($task_count_comlpited_array, [
                                "description" => get_string('lesson', 'block_g_statistics'),
                                "value" => $statistics->get_count_complited_tasks($this->config->lesson),
                            ]);
                        }

                        if ($this->config->page != 0) {

                            array_push($task_count_comlpited_array, [
                                "description" => get_string('page', 'block_g_statistics'),
                                "value" => $statistics->get_count_complited_tasks($this->config->page),
                            ]);
                        }

                        if ($this->config->quiz != 0) {

                            array_push($task_count_comlpited_array, [
                                "description" => get_string('quiz', 'block_g_statistics'),
                                "value" => $statistics->get_count_complited_tasks($this->config->quiz),
                            ]);
                        }

                        break;
                }
            }

            return ["show_statistics" => $show_statistics, 
                    "show_mean_value" => $show_mean_value, 
                    "mean_value_array" => $mean_value_array, 
                    "show_sum_balls" => $show_sum_balls, 
                    "sum_balls_array" => $sum_balls_array,
                    "show_task_count_comlpited" => $show_task_count_comlpited,
                    "task_count_comlpited_array" => $task_count_comlpited_array,
                ];

        } else {
            return [];
        }
    }
}