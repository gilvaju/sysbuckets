<?php

namespace App\Http\Controllers;

use App\Bucket;
use App\File;
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
            $request->session()->flash('status', 'Arquivo inexistente');
            return redirect(route('file.index', $request->bucket));
        }

        $this->setBucket(Bucket::find($request->bucket));

        if (Storage::disk('s3')->put($request->file('file')->getClientOriginalName(),'/')) {
             $file = File::create([
                 'filename' => $request->file('file')->getClientOriginalName(),
                 'url' => Storage::disk('s3')->url($request->file('file')->getClientOriginalName())
             ]);
            $request->session()->flash('status', 'Upload com sucesso');
        }
        return redirect(route('file.index', $request->bucket));
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
