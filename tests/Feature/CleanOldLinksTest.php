<?php

namespace Tests\Feature;

use App\Models\ShortLink;
use App\Models\User;
use App\Mail\DeletedLinksSummary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Illuminate\Support\Carbon;

class CleanOldLinksTest extends TestCase
{
    use RefreshDatabase;

    public function test_clean_old_links_command_soft_deletes_inactive_links()
    {
        Mail::fake();
        $user = User::factory()->create();

        // Active link (visited recently)
        $activeLink = ShortLink::factory()->create([
            'user_id' => $user->id,
            'last_visited_at' => now()->subDays(10),
        ]);

        // Inactive link (visited > 30 days ago)
        $inactiveLink = ShortLink::factory()->create([
            'user_id' => $user->id,
            'last_visited_at' => now()->subDays(31),
        ]);

        // Inactive link (never visited, created > 30 days ago)
        $neverVisitedLink = ShortLink::factory()->create([
            'user_id' => $user->id,
            'last_visited_at' => null,
            'created_at' => now()->subDays(31),
        ]);

        $this->artisan('links:clean')->assertExitCode(0);

        $this->assertDatabaseHas('short_links', ['id' => $activeLink->id, 'deleted_at' => null]);
        $this->assertSoftDeleted('short_links', ['id' => $inactiveLink->id]);
        $this->assertSoftDeleted('short_links', ['id' => $neverVisitedLink->id]);

        Mail::assertSent(DeletedLinksSummary::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email) && $mail->links->count() === 2;
        });
    }
}
