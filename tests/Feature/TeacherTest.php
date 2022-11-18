<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class TeacherTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
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



    /** @test */
    public function can_auth_with_wonde()
    {
        $response = Http::withToken(env('WONDE_TOKEN'))
            ->get('https://api.wonde.com/v1.0');

        $this->assertTrue($response->ok());
    }


}
