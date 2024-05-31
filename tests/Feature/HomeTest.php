<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class HomeTest extends TestCase
{
    public function testAccessLoginPage()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function testSuccessesLogin()
    {
        $response = $this->post('/login', [
            '_token' => csrf_token(),
            'email' => 'alice@mail.com',
            'password' => '123456',
        ]);

        $response->assertStatus(302);
    }

    public function testFieldLogin()
    {
        $response = $this->post('/login', [
            '_token' => csrf_token(),
            'email' => 'not_found@gmail.com',
            'password' => '123456',
        ]);

        $response->assertSessionHasErrors(['email']);
        $response->assertStatus(302);
    }

    public function testSuccessUploadImage()
    {
        $this->actingAs(User::find(1));

        $response = $this->post('/upload', [
            '_token' => csrf_token(),
            'image' => UploadedFile::fake()->image('image.jpg'),
            'caption' => 'Hello World!',
        ]);

        $response->assertStatus(302)
            ->assertRedirectToRoute('main-show');
    }

    public function testFailedCaption()
    {
        $this->actingAs(User::find(1));

        $response = $this->post('/upload', [
            '_token' => csrf_token(),
            'image' => UploadedFile::fake()->image('image.jpg'),
        ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['caption']);
    }

    public function testFailUploadFileBig()
    {
        $this->actingAs(User::find(1));

        $response = $this->post('/upload', [
            '_token' => csrf_token(),
            'image' => UploadedFile::fake()->create('image.jpg', 5000, 'image/jpeg'),
            'caption' => 'Hello World!',
        ]);

        $response->assertStatus(302)
            ->assertSessionHasErrors(['image']);
    }

    public function testSuccessAccessMain()
    {
        $this->actingAs(User::find(1));

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testUnauthorizedAccess()
    {
        $response = $this->get('/');
        $response->assertStatus(302)
            ->assertRedirect(route('login-show'));
    }


}
