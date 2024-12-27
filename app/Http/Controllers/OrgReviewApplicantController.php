<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrgReviewApplicantController extends Controller
{
    public function index(Request $request)
    {
        if (session()->exists('users')) {
            $user = session()->pull('users');
            session()->put("users", $user);

            if ($user['userType'] != "org") {
                return redirect("/logout");
            }

            $id = $request->query('id');
            if ($id) {
                $notifCount = DB::table('notifications')->where('userID', '=', $user['userID'])->where('status', '=', 'unread')->count();

                $data = json_decode(DB::table('vwapplications')->where('applicationID', '=', $id)->get(), true);

                return view('org.review', [
                    'data' => $data[0],
                    'notifCount' => $notifCount
                ]);
            }

            return redirect("/org_applications");
        }
        return redirect("/");
    }
}
