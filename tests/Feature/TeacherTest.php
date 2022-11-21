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
    //just did a couple of tests to check that I could connect

    /** @test */
    public function can_auth_with_wonde()
    {
        $response = Http::withToken(env('WONDE_TOKEN'))
            ->get('https://api.wonde.com/v1.0'); // UK domain

        $this->assertTrue($response->ok());
    }

    /** @test */
    public function can_connect_with_client()
    {
        $client = new Client(env('WONDE_TOKEN'));
        $school = $client->schools->get(env('TEST_SCHOOL_ID')); //Stdclass when get
        $this->assertTrue($school->id === env('TEST_SCHOOL_ID'));
    }

    //test the Artisan command

    /** @test */
    public function can_get_students_with_command()
    {
        $employeeId = env('TEST_TEACHER_ID');
        $command = "students:get $employeeId";
        $this->artisan($command)
            ->assertExitCode(0);
    }
}
