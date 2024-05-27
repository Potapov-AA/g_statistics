<?php

defined('MOODLE_INTERNAL') || die();


class block_g_statistics_generator extends testing_block_generator {

    /**
     * Заполнение тестовых данных для курсов
     */
    public function course_fill() {
        global $DB;

        $generator = advanced_testcase::getDataGenerator();
        $data = array();

        // Создание тестового курса
        $course = $generator->create_course();
        $data['course'] = $course;

        // Создание курса без пользователей
        $course_without_users = $generator->create_course();
        $data['course_without_users'] = $course_without_users;

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

        // Создание оцениваемых модулей на курсе
        // Создание максимального балла за курс
        $module = $generator->create_grade_item(array('courseid' => $course->id, 
                                                        'categotyid' => null,
                                                        'itemname' => null, 
                                                        'itemtype' => 'course', 
                                                        'itemmodule' => null,  
                                                        'gradetype' => 1, 
                                                        'grademax' => 700));

        // Создание 5-ти тестов
        for ($i = 1; $i <= 5; $i++) {
            $module = $generator->create_grade_item(array('courseid' => $course->id, 
                                                        'itemname' => 'quiz' . $i, 
                                                        'itemtype' => 'mod', 
                                                        'itemmodule' => 'quiz',  
                                                        'gradetype' => 1, 
                                                        'grademax' => 100));
            $data['quiz' . $i] = $module;
        }

        // Создание 2 лекций с оценивание
        for ($i = 1; $i <= 2; $i++) {
            $module = $generator->create_grade_item(array('courseid' => $course->id, 
                                                        'itemname' => 'lesson' . $i, 
                                                        'itemtype' => 'mod', 
                                                        'itemmodule' => 'lesson',  
                                                        'gradetype' => 1, 
                                                        'grademax' => 100));
            $data['lesson' . $i] = $module;
        }

        // Создание 2 лекций без оценивания
        for ($i = 3; $i <= 4; $i++) {
            $module = $generator->create_grade_item(array('courseid' => $course->id, 
                                                        'itemname' => 'lesson' . $i, 
                                                        'itemmodule' => 'lesson',  
                                                        'gradetype' => 0, 
                                                        'grademax' => 100));
            $data['lesson' . $i] = $module;
        }
        
        
        // Создание оценок за задания для пользователей
        // Для пользователей user1, user2, user3 есть выполненные тесты и задания
        // [USER1]
        $grade = $generator->create_grade_grade(array('itemid' => $data['quiz1']->id,
                                                    'userid' => $data['user1']->id,
                                                    'rawgrade' => 100));
        $data['grade1'] = $grade;

        $grade = $generator->create_grade_grade(array('itemid' => $data['quiz2']->id,
                                                    'userid' => $data['user1']->id,
                                                    'rawgrade' => 100));
        $data['grade2'] = $grade;

        $grade = $generator->create_grade_grade(array('itemid' => $data['lesson1']->id,
                                                    'userid' => $data['user1']->id,
                                                    'rawgrade' => 100));
        $data['grade3'] = $grade;

        $grade = $generator->create_grade_grade(array('itemid' => $data['quiz3']->id,
                                                    'userid' => $data['user1']->id,
                                                    'rawgrade' => null));

        $grade = $generator->create_grade_grade(array('itemid' => $data['quiz4']->id,
                                                    'userid' => $data['user1']->id,
                                                    'rawgrade' => null));

        $grade = $generator->create_grade_grade(array('itemid' => $data['quiz5']->id,
                                                    'userid' => $data['user1']->id,
                                                    'rawgrade' => null));

        $grade = $generator->create_grade_grade(array('itemid' => $data['lesson2']->id,
                                                    'userid' => $data['user1']->id,
                                                    'rawgrade' => null));


        // [USER2]
        $grade = $generator->create_grade_grade(array('itemid' => $data['quiz3']->id,
                                                    'userid' => $data['user2']->id,
                                                    'rawgrade' => 80));
        $data['grade4'] = $grade;

        $grade = $generator->create_grade_grade(array('itemid' => $data['quiz4']->id,
                                                    'userid' => $data['user2']->id,
                                                    'rawgrade' => 80));
        $data['grade5'] = $grade;

        $grade = $generator->create_grade_grade(array('itemid' => $data['lesson2']->id,
                                                    'userid' => $data['user2']->id,
                                                    'rawgrade' => 80));
        $data['grade6'] = $grade;

        $grade = $generator->create_grade_grade(array('itemid' => $data['quiz1']->id,
                                                    'userid' => $data['user2']->id,
                                                    'rawgrade' => null));

        $grade = $generator->create_grade_grade(array('itemid' => $data['quiz2']->id,
                                                    'userid' => $data['user2']->id,
                                                    'rawgrade' => null));

        $grade = $generator->create_grade_grade(array('itemid' => $data['quiz5']->id,
                                                    'userid' => $data['user2']->id,
                                                    'rawgrade' => null));

        $grade = $generator->create_grade_grade(array('itemid' => $data['lesson1']->id,
                                                    'userid' => $data['user2']->id,
                                                    'rawgrade' => null));

        // [USER3]
        $grade = $generator->create_grade_grade(array('itemid' => $data['quiz1']->id,
                                                    'userid' => $data['user3']->id,
                                                    'rawgrade' => 60));
        $data['grade7'] = $grade;

        $grade = $generator->create_grade_grade(array('itemid' => $data['quiz5']->id,
                                                    'userid' => $data['user3']->id,
                                                    'rawgrade' => 60));
        $data['grade8'] = $grade;

        $grade = $generator->create_grade_grade(array('itemid' => $data['quiz3']->id,
                                                    'userid' => $data['user3']->id,
                                                    'rawgrade' => null));

        $grade = $generator->create_grade_grade(array('itemid' => $data['quiz2']->id,
                                                    'userid' => $data['user3']->id,
                                                    'rawgrade' => null));

        $grade = $generator->create_grade_grade(array('itemid' => $data['quiz4']->id,
                                                    'userid' => $data['user3']->id,
                                                    'rawgrade' => null));

        $grade = $generator->create_grade_grade(array('itemid' => $data['lesson2']->id,
                                                    'userid' => $data['user3']->id,
                                                    'rawgrade' => null));

        $grade = $generator->create_grade_grade(array('itemid' => $data['lesson1']->id,
                                                    'userid' => $data['user3']->id,
                                                    'rawgrade' => null));


        // Вставка завершенных заданий
        $DB->insert_record('course_modules', array('course' => $course->id, 
                                                    'module' => 17, 
                                                    'completion' => 2));
        
        $DB->insert_record('course_modules', array('course' => $course->id, 
                                                    'module' => 17, 
                                                    'completion' => 2));

        $DB->insert_record('course_modules', array('course' => $course->id, 
                                                    'module' => 17, 
                                                    'completion' => 2));
        
        $DB->insert_record('course_modules', array('course' => $course->id, 
                                                    'module' => 17, 
                                                    'completion' => 2));
        
        $DB->insert_record('course_modules', array('course' => $course->id, 
                                                    'module' => 17, 
                                                    'completion' => 2));

        $DB->insert_record('course_modules', array('course' => $course->id, 
                                                    'module' => 14, 
                                                    'completion' => 2));

        $DB->insert_record('course_modules', array('course' => $course->id, 
                                                    'module' => 14, 
                                                    'completion' => 2));

        // Заполнение завершенных заданий пользователями
        // [USER1] 
        //      quiz1 - 207000
        //      quiz2 - 207001
        //      lesson1 - 207005
        $DB->insert_record('course_modules_completion', array('coursemoduleid' => 207000, 
                                                                'userid' => $data['user1']->id, 
                                                                'completionstate' => 1,
                                                                'timemodified' => time()));
                                                        
        $DB->insert_record('course_modules_completion', array('coursemoduleid' => 207001, 
                                                                'userid' => $data['user1']->id, 
                                                                'completionstate' => 1,
                                                                'timemodified' => time()));

        $DB->insert_record('course_modules_completion', array('coursemoduleid' => 207005, 
                                                                'userid' => $data['user1']->id, 
                                                                'completionstate' => 1,
                                                                'timemodified' => time()));

        // Заполнение завершенных заданий пользователями
        // [USER2] 
        //      quiz3 - 207002
        //      quiz4 - 207003
        //      lesson2 - 207006
        $DB->insert_record('course_modules_completion', array('coursemoduleid' => 207002, 
                                                                'userid' => $data['user2']->id, 
                                                                'completionstate' => 1,
                                                                'timemodified' => time()));
                                                        
        $DB->insert_record('course_modules_completion', array('coursemoduleid' => 207003, 
                                                                'userid' => $data['user2']->id, 
                                                                'completionstate' => 1,
                                                                'timemodified' => time()));

        $DB->insert_record('course_modules_completion', array('coursemoduleid' => 207006, 
                                                                'userid' => $data['user2']->id, 
                                                                'completionstate' => 1,
                                                                'timemodified' => time()));

        // Заполнение завершенных заданий пользователями
        // [USER3] 
        //      quiz1 - 207000
        //      quiz5 - 207004
        $DB->insert_record('course_modules_completion', array('coursemoduleid' => 207000, 
                                                                'userid' => $data['user3']->id, 
                                                                'completionstate' => 1,
                                                                'timemodified' => time()));
                                                        
        $DB->insert_record('course_modules_completion', array('coursemoduleid' => 207004, 
                                                                'userid' => $data['user3']->id, 
                                                                'completionstate' => 1,
                                                                'timemodified' => time()));                                                    

        return $data;
    }
}