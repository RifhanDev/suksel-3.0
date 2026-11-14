<?php

namespace App\Http\Controllers;

use App\Models\VersionHistory;
use Illuminate\Http\Request;
use Datatables;

class VersionHistoryController extends Controller
{
    public function index(Request $request)
    {
        if (!VersionHistory::canList()) {
            return $this->_access_denied();
        }

        if ($request->ajax()) {
            $items = VersionHistory::query()->select('*');

            return Datatables::of($items)
                ->editColumn('released_at', function ($item) {
                    return $item->released_at ? $item->released_at->format('j M Y') : '';
                })
                ->editColumn('notes', function ($item) {
                    $lines = $item->notes_lines;
                    if (empty($lines)) {
                        return $item->notes ?: '—';
                    }
                    $preview = count($lines) > 2 ? array_slice($lines, 0, 2) : $lines;
                    return '<ul class="mb-0 ps-3">' . implode('', array_map(function ($line) {
                        return '<li>' . e($line) . '</li>';
                    }, $preview)) . (count($lines) > 2 ? '<li class="text-muted">…</li>' : '') . '</ul>';
                })
                ->addColumn('actions', function ($item) {
                    $showUrl = route('version-histories.show', $item->id);
                    $actions = '<a href="' . e($showUrl) . '" class="btn btn-sm btn-primary rounded-8 px-3 text-nowrap">Papar</a>';

                    if ($item->canUpdate()) {
                        $editUrl = route('version-histories.edit', $item->id);
                        $actions .= '<a href="' . e($editUrl) . '" class="btn btn-sm btn-warning rounded-8 px-3 text-nowrap">Kemaskini</a>';
                    }

                    if ($item->canDelete()) {
                        $destroyUrl = route('version-histories.destroy', $item->id);
                        $csrf = csrf_field();
                        $actions .= '<form method="POST" action="' . e($destroyUrl) . '" class="d-inline">' . $csrf .
                            '<input type="hidden" name="_method" value="DELETE">' .
                            '<button type="button" class="btn btn-sm btn-danger rounded-8 px-3 text-nowrap confirm-delete">Padam</button></form>';
                    }

                    return '<div class="d-flex gap-1 justify-content-center flex-nowrap">' . $actions . '</div>';
                })
                ->rawColumns(['notes', 'actions'])
                ->make(true);
        }

        return view('version-histories.index');
    }

    public function show($id)
    {
        $versionHistory = VersionHistory::findOrFail($id);

        if (!VersionHistory::canList()) {
            return $this->_access_denied();
        }

        return view('version-histories.show', compact('versionHistory'));
    }

    public function create()
    {
        if (!VersionHistory::canCreate()) {
            return $this->_access_denied();
        }

        $versionHistory = new VersionHistory();
        return view('version-histories.create', compact('versionHistory'));
    }

    public function store(Request $request)
    {
        if (!VersionHistory::canCreate()) {
            return $this->_access_denied();
        }

        $request->validate([
            'version' => 'required|string|max:50',
            'released_at' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $versionHistory = new VersionHistory();
        $versionHistory->fill($request->only(['version', 'released_at', 'notes']));

        if (!$versionHistory->save()) {
            return $this->_validation_error($versionHistory);
        }

        return redirect()->route('version-histories.index')->with('success', $this->created_message);
    }

    public function edit($id)
    {
        $versionHistory = VersionHistory::findOrFail($id);

        if (!$versionHistory->canUpdate()) {
            return $this->_access_denied();
        }

        return view('version-histories.edit', compact('versionHistory'));
    }

    public function update(Request $request, $id)
    {
        $versionHistory = VersionHistory::findOrFail($id);

        if (!$versionHistory->canUpdate()) {
            return $this->_access_denied();
        }

        $request->validate([
            'version' => 'required|string|max:50',
            'released_at' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        if (!$versionHistory->update($request->only(['version', 'released_at', 'notes']))) {
            return $this->_validation_error($versionHistory);
        }

        return redirect()->route('version-histories.index')->with('success', $this->updated_message);
    }

    public function destroy($id)
    {
        $versionHistory = VersionHistory::findOrFail($id);

        if (!$versionHistory->canDelete()) {
            return $this->_access_denied();
        }

        $versionHistory->delete();
        return redirect()->route('version-histories.index')->with('success', $this->deleted_message);
    }
}
