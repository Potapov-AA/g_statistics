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
class block_g_statistics_edit_form extends block_edit_form {
    protected function specific_definition($mform) {

        // Отображать статистику
        $settings_show_statistics = get_config('block_g_statistics', 'settings_show_statistics') == 0 ? true : false;
        
        // Настройки отображения элементов статистики
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

        
        $configarray_statistcs = [
            get_config('block_g_statistics', 'settings_show_mean_grade_for_course'),
            get_config('block_g_statistics', 'settings_show_user_statistics'),
        ];

        if ($settings_show_statistics && !$this->is_all_false($configarray_statistcs)) {

            $mform->addElement('html', '<div class="h-5 text-center mb-5"><b>' .
                                    get_string('configadmintext', 'block_g_statistics') .
                                    '</b></div>');

            $show_meangradeforcourse =  get_config('block_g_statistics', 'settings_show_mean_grade_for_course') == 1 ? true : false;
            if ($show_meangradeforcourse) {

                $options = [
                    1 => get_string('config_select_dont_show', 'block_g_statistics'), 
                    2 => get_string('config_select_complite_tasks', 'block_g_statistics'),
                    3 => get_string('config_select_all_tasks', 'block_g_statistics'),
                    4 => get_string('config_select_show_both_options', 'block_g_statistics')
                ];

                $mform->addElement('select', 
                                    'config_meanvalueadmin', 
                                    get_string('configmeanvalueadmin', 'block_g_statistics'),
                                    $options)->setSelected(2);
                $mform->setDefault('config_meanvalueadmin', 2);

                $yesno = [
                    1 => get_string('yes', 'block_g_statistics'), 
                    2 => get_string('no', 'block_g_statistics'), 
                ];

                $mform->addElement('select', 
                                    'config_yesnounactiveusers', 
                                    get_string('yesnounactiveusers', 'block_g_statistics'),
                                    $yesno)->setSelected(2);
                $mform->setDefault('config_meanvalueadmin', 2);

                $mform->disabledIf('config_yesnounactiveusers', 'config_meanvalueadmin', 'eq', 1); 
            }

            $show_userstatistics = get_config('block_g_statistics', 'settings_show_user_statistics') == 1 ? true : false;
            if ($show_userstatistics) {

                $users = $this->get_users();

                $select_array = [1 => get_string('config_select_dont_show', 'block_g_statistics')];

                foreach($users as $user) {

                    $select_array[$user->userid] = $user->firstname . ' ' . $user->lastname;
                }

                $mform->addElement('select', 
                                    'config_userstatistics', 
                                    get_string('configuserstatistics', 'block_g_statistics'),
                                    $select_array)->setSelected(1);
                $mform->setDefault('config_userstatistics', 1);

                $mform->addElement('advcheckbox', 
                                    'config_showusermaenavalue', 
                                    '', 
                                    get_string('showusermaenavalue', 'block_g_statistics'),
                                    null,
                                    [0, 1]);
                    
                $mform->disabledIf('config_showusermaenavalue', 'config_userstatistics', 'eq', 1); 

                $mform->addElement('advcheckbox', 
                                    'config_showuserballs', 
                                    '', 
                                    get_string('showuserballs', 'block_g_statistics'),
                                    null,
                                    [0, 1]);
                    
                $mform->disabledIf('config_showuserballs', 'config_userstatistics', 'eq', 1); 

                $mform->addElement('advcheckbox', 
                                    'config_showusercounttask', 
                                    '', 
                                    get_string('showusercounttask', 'block_g_statistics'),
                                    null,
                                    [0, 1]);
                    
                $mform->disabledIf('config_showusercounttask', 'config_userstatistics', 'eq', 1); 

                $mform->addElement('advcheckbox', 
                                    'config_showuserrang', 
                                    '', 
                                    get_string('showuserrang', 'block_g_statistics'),
                                    null,
                                    [0, 1]);
                    
                $mform->disabledIf('config_showuserrang', 'config_userstatistics', 'eq', 1); 
            }
        }

        $show_leaderboard = get_config('block_g_statistics', 'showratingtable') == 1 ? true : false;

        if ($show_leaderboard) {
            $mform->addElement('header', 'configleaderboardheader', get_string('configleaderboardheader', 'block_g_statistics'));

            $rank_type = [
                -1 => get_string('all', 'block_g_statistics'), 
                17 => get_string('quiz', 'block_g_statistics'),
                1 => get_string('assign', 'block_g_statistics'),
                14 => get_string('lesson', 'block_g_statistics')
            ];

            $mform->addElement('select', 
                                'config_ranktype', 
                                get_string('configranktype', 'block_g_statistics'),
                                $rank_type)->setSelected(-1);
            $mform->setDefault('config_ranktype', -1);

            $mform->addElement('html', '<div class="h-5 text-center mb-5"><b>' .
                                    get_string('configusertext', 'block_g_statistics') .
                                    '</b></div>');
            
            $mform->addElement('advcheckbox', 
                                'config_showleaderboarduser', 
                                '', 
                                get_string('showleaderboard', 'block_g_statistics'),
                                null,
                                [0, 1]);

            $mform->addElement('text', 'config_maxtopuser', get_string('configmaxtop', 'block_g_statistics'));
            $mform->addRule('config_maxtopuser', get_string('numeric', 'block_g_statistics'), 'numeric');
            $mform->addRule('config_maxtopuser', get_string('nonzero', 'block_g_statistics'), 'nonzero');
            $mform->disabledIf('config_maxtopuser', 'config_showleaderboard', 'eq', 0); 
            $mform->setDefault('config_maxtopuser', '5'); 
            
            $mform->addElement('text', 'config_maxbotuser', get_string('configmaxbot', 'block_g_statistics'));
            $mform->addRule('config_maxbotuser', get_string('numeric', 'block_g_statistics'), 'numeric');
            $mform->addRule('config_maxbotuser', get_string('nonzero', 'block_g_statistics'), 'nonzero');
            $mform->disabledIf('config_maxbotuser', 'config_showleaderboard', 'eq', 0); 
            $mform->setDefault('config_maxbotuser', '5'); 


            $mform->addElement('html', '<div class="h-5 text-center mb-5"><b>' .
                                    get_string('configadmintext', 'block_g_statistics') .
                                    '</b></div>');

            $mform->addElement('advcheckbox', 
                                    'config_showleaderboardadmin', 
                                    '', 
                                    get_string('showleaderboard', 'block_g_statistics'),
                                    null,
                                    [0, 1]);

            $mform->addElement('text', 'config_maxtopadmin', get_string('configmaxtop', 'block_g_statistics'));
            $mform->addRule('config_maxtopadmin', get_string('numeric', 'block_g_statistics'), 'numeric');
            $mform->addRule('config_maxtopadmin', get_string('nonzero', 'block_g_statistics'), 'nonzero');
            $mform->disabledIf('config_maxtopadmin', 'config_showleaderboard', 'eq', 0);
            $mform->setDefault('config_maxtopadmin', '5'); 

            $mform->addElement('text', 'config_maxbotadmin', get_string('configmaxbot', 'block_g_statistics'));
            $mform->addRule('config_maxbotadmin', get_string('numeric', 'block_g_statistics'), 'numeric');
            $mform->addRule('config_maxbotadmin', get_string('nonzero', 'block_g_statistics'), 'nonzero');
            $mform->disabledIf('config_maxbotadmin', 'config_showleaderboard', 'eq', 0); 
            $mform->setDefault('config_maxbotadmin', '5');
        }

        

        
    }

    private function get_users() {
        global $DB, $COURSE;

        $users = $DB->get_records_sql(
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

        return $users;
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