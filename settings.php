<?php
if($ADMIN->fulltree) {
    $settings->add(new admin_setting_configcheckbox('block_g_statistics/showcourses', 
                    get_string('showcourses', 'block_g_statistics'), 
                    get_string('showcoursesdescription', 'block_g_statistics'), 0));
}

