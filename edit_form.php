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
        $mform->addElement('header', 'configstatisticsheader', 'Настройка отображения статистики');
        
        $mform->addElement('select', 
                            'config_meanvalue', 
                            'Выбирите способ отображения',
                            array(
                                1 => 'Не отображать', 
                                2 => 'Относительно количества пройденных заданий',
                                3 => 'Относительно общего количества заданий')
                            )->setSelected(2);
        $mform->setDefault('config_meanvalue', 2);

        // $mform->addElement('header', 'config_header', 'Еще какой-то хедер');
    }
}