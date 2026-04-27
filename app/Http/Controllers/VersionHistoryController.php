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
                    $editUrl = route('version-histories.edit', $item->id);
                    $destroyUrl = route('version-histories.destroy', $item->id);
                    $csrf = csrf_field();
                    $iconEye = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>';
                    $iconEdit = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>';
                    $iconTrash = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>';
                    return '<div class="d-flex flex-wrap gap-1 align-items-center version-history-actions">' .
                        '<a href="' . e($showUrl) . '" class="btn btn-sm btn-action btn-action-success" title="Papar" data-bs-toggle="tooltip">' . $iconEye . '</a>' .
                        '<a href="' . e($editUrl) . '" class="btn btn-sm btn-action btn-action-primary" title="Kemaskini" data-bs-toggle="tooltip">' . $iconEdit . '</a>' .
                        '<form method="POST" action="' . e($destroyUrl) . '" class="d-inline">' . $csrf . '<input type="hidden" name="_method" value="DELETE">' .
                        '<button type="button" class="btn btn-sm btn-action btn-action-danger confirm-delete" title="Padam" data-bs-toggle="tooltip">' . $iconTrash . '</button></form>' .
                        '</div>';
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
