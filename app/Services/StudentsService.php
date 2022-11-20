<?php

namespace App\Services;

use Wonde\Client;
use Carbon\Carbon;

class StudentsService
{
    private $school;
    private $classes;

    public function __construct()
    {
        $client = new Client(env('WONDE_TOKEN'));
        $this->school = $client->school(env('TEST_SCHOOL_ID'));
    }

    public function getStudentsForTeacher(string $employeeId): array
    {
        //validate the id

        $this->setClasses($employeeId);

        $studentsDays = [];

        foreach ($this->classes as $class) {
            foreach ($class->lessons->data as $lesson) { //better way to do this
                //get the day from the lesson
                $day      = Carbon::parse($lesson->start_at->date)->dayOfWeek; // 0 = sunday
                $students = $this->school
                    ->classes
                    ->get($class->id, ['students'])
                    ->students
                    ->data;

                $studentsDays[$day]['students'] = array_merge($studentsDays[$day]['students'] ?? [], $students);
            }
        }

        $uniqueStudentDays = [];
        //make unique now
        foreach ($studentsDays as $key => $value){
            $students = collect($value['students']);
            $uniqueStudents = $students->unique('id');
            $uniqueStudentDays[$key] = $uniqueStudents;
        }
        ksort($uniqueStudentDays);

        return $uniqueStudentDays;
    }





    private function setClasses(string $employeeId)
    {
        $this->classes = $this->school->employees->get($employeeId, ['classes.lessons'])
            ->classes
            ->data;

    }



}
