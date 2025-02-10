<?php

namespace App\Http\Controllers;

use App\Models\Students;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserMyDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (session()->exists('users')) {
            $user = session()->pull('users');
            session()->put("users", $user);

            if ($user['userType'] != "user") {
                return redirect("/logout");
            }

            $notifCount = DB::table('notifications')->where('userID', '=', $user['userID'])->where('status', '=', 'unread')->count();

            $mDate =  date('Y-m-d', strtotime('-14 years'));
            return view('user.mydetails', ['user' => $user, 'maxDate' => $mDate, 'notifCount' => $notifCount]);
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

            if ($user['userType'] != "user") {
                return redirect("/logout");
            }


            if ($request->btnSaveDetails) {
                $fileProfile = $request->file('profile');
                $profileFileName = "";
                if ($fileProfile) {
                    $mimeType = $fileProfile->getMimeType();

                    if ($mimeType == "image/png" || $mimeType == "image/jpg" || $mimeType == "image/JPG" || $mimeType == "image/JPEG" || $mimeType == "image/jpeg" || $mimeType == "image/PNG") {
                        $destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/profiles';
                        $profileFileName = strtotime(now()) . "." . $fileProfile->getClientOriginalExtension();
                        $isFile = $fileProfile->move($destinationPath,  $profileFileName);
                        chmod($destinationPath, 0755);
                    } else {
                        session()->put("invalidProfile", true);
                        return redirect("/user_details");
                    }
                }

                $fileGrade = $request->file('grade');
                $gradeFileName = "";

                if ($fileGrade) {
                    $gradeMimeType = $fileGrade->getMimeType();

                    if ($gradeMimeType == "image/png" || $gradeMimeType == "image/jpg" || $gradeMimeType == "image/JPG" || $gradeMimeType == "image/JPEG" || $gradeMimeType == "image/jpeg" || $mimeType == "image/PNG") {
                        $destinationPath = $_SERVER['DOCUMENT_ROOT'] . '/grades';
                        $gradeFileName = strtotime(now()) . "." . $fileGrade->getClientOriginalExtension();
                        $isFile = $fileGrade->move($destinationPath,  $gradeFileName);
                        chmod($destinationPath, 0755);
                    } else {
                        session()->put("invalidGrade", true);
                        return redirect("/user_details");
                    }
                }



                $count = DB::table('students')->where('userID', '=', $user['userID'])->count();
                if ($count > 0) {

                    $updateCount = DB::table('students')
                        ->where('userID', $user['userID'])
                        ->update([
                            'firstName' => $request->firstName,
                            'middleName' => $request->middleName,
                            'lastName' => $request->lastName,
                            'address' => $request->address,
                            'birthDate' => $request->birthDate,
                            'gender' => $request->gender,
                            'contactNumber' => $request->contactNumber,
                            'school' => $request->school,
                            'schoolDate' => $request->schoolDate,
                            'fatherFirstName' => $request->fatherFirstName,
                            'fatherMiddleName' => $request->fatherMiddleName,
                            'fatherLastName' => $request->fatherLastName,
                            'fatherBirthDate' => $request->fatherBirthDate,
                            'fatherOccupation' => $request->fatherOccupation,
                            'fatherContactNumber' => $request->fatherContactNumber,
                            'motherFirstName' => $request->motherFirstName,
                            'motherMiddleName' => $request->motherMiddleName,
                            'motherLastName' => $request->motherLastName,
                            'motherBirthDate' => $request->motherBirthDate,
                            'motherOccupation' => $request->motherOccupation,
                            'motherContactNumber' => $request->motherContactNumber,
                            'monthlyGross' => $request->monthlyGross,
                            'monthlyNet' => $request->monthlyNet,
                            'profile' => $profileFileName,
                            'grade' => $gradeFileName,
                        ]);
                    if ($updateCount > 0) {
                        session()->put("successUpdateDetails", true);
                    } else {
                        session()->put("errorUpdateDetails", true);
                    }
                } else {
                    $newStudent = new Students();
                    $newStudent->userID = $user['userID'];
                    $newStudent->firstName = $request->firstName;
                    $newStudent->middleName = $request->middleName;
                    $newStudent->lastName = $request->lastName;
                    $newStudent->address = $request->address;
                    $newStudent->birthDate = $request->birthDate;
                    $newStudent->gender = $request->gender;
                    $newStudent->contactNumber = $request->contactNumber;
                    $newStudent->school = $request->school;
                    $newStudent->schoolDate = $request->schoolDate;
                    $newStudent->fatherFirstName = $request->fatherFirstName;
                    $newStudent->fatherMiddleName = $request->fatherMiddleName;
                    $newStudent->fatherLastName = $request->fatherLastName;
                    $newStudent->fatherBirthDate = $request->fatherBirthDate;
                    $newStudent->fatherOccupation = $request->fatherOccupation;
                    $newStudent->fatherContactNumber = $request->fatherContactNumber;
                    $newStudent->motherFirstName = $request->motherFirstName;
                    $newStudent->motherMiddleName = $request->motherMiddleName;
                    $newStudent->motherLastName = $request->motherLastName;
                    $newStudent->motherBirthDate = $request->motherBirthDate;
                    $newStudent->motherOccupation = $request->motherOccupation;
                    $newStudent->motherContactNumber = $request->motherContactNumber;
                    $newStudent->monthlyGross = $request->monthlyGross;
                    $newStudent->monthlyNet = $request->monthlyNet;
                    $newStudent->profile = $profileFileName;
                    $newStudent->grade = $gradeFileName;
                    $isSave =  $newStudent->save();
                    if ($isSave) {
                        session()->put("successUpdateDetails", true);
                    } else {
                        session()->put("errorUpdateDetails", true);
                    }
                }
            }
            return redirect("/user_details");
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
