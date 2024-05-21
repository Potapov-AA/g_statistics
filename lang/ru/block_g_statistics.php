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

// Перевод для отображения таблицы лидеров
$string['settings_leaderboard_head'] = 'Настройки отображения таблицы лидеров';

$string['settings_show_leaderboard'] = 'Показывать таблицу лидеров';
$string['settings_show_leaderboard_description'] = 'По умолчанию включено. При выключение таблица лидеров отображаться не будет';

// Перевод для отображения для админа курса
$string['settings_admin_head'] = 'Настройки отображения для админа';

$string['settings_show_mean_grade_for_course'] = 'Средняя оценка по курсу';
$string['settings_show_mean_grade_for_course_description'] = 'По умолчанию включено. При выключение средняя оценка по курсу отображаться не будет';

$string['settings_show_user_statistics'] = 'Статистика по выбранному пользователю';
$string['settings_show_user_statistics_description'] = 'По умолчанию включено. При выключение статистика по выбранному пользователю отображаться не будет';


/**
 * Перевод для edit_form.php
 */
// Блок статистики
$string['config_statistics_header'] = 'Настройки отображения статистики';

// Блок статистики для пользователя
$string['config_user_text'] = 'ДЛЯ ПОЛЬЗОВАТЕЛЯ (СТУДЕНТА)';

$string['config_mean_value'] = 'Выберите способ отображения средней оценки';
$string['config_sum_balls'] = 'Выберите способ отображения общего количества баллов';
$string['config_task_count_comlpited'] = 'Выберите способ отображения количества выполненных заданий';

// Блок статистики для админа курса
$string['config_admin_text'] = 'ДЛЯ АДМИНИСТРАТОРА';

$string['config_mean_value_for_course'] = 'Выберите способ отображения средней оценки по курсу';
$string['config_yes_no_unactive_users'] = 'Учитывать неактивных пользователей';
$string['config_user_statistics'] = 'Выберите студента для отображения его статистики';
$string['config_show_user_mean_value'] = 'Показывать среднюю оценку пользователя';
$string['config_show_user_balls'] = 'Показывать баллы пользователя';
$string['config_show_user_count_tasks'] = 'Показывать количество выполненных заданий пользователем';
$string['config_show_user_rang'] = 'Показывать место пользователя в рейтинге';

// Таблица лидера
$string['config_leaderboard_header'] = 'Настройки таблицы лидеров';

$string['config_show_leaderboard'] = 'Показывать таблицу лидеров';

$string['config_rang_type'] = 'Выбирите на основе чего выстраивать рейтинг';

$string['config_max_top'] = 'Максимальное количество отображаемых записей сверху';
$string['config_max_bot'] = 'Максимальное количество отображаемых записей снизу';


/**
 * Перевод для сообщение об ошибке (edit_form)
 */
$string['config_numeric'] = 'Передано не число';
$string['config_nonzero'] = 'Число не может быть равным 0';


/**
 * Перевод для SELECT-FORM (edit_form)
 */
$string['config_select_dont_show'] = 'Не показывать';
$string['config_select_complite_tasks'] = 'Относительно количества пройденных заданий';
$string['config_select_all_tasks'] = 'Относительно общего количества заданий';
$string['config_select_show_both_options'] = 'Показывать оба вариант';
$string['config_select_show_total'] = 'Показывать только общее количество';
$string['config_select_show_all'] = 'Показывать все';
$string['config_select_setting_show'] = 'Настроить, что показывать';
$string['config_yes'] = 'Да';
$string['config_no'] = 'Нет';


/**
 * Перевод для типов элементов (edit_form)
 */
$string['all'] = 'Все';
$string['allelements'] = 'Общее количество';
$string['lesson'] = 'Лекции';
$string['page'] = 'Страницы';
$string['quiz'] = 'Тесты';
$string['assign'] = 'Задания';


/**
 * Перевод для описания средней оценки для админитратора 
 */
$string['description_count_tasks_with_inactive_users'] = 'Относительно количества пройденных заданий c учетом неактивных пользователей';
$string['description_count_tasks_without_inactive_users'] = 'Относительно количества пройденных заданий без учета неактивных пользователей';
$string['description_max_count_tasks_with_inactive_users'] = 'Относительно общего количества заданий с учетом неактивных пользователей';
$string['description_max_count_tasks_withpout_inactive_users'] = 'Относительно общего количества заданий без учета неактивных пользователей';


/**
 * Перевод для блока
 */
$string['block_statistics_title'] = 'Cтатистика';
$string['block_statistics_balls'] = 'Всего баллов';
$string['block_statistics_mean_grade'] = 'Ср. оценка';
$string['block_statistics_count_complited_tasks'] = 'Количество завершенных заданий';
$string['block_statistics_title_for_user'] = 'Статистика для пользователя';

$string['block_statistics_mean_grade_for_course'] = 'Ср. оценка по курсу';
$string['block_statirang_for_admin'] = "Место в рейтинге";

$string['block_leaderboard_title'] = 'Таблица лидеров';
$string['block_leaderboard_username'] = 'Имя';
$string['block_leaderboard_balls'] = 'Баллы';