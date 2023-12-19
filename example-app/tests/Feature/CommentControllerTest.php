<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function 댓글을_작성할_수_있다(): void
    {
        $user = User::factory()->create();

        $article = Article::factory()->create();

        $payload = ['article_id' => $article->id, 'body' => 'hello'];

        $this->actingAs($user)
            ->post(route('comments.store'), ['article_id' => $article->id, 'body' => 'hello'])
            ->assertStatus(302)
            ->assertRedirectToRoute('articles.show', ['article' => $article->id]);

        $this->assertDatabaseHas('comments', $payload);
    }
}
