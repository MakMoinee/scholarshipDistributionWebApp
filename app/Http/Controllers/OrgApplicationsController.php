<?php

namespace App\Http\Controllers;

use App\Models\ApplicationRemarks;
use App\Models\Notifications;
use App\Models\Transactions;
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
            $notifCount = DB::table('notifications')->where('userID', '=', $user['userID'])->where('status', '=', 'unread')->count();

            $allData = DB::table('vwapplications')
                ->where('userID', '=', $user['userID'])
                ->orderBy('applicationCreateDate', 'desc')
                ->paginate(10);

            return view('org.applications', ['applications' => $allData, 'notifCount' => $notifCount]);
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
                    $newNotif = new Notifications();
                    $newNotif->userID = $request->studentID;
                    $newNotif->message = $request->remarks;
                    $newNotif->status = 'unread';
                    $newNotif->save();
                } else {
                    session()->put("errorAddRemarks", true);
                }
            } else if ($request->btnApprove) {
                $newRemarks = new ApplicationRemarks();
                $newRemarks->studentID = $request->studentID;
                $newRemarks->remarks = $request->remarks;
                $isSave = $newRemarks->save();
                if ($isSave) {
                    $newNotif = new Notifications();
                    $newNotif->userID = $request->studentID;
                    $newNotif->message = $request->remarks;
                    $newNotif->status = 'unread';
                    $newNotif->save();

                    $newTrans = new Transactions();
                    $newTrans->applicationID = $request->applicationID;
                    $newTrans->status = "waiting for disbursement";
                    $newTrans->save();


                    $updateCount = DB::table('applications')->where('id', '=', $request->applicationID)->update(['status' => 'approved']);
                    if ($updateCount > 0) {
                        session()->put("successApproved", true);
                    } else {
                        session()->put("errorApproved", true);
                    }
                } else {
                    session()->put("errorApproved", true);
                }
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
