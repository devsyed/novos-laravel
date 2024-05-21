<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\File;

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

    public function test_get_file():void
    {
        $fileName = 'test_file.txt';
        Storage::put('novos-text-files/' . $fileName, 'Test content');
        $file = File::factory()->create(['file_name' => $fileName, 'ref_name' => $fileName]);
        $response = $this->getJson('/api/novos/v1/get-file/' . $fileName);
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'file_name' => $file->file_name,
                    'file_content' => 'Test content',
                ]
            ]);
    
    }
}
