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

    public function download($filepath) {

        if (!Storage::disk('private')->exists($filepath)){
            abort('404');
        }

        return response()->file(storage_path('app/files'.DIRECTORY_SEPARATOR.($filepath)));
    }

    /**
     * Display a listing of the resource.
     *
     * @param Bucket $bucket
     * @return void
     */
    public function index(Bucket $bucket, Request $request)
    {
        $this->setBucket($bucket);

        try {
            $filesBucket = Storage::disk('s3')->files();
        } catch (\Exception $exception) {
            $request->session()->flash('error', 'Dados do bucket inválidos');
            return redirect(route('bucket.index'));
        }

//        $files = [];
//        foreach ($filesBucket as $file) {
//            $files[] = [
//                'name' => $file,
//                'url' => $this->getTemporaryUrl($file)
//            ];
//        }

        return view('file')
            ->with('files', $filesBucket)
            ->with('bucket', $bucket->id)
            ->with('bucketName', $bucket->name);
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
    public function show($id, Bucket $bucket, Request $request)
    {
        $this->setBucket(Bucket::find($bucket->id));
        $this->dowloadFile($id);

        return view('file-show')
            ->with('path', $id)
            ->with('bucket', $bucket->id)
            ->with('bucketName', $bucket->name);
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

    /**
     * @param int $id
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function dowloadFile($id)
    {
        $s3_file = Storage::disk('s3')->get($id);
        $s3 = Storage::disk('private');
        $s3->put("./" . $id, $s3_file, 'private');
    }


}
