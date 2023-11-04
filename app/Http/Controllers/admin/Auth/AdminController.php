<?php

namespace App\Http\Controllers\admin\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\userTicketsModel;
use App\Models\userModel;
class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.dashboard')->
               with('menubar', $this->list_sidebar());
    }

}
