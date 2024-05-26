<?php

defined('MOODLE_INTERNAL') || die();


class block_g_statistics_generator extends testing_block_generator {

    /**
     * TODO: СДЕЛАТЬ НОРМАЛЬНЫЙ КОММЕНТАРИЙ И ПЕРЕВОД
     * Create (simulated) logged in users and add some of them to groups in a course
     */
    public function create_logged_in_users() {
        global $DB;

        $generator = advanced_testcase::getDataGenerator();
        $data = array();

        // Создание тестового курса
        $course = $generator->create_course();
        $data['course'] = $course;

        // Создание 5-ти (смоделированных) вошедших в систему пользователей, зарегистрированных в $course с ролью студент
        for ($i = 1; $i <= 5; $i++) {
            $user = $generator->create_user();
            $DB->set_field('user', 'lastaccess', time(), array('id' => $user->id));
            $generator->enrol_user($user->id, $course->id);
            $DB->insert_record('user_lastaccess', array('userid' => $user->id, 'courseid' => $course->id, 'timeaccess' => time()));
            $data['user' . $i] = $user;
        }

        // Создание 2-х (смоделированных) вошедших в систему пользователей, зарегистрированных в $course с ролью учитель
        for ($i = 6; $i <= 7; $i++) {
            $user = $generator->create_user();
            $DB->set_field('user', 'lastaccess', time(), array('id' => $user->id));
            $generator->enrol_user($user->id, $course->id, 3);
            $DB->insert_record('user_lastaccess', array('userid' => $user->id, 'courseid' => $course->id, 'timeaccess' => time()));
            $data['user' . $i] = $user;
        }

        // Создание 2-х (смоделированных) вошедших в систему пользователей, зарегистрированных в $course с ролью ассистент
        for ($i = 8; $i <= 9; $i++) {
            $user = $generator->create_user();
            $DB->set_field('user', 'lastaccess', time(), array('id' => $user->id));
            $generator->enrol_user($user->id, $course->id, 4);
            $DB->insert_record('user_lastaccess', array('userid' => $user->id, 'courseid' => $course->id, 'timeaccess' => time()));
            $data['user' . $i] = $user;
        }

        // Создание вошедшего в систему пользователя и не зарегестрированного ни на каком курсе
        $user = $generator->create_user();
        $DB->set_field('user', 'lastaccess', time(), array('id' => $user->id));
        $data['user10'] = $user;

        return $data;
    }
}