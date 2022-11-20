

## About Larateach

Larateach is written to utilise the Wonde API.

### User Story:
As a Teacher I want to be able to see which students are in my class each day of the week so that I can be suitably 
prepared.

## Development

## Exploratory step
Initially I started in TeacherTest.php because I normally TDD. This is my 'rough work' which I would normally delete.
I just wanted to check that I could:
* Auth with the Wonde API.
* Connect with the SDK.
* Work out the logic and which calls were required.
* I was stuck here for a while because I thought that there would be an easier way to access the lessons.
* I don't really like the nested foreach and ideally I would like to remove the iteration.
* Normally I would be mocking external api calls

## Extracted StudentService.php
* Extracted the code out, so that it could be accessed from an endpoint (for the FE to access) or command.
* I didn't add an interface but probably would in a real project.
* Created TeacherStudentService unit test just to TDD the service.


## Artisan command
The signature is: 
```php artisan students:get <employeeId>```

## ToDo
Remove the iteration. 
