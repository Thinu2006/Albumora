<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;
use App\Http\Resources\AlbumResource;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{

    public function index()
    {
        try {
            $albums = Album::with(['genres', 'creator'])->latest()->get();
            return response()->json([
                'success' => true,
                'data' => AlbumResource::collection($albums)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load albums',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        \Log::debug('Request data', ['data' => $request->all()]);
        \Log::debug('Files', ['files' => $request->file() ? ['has_files' => true] : ['has_files' => false]]);

        // Handle genres whether they come as array or JSON string
        $genres = $request->input('genres');
        if (is_string($genres)) {
            $genres = json_decode($genres, true);
            $request->merge(['genres' => $genres]);
        }
        \Log::debug('Genres', ['genres' => $request->input('genres', [])]);

        $request->validate([
            'title' => 'required|string|max:255',
            'artist' => 'required|string|max:255',
            'release_year' => 'required|integer|min:1900|max:' . now()->year,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'cover_image' => 'nullable|image|max:2048',
            'genres' => 'required',
        ]);

        try {
            $album = new Album($request->only([
                'title', 'artist', 'release_year', 'price', 'stock'
            ]));

            // Change from creator_id to creator_by to match your migration
            $album->creator_by = auth()->id() ?? 1;

            if ($request->hasFile('cover_image')) {
                $path = $request->file('cover_image')->store('album_covers', 'public');
                $album->cover_image = $path;
            }

            $album->save();

            // Ensure genres is an array before syncing
            $genresToSync = is_array($request->genres) ? $request->genres : [$request->genres];
            $album->genres()->sync($genresToSync);

            return response()->json([
                'success' => true,
                'message' => 'Album created successfully',
                'data' => new AlbumResource($album->load('genres'))
            ]);
        } catch (\Exception $e) {
            \Log::error('Album creation failed:', ['error' => $e->getMessage(), 'trace' => $e->getTrace()]);
            return response()->json([
                'success' => false,
                'message' => 'Album creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $album = Album::with(['genres', 'creator'])->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => new AlbumResource($album)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load album',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        \Log::debug('Update request data', ['data' => $request->all()]);

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'artist' => 'sometimes|string|max:255',
            'release_year' => 'sometimes|integer|min:1900|max:' . now()->year,
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'cover_image' => 'sometimes|image|max:2048',
            'genres' => 'sometimes',
        ]);

        try {
            $album = Album::findOrFail($id);
            
            // Handle genres whether they come as array or JSON string
            $genres = $request->input('genres');
            if (is_string($genres)) {
                $genres = json_decode($genres, true);
                $request->merge(['genres' => $genres]);
            }

            $album->update($request->only([
                'title', 'artist', 'release_year', 'price', 'stock'
            ]));

            if ($request->hasFile('cover_image')) {
                // Delete old cover image if it exists
                if ($album->cover_image) {
                    Storage::disk('public')->delete($album->cover_image);
                }
                $path = $request->file('cover_image')->store('album_covers', 'public');
                $album->cover_image = $path;
                $album->save();
            }

            if ($request->has('genres')) {
                $genresToSync = is_array($request->genres) ? $request->genres : [$request->genres];
                $album->genres()->sync($genresToSync);
            }

            return response()->json([
                'success' => true,
                'message' => 'Album updated successfully',
                'data' => new AlbumResource($album->load('genres'))
            ]);
        } catch (\Exception $e) {
            \Log::error('Album update failed:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Album update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $album = Album::findOrFail($id);
            
            // Delete cover image if it exists
            if ($album->cover_image) {
                Storage::disk('public')->delete($album->cover_image);
            }
            
            $album->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Album deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Album deletion failed:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Album deletion failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}