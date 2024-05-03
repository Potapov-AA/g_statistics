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
        ];

        if ($show_statistics && !$this->is_all_false($configarray_statistcs)) {

            $mform->addElement('header', 'configstatisticsheader', get_string('configstatisticsheader', 'block_g_statistics'));
            
            $options = [
                1 => get_string('selectdontshow', 'block_g_statistics'), 
                2 => get_string('selectcomplitetasks', 'block_g_statistics'),
                3 => get_string('selectalltasks', 'block_g_statistics'),
                4 => get_string('showbothoptions', 'block_g_statistics')
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
        }

        // $mform->addElement('header', 'config_header', 'Еще какой-то хедер');
    }

    private function is_all_false($configarray) {
        foreach($configarray as $item) {
            if ($item == 1) return false;
        }

        return true;
    }
}