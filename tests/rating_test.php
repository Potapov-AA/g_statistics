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
 * Rating testcase
 *
 * @package    block_g_statistics
 * @category   test
 * @copyright 2024 Streje
 * @author    Alexander Potapov <san_sanih99@mail.ru>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_g_statistics;

use block_g_statistics\rating;

class rating_test extends \advanced_testcase {

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
     * Тест на таблицы рейтинга в зависимости от типа модуля
     * 
     * Проверяется для всех пользователей на курсе
     *  user1 
     *      Общее количество баллов 300, 200 за тесты, 100 за лекции.
     *  user2
     *      Общее количество баллов 240, 160 за тесты, 80 за лекции.
     *  user3
     *      Общее количество баллов 120, 120 за тесты.
     * 
     * Неактивные пользователи должны иметь ранг ###
     * 
     * Всего пользователей 5
     * 
     * Проверяется, что изменяется количество баллов в рейтинге, в зависимости от типа
     * 
     * Проверяется, что количество записей в рейтинге меняется при изменении границ
     */
    public function test_get_rating() {

        $rating = new rating();

        $course = $this->data['course']; // Текущий курс

        // Проверка для общего полного рейтинга
        // Должно быть 5 записей
        // user1 - 1 место И 300 баллов
        // user2 - 2 место И 240 баллов
        // user3 - 3 место И 120 баллов
        // user4 и user5 - ### место и ### баллов
        $data = $rating->get_rating($min = -1, $max = -1, $moduleid = -1, $courseid = $course->id);
        $this->assertEquals(1, $data[0]["rang"]);
        $this->assertEquals($this->data['user1']->id, $data[0]["id"]);
        $this->assertEquals(300, $data[0]["balls"]);

        $this->assertEquals(2, $data[1]["rang"]);
        $this->assertEquals($this->data['user2']->id, $data[1]["id"]);
        $this->assertEquals(240, $data[1]["balls"]);

        $this->assertEquals(3, $data[2]["rang"]);
        $this->assertEquals($this->data['user3']->id, $data[2]["id"]);
        $this->assertEquals(120, $data[2]["balls"]);

        $this->assertEquals('###', $data[3]["rang"]);
        $this->assertEquals($this->data['user4']->id, $data[3]["id"]);
        $this->assertEquals('###', $data[3]["balls"]);

        $this->assertEquals('###', $data[4]["rang"]);
        $this->assertEquals($this->data['user5']->id, $data[4]["id"]);
        $this->assertEquals('###', $data[4]["balls"]);

        // Проверка для полного рейтинга по тестам
        // Должно быть 5 записей
        // user1 - 1 место И 200 баллов
        // user2 - 2 место И 160 баллов
        // user3 - 3 место И 120 баллов
        // user4 и user5 - ### место и ### баллов
        $data = $rating->get_rating($min = -1, $max = -1, $moduleid = 17, $courseid = $course->id);
        $this->assertEquals(1, $data[0]["rang"]);
        $this->assertEquals($this->data['user1']->id, $data[0]["id"]);
        $this->assertEquals(200, $data[0]["balls"]);

        $this->assertEquals(2, $data[1]["rang"]);
        $this->assertEquals($this->data['user2']->id, $data[1]["id"]);
        $this->assertEquals(160, $data[1]["balls"]);

        $this->assertEquals(3, $data[2]["rang"]);
        $this->assertEquals($this->data['user3']->id, $data[2]["id"]);
        $this->assertEquals(120, $data[2]["balls"]);

        $this->assertEquals('###', $data[3]["rang"]);
        $this->assertEquals($this->data['user4']->id, $data[3]["id"]);
        $this->assertEquals('###', $data[3]["balls"]);

        $this->assertEquals('###', $data[4]["rang"]);
        $this->assertEquals($this->data['user5']->id, $data[4]["id"]);
        $this->assertEquals('###', $data[4]["balls"]);

        // Проверка для полного рейтинга по тестам
        // Должно быть 5 записей
        // user1 - 1 место И 100 баллов
        // user2 - 2 место И 80 баллов
        // user3 - 3 место И 120 баллов
        // user4 и user5 - ### место и ### баллов
        $data = $rating->get_rating($min = -1, $max = -1, $moduleid = 14, $courseid = $course->id);
        $this->assertEquals(1, $data[0]["rang"]);
        $this->assertEquals($this->data['user1']->id, $data[0]["id"]);
        $this->assertEquals(100, $data[0]["balls"]);

        $this->assertEquals(2, $data[1]["rang"]);
        $this->assertEquals($this->data['user2']->id, $data[1]["id"]);
        $this->assertEquals(80, $data[1]["balls"]);

        $this->assertEquals(3, $data[2]["rang"]);
        $this->assertEquals($this->data['user3']->id, $data[2]["id"]);
        $this->assertEquals(0, $data[2]["balls"]);

        $this->assertEquals('###', $data[3]["rang"]);
        $this->assertEquals($this->data['user4']->id, $data[3]["id"]);
        $this->assertEquals('###', $data[3]["balls"]);

        $this->assertEquals('###', $data[4]["rang"]);
        $this->assertEquals($this->data['user5']->id, $data[4]["id"]);
        $this->assertEquals('###', $data[4]["balls"]);

        // Проверка на срез рейтинга $min=1 и $max=1
        // Должно быть 3 записи
        $data = $rating->get_rating($min = 1, $max = 1, $moduleid = -1, $courseid = $course->id);
        $this->assertCount(3, $data);
    }


    /**
     * Тест на ранг в таблице общего рейтинга
     * 
     * user1 - 1 место
     * user2 - 2 место
     * user3 - 3 место
     * user4 и user 5 - ###
     */
    public function test_get_user_rang() {

        $rating = new rating();

        $course = $this->data['course']; // Текущий курс

        $rang = $rating->get_user_rang($this->data['user1']->id, $course->id);
        $this->assertEquals(1, $rang);

        $rang = $rating->get_user_rang($this->data['user2']->id, $course->id);
        $this->assertEquals(2, $rang);

        $rang = $rating->get_user_rang($this->data['user3']->id, $course->id);
        $this->assertEquals(3, $rang);

        $rang = $rating->get_user_rang($this->data['user4']->id, $course->id);
        $this->assertEquals('###', $rang);

        $rang = $rating->get_user_rang($this->data['user5']->id, $course->id);
        $this->assertEquals('###', $rang);
    }
}