<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $search = $request->get('search');
        $partners = \App\Models\Partner::when($search, function ($query) use ($search) {
            $query->where('name', 'LIKE', '%' . $search . '%');
        })->get();
        return view('admin.partners.index', compact('partners', 'search'));
    }

    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo_url' => 'required|string',
        ]);

        \App\Models\Partner::create([
            'name' => $request->name,
            'logo_url' => $request->logo_url,
        ]);

        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil ditambahkan');
    }

    public function edit(\App\Models\Partner $partner)
    {
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(\Illuminate\Http\Request $request, \App\Models\Partner $partner)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo_url' => 'required|string',
        ]);
        $partner->update([
            'name' => $request->name,
            'logo_url' => $request->logo_url,
        ]);
        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil diperbarui');
    }

    public function destroy(\App\Models\Partner $partner)
    {
        $partner->delete();
        return redirect()->route('admin.partners.index')->with('success', 'Partner berhasil dihapus');
    }
}
