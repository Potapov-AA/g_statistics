<?php
if($ADMIN->fulltree) {    
    $settings->add(new admin_setting_configcheckbox('block_g_statistics/showmeanvalue',
                    'Отображать среднюю оценку',
                    'Включить/выключить отображения средней оценки', 0));

    $settings->add(new admin_setting_configcheckbox('block_g_statistics/showcurrentballs',
                    'Отображать общее количество баллов',
                    'Включить/выключить отображение общего количества баллов', 0));

    $settings->add(new admin_setting_configcheckbox('block_g_statistics/showratingtable',
                    'Отображать таблицу лидеров',
                    'Включить/выключить отображение таблицы лидеров', 0));
}

