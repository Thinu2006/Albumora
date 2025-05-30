<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Album;
use App\Models\User;
use App\Models\Order;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Check if the authenticated user is an admin
        if (Auth :: check() && Auth :: user()->user_type === 'admin') {
            $counts = $this->getCounts();

            return view('admin.dashboard',[
                'albumCount' => $counts['AlbumCount'],
                'userCount' => $counts['UserCount'],
                'orderCount' => $counts['OrderCount'],
            ]); // Return the admin dashboard view
        }

        // If not an admin, abort with a 403 Forbidden response
        abort(403, 'Unauthorized access');
    }

    public function albums()
    {
        // Check if the authenticated user is an admin
        if (Auth :: check() && Auth :: user()->user_type === 'admin') {
            return view('admin.albums'); // Return the admin dashboard view
        }

        // If not an admin, abort with a 403 Forbidden response
        abort(403, 'Unauthorized access');
    }

    //Get the counts of albums, users and orders
    private function getCounts()
    {
        $AlbumCount = Album::count();
        $UserCount = User::where('user_type', 'user')->count();
        $OrderCount = Order::count();

        return compact('AlbumCount', 'UserCount', 'OrderCount');
    }
    public function orders()
    {
        // Check if the authenticated user is an admin
        if (Auth::check() && Auth::user()->user_type === 'admin') {
            return view('admin.orders'); // Return the admin orders view
        }

        // If not an admin, abort with a 403 Forbidden response
        abort(403, 'Unauthorized access');
    }   
    public function orderDetail($id)
    {
        // Check if the authenticated user is an admin
        if (Auth::check() && Auth::user()->user_type === 'admin') {
            return view('admin.order-detail', ['orderId' => $id]);
        }

        // If not an admin, abort with a 403 Forbidden response
        abort(403, 'Unauthorized access');
    }
    public function users()
    {
        // Check if the authenticated user is an admin
        if (Auth::check() && Auth::user()->user_type === 'admin') {
            return view('admin.users'); // Return the admin users view
        }

        // If not an admin, abort with a 403 Forbidden response
        abort(403, 'Unauthorized access');
    }   
}
