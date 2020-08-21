<?php

namespace App\Http\Controllers;

use App\Bucket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Bucket $bucket
     * @return void
     */
    public function index(Bucket $bucket)
    {
        $this->setBucket($bucket);
        $files = Storage::disk('s3')->files();
        return view('file')->with('files', $files)->with('bucket', $bucket->id);
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
        if (!$request->file('file')) {
            $request->session()->flash('status', 'Erro no upload');
            return redirect('/file');
        }

        $this->setBucket(Bucket::find($request->bucket));
        $path = $request->file('file')->store('images');

        if (Storage::disk('s3')->put($request->file('file')->getClientOriginalName(), $path)) {
            // $image = Image::create([
            //     'filename' => basename($path),
            //     'url' => Storage::disk('s3')->url($path)
            // ]);
            $request->session()->flash('status', 'Upload com sucesso');
        }

        return redirect('/file');
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
        return 1;
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

    /**
     * @param Bucket $bucket
     */
    private function setBucket(Bucket $bucket): void
    {
        $bucketConfig = [
            'driver' => 's3',
            'key' => $bucket->key,
            'secret' => $bucket->secret,
            'region' => $bucket->region,
            'bucket' => $bucket->name,
        ];
        config(['filesystems.disks.s3' => $bucketConfig]);
    }
}
