<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $partners = Partner::when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', '%' . $search . '%');
        })->latest()->get();

        return view('admin.partners.index', compact('partners'));
    }

    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
            'logo_url' => 'nullable|string|max:1000',
        ]);

        $logoUrl = $request->logo_url;
        if ($request->hasFile('logo')) {
            $logoUrl = \App\Services\CloudinaryService::upload($request->file('logo'), 'partners');
        }

        Partner::create([
            'name' => $request->name,
            'logo_url' => $logoUrl,
        ]);

        return redirect()
            ->route('admin.partners.index')
            ->with('success', 'Partner berhasil ditambahkan');
    }

    public function edit(Partner $partner)
    {
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
            'logo_url' => 'nullable|string|max:1000',
        ]);

        $logoUrl = $partner->logo_url;
        if ($request->hasFile('logo')) {
            $logoUrl = \App\Services\CloudinaryService::upload($request->file('logo'), 'partners');
        } elseif ($request->filled('logo_url')) {
            $logoUrl = $request->logo_url;
        }

        $partner->update([
            'name' => $request->name,
            'logo_url' => $logoUrl,
        ]);

        return redirect()
            ->route('admin.partners.index')
            ->with('success', 'Partner berhasil diperbarui');
    }


    public function destroy(Partner $partner)
    {
        $partner->delete();

        return redirect()
            ->route('admin.partners.index')
            ->with('success', 'Partner berhasil dihapus');
    }
}
