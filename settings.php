<?php
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