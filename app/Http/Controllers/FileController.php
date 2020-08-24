<?php

namespace App\Http\Controllers;

use App\Bucket;
use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

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
     * @param Request $request
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

        return view('file')
            ->with('files', $this->filesForArray($filesBucket, $bucket))
            ->with('bucket', $bucket->id)
            ->with('bucketName', $bucket->name);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
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
     * @param int $id
     * @param Bucket $bucket
     * @param Request $request
     * @return Response
     * @throws FileNotFoundException
     */
    public function show($id, Bucket $bucket, Request $request)
    {
        $this->dowloadFile($id, $bucket->id);
        return response()->file(storage_path('app/files'.DIRECTORY_SEPARATOR.($id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
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
     * @param $bucketId
     * @throws FileNotFoundException
     */
    private function dowloadFile($id, $bucketId)
    {
        $this->setBucket(Bucket::find($bucketId));

        $s3_file = Storage::disk('s3')->get($id);
        $s3 = Storage::disk('private');
        $s3->put("./" . $id, $s3_file, 'private');
    }

    /**
     * @param array $filesBucket
     * @param Bucket $bucket
     * @return array
     */
    private function filesForArray(array $filesBucket, Bucket $bucket): array
    {
        $files = [];
        foreach ($filesBucket as $file) {
            $files[] = [
                'name' => $file,
                'url' => URL::temporarySignedRoute('file.show', now()->addMinutes($this->bucketExpirationTime), ['id' => $file, 'bucket' => $bucket->id])
            ];
        }
        return $files;
    }


}
