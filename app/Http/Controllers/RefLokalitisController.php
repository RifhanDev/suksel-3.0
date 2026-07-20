<?php

namespace App\Http\Controllers;

use App\Models\Ref\RefLokaliti;
use Illuminate\Http\Request;

class RefLokalitisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lokalitis = RefLokaliti::where('active', true)->get();
        return view('ref.lokalitis.index', compact('lokalitis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ref.lokalitis.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'active' => 'boolean',
        ]);

        RefLokaliti::create($request->all());

        return redirect()->route('ref.lokalitis.index')
            ->with('success', 'Lokaliti berjaya dicipta.');
    }

    /**
     * Display the specified resource.
     */
    public function show(RefLokaliti $lokaliti)
    {
        return view('ref.lokalitis.show', compact('lokaliti'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RefLokaliti $lokaliti)
    {
        return view('ref.lokalitis.edit', compact('lokaliti'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RefLokaliti $lokaliti)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'active' => 'boolean',
        ]);

        $lokaliti->update($request->all());

        return redirect()->route('ref.lokalitis.index')
            ->with('success', 'Lokaliti berjaya dikemaskini.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RefLokaliti $lokaliti)
    {
        $lokaliti->delete();

        return redirect()->route('ref.lokalitis.index')
            ->with('success', 'Lokaliti berjaya dipadam.');
    }
}
