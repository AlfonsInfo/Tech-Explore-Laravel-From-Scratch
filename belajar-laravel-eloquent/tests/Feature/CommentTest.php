<?php

namespace Tests\Feature;

use App\Models\Comment;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommentTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        DB::delete("delete from comments");
    }

    public function testCreate(): void
    {
        $comment = new Comment();
        $comment->email = "alfons@example.com";
        $comment->title = "sample title";
        $comment->comment = "sample comment";
        //* default timenya 16:01:44 ???
        $result = $comment->save();
        self::assertTrue($result);
        

        //* multiple 
    }


    public function testCreateUseFillable()
    {
        $request = [
            "email" => "ucup@gmail.com",
            "title" => "INFO LOKER",
            "comment" => "Ada info loker ga ya bro?",
        ];

        $comment = new Comment($request);
        $result = $comment->save();
        self::assertTrue($result) ;
        //* if filalble / guarded not set -> MassAssignmentException

        //* shortcut Way
        $request = [
            "email" => "udin@gmail.com",
            "title" => "INFO LOKER",
            "comment" => "Ada info loker ga ya bro?",
        ];
        $comment = Comment::query()->create($request);
        // Log::info($comment->email);

        self::assertEquals("udin@gmail.com", $comment->email);
    }

}
