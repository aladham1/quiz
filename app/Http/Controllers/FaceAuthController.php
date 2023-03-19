<?php

namespace App\Http\Controllers;

use App\FaceAuth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FaceAuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return FaceAuth::all()->toJson();
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
    public function store(Request $request, $cmd = null)
    {
        if ($cmd == 'index.php') {

            return response('dsaasdgdfsdsazxxz');
        }
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->input('image')));
        $path = 'faceapp/'.uniqid().'.jpg';
        Storage::put($path, $data);
        $fa = FaceAuth::Create([
            'image' => $path,
            'is_allowed' => $request->input('is_allowed', false),
            'hardware_name' => $request->input('hardware_name')
        ]);
        return $fa->toJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FaceAuth  $faceAuth
     * @return \Illuminate\Http\Response
     */
    public function show(FaceAuth $faceAuth)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FaceAuth  $faceAuth
     * @return \Illuminate\Http\Response
     */
    public function edit(FaceAuth $faceAuth)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FaceAuth  $faceAuth
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FaceAuth $faceAuth)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FaceAuth  $faceAuth
     * @return \Illuminate\Http\Response
     */
    public function destroy(FaceAuth $faceAuth = null, Request $req)
    {
        FaceAuth::destroy($faceAuth->id);
    }
}
