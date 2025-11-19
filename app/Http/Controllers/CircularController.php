<?php

namespace App\Http\Controllers;

use App\Models\Circular;
use Carbon\Carbon;
use Datatables;
use Illuminate\Http\Request;

class CircularController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (!Circular::canList())
            return $this->_access_denied();

        if ($request->ajax()) {
            $circulars = Circular::select('*');

            return Datatables::of($circulars)
                ->editColumn('published', function ($circular) {
                    return boolean_icon($circular->published);
                })
                ->editColumn('created_at', function ($circular) {
                    return Carbon::parse($circular->created_at)->format('j M Y');
                })
                ->addColumn('actions', function ($circular) {

                    $link = ($circular->pdf_link) ? $circular->pdf_link : $circular->file->url . '/' . $circular->file->name;

                    $actions   = [];
                    $actions[] = '<div class="btn-group">';
                    $actions[] = link_to_route('circulars.edit', 'Kemaskini', $circular->id, ['class' => 'btn btn-xs btn-primary']);
                    $actions[] = link_to_route('circulars.publish', $circular->published ? 'Batal Siar' : 'Siar', $circular->id, ['class' => 'btn btn-xs btn-danger']);
                    $actions[] = '<a href="'  . $link . '" class="btn btn-xs btn-success btn-show-circular" target="_blank">Lihat Pekeliling</a>';
                    $actions[] = '</div>';
                    return implode(' ', $actions);
                })
                ->removeColumn('id')
                ->rawColumns(['title', 'published', 'created_at', 'actions'])
                ->make();
        }

        return view('circulars.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if (!Circular::canCreate())
            return $this->_access_denied();
        $circular = new Circular;
        return view('circulars.create', compact('circular'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (!Circular::canCreate())
            return $this->_access_denied();

        $this->validate($request, [
            'file' => 'required_without:pdf_link',
            'pdf_link' => 'required_without:file'
        ]);

        $data = $request->all();
        // dd($data);
        $circular = new Circular;
        $circular->fill($data);

        if (!$circular->save())
            return $this->_validation_error($circular);

        return redirect('circulars')->with('success', $this->created_message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Circular  $circular
     * @return \Illuminate\Http\Response
     */
    public function show(Circular $circular)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Circular  $circular
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $circular = Circular::findOrFail($id);
        if (!$circular->canUpdate())
            return _access_denied();
        return view('circulars.edit', compact('circular'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Circular  $circular
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $circular = Circular::findOrFail($id);

        if (!$circular->canUpdate())
            return $this->_access_denied();

        $data = $request->all();

        if (!isset($data['published'])) $data['published'] = 0;

        if (!$circular->update($data))
            return $this->_validation_error($circular);

        return redirect('circulars')->with('success', $this->updated_message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Circular  $circular
     * @return \Illuminate\Http\Response
     */
    public function destroy(Circular $circular)
    {
        //
    }

    public function publish($id)
    {

        $circular = Circular::findOrFail($id);
        if (!$circular->canUpdate())
            return $this->_access_denied();

        if ($circular->published) {
            $circular->published = 0;
        } else {
            $circular->published = 1;
        }

        $circular->save();
        return redirect('circulars')->with('success', $this->updated_message);
    }

    public function public()
    {
        $circulars = Circular::with('file')->where('published', 1)->orderBy('position')->get();

        return view('circulars.public', compact('circulars'));
    }

    public function sortPosition()
    {

        if (!Circular::canCreate())
            return $this->_access_denied();

        $circulars = Circular::where('published', 1)->orderBy('position')->get();

        return view('circulars.sort', compact('circulars'));
    }

    public function updatePosition(Request $request)
    {

        if (!Circular::canCreate())
            return $this->_access_denied();

        $positon = 0;

        foreach ($request->order as $id) {
            $positon++;
            $circular = Circular::findOrFail($id);
            $circular->position = $positon;
            $circular->save();
        }

        $request->session()->flash('success', 'Susunan Pekeliling telah dikemaskini.');

        return true;
    }
}
