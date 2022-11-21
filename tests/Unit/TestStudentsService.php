<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\StudentsService;



class TestStudentsService extends TestCase
{

    /** @test */
    public function test_teacher_can_get_students()
    {
        $service = new StudentsService();
        $studentsAndDays =  $service->getStudentsForTeacher(env('TEST_TEACHER_ID'));

        $this->assertTrue(count($studentsAndDays) > 0);
    }

    /** @test */
    public function invalid_employee_is_handled()
    {
        $service = new StudentsService();
        $studentsAndDays =  $service->getStudentsForTeacher('fdsafdsf'); //would normally use Faker

        $this->assertTrue($service->errorMessage === 'Invalid employee ID');
    }

}
