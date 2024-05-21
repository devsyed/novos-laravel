<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class TextFilesTest extends TestCase
{
    /**
     * Create File
     * 
     */
    public function test_create_file(): void
    {
        $user = User::find(1);
        $this->actingAs($user);
        Storage::fake('local');

        $response = $this->postJson('/api/novos/v1/create_file', [
            'fileName' => 'Test File',
            'content' => 'Test content',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('files', ['file_name' => 'Test File']);

        Storage::disk('local')->assertExists('novos-text-files/');
    }
}
