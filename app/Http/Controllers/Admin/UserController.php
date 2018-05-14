<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request) {
        $data['title'] = 'Users';
        if ($request->ajax()) {
            $users = User::query()->whereNull('role_id');
            return DataTables::eloquent($users)
                            ->addColumn('status', function ($user) {
                                if ($user->status == 1) {
                                    return '<div class="btn-group status-toggle" data-id="' . $user->id . '" data-url="' . route('users-status') . '"><button class="btn active btn-primary" data-value="1">Active</button><button class="btn btn-default" data-value="0">Deactive</button></div>';
                                } else {
                                    return '<div class="btn-group status-toggle" data-id="' . $user->id . '" data-url="' . route('users-status') . '"><button class="btn btn-default" data-value="1">Active</button><button class="btn active btn-primary" data-value="0">Deactive</button></div>';
                                }
                            })
                            ->rawColumns(['status'])->toJson();
        }
        return view('admin.users.index', $data);
    }
}
