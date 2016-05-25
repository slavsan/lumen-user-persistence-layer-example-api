<?php

use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function testFetchAllUsers()
    {
        factory('App\User')->create();
        factory('App\User')->create([
            'email' => 'jane.doe@example.com'
        ]);

        $this->get('/api/v1/users')
            ->seeJsonContains([
                'email' => 'john.doe@example.com',
            ])
            ->seeJsonContains([
                'email' => 'jane.doe@example.com',
            ]);
    }

    public function testFetchOneUser()
    {
        factory('App\User')->create();

        $this->get('/api/v1/users/1')
            ->seeJsonContains([
                'email' => 'john.doe@example.com',
            ]);
    }

    public function testUserDeletion()
    {
        factory('App\User')->create();

        $this->delete('/api/v1/users/1')
            ->isEmpty();

        $this->notSeeInDatabase('user', ['email' => 'john.doe@example.com']);
    }

    public function testSuccessfulUserCreation()
    {
        $user = [
            'forename' => 'John',
            'surname' => 'Doe',
            'email' => 'john.doe@example.com',
            'created' => '2016-05-03 10:21:50',
        ];

        $this->json('POST', '/api/v1/users', $user)
            ->assertResponseStatus(Response::HTTP_CREATED);

        $this->seeInDatabase('user', ['email' => 'john.doe@example.com']);
    }

    public function testUserCreationWhenInvalidDataIsProvidedShouldReturnErrors()
    {
        $user = [];

        $this->json('POST', '/api/v1/users', $user)
            ->seeJsonEquals([
                'email' => ['The email field is required.'],
                'forename' => ['The forename field is required.'],
                'surname' => ['The surname field is required.'],
            ])
            ->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUnsuccessfulUserCreationWhenValidationFails()
    {
        $user = [
            'email' => 'invalid email',
            'forename' => 'inv4lid',
            'surname' => 'a',
        ];

        $this->json('POST', '/api/v1/users', $user)
            ->seeJsonEquals([
                'email' => ['The email must be a valid email address.'],
                'forename' => ['The forename may only contain letters.'],
                'surname' => ['The surname must be at least 2 characters.'],
            ])
            ->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUnsuccessfulUserCreationWhenEmailIsAlreadyTaken()
    {
        factory('App\User')->create();

        $user = [
            'email' => 'john.doe@example.com',
            'forename' => 'John',
            'surname' => 'Doe',
        ];

        $this->json('POST', '/api/v1/users', $user)
            ->seeJsonEquals([
                'email' => ['The email has already been taken.'],
            ])
            ->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testSuccessfulUserUpdate()
    {
        factory('App\User')->create();

        $userUpdate = [
            'forename' => 'Peter',
            'surname' => 'Griffin',
            'email' => 'peter.griffin@example.com',
            'created' => '2016-05-03 10:21:50',
        ];

        $this->json('PUT', '/api/v1/users/1', $userUpdate)
            ->seeJson([
                'forename' => 'Peter',
                'email' => 'peter.griffin@example.com',
            ]);
    }

    public function testUserUpdateWhenInvalidDataIsProvided()
    {
        factory('App\User')->create();
        
        $userUpdate = [
            'forename' => 'Peter',
            'surname' => 'inv4lid',
            'email' => 'peter.griffin@example.com',
            'created' => '2016-05-03 10:21:50',
        ];

        $this->json('PUT', '/api/v1/users/1', $userUpdate)
            ->seeJson([
                'surname' => ['The surname may only contain letters.'],
            ])
            ->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}