<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Todo;
use App\Models\User;
use Database\Seeders\ContactsSeeder;
use Database\Seeders\TodoSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        User::query()->delete("DELETE FROM users");
    }


    /**
     * A basic feature test example.
     */
    public function testAuthAttempt(): void
    {
        $this->seed([UserSeeder::class]);

        $response = Auth::attempt([
            "email" => "1@localhost",
            "password" => "1"
        ], true);
        self::assertTrue($response);

        $user = Auth::user();

        self::assertEquals("1@localhost", $user->email);
    }

    public function testLoginSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->post("/users/login",
        [
            "email" => "2@localhost",
            "password" => "2",
        ])
        ->assertRedirect("/users/current");
    }

    public function testCurrent()
    {

        $this->seed([UserSeeder::class]);

        $this->get("/users/current")
            ->assertRedirect("/users/login")
            ->assertDontSeeText("Hello Guest");

            $user  = User::where("email", "2@localhost")->first();
            $this->actingAs($user)
            ->get("/users/current")
            ->assertSeeText("Hello 2");

    }

    public function testGuard()
    {

        $this->seed([UserSeeder::class]);

        $this->get("/api/users/current", [
            "Authorization" => 'secret',
        ])
            ->assertStatus(200)
            ->assertSeeText("Hello 1");

    }

    public function testCustomProvider()
    {

        $this->seed([UserSeeder::class]);

        $this->get("/simple-api/users/current", [
            "Authorization" => 'secret',
        ])
            ->assertStatus(200)
            ->assertSeeText("Hello khannedy");

    }

    public function testGateTest()
    {

        $this->seed([UserSeeder::class, ContactsSeeder::class]);

        $user = User::where("email", "1@localhost")->first();

        Auth::login($user);

        $contact = Contact::where("name", "=", "test")
        ->first();

        self::assertTrue(Gate::allows("get-contact", $contact));

    }

    public function testGateFacades()
    {

        $this->seed([UserSeeder::class, ContactsSeeder::class]);

        $user = User::where("email", "1@localhost")->first();

        Auth::login($user);

        $contact = Contact::where("name", "=", "test")
            ->first();

        self::assertTrue(Gate::allows("get-contact", $contact));

        self::assertTrue(Gate::any(["delete-contact", "delete-contact", "update-contact"], $contact));
        self::assertFalse(Gate::none(["delete-contact", "delete-contact", "update-contact"], $contact));

    }

    public function testGateUser()
    {

        $this->seed([UserSeeder::class, ContactsSeeder::class]);

        $user = User::where("email", "1@localhost")->first();

        $gate = Gate::forUser($user);

        $contact = Contact::where("name", "=", "test")
            ->first();

        self::assertTrue($gate->allows("get-contact", $contact));
        self::assertTrue($gate->any(["delete-contact", "delete-contact", "update-contact"], $contact));
        self::assertFalse($gate->none(["delete-contact", "delete-contact", "update-contact"], $contact));
    }

    public function testGateResponse()
    {

        $this->seed([UserSeeder::class, ContactsSeeder::class]);

        $user = User::where("email", "1@localhost")->first();

        Auth::login($user);

        $contact = Contact::where("name", "=", "test")
            ->first();

        $response = Gate::inspect("create-contact");

        self::assertFalse($response->allowed());
        self::assertEquals("You're Not The Admin",$response->message());

    }

    public function testPolicy()
    {

        $this->seed([UserSeeder::class, TodoSeeder::class]);

        $user = User::where("email", "=", "1@localhost")->first();

        Auth::login($user);

        $todo = Todo::first();

        self::assertTrue(Gate::allows("view", $todo));
        self::assertTrue(Gate::allows("delete", $todo));

    }

    public function testAuthorizable()
    {

        $this->seed([UserSeeder::class, TodoSeeder::class]);

        $user = User::where("email", "=", "1@localhost")->first();

        $todo = Todo::first();

        self::assertTrue($user->can("view", $todo));
        self::assertTrue($user->can("delete", $todo));

    }

    public function testRequestAuthorization()
    {

        $this->seed([UserSeeder::class, TodoSeeder::class]);

        $this->post("/api/todo")
            ->assertStatus(403);

        $user = User::where("name", "admin")->first();

        $this->actingAs($user)
            ->post("/api/todo")
            ->assertStatus(200);

    }

    public function testView()
    {

        $this->seed([UserSeeder::class, TodoSeeder::class]);
//
//        $user = User::where("email", "=", "1@localhost")->first();
//
//        Auth::login($user);

        $todo = Todo::query()->get();

        $this->view("todo", [
            "todos" => $todo
        ])
            ->assertSeeText("No Edit");


    }

    public function testRegistrationGuest()
    {

        self::assertTrue(Gate::allows("create", User::class));

    }

    public function testRegistrattionUser()
    {
        $this->seed([UserSeeder::class]);

        $user = User::where("email", "1@localhost")->first();

        Auth::login($user);

        self::assertFalse(Gate::allows("create", User::class));
    }

    public function testAdmin()
    {

        $this->seed([UserSeeder::class, TodoSeeder::class]);

        $todo = Todo::first();

        $user = new User([
           "name" => "superadmin",
           "email" => "superadmin@localhost",
           "password" => Hash::make("rahasia"),
        ]);

        self::assertTrue($user->can("view", $todo));

    }


}
