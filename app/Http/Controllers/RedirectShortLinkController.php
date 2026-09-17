<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShortLink;

class RedirectShortLinkController extends Controller
{
    public function __invoke(String $code)
    {

        $shortLink = ShortLink::where('short_code', $code)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->firstOrFail();

        $shortLink->increment('clicks_count', 1, ['last_visited_at' => now()]);

        return redirect()->away($shortLink->original_url);
    }
}
