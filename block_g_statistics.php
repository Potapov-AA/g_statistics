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

        $config_mean_value = $this->config->meanvalue;
        $show_mean_value = (get_config('block_g_statistics', 'showmeanvalue') == 0 || $config_mean_value == 1) ? false : true;

        if ($show_mean_value) {
            $result = $statistics->get_mean_value($config_mean_value);
            if($result != -1) {
                $mean_value = $result;
            } else {
                $mean_value = "-//-";
            }
        } else {
            $mean_value = "-//-";
        }
        
        

        $data_for_statistics = [
            "show_mean_value" => $show_mean_value,
            "mean_value" => $mean_value,
        ];


        $this->content = new stdClass;
        $this->content->text = $OUTPUT->render_from_template("block_g_statistics/statistics", $data_for_statistics);
        $this->content->footer = ''; 

        return $this->content;
    }
}
