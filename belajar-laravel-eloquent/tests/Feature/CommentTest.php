<?php

namespace Tests\Feature;

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
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

}
