<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Comment;
class CommmentSeeder extends Seeder
{


    public function run(): void
    {
        $comment = new Comment();
        $comment->email = "alfons@example.com";
        $comment->title = "sample title";
        $comment->comment = "sample comment";

        $comment->save();
    }
}
