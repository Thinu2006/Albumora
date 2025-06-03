<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Album;
use App\Models\User;
use App\Models\Order;

class AdminController extends Controller
{
    // Function to display the admin dashboard
    public function dashboard()
    {
        if (Auth :: check() && Auth :: user()->user_type === 'admin') {
            $counts = $this->getCounts();

            return view('admin.dashboard',[
                'albumCount' => $counts['AlbumCount'],
                'userCount' => $counts['UserCount'],
                'orderCount' => $counts['OrderCount'],
            ]);
        }

        abort(403, 'Unauthorized access');
    }

    // Function to display the albums page for admin
    public function albums()
    {
        if (Auth :: check() && Auth :: user()->user_type === 'admin') {
            return view('admin.albums'); 
        }

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

    // Function to display the order list page for admin
    public function orders()
    {
        if (Auth::check() && Auth::user()->user_type === 'admin') {
            return view('admin.orders'); 
        }

        abort(403, 'Unauthorized access');
    }   

    // Function to display the order details page for admin
    public function orderDetail($id)
    {
        if (Auth::check() && Auth::user()->user_type === 'admin') {
            return view('admin.order-detail', ['orderId' => $id]);
        }

        abort(403, 'Unauthorized access');
    }


    // Function to display the users page for admin
    public function users()
    {
        
        if (Auth::check() && Auth::user()->user_type === 'admin') {
            return view('admin.users'); 
        }

        abort(403, 'Unauthorized access');
    }   
}
