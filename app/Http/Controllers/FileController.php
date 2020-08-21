<?php

namespace App\Http\Controllers;

use App\Bucket;
use Illuminate\Http\Request;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $id
     * @return void
     */
    public function index(Bucket $bucket)
    {
        $arrayWithNewSettings = [
            'name' => $bucket->name,
            'region' => $bucket->region,
            'key' => $bucket->key,
            'secret' => $bucket->secret
        ];
        config(['filesystems.disks.s3' => $arrayWithNewSettings]);
        $file = Storage::disk('s3')->url('cake.jpg');
        return $file;
//        dd(config());
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
