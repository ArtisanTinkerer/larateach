<?php

namespace App\Services;

use Wonde\Client;
use Carbon\Carbon;

class StudentsService
{
    /**
     * @var \Wonde\Endpoints\Schools
     */
    private $school;

    /**
     * @var
     */
    private $classes;

    /**
     * @var string
     */
    public $errorMessage;

    /**
     * @throws \Wonde\Exceptions\InvalidTokenException
     */
    public function __construct()
    {
        $client       = new Client(env('WONDE_TOKEN'));
        $this->school = $client->school(env('TEST_SCHOOL_ID'));
    }

    /**
     * Check that the employee exists (in this school)
     * so that we can get a nice message.
     *
     * @param $employeeId
     *
     * @return bool
     */
    private function validateEmployee($employeeId): bool
    {
        try{
            $employee = $this->school->employees->get($employeeId);
        }catch(\Exception $e){
            return false;
        }
        return true;
    }

    /**
     * Get all the students which a teacher will see this week.
     *
     * @param string $employeeId
     *
     * @return array
     */
    public function getStudentsForTeacher(string $employeeId): array
    {
        if (!$this->validateEmployee($employeeId)){
            $this->errorMessage = 'Invalid employee ID';
            return [];
        }

        $this->getClasses($employeeId);

        $studentsDays = [];
        foreach ($this->classes as $class) {
            foreach ($class->lessons->data as $lesson) { //better way to do this?
                //get the day from the lesson
                $day      = Carbon::parse($lesson->start_at->date)->dayOfWeek; // 0 = sunday
                $students = $this->getStudentsForClass($class->id);

                $studentsDays[$day]['students'] = array_merge($studentsDays[$day]['students'] ?? [], $students);
            }
        }

        return $this->getUniqueStudentDays($studentsDays);
    }

    /**
     * Get the students for a classId.
     *
     * @param string $classId
     *
     * @return mixed
     */
    private function getStudentsForClass(string $classId): mixed
    {
        return $this->school
            ->classes
            ->get($classId, ['students'])
            ->students
            ->data;
    }

    /**
     * Get the classes for this employee.
     *
     * @param string $employeeId
     *
     * @return void
     */
    private function getClasses(string $employeeId): void
    {
        $this->classes = $this->school->employees->get($employeeId, ['classes.lessons'])
            ->classes
            ->data;
    }

    /**
     * Students could be duplicated.
     * Iterate thorough each day and make unique.
     *
     * @param array $studentsDays
     *
     * @return array
     */
    private function getUniqueStudentDays(array $studentsDays): array
    {
        $uniqueStudentDays = [];
        //make unique now
        foreach ($studentsDays as $key => $value) {
            $students                = collect($value['students']);
            $uniqueStudents          = $students->unique('id');
            $uniqueStudentDays[$key] = $uniqueStudents;
        }
        ksort($uniqueStudentDays);

        return $uniqueStudentDays;
    }
}
