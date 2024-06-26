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

// Перевод для settings
$string['statisticshead'] = 'Statistics settings';

$string['showstatistics'] = 'Dont show statistics';
$string['showstatisticsdescription'] = 'Enable/disable statistics display';

$string['showavggrade'] = 'Show average rating';
$string['showavggradedescription'] = 'Enable/disable average rating display';

$string['showsumballs'] = 'Show total points';
$string['showsumballsdescription'] = 'Enable/disable display of total points';

$string['showtaskcountcomlpited'] = 'Display the number of completed tasks';
$string['showtaskcountcomlpiteddescription'] = 'Enable/disable display of the number of completed tasks';


$string['ratingtablehead'] = 'Leaderboard settings';

$string['showleaderboard'] = 'Show leaderboard';
$string['showleaderboarddescription'] = 'Enable/disable leaderboard display';


$string['adminhead'] = 'Display settings for admin';

$string['showmeangradeforcourse'] = 'Show average course grade';
$string['showmeangradeforcoursedescription'] = 'Enable/disable display of average course grade';

$string['showuserstatistics'] = 'Show statistics for selected user';
$string['showuserstatisticsdescription'] = 'Enable/disable display of statistics for the selected user';


// Перевод для edit_form
$string['configstatisticsheader'] = 'Statistics display settings';

$string['configusertext'] = 'FOR USER (STUDENT)';

$string['configmeanvalue'] = 'Choose how to display your average rating';
$string['configcurrentballs'] = 'Choose how you want to display your total points';
$string['configtaskcounts'] = 'Choose how to display the number of completed tasks';

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

// Перевод для SELECT-FORM (средний балл и количество баллов) (edit_form)
$string['selectdontshow'] = "Don't show";
$string['selectcomplitetasks'] = 'Regarding the number of completed tasks';
$string['selectalltasks'] = 'Regarding the total number of tasks';
$string['selectshowbothoptions'] = 'Show both options';
$string['selectshowtotal'] = 'Show only total quantity';
$string['selectshowall'] = 'Show all';
$string['selectsettingshow'] = 'Set what to show';
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