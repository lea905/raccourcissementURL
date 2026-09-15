<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

use App\Models\ShortLink;
use Illuminate\Support\Facades\Mail;
use App\Mail\DeletedLinksSummary;

#[Signature('links:clean')]
#[Description('Supprime les liens inactifs depuis plus de X jours et envoie un récapitulatif par e-mail')]
class CleanOldLinks extends Command
{
    protected $signature = 'links:clean';
    protected $description = 'Supprime les liens inactifs depuis plus de X jours et envoie un récapitulatif par e-mail';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = env('LINK_INACTIVE_DAYS', 30);
        $threshold = now()->subDays($days);

        $inactiveLinks = ShortLink::where(function ($query) use ($threshold) {
            $query->where('last_visited_at', '<', $threshold)
                ->orWhere(function ($q) use ($threshold) {
                    $q->whereNull('last_visited_at')
                        ->where('created_at', '<', $threshold);
                });
        })->get();

        if ($inactiveLinks->isEmpty()) {
            $this->info('Aucun lien inactif à supprimer.');
            return;
        }

        $groupedLinks = $inactiveLinks->groupBy('user_id');

        foreach ($groupedLinks as $userId => $links) {
            $user = $links->first()->user;

            if ($user && $user->email) {
                Mail::to($user->email)->send(new DeletedLinksSummary($links));
            }

            foreach ($links as $link) {
                $link->delete();
            }
        }

        $this->info(count($inactiveLinks) . ' lien(s) inactif(s) supprimé(s) avec succès.');
    }
}
