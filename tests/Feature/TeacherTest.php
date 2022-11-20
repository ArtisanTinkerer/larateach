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
     * @var
     */
    private $school;

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



    // As a Teacher I want to be able to see which students are in my class each day of the week so that I can be
    // suitably prepared.

    //Full documentation on the Wonde API can be found here:
    //https://wonde.com/docs/api/1.0/
    //We also have a PHP client SDK that you can use:
    //https://github.com/wondeltd/php-client
    //We expect you will need to focus on the following APIs in particular:
    //● Authentication https://docs.wonde.com/docs/api/sync#authentication
    //● Employees https://docs.wonde.com/docs/api/sync#employees
    //● Classes https://docs.wonde.com/docs/api/sync#classes
    //We have a test school (Wonde Testing School ID: A1930499544) for you use.

    //focus on:
    // auth []
    // employees []
    // classes []

//curl https://api.wonde.com/v1.0 \
//-H "Authorization: Bearer 7e76896f9aca62569048c667db292d72dd84f224"

    // can auth [x]

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

    //sdk has classes index
    //and teachers index

    //exception handing[]

    //teacher wants to see who is in class
    //each day of week

    // could use GET Employees
    // will return classes

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

    //get the lessons for an employee  (lessons have the date)
    //lesson has one class
    //class has many students

    /** @test */
    public function can_get_it_all()
    {
        //Please be aware that the lessons endpoint will only return one academic week of data at a time. Even in the
        //event of specifying the ‘lessons_start_after’ paramater you would only return that academic week.

        $client = new Client(env('WONDE_TOKEN'));
        $school = $client->school(env('TEST_SCHOOL_ID'));

        $employeeId = 'A2082387062';

        $classes = $school->employees->get($employeeId, ['classes.lessons'])
            ->classes
            ->data;

        $studentsDays = [];
        //iterate the classes to get the lessons
        foreach ($classes as $class) {
            foreach ($class->lessons->data as $lesson) { //better way to do this
                //get the day from the lesson
                $day      = Carbon::parse($lesson->start_at->date)->format('l');
                $students = $school
                    ->classes
                    ->get($class->id, ['students'])
                    ->students
                    ->data;

                $studentDays[$day]['students'] = $students;
            }

            $this->markTestIncomplete();
        }
    }
}
