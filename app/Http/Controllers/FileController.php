<?php

namespace App\Http\Controllers;

use App\Bucket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{

    /**
     * @var mixed
     */
    private $bucketExpirationTime;

    /**
     * Block with middleware default for auth.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Bucket $bucket
     * @return void
     */
    public function index(Bucket $bucket)
    {
        $this->setBucket($bucket);
        $filesBucket = Storage::disk('s3')->files();

        $files = [];
        foreach ($filesBucket as $file) {
            $files[] = [
                'name' => $file,
                'url' => $this->getTemporaryUrl($file)
            ];
        }

        return view('file')
            ->with('files', $files)
            ->with('bucket', $bucket->id)
            ->with('bucketName', $bucket->name);
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
            $request->session()->flash('error', 'Arquivo inexistente');
            return redirect(route('file.index', $request->bucket));
        }

        $this->setBucket(Bucket::find($request->bucket));

        if ($request->file('file')->storeAs('/', $request->file('file')->getClientOriginalName(), 's3')) {
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
    public function destroy(Request $request, $id)
    {
        $this->setBucket(Bucket::find($request->bucket));

        if(Storage::disk('s3')->exists($id)) {
            Storage::disk('s3')->delete($id);
            $request->session()->flash('status', 'Arquivo excluído!');
        }
        return redirect(route('file.index', $request->bucket));
    }

    /**
     * @param Bucket $bucket
     */
    private function setBucket(Bucket $bucket): void
    {
        $this->bucketExpirationTime = $bucket->expirationTime;
        $bucketConfig = [
            'driver' => 's3',
            'key' => $bucket->key,
            'secret' => $bucket->secret,
            'region' => $bucket->region,
            'bucket' => $bucket->name,
        ];
        config(['filesystems.disks.s3' => $bucketConfig]);
    }

    /**
     * @param $file
     * @return mixed
     */
    private function getTemporaryUrl($file)
    {
        return Storage::disk('s3')->temporaryUrl($file, Carbon::now()->addMinutes($this->bucketExpirationTime));
    }
}
