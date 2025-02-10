<?php

namespace App\Http\Controllers;

use App\Models\Cashins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class OrgTransactionController extends Controller
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

            $allData = DB::table('vwtransactions')
                ->where('ownerID', '=', $user['userID'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $phpRate = $this->getEthToPhpRate();
            $contractABI = config('contract.abi');
            $contractAddress = env('CONTRACT_ADDRESS');
            $myBalanceArr = json_decode(DB::table('balances')->where('userID', '=', $user['userID'])->get(), true);
            $myBalance = $myBalanceArr[0]['amount'];
            return view('org.transactions', [
                'transactions' => $allData,
                'notifCount' => $notifCount,
                'phpRate' => $phpRate,
                'contractABI' => $contractABI,
                'contractAddress' => $contractAddress,
                'userID' => $user['userID'],
                'myBalance' => $myBalance
            ]);
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

            if ($request->btnAddCash) {
                $newCashin = new Cashins();
                $newCashin->userID = $user['userID'];
                $newCashin->amount = $request->amount;
                $newCashin->transactionHash = $request->thash;
                $newCashin->ethAmount = $request->eth;
                $isSave = $newCashin->save();
                $myBalanceArr = json_decode(DB::table('balances')->where('userID', '=', $user['userID'])->get(), true);
                $myBalance = $myBalanceArr[0]['amount'];
                $myBalance = $myBalance + $request->amount;
                DB::table('balances')->where('id', '=', $myBalanceArr[0]['id'])->update([
                    'amount' => $myBalance,
                ]);
                if ($isSave) {
                    session()->put("successCashin", true);
                } else {
                    session()->put("errorCashin", true);
                }
            }
            return redirect("/org_transactions");
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

    function getEthToPhpRate()
    {
        $response = Http::get('https://api.coingecko.com/api/v3/simple/price', [
            'ids' => 'ethereum',
            'vs_currencies' => 'php'
        ]);

        if ($response->successful()) {
            return $response->json()['ethereum']['php'];
        }

        return null; // Handle API failure
    }
}
