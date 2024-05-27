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
 * Users testcase
 *
 * @package    block_g_statistics
 * @category   test
 * @copyright 2024 Streje
 * @author    Alexander Potapov <san_sanih99@mail.ru>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_g_statistics;

use block_g_statistics\users;

class users_test extends \advanced_testcase {

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
     * Тест на получение индитификатора роли пользователя на курсе
     * 
     * Проверяется корректность индитификатора роли и также количество пользователей с данными ролями: 
     *  для студентов индитификатор роли - 5
     *  для ассистента индитификатор роли - 4
     *  для учителя индитификатор роли - 3
     */
    public function test_get_user_roleid() {

        $users = new users();

        $course = $this->data['course']; // Текущий курс

        // Проверка, что пользователь user1 - является студентом
        $user = $this->data['user1'];
        $user_roleid = $users->get_user_roleid($user->id, $course->id);
        $this->assertEquals(5, $user_roleid, 'Пользователь user1 не является студентом');

        // Проверка, что пользователь user6 - является учителем
        $user = $this->data['user6'];
        $user_roleid = $users->get_user_roleid($user->id, $course->id);
        $this->assertEquals(3, $user_roleid, 'Пользователь user6 не является учителем');

        // Проверка, что пользователь user8 - является ассистентом
        $user = $this->data['user8'];
        $user_roleid = $users->get_user_roleid($user->id, $course->id);
        $this->assertEquals(4, $user_roleid, 'Пользователь user8 не является ассистентом');

        // Проверка, что на курсе 5 студентов, 2 учителя и 2 ассистента
        $count_students = 0;
        $count_teachers = 0;
        $count_assistants = 0;
        for($i = 1; $i <= 9; $i++) {
            
            $user = $this->data['user' . $i];
            $user_roleid = $users->get_user_roleid($user->id, $course->id);

            if($user_roleid == 5) $count_students++;
            if($user_roleid == 4) $count_assistants++;
            if($user_roleid == 3) $count_teachers++;
        }

        $this->assertEquals(5, $count_students, 'Количество студентов на курсе не равно 5-ти');
        $this->assertEquals(2, $count_assistants, 'Количество ассистентов на курсе не равно 5-ти');
        $this->assertEquals(2, $count_teachers, 'Количество студентов на курсе не равно 5-ти');

        // Проверка, что если студента нет на курсе вернется -1 (user10)
        $user = $this->data['user10'];
        $user_roleid = $users->get_user_roleid($user->id, $course->id);
        $this->assertEquals(-1, $user_roleid, 'Пользователь user10 возможно присутствует на курсе');
    }

    
    /**
     * Тест на получение информации пользователя на курсе
     * 
     * Проверяется корректность получаемого объекта для:
     *  пользователя находящегося на курсе
     *  пользователя не находящегося на курсе
     * 
     * Проверяется корретность существующих полей
     */
    public function test_get_user_info() {

        $users = new users();

        $course = $this->data['course']; // Текущий курс

        // Проверка для пользователя находящегося на курсе (user1)
        // Массив не должен быть пустой
        // Массив содержит следующие поля (firstname, lastname)
        $user = $this->data['user1'];
        $user_info = (array) $users->get_user_info($user->id, $course->id);

        $this->assertArrayHasKey('firstname', $user_info, 'Ключ firstname не найден');
        $this->assertArrayHasKey('lastname', $user_info, 'Ключ lastname не найден');
        $this->assertNotCount(0, $user_info, 'Массив не содержит элементов');

        // Проверка для пользователя не находящегося на курсе (user10)
        // должен быть пустой массив
        $user = $this->data['user10'];
        $user_info = (array) $users->get_user_info($user->id, $course->id);

        $this->assertEmpty($user_info);
    }


    /**
     * Тест на получение информации по всем пользователям на курсе
     * 
     * Проверяется количество студентов на курсе
     * Проверяется, что записи содержат необходимые поля 
     */
    public function test_get_users_info() {

        $users = new users();

        $course = $this->data['course']; // Текущий курс

        $users_info = $users->get_users_info($course->id);

        // Проверяется количество студентов на курсе (должно быть 5)
        $this->assertCount(5, $users_info, 'На курсе больше или меньше студентов');

        // Проверяется, что каждый из студентов содержит поля (userid, firstname, lastname)
        foreach($users_info as $user) {
            $this->assertArrayHasKey('userid', (array) $user, 'Ключ userid не найден');
            $this->assertArrayHasKey('firstname', (array) $user, 'Ключ firstname не найден');
            $this->assertArrayHasKey('lastname', (array) $user, 'Ключ lastname не найден');
        }
    }


    /**
     * Тест на получение списка активных пользователей
     * 
     * Проверяется количество активных пользователей на курсе
     */
    public function test_get_active_users() {

        $users = new users();

        $course = $this->data['course']; // Текущий курс

        $users_info = $users->get_active_users($course->id);

        // Проверка на количество активных пользователей
        $this->assertCount(3, $users_info, 'Активных студентов больше или меньше заданного числа');
    }
}