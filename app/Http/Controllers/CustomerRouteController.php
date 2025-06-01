<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Album;
use App\Models\Order;
use App\Models\User;


class CustomerRouteController extends Controller
{

    //Display the album search page for users.
    public function albumsearch()
    {
        if (Auth :: check() && Auth :: user()->user_type === 'user') {
            return view('user.album-search'); 
        }

        abort(403, 'Unauthorized access');
    }

    //Display the cart page for users.
    public function cart()
    {
        if (Auth::check() && Auth::user()->user_type === 'user') {
            // Get all albums with their genres and creator
            $albums = Album::with(['genres', 'creator'])->get();
            
            return view('user.cart', [
                'allAlbums' => $albums->map(function($album) {
                    return [
                        'id' => $album->id,
                        'title' => $album->title,
                        'artist' => $album->artist,
                        'price' => (float)$album->price, // Ensure price is a float
                        'cover_image' => $album->cover_image,
                        'genres' => $album->genres->pluck('name')->toArray()
                    ];
                })
            ]);
        }

        abort(403, 'Unauthorized access');
    }


    //Display the checkout page for users.
    public function checkout()
    {
        if (Auth::check() && Auth::user()->user_type === 'user') {
            // Get all albums with their genres and creator
            $albums = Album::with(['genres', 'creator'])->get();
            
            return view('user.checkout', [
                'allAlbums' => $albums->map(function($album) {
                    return [
                        'id' => $album->id,
                        'title' => $album->title,
                        'artist' => $album->artist,
                        'price' => (float)$album->price, // Ensure price is a float
                        'cover_image' => $album->cover_image,
                        'genres' => $album->genres->pluck('name')->toArray()
                    ];
                })
            ]);
        }
        abort(403, 'Unauthorized access');
    }
    

    //Display the order details for a specific order.
    public function ordershow($id)
    {
        if (Auth::check() && Auth::user()->user_type === 'user') {
            $order = Order::with(['albums', 'payment', 'shipment'])
                ->where('customer_id', Auth::id())
                ->findOrFail($id);
            
        return view('user.orders.show', ['order' => $order]);
        }
        abort(403, 'Unauthorized access');
    }


    //Display the order list for users.
    public function orderlist()
    {
        if (Auth::check() && Auth::user()->user_type === 'user') {
            return view('user.orders.list', [
                'userId' => Auth::id()
            ]); 
        }
        abort(403, 'Unauthorized access');
    }

    //Display the Review page
    public function review()
    {
        if (Auth::check() && Auth::user()->user_type === 'user') {
            return view('user.review', [
                'userId' => Auth::id()
            ]); 
        }
        abort(403, 'Unauthorized access');
    }
}
