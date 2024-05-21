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
 * Language file [RU].
 *
 * @package   block_g_statistics
 * @copyright 2024 Streje
 * @author    Alexander Potapov <san_sanih99@mail.ru>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['html:addinstance'] = 'Добавить новый блок G-статистики';
$string['html:myaddinstance'] = 'Добавить новый блок на панель управления G-статистики';

$string['g-statistics'] = '(Новый блок G-статистики)';
$string['pluginname'] = 'G-статистика';

/**
 * Перевод для settings.php
 */
// Перевод настроек отображения блока статистики
$string['settings_statistics_head'] = 'Настройки отображения статистики пользователя';

$string['settings_show_statistics'] = 'Не показывать статистику';
$string['settings_show_statistics_description'] = 'По умолчанию отключено. При включение блок статистики отображаться не будет';

$string['settings_show_mean_value'] = 'Средняя оценка';
$string['settings_show_mean_value_description'] = 'По умолчанию включено. При выключение средняя оценка отображаться не будет';

$string['settings_show_sum_balls'] = 'Общее количество баллов';
$string['settings_show_sum_balls_description'] = 'По умолчанию включено. При выключение общее количество баллов отображаться не будет';

$string['settings_show_task_count_comlpited'] = 'Количество выполненных заданий';
$string['settings_show_task_count_comlpited_description'] = 'По умолчанию включено. При выключение общее количество выполненных заданий отображаться не будет';


$string['ratingtablehead'] = 'Настройки таблицы лидеров';

$string['showleaderboard'] = 'Отображать таблицу лидеров';
$string['showleaderboarddescription'] = 'Включить/выключить отображение таблицы лидеров';


$string['adminhead'] = 'Настройки отображения для админа';

$string['showmeangradeforcourse'] = 'Показывать среднюю оценку за курс';
$string['showmeangradeforcoursedescription'] = 'Включить/выключить отображение средней оценки за курс';

$string['showuserstatistics'] = 'Показывать статистику для выбранного пользователя';
$string['showuserstatisticsdescription'] = 'Включить/выключить отображение статистики для выбранного пользователя';


// Перевод для edit_form
$string['configstatisticsheader'] = 'Настройки отображения статистики';

$string['configusertext'] = 'ДЛЯ ПОЛЬЗОВАТЕЛЯ (СТУДЕНТА)';

$string['configmeanvalue'] = 'Выберете способ отображения средней оценки';
$string['configcurrentballs'] = 'Выберете способ отображения общего количества баллов';
$string['configtaskcounts'] = 'Выберете способ отображения количества выполненных заданий';

$string['configadmintext'] = 'ДЛЯ АДМИНИСТРАТОРА';

$string['configmeanvalueadmin'] = 'Выберете способ отображения средней оценки по курсу';
$string['yesnounactiveusers'] = 'Учитывать неактивных пользователей';
$string['configuserstatistics'] = 'Выберете студента для отображения его статистики';
$string['showusermaenavalue'] = 'Показывать среднюю оценку пользователя';
$string['showuserballs'] = 'Показывать баллы пользователя';
$string['showusercounttask'] = 'Показывать количество выполненных заданий пользователем';
$string['showuserrang'] = 'Показывать место пользователя в рейтинге';

$string['configleaderboardheader'] = 'Настройки таблицы лидеров';

$string['showleaderboard'] = 'Показывать таблицу лидеров';

$string['configranktype'] = 'Выбирите на основе чего выстраивать рейтинг';

$string['configmaxtop'] = 'Максимальное количество отображаемых записей сверху';
$string['configmaxbot'] = 'Максимальное количество отображаемых записей снизу';



// Перевод для сообщение об ошибке
$string['numeric'] = 'Передано не число';
$string['nonzero'] = 'Число не может быть равным 0';


// Перевод для SELECT-FORM (edit_form)
$string['selectdontshow'] = 'Не показывать';
$string['selectcomplitetasks'] = 'Относительно количества пройденных заданий';
$string['selectalltasks'] = 'Относительно общего количества заданий';
$string['selectshowbothoptions'] = 'Показывать оба вариант';
$string['selectshowtotal'] = 'Показывать только общее количество';
$string['selectshowall'] = 'Показывать все';
$string['selectsettingshow'] = 'Настроить, что показывать';
$string['yes'] = 'Да';
$string['no'] = 'Нет';

// Перевод для типов элементов
$string['all'] = 'Все';
$string['allelements'] = 'Общее количество';
$string['lesson'] = 'Лекции';
$string['page'] = 'Страницы';
$string['quiz'] = 'Тесты';
$string['assign'] = 'Задания';

// Перевод для описания средней оценкци для админитратора 
$string['descriptioncounttaskswithinactiveusers'] = 'Относительно количества пройденных заданий c учетом неактивных пользователей';
$string['descriptioncounttaskswithoutinactiveusers'] = 'Относительно количества пройденных заданий без учета неактивных пользователей';
$string['descriptionmaxcounttaskswithinactiveusers'] = 'Относительно общего количества заданий с учетом неактивных пользователей';
$string['descriptionmaxcounttaskswithpoutinactiveusers'] = 'Относительно общего количества заданий без учета неактивных пользователей';



// Перевод для блока
$string['blockstatisticstitle'] = 'Cтатистика';
$string['blockstatisticsballs'] = 'Всего баллов';
$string['blockstatisticsmaingrade'] = 'Ср. оценка';
$string['blockstatisticscounttasks'] = 'Количество завершенных заданий';
$string['blockstatisticstitleforuser'] = 'Статистика для пользователя';

$string['blockstatisticsmaingradeadmin'] = 'Ср. оценка по курсу';
$string['blockstatirangforadmin'] = "Место в рейтинге";


$string['blockleaderboardtitle'] = 'Таблица лидеров';
$string['blockleaderboardname'] = 'Имя';
$string['blockleaderboardballs'] = 'Баллы';