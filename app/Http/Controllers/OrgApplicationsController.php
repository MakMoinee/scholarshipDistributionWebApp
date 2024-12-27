<?php

namespace App\Http\Controllers;

use App\Models\ApplicationRemarks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrgApplicationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (session()->exists('users')) {
            $user = session()->pull('users');
            session()->put("users", $user);

            if ($user['userType'] != "org") {
                return redirect("/logout");
            }

            $allData = DB::table('vwapplications')
                ->where('userID', '=', $user['userID'])
                ->orderBy('applicationCreateDate', 'desc')
                ->paginate(10);

            return view('org.applications', ['applications' => $allData]);
        }
        return redirect("/");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (session()->exists('users')) {
            $user = session()->pull('users');
            session()->put("users", $user);

            if ($user['userType'] != "org") {
                return redirect("/logout");
            }

            if ($request->btnUpdateAppl) {
                $newRemarks = new ApplicationRemarks();
                $newRemarks->studentID = $request->studentID;
                $newRemarks->remarks = $request->remarks;
                $isSave = $newRemarks->save();
                if ($isSave) {
                    session()->put("successAddRemarks", true);
                } else {
                    session()->put("errorAddRemarks", true);
                }
            } else if ($request->btnApprove) {
            }

            return redirect("/org_applications");
        }
        return redirect("/");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
