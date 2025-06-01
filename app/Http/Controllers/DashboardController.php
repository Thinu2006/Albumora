<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Album;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        // Get 3 New Releaseed Albums from MySQL
        $newReleases = Album::with(['genres', 'creator'])
            ->orderBy('release_year', 'desc')
            ->take(3)
            ->get()
            ->map(function($album) {
                $album->cover_image_url = $album->cover_image ? 
                    Storage::url($album->cover_image) : 
                    asset('images/default-album.jpg');
                return $album;
            });
            
        // Get 3 most recent reviews from MongoDB
        $recentReviews = Review::orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('dashboard', compact('newReleases', 'recentReviews'));
    }
}
