<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /**
     * Store a newly created comment.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'chirp_id' => 'required|exists:chirps,id',
            'content' => 'required|string|max:255',
        ]);

        $comment = new Comment();
        $comment->user_id = auth()->user()->id; // Lisa kasutaja ID väärtus
        $comment->chirp_id = $validated['chirp_id'];
        $comment->content = $validated['content'];
        $comment->save();

        return redirect()->back()->with('success', 'Kommentaar on edukalt lisatud.');
    }

    /**
     * Remove the specified comment from storage.
     * kasutatakse nüüd Gate'i (väravat), et kontrollida, kas kasutajal on õigus kommentaari kustutada
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        if (Gate::allows('delete-comment', $comment)) {
            $chirp = $comment->chirp;
            $comment->delete();

            return redirect()->route('chirps.show', $chirp);
        }

        return redirect()->back()->with('error', 'Unauthorized');
    }
}
