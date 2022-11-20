<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\StudentsService;

class GetStudents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'students:get {employeeId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets the students that a teacher will see that day';

    /**
     * Execute the console command.
     *
     * @param StudentsService $service
     *
     * @return int
     */
    public function handle(StudentsService $service): int
    {
        //could use an interface ^ but overkill for this task

        $studentsAndDays =  $service->getStudentsForTeacher($this->argument('employeeId'));
        if($service->errorMessage !=''){
            $this->error($service->errorMessage);
            return Command::FAILURE;
        }

        $this->info('Your students will be:');
        foreach ($studentsAndDays as $key => $value){
            $day = jddayofweek($key-1,1); //0 is monday
            $this->error("Day: $day -------------------------------------------------------");
            foreach($value as $student){
                $this->info("$student->forename $student->surname");
            }
        }

        return Command::SUCCESS;
    }
}
