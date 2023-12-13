<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    /**
     * @test
     */
    public function 글쓰기_넘어감(): void
    {
        $this->get(route('articles.create'))
        ->assertStatus(200)
        ->assertSee('글쓰기');
    }

    /**
     * @test
     */
    public function 글을_작성함(): void
    {
        $testData = [
            'body' => 'test article'
        ];
        $user = User::factory()->create();
        $this->actingAs($user)
        ->post(route('articles.store'), $testData)
        // ->assertSuccessful();
        ->assertRedirect(route('articles.index'));

        $this->assertDatabaseHas('articles', $testData);
    }
    
}
