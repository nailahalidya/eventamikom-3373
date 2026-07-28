<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organizer;
use Illuminate\Http\Request;

class OrganizerController extends Controller
{
    /**
     * Menampilkan seluruh organizer.
     */
    public function index()
    {
        $organizers = Organizer::latest()->paginate(10);

        return view('admin.organizers.index', compact('organizers'));
    }

    public function show(Organizer $organizer)
    {
        return view('admin.organizers.show', compact('organizer'));
    }

    /**
     * Approve organizer.
     */
    public function approve(Organizer $organizer)
    {
        $organizer->update([
            'status' => 'approved',
        ]);

        return redirect()
            ->route('admin.organizers.index')
            ->with('success', 'Organizer berhasil disetujui.');
    }

    /**
     * Reject organizer.
     */
    public function reject(Organizer $organizer)
    {
        $organizer->update([
            'status' => 'rejected',
        ]);

        return redirect()
            ->route('admin.organizers.index')
            ->with('success', 'Organizer berhasil ditolak.');
    }

    /**
     * Hapus data organizer.
     */
    public function destroy(Organizer $organizer)
    {
        if ($organizer->logo && !\Illuminate\Support\Str::startsWith($organizer->logo, ['http://', 'https://'])) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($organizer->logo);
        }

        $organizer->delete();

        return redirect()
            ->route('admin.organizers.index')
            ->with('success', 'Data organizer berhasil dihapus.');
    }
}
