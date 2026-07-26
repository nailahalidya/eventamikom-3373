public function update(Request $request, Event $event)
    {
        $organizer = Auth::user()->organizer;

        abort_if($event->organizer_id !== $organizer->id, 403);

        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:40960',
        ]);

        if ($request->hasFile('poster')) {
            // Hanya hapus file lokal jika BUKAN URL Cloudinary dan file lokal ada
            if (
                $event->poster_path &&
                !\Illuminate\Support\Str::startsWith($event->poster_path, ['http://', 'https://'])
            ) {
                try {
                    Storage::disk('public')->delete($event->poster_path);
                } catch (\Exception $e) {
                    // Abaikan jika storage read-only
                }
            }

            $uploadedUrl = \App\Services\CloudinaryService::upload($request->file('poster'), 'posters');
            if ($uploadedUrl) {
                $data['poster_path'] = $uploadedUrl;
            }
        }

        $event->update($data);

        return redirect()
            ->route('organizer.events.index')
            ->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy(Event $event)
    {
        $organizer = Auth::user()->organizer;

        abort_if($event->organizer_id !== $organizer->id, 403);

        if (
            $event->poster_path &&
            !\Illuminate\Support\Str::startsWith($event->poster_path, ['http://', 'https://'])
        ) {
            try {
                Storage::disk('public')->delete($event->poster_path);
            } catch (\Exception $e) {
                // Abaikan jika storage read-only
            }
        }

        $event->delete();

        return redirect()
            ->route('organizer.events.index')
            ->with('success', 'Event berhasil dihapus.');
    }