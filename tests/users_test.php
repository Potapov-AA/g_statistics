<?php

namespace block_g_statistics;

use block_g_statistics\users;

class users_test extends \advanced_testcase {

    protected $data;

    /**
     * Предподготовка к тестам, создание курсов и зачисление на них пользователей
     */
    protected function setUp(): void {

        $generator = $this->getDataGenerator()->get_plugin_generator('block_g_statistics');
        $this->data = $generator->create_logged_in_users();

        $this->resetAfterTest(true);
    }


    /**
     * Тест на получение индитификатора роли пользователя на курсе
     * 
     * Проверяется корректность индитификатора роли и также количество пользователей с данными ролями: 
     * для студентов индитификатор роли - 5
     * для ассистента индитификатор роли - 4
     * для учителя индитификатор роли - 3
     */
    public function test_get_user_roleid() {

        global $DB;

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

    
}