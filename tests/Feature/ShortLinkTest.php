<?php

namespace Tests\Feature;

use App\Models\ShortLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortLinkTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_their_links_on_dashboard()
    {
        $user = User::factory()->create();
        $link = ShortLink::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee($link->original_url);
    }

    public function test_user_can_create_a_short_link()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/links', [
            'original_url' => 'https://www.example.com',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('short_links', [
            'original_url' => 'https://www.example.com',
            'user_id' => $user->id,
        ]);
    }

    public function test_user_can_update_their_link()
    {
        $user = User::factory()->create();
        $link = ShortLink::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->put('/links/' . $link->id, [
            'original_url' => 'https://www.example.com/updated',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('short_links', [
            'id' => $link->id,
            'original_url' => 'https://www.example.com/updated',
        ]);
    }

    public function test_user_cannot_update_other_users_link()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $link = ShortLink::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->put('/links/' . $link->id, [
            'original_url' => 'https://www.example.com/updated',
        ]);

        $response->assertStatus(403);
    }

    public function test_user_can_soft_delete_their_link()
    {
        $user = User::factory()->create();
        $link = ShortLink::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete('/links/' . $link->id);

        $response->assertRedirect('/dashboard');
        $this->assertSoftDeleted('short_links', [
            'id' => $link->id,
        ]);
    }

    public function test_short_link_redirects_and_increments_clicks()
    {
        $user = User::factory()->create();
        $link = ShortLink::factory()->create([
            'user_id' => $user->id,
            'original_url' => 'https://www.google.com',
            'clicks_count' => 0,
            'last_visited_at' => null,
        ]);

        $response = $this->get('/' . $link->short_code);

        $response->assertRedirect('https://www.google.com');

        $link->refresh();
        $this->assertEquals(1, $link->clicks_count);
        $this->assertNotNull($link->last_visited_at);
    }

    public function test_deleted_link_returns_404()
    {
        $user = User::factory()->create();
        $link = ShortLink::factory()->create(['user_id' => $user->id]);
        $link->delete(); // soft delete

        $response = $this->get('/' . $link->short_code);

        $response->assertStatus(404);
    }
}
