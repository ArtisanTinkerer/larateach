<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Wonde\Client;
use Wonde\Endpoints\Schools;

class TeacherTest extends TestCase
{

    //normally I would mock external calls in a test
    //but this is how I started the task
    //just to check that I can get the sdk working

    /**
     * Make a Wonde school.
     * Can't do this in setUp because we won't be able to access env()
     *
     * @return void
     * @throws \Wonde\Exceptions\InvalidTokenException
     */
    private function makeSchool(): void
    {
        $client       = new Client(env('WONDE_TOKEN'));
        $this->school = $client->school(env('TEST_SCHOOL_ID'));
    }


    /** @test */
    public function can_auth_with_wonde()
    {
        $response = Http::withToken(env('WONDE_TOKEN'))
            ->get('https://api.wonde.com/v1.0'); // UK domain

        $this->assertTrue($response->ok());
    }

    // look at the sdk[x]

    /** @test */
    public function can_connect_with_client()
    {
        $client = new Client(env('WONDE_TOKEN'));
        $school = $client->schools->get(env('TEST_SCHOOL_ID')); //Stdclass when get
        $this->assertTrue($school->id === env('TEST_SCHOOL_ID'));
    }

    //grab and employee for testsing

    /** @test */
    public function can_get_employees()
    {
        $this->makeSchool();
        //use the iterator
        foreach ($this->school->employees->all() as $employee) {
            echo $employee->forename . ' ' . $employee->surname . PHP_EOL;
        }

        $this->markTestSkipped();
    }

    //now I have an employee

    //result = {stdClass} [15]
    // id = "A571916931"
    // upi = "52d15453c0b645a9aef90b1f94446da5"
    // mis_id = "6910"
    // initials = "KW"
    // title = "Miss"
    // surname = "Wells"
    // forename = "Kris"
    // middle_names = null
    // legal_surname = "Wells"
    // legal_forename = "Kris"
    // gender = null
    // date_of_birth = null
    // restored_at = null

    /** @test */
    public function can_get_it_all()
    {
        //Please be aware that the lessons endpoint will only return one academic week of data at a time. Even in the
        //event of specifying the ‘lessons_start_after’ paramater you would only return that academic week.

        $client = new Client(env('WONDE_TOKEN'));
        $school = $client->school(env('TEST_SCHOOL_ID'));

        $employeeId = env('TEST_TEACHER_ID');

        $classes = $school->employees->get($employeeId, ['classes.lessons'])
            ->classes
            ->data;

        //employee has many classes
        // class has many lessons
        // get classes for an employee
        // get lessons or each class
        //       lesson has start_at - to get day
        // get the students from the class


        //something is slow here
        $studentsDays = [];
        //iterate the classes to get the lessons
        foreach ($classes as $class) {
            foreach ($class->lessons->data as $lesson) { //better way to do this
                //get the day from the lesson
                $day      = Carbon::parse($lesson->start_at->date)->dayOfWeekIso;
                $students = $school
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

        $this->markTestIncomplete();

            $this->markTestIncomplete();
    }

}
