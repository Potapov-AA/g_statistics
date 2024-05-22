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
 * Block G-statistics edit form.
 *
 * @package   block_g_statistics
 * @copyright 2024 Streje
 * @author    Alexander Potapov <san_sanih99@mail.ru>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use block_g_statistics\users;

class block_g_statistics_edit_form extends block_edit_form {
    protected function specific_definition($mform) {

        $users = new users();

        // Отображать статистику
        $settings_show_statistics = get_config('block_g_statistics', 'settings_show_statistics') == 0 ? true : false;
        
        // Настройки отображения элементов статистики для пользователя
        $array_settings_show_statistics_for_user = [
            get_config('block_g_statistics', 'settings_show_mean_value'),
            get_config('block_g_statistics', 'settings_show_sum_balls'),
            get_config('block_g_statistics', 'settings_show_task_count_comlpited'),
        ];
        
        // Если отключен блок статистики или отключены все эелементы, настройки отображаться не будут
        if ($settings_show_statistics && !$this->is_all_false($array_settings_show_statistics_for_user)) {

            $mform->addElement('header', 'config_statistics_header', get_string('config_statistics_header', 'block_g_statistics'));
            
            $mform->addElement('html', '<div class="h-5 text-center mb-5"><b>' .
                                get_string('config_user_text', 'block_g_statistics') .
                                '</b></div>');
            

            $options_for_mean_value_and_current_balls = [
                1 => get_string('config_select_dont_show', 'block_g_statistics'), 
                2 => get_string('config_select_complite_tasks', 'block_g_statistics'),
                3 => get_string('config_select_all_tasks', 'block_g_statistics'),
                4 => get_string('config_select_show_both_options', 'block_g_statistics')
            ];

            $settings_show_mean_value =  get_config('block_g_statistics', 'settings_show_mean_value') == 1 ? true : false;
            if ($settings_show_mean_value) {
                $mform->addElement('select', 
                                    'config_mean_value', 
                                    get_string('config_mean_value', 'block_g_statistics'),
                                    $options_for_mean_value_and_current_balls)->setSelected(2);
                $mform->setDefault('config_mean_value', 2);
            }
            
            $settings_show_sum_balls =  get_config('block_g_statistics', 'settings_show_sum_balls') == 1 ? true : false;
            if ($settings_show_sum_balls) {
                $mform->addElement('select', 
                                    'config_sum_balls', 
                                    get_string('config_sum_balls', 'block_g_statistics'),
                                    $options_for_mean_value_and_current_balls)->setSelected(2);
                $mform->setDefault('config_sum_balls', 2);
            }


            $options_for_task_count_complited = [
                1 => get_string('config_select_dont_show', 'block_g_statistics'),
                2 => get_string('config_select_show_total', 'block_g_statistics'),
                3 => get_string('config_select_show_all', 'block_g_statistics'),
                4 => get_string('config_select_setting_show', 'block_g_statistics'),
            ];

            $settings_show_task_count_comlpited = get_config('block_g_statistics', 'settings_show_task_count_comlpited') == 1 ? true : false;
            if($settings_show_task_count_comlpited) {

                $mform->addElement('select', 
                                    'config_task_count_comlpited', 
                                    get_string('config_task_count_comlpited', 'block_g_statistics'),
                                    $options_for_task_count_complited)->setSelected(2);
                $mform->setDefault('config_task_count_comlpited', 2);
                
                // Типы задач
                $tasks_type = [
                    -1 => 'allelements',
                    1 => 'assign',
                    14 => 'lesson',
                    16 => 'page',
                    17 => 'quiz',
                ];

                foreach($tasks_type as $key => $value) {
                    $name = 'config_' . $value;

                    $mform->addElement('advcheckbox', 
                                        $name, 
                                        '', 
                                        get_string($value, 'block_g_statistics'),
                                        null,
                                        [0, $key]);
                    
                    $mform->disabledIf($name, 'config_task_count_comlpited', 'neq', 4); 
                }
            }
        }

        // Настройки отображения элементов статистики для учителя/админа курса
        $array_settings_show_statistics_for_admin = [
            get_config('block_g_statistics', 'settings_show_mean_grade_for_course'),
            get_config('block_g_statistics', 'settings_show_user_statistics'),
        ];

        // Если отключен блок статистики или отключены все эелементы, настройки отображаться не будут
        if ($settings_show_statistics && !$this->is_all_false($array_settings_show_statistics_for_admin)) {

            $mform->addElement('html', '<div class="h-5 text-center mb-5"><b>' .
                                    get_string('config_admin_text', 'block_g_statistics') .
                                    '</b></div>');


            $settings_show_mean_grade_for_course =  get_config('block_g_statistics', 'settings_show_mean_grade_for_course') == 1 ? true : false;
            if ($settings_show_mean_grade_for_course) {

                $options_for_mean_value_for_course = [
                    1 => get_string('config_select_dont_show', 'block_g_statistics'), 
                    2 => get_string('config_select_complite_tasks', 'block_g_statistics'),
                    3 => get_string('config_select_all_tasks', 'block_g_statistics'),
                    4 => get_string('config_select_show_both_options', 'block_g_statistics')
                ];

                $mform->addElement('select', 
                                    'config_mean_value_for_course', 
                                    get_string('config_mean_value_for_course', 'block_g_statistics'),
                                    $options_for_mean_value_for_course)->setSelected(2);
                $mform->setDefault('config_mean_value_for_course', 2);


                $options_yes_no = [
                    1 => get_string('config_yes', 'block_g_statistics'), 
                    2 => get_string('config_no', 'block_g_statistics'), 
                ];

                $mform->addElement('select', 
                                    'config_yes_no_unactive_users', 
                                    get_string('config_yes_no_unactive_users', 'block_g_statistics'),
                                    $options_yes_no)->setSelected(2);
                $mform->setDefault('config_mean_value_for_course', 2);
                $mform->disabledIf('config_yes_no_unactive_users', 'config_mean_value_for_course', 'eq', 1); 
            }


            $settings_show_user_statistics = get_config('block_g_statistics', 'settings_show_user_statistics') == 1 ? true : false;
            if ($settings_show_user_statistics) {

                $users = $users->get_users();

                $options_for_user_statistics = [1 => get_string('config_select_dont_show', 'block_g_statistics')];

                foreach($users as $user) {
                    $options_for_user_statistics[$user->userid] = $user->firstname . ' ' . $user->lastname;
                }

                $mform->addElement('select', 
                                    'config_user_statistics', 
                                    get_string('config_user_statistics', 'block_g_statistics'),
                                    $options_for_user_statistics)->setSelected(1);
                $mform->setDefault('config_user_statistics', 1);

                $mform->addElement('advcheckbox', 
                                    'config_show_user_mean_value', 
                                    '', 
                                    get_string('config_show_user_mean_value', 'block_g_statistics'),
                                    null,
                                    [0, 1]);    
                $mform->disabledIf('config_show_user_mean_value', 'config_user_statistics', 'eq', 1); 

                $mform->addElement('advcheckbox', 
                                    'config_show_user_balls', 
                                    '', 
                                    get_string('config_show_user_balls', 'block_g_statistics'),
                                    null,
                                    [0, 1]);  
                $mform->disabledIf('config_show_user_balls', 'config_user_statistics', 'eq', 1); 

                $mform->addElement('advcheckbox', 
                                    'config_show_user_count_tasks', 
                                    '', 
                                    get_string('config_show_user_count_tasks', 'block_g_statistics'),
                                    null,
                                    [0, 1]);  
                $mform->disabledIf('config_show_user_count_tasks', 'config_user_statistics', 'eq', 1); 

                $mform->addElement('advcheckbox', 
                                    'config_show_user_rang', 
                                    '', 
                                    get_string('config_show_user_rang', 'block_g_statistics'),
                                    null,
                                    [0, 1]);
                $mform->disabledIf('config_show_user_rang', 'config_user_statistics', 'eq', 1); 
            }
        }


        $settings_show_leaderboard = get_config('block_g_statistics', 'settings_show_leaderboard') == 1 ? true : false;
        if ($settings_show_leaderboard) {
            $mform->addElement('header', 'config_leaderboard_header', get_string('config_leaderboard_header', 'block_g_statistics'));

            $option_rang_type = [
                -1 => get_string('all', 'block_g_statistics'), 
                17 => get_string('quiz', 'block_g_statistics'),
                1 => get_string('assign', 'block_g_statistics'),
                14 => get_string('lesson', 'block_g_statistics')
            ];

            $mform->addElement('select', 
                                'config_rang_type', 
                                get_string('config_rang_type', 'block_g_statistics'),
                                $option_rang_type)->setSelected(-1);
            $mform->setDefault('config_rang_type', -1);


            $mform->addElement('html', '<div class="h-5 text-center mb-5"><b>' .
                                    get_string('config_user_text', 'block_g_statistics') .
                                    '</b></div>');
            
            $mform->addElement('advcheckbox', 
                                'config_show_leaderboard_for_user', 
                                '', 
                                get_string('config_show_leaderboard', 'block_g_statistics'),
                                null,
                                [0, 1]);

            $mform->addElement('text', 'config_max_top_user', get_string('config_max_top', 'block_g_statistics'));
            $mform->addRule('config_max_top_user', get_string('config_numeric', 'block_g_statistics'), 'numeric');
            $mform->addRule('config_max_top_user', get_string('config_nonzero', 'block_g_statistics'), 'nonzero');
            $mform->disabledIf('config_max_top_user', 'config_show_leaderboard_for_user', 'eq', 0); 
            $mform->setDefault('config_max_top_user', '5'); 
            
            $mform->addElement('text', 'config_max_bot_user', get_string('config_max_bot', 'block_g_statistics'));
            $mform->addRule('config_max_bot_user', get_string('config_numeric', 'block_g_statistics'), 'numeric');
            $mform->addRule('config_max_bot_user', get_string('config_nonzero', 'block_g_statistics'), 'nonzero');
            $mform->disabledIf('config_max_bot_user', 'config_show_leaderboard_for_user', 'eq', 0); 
            $mform->setDefault('config_max_bot_user', '5'); 


            $mform->addElement('html', '<div class="h-5 text-center mb-5"><b>' .
                                    get_string('config_admin_text', 'block_g_statistics') .
                                    '</b></div>');

            $mform->addElement('advcheckbox', 
                                    'config_show_leaderboard_for_admin', 
                                    '', 
                                    get_string('config_show_leaderboard', 'block_g_statistics'),
                                    null,
                                    [0, 1]);

            $mform->addElement('text', 'config_max_top_admin', get_string('config_max_top', 'block_g_statistics'));
            $mform->addRule('config_max_top_admin', get_string('config_numeric', 'block_g_statistics'), 'numeric');
            $mform->addRule('config_max_top_admin', get_string('config_nonzero', 'block_g_statistics'), 'nonzero');
            $mform->disabledIf('config_max_top_admin', 'config_show_leaderboard_for_admin', 'eq', 0);
            $mform->setDefault('config_max_top_admin', '5'); 

            $mform->addElement('text', 'config_max_bot_admin', get_string('config_max_bot', 'block_g_statistics'));
            $mform->addRule('config_max_bot_admin', get_string('config_numeric', 'block_g_statistics'), 'numeric');
            $mform->addRule('config_max_bot_admin', get_string('config_nonzero', 'block_g_statistics'), 'nonzero');
            $mform->disabledIf('config_max_bot_admin', 'config_show_leaderboard_for_admin', 'eq', 0); 
            $mform->setDefault('config_max_bot_admin', '5');
        }
    }
    

    /**
     * Проверяет все ли элементы в массиве являются false
     *
     * @param array - массив содержащий значения настроек 1/0
     * @return boolean - true (все элементы массива = 0), false (хотя бы один элемент = 1)
     */
    private function is_all_false($configarray) {
        foreach($configarray as $item) {
            if ($item == 1) return false;
        }

        return true;
    }
}