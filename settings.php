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
 * Block G-statistics settings.
 *
 * @package   block_g_statistics
 * @copyright 2024 Streje
 * @author    Alexander Potapov <san_sanih99@mail.ru>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if($ADMIN->fulltree) {

    // Statistics

    $settings->add(new admin_setting_heading('block_g_statistics/statisticshead', 
                    get_string('statisticshead', 'block_g_statistics'), ""));

    $settings->add(new admin_setting_configcheckbox('block_g_statistics/showstatistics',
                    get_string('showstatistics', 'block_g_statistics'),
                    get_string('showstatisticsdescription', 'block_g_statistics'), 0));

    $settings->add(new admin_setting_configcheckbox('block_g_statistics/showmeanvalue',
                    get_string('showavggrade', 'block_g_statistics'),
                    get_string('showavggradedescription', 'block_g_statistics'), 1));

    $settings->add(new admin_setting_configcheckbox('block_g_statistics/showcurrentballs',
                    get_string('showsumballs', 'block_g_statistics'),
                    get_string('showsumballsdescription', 'block_g_statistics'), 1));

    $settings->add(new admin_setting_configcheckbox('block_g_statistics/showtaskcountcomlpited',
                    get_string('showtaskcountcomlpited', 'block_g_statistics'),
                    get_string('showtaskcountcomlpiteddescription', 'block_g_statistics'), 1));

    // Leaderboard
    
    $settings->add(new admin_setting_heading('block_g_statistics/ratingtablehead', 
                    get_string('ratingtablehead', 'block_g_statistics'), ""));

    $settings->add(new admin_setting_configcheckbox('block_g_statistics/showratingtable',
                    get_string('showleaderboard', 'block_g_statistics'),
                    get_string('showleaderboarddescription', 'block_g_statistics'), 1));


    // Admin
    
    $settings->add(new admin_setting_heading('block_g_statistics/adminhead', 
                    get_string('adminhead', 'block_g_statistics'), ""));

    $settings->add(new admin_setting_configcheckbox('block_g_statistics/showmeangradeforcourse',
                    get_string('showmeangradeforcourse', 'block_g_statistics'),
                    get_string('showmeangradeforcoursedescription', 'block_g_statistics'), 1));

    $settings->add(new admin_setting_configcheckbox('block_g_statistics/showuserstatistics',
                    get_string('showuserstatistics', 'block_g_statistics'),
                    get_string('showuserstatisticsdescription', 'block_g_statistics'), 1));
}