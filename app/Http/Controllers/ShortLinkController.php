<?php

namespace App\Http\Controllers;

use App\Models\ShortLink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShortLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $links = auth()->user()->shortLinks()->paginate(10);

        return view('dashboard', compact('links'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'original_url' => 'required|url|max:2048'
        ]);
        do {
            $code = Str::random(6);
        } while (ShortLink::where('short_code', $code)->exists());

        auth()->user()->shortLinks()->create([
            'original_url' => $request->original_url,
            'short_code' => $code,
        ]);

        return redirect()->route('dashboard')->with('status', 'Lien généré avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(ShortLink $shortLink)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShortLink $shortLink)
    {
        if ($shortLink->user_id !== auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        return view('links.edit', compact('shortLink'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShortLink $shortLink)
    {
        if ($shortLink->user_id !== auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        $request->validate([
            'original_url' => 'required|url|max:2048'
        ]);

        $shortLink->update([
            'original_url' => $request->original_url
        ]);

        return redirect()->route('dashboard')->with('status', 'Le lien a été mis à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShortLink $shortLink)
    {
        if ($shortLink->user_id !== auth()->id()) {
            abort(403, 'Action non autorisée.');
        }

        $shortLink->delete();

        return redirect()->route('dashboard')->with('status', 'Le lien a été supprimé.');
    }
}
