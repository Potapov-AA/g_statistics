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
 * Block edit form class for the block_pluginname plugin.
 *
 * @package   block_g_statistics
 * @copyright 2024 Streje (san_sanih99@mail.ru)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_g_statistics_edit_form extends block_edit_form {
    protected function specific_definition($mform) {

        $show_statistics = get_config('block_g_statistics', 'showstatistics') == 0 ? true : false;
        
        $configarray_statistcs = [
            get_config('block_g_statistics', 'showmeanvalue'),
            get_config('block_g_statistics', 'showcurrentballs'),
            get_config('block_g_statistics', 'showtaskcountcomlpited'),
        ];

        if ($show_statistics && !$this->is_all_false($configarray_statistcs)) {

            $mform->addElement('header', 'configstatisticsheader', get_string('configstatisticsheader', 'block_g_statistics'));
            
            $mform->addElement('html', '<div class="h-5 text-center mb-5"><b>' .
                                get_string('configusertext', 'block_g_statistics') .
                                '</b></div>');
            
            $options = [
                1 => get_string('selectdontshow', 'block_g_statistics'), 
                2 => get_string('selectcomplitetasks', 'block_g_statistics'),
                3 => get_string('selectalltasks', 'block_g_statistics'),
                4 => get_string('selectshowbothoptions', 'block_g_statistics')
            ];

            $show_meanvalue =  get_config('block_g_statistics', 'showmeanvalue') == 1 ? true : false;
            if ($show_meanvalue) {
                $mform->addElement('select', 
                                    'config_meanvalue', 
                                    get_string('configmeanvalue', 'block_g_statistics'),
                                    $options)->setSelected(2);
                $mform->setDefault('config_meanvalue', 2);
            }
            
            $show_currentballs =  get_config('block_g_statistics', 'showcurrentballs') == 1 ? true : false;
            if ($show_currentballs) {
                $mform->addElement('select', 
                                    'config_currentballs', 
                                    get_string('configcurrentballs', 'block_g_statistics'),
                                    $options)->setSelected(2);
                $mform->setDefault('config_currentballs', 2);
            }


            $options = [
                1 => get_string('selectdontshow', 'block_g_statistics'),
                2 => get_string('selectshowtotal', 'block_g_statistics'),
                3 => get_string('selectshowall', 'block_g_statistics'),
                4 => get_string('selectsettingshow', 'block_g_statistics'),
            ];

            $tasks_type = [
                -1 => 'allelements',
                1 => 'assign',
                14 => 'lesson',
                16 => 'page',
                17 => 'quiz',

            ];

            $show_taskcountcomlpited = get_config('block_g_statistics', 'showtaskcountcomlpited') == 1 ? true : false;

            if($show_taskcountcomlpited) {

                $mform->addElement('select', 
                                    'config_taskcount', 
                                    get_string('configtaskcounts', 'block_g_statistics'),
                                    $options)->setSelected(2);
                $mform->setDefault('config_taskcount', 2);
 
                foreach($tasks_type as $key => $value) {
                    $name = 'config_' . $value;

                    $mform->addElement('advcheckbox', 
                                        $name, 
                                        '', 
                                        get_string($value, 'block_g_statistics'),
                                        null,
                                        [0, $key]);
                    
                    $mform->disabledIf($name, 'config_taskcount', 'neq', 4); 
                }
            }
        }

        
        $configarray_statistcs = [
            get_config('block_g_statistics', 'showmeangradeforcourse'),
            get_config('block_g_statistics', 'showuserstatistics'),
        ];

        if ($show_statistics && !$this->is_all_false($configarray_statistcs)) {

            $mform->addElement('html', '<div class="h-5 text-center mb-5"><b>' .
                                    get_string('configadmintext', 'block_g_statistics') .
                                    '</b></div>');

            $show_meangradeforcourse =  get_config('block_g_statistics', 'showmeangradeforcourse') == 1 ? true : false;
            if ($show_meangradeforcourse) {

                $options = [
                    1 => get_string('selectdontshow', 'block_g_statistics'), 
                    2 => get_string('selectcomplitetasks', 'block_g_statistics'),
                    3 => get_string('selectalltasks', 'block_g_statistics'),
                    4 => get_string('selectshowbothoptions', 'block_g_statistics')
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

            $show_userstatistics = get_config('block_g_statistics', 'showuserstatistics') == 1 ? true : false;
            if ($show_userstatistics) {

                $users = $this->get_users();

                $select_array = [1 => get_string('selectdontshow', 'block_g_statistics')];

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
            }
        }



        $mform->addElement('header', 'configleaderboardheader', 'Таблица лидеров'); //TODO: Добавить переводы
    }

    private function is_all_false($configarray) {
        foreach($configarray as $item) {
            if ($item == 1) return false;
        }

        return true;
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
}