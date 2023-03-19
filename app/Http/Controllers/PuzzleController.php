<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Questions\Puzzle;
use App\Jobs\ProcessGame;
use Illuminate\Support\Facades\Storage;

class PuzzleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            //ProcessGame::dispatch($request, $game_id, $author_id);
        $author_id = \Auth::user()->id;
        $game = Puzzle::create([
            'name' => $request->input('puzzle_name'),
            'author_id' => $author_id,
        ]);
            
        $game_id = $game->id;
        if ($request->filled('puzzle_description')) {
            $game->description = $request->input('puzzle_description');
            $request->merge([
                'puzzle_description' => '',
            ]);
        }

        $request->file('puzzle')->storeAs('puzzles', $author_id."_".$game_id.".".$request->file('puzzle')->getClientOriginalExtension());
        $puzzle_url = 'puzzles/'.$author_id."_".$game_id.".".$request->file('puzzle')->getClientOriginalExtension();
        
        //store b64imgs get path
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
        return redirect()->route('home')->with('status', 'completed');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $game = Puzzle::find($id);
        return view('play', ['game' => $game]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
