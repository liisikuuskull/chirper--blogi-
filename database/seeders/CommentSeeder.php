<?php

use App\Models\Comment;
use App\Models\Chirp;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $chirps = Chirp::all();

        // Määrake iga kommentaari jaoks vastav chirp_id väärtus
        Comment::all()->each(function ($comment) use ($chirps) {
            $comment->chirp_id = $chirps->random()->id;
            $comment->save();
        });
    }
}

