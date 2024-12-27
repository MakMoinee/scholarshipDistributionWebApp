<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserViewRequirementsController extends Controller
{
    //
    public function index(Request $request)
    {
        if (session()->exists('users')) {
            $user = session()->pull('users');
            session()->put("users", $user);

            if ($user['userType'] != "user") {
                return redirect("/logout");
            }

            $id = $request->query('id');
            if ($id) {
                $data = json_decode(DB::table('scholarships')->where('id', '=', $id)->get(), true);
                return view('user.viewr', [
                    'req' => $data[0]
                ]);
            }

            return redirect('/user_home');
        }
        return redirect("/");
    }
}
