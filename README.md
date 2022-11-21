

## About Larateach

Larateach is written to utilise the Wonde API.

### User Story:
As a Teacher I want to be able to see which students are in my class each day of the week so that I can be suitably 
prepared.

## Artisan command
The signature is: 
```php artisan students:get <employeeId>```

 
## Todo
* don't like the nested foreach (Uncle Bob wouldn't either).
* in the real world the service class would have an interface.
* handle the Wonde API not being available.
* environment variable should be accessed using config

## Files

TeacherTest.php - some quick tests to check that I could connect to Wonde
and to see what wes being retrieved.
TestStudentsService.php - unit tests used to drive the development of the service.
StudentsService.php - the service which makes the call to Wonde.
GetService.php - an Artisan command which utilises the service.

