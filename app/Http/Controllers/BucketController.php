<?php

namespace App\Http\Controllers;

use App\Bucket;
use Illuminate\Http\Request;

class BucketController extends Controller
{
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
     * @return \Illuminate\Http\Response
     */
    public function index(Bucket $bucket)
    {
        $buckets = $bucket->all();
        return view('buckets')->with('buckets', $buckets);
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
    public function store(Request $request, Bucket $bucket)
    {
        $this->validateData();

        $bucket->create($request->all());
        $request->session()->flash('status', 'Bucket salvo!');
        return redirect('/bucket');
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
     * @param Bucket $bucket
     * @return \Illuminate\Http\Response
     */
    public function edit(Bucket $bucket)
    {
        return view('buckets-edit')->with('bucket', $bucket);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Bucket $bucket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bucket $bucket)
    {
        $this->validateData();

        $bucket->fill($request->all())->save();
        return redirect('/bucket');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Bucket $bucket
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Request $request, Bucket $bucket)
    {
        $bucket->delete();
        $request->session()->flash('status', 'Bucket excluído!');
        return redirect('/bucket');
    }

    private function validateData(): void
    {
        request()->validate([
            'name' => ['required'],
            'region' => ['required'],
            'key' => ['required'],
            'secret' => ['required'],
            'expirationTime' => ['required']
        ]);
    }
}
