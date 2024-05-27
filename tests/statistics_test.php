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
 * Statistics testcase
 *
 * @package    block_g_statistics
 * @category   test
 * @copyright 2024 Streje
 * @author    Alexander Potapov <san_sanih99@mail.ru>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_g_statistics;

use block_g_statistics\statistics;

class statistics_test extends \advanced_testcase {

    protected $data;

    /**
     * Предподготовка к тестам, создание курсов и зачисление на них пользователей
     */
    protected function setUp(): void {

        $generator = $this->getDataGenerator()->get_plugin_generator('block_g_statistics');
        $this->data = $generator->course_fill();

        $this->resetAfterTest(true);
    }


    /**
     * Тест на получение корректной средней оценки по курсу
     * 
     * Проверяется при следующих условиях:
     *  Всего на курсе 5 студентов, из которых 3 актинвых и у которых выполнены некоторые задния
     *  Всего есть 5 оцениваемых тестов по 100 баллов и 2 оцениваемые лекции по 100 баллов
     * 
     * Проверяется, что будет корректная оценка при следующих условиях:
     *  относительно количества пройденных заданий с учетом неактинвых пользователей и без учета
     *  относительно общего количества заданий с учетом неактинвых пользователей и без учета
     * 
     * Проверяется, что если нет активных пользователей на курсе, то возвращается -1
     */
    public function test_get_mean_value_for_course() {

        $statistics = new statistics();

        $course = $this->data['course']; // Текущий курс

        // Проверка относительно количества пройденных заданий без учета неактивных пользователей
        // user1 - 100
        // user2 - 80
        // user3 - 60
        // (100 + 80 + 60) / 3 = 80
        $mean_value_for_course = $statistics->get_mean_value_for_course(2, false, $course->id);
        $this->assertEquals(80, $mean_value_for_course);

        // Проверка отностильно количества пройденных заданий с учетом неактивных пользователей
        // (100 + 80 + 60) / 5 = 48
        $mean_value_for_course = $statistics->get_mean_value_for_course(2, true, $course->id);
        $this->assertEquals(48, $mean_value_for_course);

        // Проверка отностильно относительно общего количества заданий без учета неактивных пользователей
        // user1 - 43
        // user2 - 34
        // user3 - 17
        // (43 + 34 + 17) / 3 = 31
        $mean_value_for_course = $statistics->get_mean_value_for_course(3, false, $course->id);
        $this->assertEquals(31, $mean_value_for_course);

        // Проверка отностильно относительно общего количества заданий с учетом неактивных пользователей
        // (43 + 34 + 17) / 5 = 19
        $mean_value_for_course = $statistics->get_mean_value_for_course(3, true, $course->id);
        $this->assertEquals(19, $mean_value_for_course);

        // Проверяется, что если нет активных пользователей на курсе, то возвращается -1
        $course_without_users = $this->data['course_without_users'];
        $mean_value_for_course = $statistics->get_mean_value_for_course(2, false, $course_without_users->id);
        $this->assertEquals(-1, $mean_value_for_course);
    }


    /**
     * Тест на получение корректной средней оценки по курсу для пользователя
     * 
     * Проверяется для активных пользователей на курсе:
     *  user1
     *  user2
     *  user3
     * 
     * Всего есть 5 оцениваемых тестов по 100 баллов и 2 оцениваемые лекции по 100 баллов
     * 
     * Проверяется, что будет корректная оценка при следующих условиях:
     *  относительно количества пройденных заданий
     *  относительно общего количества заданий
     * 
     * Проверяется, что если пользователь не активный, то вренется -1
     */
    public function test_get_mean_value() {

        $statistics = new statistics();

        $course = $this->data['course']; // Текущий курс

        $user1 = $this->data['user1'];
        $user2 = $this->data['user2'];
        $user3 = $this->data['user3'];
        $user4 = $this->data['user4'];

        // Относительно количества пройденных заданий
        // user1 - 100
        // user2 - 80
        // user3 - 60
        // user4 - -1
        $mean_value = $statistics->get_mean_value(2, $user1->id, $course->id);
        $this->assertEquals(100, $mean_value);

        $mean_value = $statistics->get_mean_value(2, $user2->id, $course->id);
        $this->assertEquals(80, $mean_value);

        $mean_value = $statistics->get_mean_value(2, $user3->id, $course->id);
        $this->assertEquals(60, $mean_value);

        $mean_value = $statistics->get_mean_value(2, $user4->id, $course->id);
        $this->assertEquals(-1, $mean_value);


        // Относительно общего количества заданий
        // user1 - 43
        // user2 - 34
        // user3 - 17
        // user4 - -1
        $mean_value = $statistics->get_mean_value(3, $user1->id, $course->id);
        $this->assertEquals(43, $mean_value);

        $mean_value = $statistics->get_mean_value(3, $user2->id, $course->id);
        $this->assertEquals(34, $mean_value);

        $mean_value = $statistics->get_mean_value(3, $user3->id, $course->id);
        $this->assertEquals(17, $mean_value);

        $mean_value = $statistics->get_mean_value(3, $user4->id, $course->id);
        $this->assertEquals(-1, $mean_value);
    }


    /**
     * Тест на получение количество баллов для пользователя
     * 
     * Проверяется для активных пользователей на курсе:
     *  user1
     *  user2
     *  user3
     * 
     * Всего есть 5 оцениваемых тестов по 100 баллов и 2 оцениваемые лекции по 100 баллов
     * 
     * Проверяется, что будет корректная оценка при следующих условиях:
     *  относительно количества пройденных заданий
     *  относительно общего количества заданий
     * 
     * Проверяется, что если пользователь не активный, то вренется -1
     */
    public function test_get_balls() {

        $statistics = new statistics();

        $course = $this->data['course']; // Текущий курс

        $user1 = $this->data['user1'];
        $user2 = $this->data['user2'];
        $user3 = $this->data['user3'];
        $user4 = $this->data['user4'];

         // Относительно количества пройденных заданий
        // user1 - 300/300
        // user2 - 240/300
        // user3 - 120/200
        // user4 - -1
        $balls = $statistics->get_balls(2, $user1->id, $course->id);
        $this->assertEquals('300/300', $balls);

        $balls = $statistics->get_balls(2, $user2->id, $course->id);
        $this->assertEquals('240/300', $balls);

        $balls = $statistics->get_balls(2, $user3->id, $course->id);
        $this->assertEquals('120/200', $balls);

        $balls = $statistics->get_balls(2, $user4->id, $course->id);
        $this->assertEquals(-1, $balls);


        // Относительно общего количества заданий
        // user1 - 300/700
        // user2 - 240/700
        // user3 - 120/700
        // user4 - -1
        $balls = $statistics->get_balls(3, $user1->id, $course->id);
        $this->assertEquals('300/700', $balls);

        $balls = $statistics->get_balls(3, $user2->id, $course->id);
        $this->assertEquals('240/700', $balls);

        $balls = $statistics->get_balls(3, $user3->id, $course->id);
        $this->assertEquals('120/700', $balls);

        $balls = $statistics->get_balls(3, $user4->id, $course->id);
        $this->assertEquals(-1, $balls);
    }


    /**
     * Тест на получение количество выполненных заданий за пользователя
     * 
     * Проверяется для активных пользователей на курсе:
     *  user1 - выполненно 2 теста и 1 лекция
     *  user2 - выполненно 2 теста и 1 лекция
     *  user3 - выполненно 2 теста
     *  user4 - 0 выполненных задач
     * 
     * Всего задач, которые можно выполнить на курсе 7 (5 тестов и 2 лекции)
     * 
     * Проверяется корректность отображения количества выполнненых задач на курсе:
     *  Общее количество задач
     *  Количество тестов
     *  Количество лекций
     */
    public function test_get_count_complited_tasks() {

        $statistics = new statistics();

        $course = $this->data['course']; // Текущий курс

        $user1 = $this->data['user1'];
        $user2 = $this->data['user2'];
        $user3 = $this->data['user3'];
        $user4 = $this->data['user4'];

        // Проверка для user1
        // Должно быть выоленно 2/5 тестов
        // Должно быть выоленно 1/2 лекций
        // Всего выполненно 3/7 задач
        $data = $statistics->get_count_complited_tasks(17, $user1->id, $course->id);
        $this->assertEquals('2/5', $data);
        $data = $statistics->get_count_complited_tasks(14, $user1->id, $course->id);
        $this->assertEquals('1/2', $data);
        $data = $statistics->get_count_complited_tasks(-1, $user1->id, $course->id);
        $this->assertEquals('3/7', $data);

        // Проверка для user2
        // Должно быть выоленно 2/5 тестов
        // Должно быть выоленно 1/2 лекций
        // Всего выполненно 3/7 задач
        $data = $statistics->get_count_complited_tasks(17, $user2->id, $course->id);
        $this->assertEquals('2/5', $data);
        $data = $statistics->get_count_complited_tasks(14, $user2->id, $course->id);
        $this->assertEquals('1/2', $data);
        $data = $statistics->get_count_complited_tasks(-1, $user2->id, $course->id);
        $this->assertEquals('3/7', $data);

        // Проверка для user3
        // Должно быть выоленно 2/5 тестов
        // Должно быть выоленно 0/2 лекций
        // Всего выполненно 2/7 задач
        $data = $statistics->get_count_complited_tasks(17, $user3->id, $course->id);
        $this->assertEquals('2/5', $data);
        $data = $statistics->get_count_complited_tasks(14, $user3->id, $course->id);
        $this->assertEquals('0/2', $data);
        $data = $statistics->get_count_complited_tasks(-1, $user3->id, $course->id);
        $this->assertEquals('2/7', $data);

        // Проверка для user4
        // Должно быть выоленно 0/5 тестов
        // Должно быть выоленно 0/2 лекций
        // Всего выполненно 0/7 задач
        $data = $statistics->get_count_complited_tasks(17, $user4->id, $course->id);
        $this->assertEquals('0/5', $data);
        $data = $statistics->get_count_complited_tasks(14, $user4->id, $course->id);
        $this->assertEquals('0/2', $data);
        $data = $statistics->get_count_complited_tasks(-1, $user4->id, $course->id);
        $this->assertEquals('0/7', $data);
    }
}