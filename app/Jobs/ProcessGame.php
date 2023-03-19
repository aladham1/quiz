<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;
use App\Game;

class ProcessGame implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $request;
    protected $game;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Request $request, int $game_id, int $author_id)
    {
        $this->request = $request;
        $this->game_id = $game_id;
        $this->author_id = $author_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $request = $this->request;
        $author_id = $this->$author_id;
        $game_id = $this->game_id;
        $game = Game::find($game_id);

        $request->file('puzzle')->storeAs('puzzles', $author_id."_".$game_id.".".$request->file('puzzle')->getClientOriginalExtension());
        $puzzle_url = 'puzzles/'.$author_id."_".$game_id.".".$request->file('puzzle')->getClientOriginalExtension();

        $pieces = \json_decode($request->input('keys'), true);
        foreach ($pieces as $key => $piece) {
            if ($key == 'original_size') {
                continue;
            }
            $data = preg_replace('/data\:image\/\w+\;base64,/', '', $piece['piece']);
            Storage::put("pieces/".$key."_".$author_id."_".$game_id.".png", base64_decode($data));
            $piece['piece'] = "pieces/".$key."_".$author_id."_".$game_id.".png";
            $pieces[$key] = $piece;
        }

        $thumb_url = '';

        if ($request->hasFile('thumb')) {
            $request->file('thumb')->storeAs('thumbs', $author_id."_".$game_id.".".$request->file('thumb')->getClientOriginalExtension());
            $thumb_url = 'thumbs/'.$author_id."_".$game_id.".".$request->file('thumb')->getClientOriginalExtension();
        } else {
            $thumb_url = $puzzle_url;
        }

        $game->puzzle = $puzzle_url;
        $game->thumb = $thumb_url;
        $game->pieces = json_encode($pieces);
        $game->save();
    }
}
