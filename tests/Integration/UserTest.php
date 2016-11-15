<?php

namespace App\Test\Integration;

use App\Test\TestCase;
use App\User;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Illuminate\Http\Response;

class UserTest extends TestCase
{
    use DatabaseMigrations, DatabaseTransactions;

    public function testShouldCreateAnUser()
    {
        $this->json('POST', '/users', ['id' => 'Sally'])
            ->seeJson(['id' => 'Sally'])
            ->seeStatusCode(Response::HTTP_CREATED);

        $this->seeInDatabase('users', ['id' => 'Sally']);
    }

    public function testShouldReturnConflictWhenCreateAnExistingUser()
    {
        factory(User::class)->create(['id' => 'Sally']);

        $this->seeInDatabase('users', ['id' => 'Sally']);

        $this->json('POST', '/users', ['id' => 'Sally'])
            ->seeJson(['id' => 'Sally'])
            ->seeStatusCode(Response::HTTP_CONFLICT);
    }

    public function testShouldSuccessfulRemoveAnUser()
    {
        factory(User::class)->create(['id' => 'Sally']);

        $this->seeInDatabase('users', ['id' => 'Sally']);

        $this->json('DELETE', '/users/Sally')
            ->seeStatusCode(Response::HTTP_OK);

        $this->notSeeInDatabase('users', ['id' => 'Sally']);
    }

    public function testShouldNotRemoveAnAbsentUser()
    {
        $this->json('DELETE', '/users/Sally')
            ->seeStatusCode(Response::HTTP_NOT_FOUND);
    }
}
