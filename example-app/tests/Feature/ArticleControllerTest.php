<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function 로그인후_글쓰기_넘어감(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('articles.create'))
            ->assertStatus(200)
            ->assertSee('글쓰기');
    }

    /**
     * @test
     */
    public function 로그인_안하면_글쓰기로_못넘어감(): void
    {
        $this
            ->get(route('articles.create'))
            ->assertStatus(302)
            ->assertRedirectToRoute('login');
    }

    /**
     * @test
     */
    public function 글을_작성함(): void
    {
        $testData = [
            'body' => 'test article',
        ];
        $user = User::factory()->create();
        $this->actingAs($user)
            ->post(route('articles.store'), $testData)
            // ->assertSuccessful();
            ->assertRedirect(route('articles.index'));

        $this->assertDatabaseHas('articles', $testData);
    }
    /**
     * @test
     */
    public function 로그인_안하면_글작성_못함(): void
    {
        $testData = [
            'body' => 'test article',
        ];
        $this
            ->post(route('articles.store'), $testData)
            ->assertRedirectToRoute('login');

        $this->assertDatabaseMissing('articles', $testData);
    }

    /**
     * @test
     */
    public function 로그인_해야_글수정_화면보임(): void
    {
        $user = User::factory()->create();

        $article = Article::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get(route('articles.edit', ['article' => $article->id]))
            ->assertStatus(200)
            ->assertSee('수정하기');
    }

    /**
     * @test
     */
    public function 로그인_안하면_글수정_안보임(): void
    {
        $article = Article::factory()->create();

        $this
            ->get(route('articles.edit', ['article' => $article->id]))
            ->assertStatus(302)
            ->assertRedirectToRoute('login');
    }

    /**
     * @test
     */
    public function 글_목록을_확인한다(): void
    {
        $now = Carbon::now();
        $afterOneSecond = (clone $now)->addSecond();

        $article1 = Article::factory()->create(
            ['created_at' => $now]
        );
        $article2 = Article::factory()->create(
            ['created_at' => $afterOneSecond]
        );

        $this
            ->get(route('articles.index'))
            ->assertSeeInOrder([
                $article2->body,
                $article1->body
            ]);
    }

    /**
     * @test
     */
    public function 개별_글을_조회가능(): void
    {
        $article = Article::factory()->create();

        $this
            ->get(route('articles.show', ['article' => $article->id]))
            ->assertSuccessful()
            ->assertSee($article->body);
    }


    /**
     * @test
     */
    public function 로그인해야_글을_수정함(): void
    {
        $user = User::factory()->create();

        $payload = ['body' => '수정된 글'];

        $article = Article::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->patch(
                route('articles.update', ['article' => $article->id]),
                $payload
            )->assertRedirect(route('articles.index'));

        $this->assertDatabaseHas('articles', $payload);
        $this->assertEquals($payload['body'], $article->refresh()->body);
    }


    /**
     * @test
     */
    public function 로그인안하면_글을_수정못함(): void
    {
        $payload = ['body' => '수정된 글'];

        $article = Article::factory()->create();

        $this
            ->patch(
                route('articles.update', ['article' => $article->id]),
                $payload
            )->assertRedirect(route('login'));

        $this->assertDatabaseMissing('articles', $payload);
        $this->assertNotEquals($payload['body'], $article->refresh()->body);
    }

    /**
     * @test
     */
    public function 로그인하면_글을_삭제_가능함(): void
    {
        $user = User::factory()->create();

        $article = Article::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->delete(route('articles.destroy', ['article' => $article->id]))

            ->assertRedirect(route('articles.index'));

        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }
    /**
     * @test
     */
    public function 로그인안하면_글을_삭제_못한다(): void
    {
        $article = Article::factory()->create();

        $this

            ->delete(route('articles.destroy', ['article' => $article->id]))

            ->assertRedirectToRoute('login');

        $this->assertDatabaseHas('articles', ['id' => $article->id]);
    }
}
