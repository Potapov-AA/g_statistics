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
 * Language file [ENG].
 *
 * @package   block_g_statistics
 * @copyright 2024 Streje
 * @author    Alexander Potapov <san_sanih99@mail.ru>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['html:addinstance'] = 'Add a new G-statistics block';
$string['html:myaddinstance'] = 'Add a new G-statistics block to Dashboard';

$string['g-statistics'] = '(New G-statistics block)';
$string['pluginname'] = 'G-statistics';

/**
 * Перевод для settings.php
 */
// Перевод настроек отображения блока статистики
$string['settings_statistics_head'] = 'User statistics display settings';

$string['settings_show_statistics'] = 'Dont show statistics';
$string['settings_show_statistics_description'] = 'Disabled by default. When enabled, the statistics block will not be displayed';

$string['settings_show_mean_value'] = 'Average rating';
$string['settings_show_mean_value_description'] = 'Enabled by default. When turned off, the average rating will not be displayed';

$string['settings_show_sum_balls'] = 'Total points';
$string['settings_show_sum_balls_description'] = 'Enabled by default. When turned off, the total number of points will not be displayed';

$string['settings_show_task_count_comlpited'] = 'Number of completed tasks';
$string['settings_show_task_count_comlpited_description'] = 'Enabled by default. When turned off, the total number of completed tasks will not be displayed';

// Перевод для отображения таблицы лидеров
$string['settings_leaderboard_head'] = 'Leaderboard display settings';

$string['settings_show_leaderboard'] = 'Show leaderboard';
$string['settings_show_leaderboard_description'] = 'Enabled by default. When turned off, the leaderboard will not be displayed';

// Перевод для отображения для админа курса
$string['settings_admin_head'] = 'Display settings for admin';

$string['settings_show_mean_grade_for_course'] = 'Average grade for the course';
$string['settings_show_mean_grade_for_course_description'] = 'Enabled by default. When turned off, the average grade for the course will not be displayed';

$string['settings_show_user_statistics'] = 'Statistics for the selected user';
$string['settings_show_user_statistics_description'] = 'Enabled by default. When turned off, statistics for the selected user will not be displayed';


/**
 * Перевод для edit_form.php
 */
$string['config_statistics_header'] = 'Statistics display settings';

// Блок статистики для пользователя
$string['config_user_text'] = 'FOR USER (STUDENT)';

$string['config_mean_value'] = 'Choose how to display your average rating';
$string['config_sum_balls'] = 'Choose how you want to display your total points';
$string['config_task_count_comlpited'] = 'Choose how to display the number of completed tasks';

$string['configadmintext'] = 'FOR ADMINISTRATOR';

$string['configmeanvalueadmin'] = 'Choose how to display the average grade for the course';
$string['yesnounactiveusers'] = 'Take inactive users into account';
$string['configuserstatistics'] = 'Select a student to display his statistics';
$string['showusermaenavalue'] = 'Show average user rating';
$string['showuserballs'] = 'Show user scores';
$string['showusercounttask'] = 'Show the number of tasks completed by the user';
$string['showuserrang'] = "Show the user's place in the ranking";

$string['configleaderboardheader'] = 'Leaderboard settings';

$string['showleaderboard'] = 'Show leaderboard';

$string['configranktype'] = 'Choose what the rating is based on';

$string['configmaxtop'] = 'Maximum number of records displayed at top';
$string['configmaxbot'] = 'Maximum number of records displayed below';

// Перевод для сообщение об ошибке
$string['numeric'] = 'Not a number transmitted';
$string['nonzero'] = 'The number cannot be 0';




/**
 * Перевод для SELECT-FORM (edit_form)
 */
$string['config_select_dont_show'] = "Don't show";
$string['config_select_complite_tasks'] = 'Regarding the number of completed tasks';
$string['config_select_all_tasks'] = 'Regarding the total number of tasks';
$string['config_select_show_both_options'] = 'Show both options';
$string['config_select_show_total'] = 'Show only total quantity';
$string['config_select_show_all'] = 'Show all';
$string['config_select_setting_show'] = 'Set what to show';
$string['yes'] = 'Yes';
$string['no'] = 'No';

// Перевод для типов элементов
$string['all'] = 'All';
$string['allelements'] = 'Total';
$string['lesson'] = 'Lessons';
$string['page'] = 'Pages';
$string['quiz'] = 'Tests';
$string['assign'] = 'Tasks';

// Перевод для описания средней оценкци для админитратора 
$string['descriptioncounttaskswithinactiveusers'] = 'Regarding the number of completed tasks, taking into account inactive users';
$string['descriptioncounttaskswithoutinactiveusers'] = 'Regarding the number of completed tasks without taking into account inactive users';
$string['descriptionmaxcounttaskswithinactiveusers'] = 'Relative to the total number of jobs including inactive users';
$string['descriptionmaxcounttaskswithpoutinactiveusers'] = 'Relative to the total number of jobs excluding inactive users';

// Перевод для блока
$string['blockstatisticstitle'] = 'Statistics';
$string['blockstatisticsballs'] = 'Total points';
$string['blockstatisticsmaingrade'] = 'Avg score';
$string['blockstatisticscounttasks'] = 'Number of completed tasks';
$string['blockstatisticstitleforuser'] = 'Statistics for the user';

$string['blockstatisticsmaingradeadmin'] = 'Avg grade for the course';
$string['blockstatirangforadmin'] = "Rank";

$string['blockleaderboardtitle'] = 'Leaderboard';
$string['blockleaderboardname'] = 'Name';
$string['blockleaderboardballs'] = 'Score';