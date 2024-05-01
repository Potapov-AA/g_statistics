<?php
defined('MOODLE_INTERNAL') || die();

if($ADMIN->fulltree) {

    $settings->add(new admin_setting_heading('block_g_statistics/statisticshead', 
                    get_string('statisticshead', 'block_g_statistics'), ""));

    $settings->add(new admin_setting_configcheckbox('block_g_statistics/showmeanvalue',
                    get_string('showavggrade', 'block_g_statistics'),
                    get_string('showavggradedescription', 'block_g_statistics'), 0));

    $settings->add(new admin_setting_configcheckbox('block_g_statistics/showcurrentballs',
                    get_string('showsumballs', 'block_g_statistics'),
                    get_string('showsumballsdescription', 'block_g_statistics'), 0));

    $settings->add(new admin_setting_heading('block_g_statistics/ratingtablehead', 
                    get_string('ratingtablehead', 'block_g_statistics'), ""));

    $settings->add(new admin_setting_configcheckbox('block_g_statistics/showratingtable',
                    get_string('showleaderboard', 'block_g_statistics'),
                    get_string('showleaderboarddescription', 'block_g_statistics'), 0));
}