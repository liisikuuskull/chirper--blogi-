<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\User;

class ChirpController extends Controller
{
    public function index()
    {
        $chirps = Chirp::latest()->get();

        return view('chirps.index', compact('chirps'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|max:255',
        ]);

        $chirp = new Chirp();
        $chirp->message = $request->message;
        $chirp->user_id = auth()->id();
        $chirp->save();

        return redirect()->route('chirps.index')
            ->with('success', 'Chirp created successfully.');
    }

    public function edit(Chirp $chirp)
    {
        return view('chirps.edit', compact('chirp'));
    }

    public function update(Request $request, Chirp $chirp)
    {
        $request->validate([
            'message' => 'required|max:255',
        ]);

        $chirp->message = $request->message;
        $chirp->save();

        return redirect()->route('chirps.index')
            ->with('success', 'Chirp updated successfully.');
    }

    public function destroy(Chirp $chirp): RedirectResponse
    {
        $this->authorize('delete', $chirp);

        $chirp->delete();

        return redirect(route('chirps.index'));
    }

    public function show(Chirp $chirp)
    {
        return view('chirps.show', compact('chirp'));
    }

    public function deleteComment($commentId)
    {
        // Administraatori kontroll
        $user = User::find('Admin'); // Asendage 1 vastava administraatori kasutaja ID-ga

        if (!$user || !$user->isAdmin()) {
            abort(403, 'Teil pole õigust selle kommentaari kustutamiseks.');
        }

        // Kommentaari kustutamise loogika
        // ...

        return redirect()->back()->with('success', 'Kommentaar on edukalt kustutatud.');
    }
}
