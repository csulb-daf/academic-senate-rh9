<?php

namespace App\Http\Controllers;

use App\Charges;
use App\Committees;
use App\Community;
use App\Rank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $comms = Committees::all('id', 'committeename');

        return view('list', [
            'comms' => $comms,
        ]);
    }

    /*** Commmunity Members ***/
    public function getCommunityMembers()
    {
        return Community::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createCommunity()
    {
        return view('community-form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function storeCommunity(Request $request)
    {
        $validatedData = request()->validate([
            'fName' => 'required',
            'lName' => 'required',
            'email' => 'required',
        ], [
            'fName.required' => 'Please Enter First Name',
            'lName.required' => 'Please Enter Last Name',
            'email.required' => 'Please Enter your email address',
        ]);

        if ($validatedData) {
            $community = new Community;
            $community->user_id = Auth::id();
            $community->firstname = $request->fName;
            $community->lastname = $request->lName;
            $community->email = $request->email;
            $community->notes = $request->notes;
            $community->save();

            return redirect()->route('list')->withInput($request->all)->with('community', 'New Community Member Added');
        } else {
            return redirect()->route('list')->withInput($request->all)->with('error');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCommunity(Request $request)
    {
        request()->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required',
        ], [
            'firstname.required' => 'Please Enter First Name',
            'lastname.required' => 'Please Enter Last Name',
            'email.required' => 'Please Enter your email address',
        ]);

        Community::where('id', $request->id)
            ->update([
                'user_id' => Auth::id(),
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'notes' => $request->notes,
            ]);

        return $request;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyCommunity(Request $request)
    {
        Community::where('id', $request->id)->delete();

        return $request;
    }

    /*** Charge Membership ***/
    public function getCharges()
    {
        return Charges::all('id', 'charge');
    }

    public function storeCharge(Request $request)
    {
        $validatedData = request()->validate([
            'chargeName' => 'required',
        ], [
            'chargeName.required' => 'Please Enter Charge Membership',
        ]);

        if ($validatedData) {
            $charges = new Charges;
            $charges->user_id = Auth::id();
            $charges->charge = $request->chargeName;
            $charges->save();

            return redirect()->route('list')->withInput($request->all)->with('charge', 'New Charge Membership Added');
        } else {
            return redirect()->route('list')->withInput($request->all)->with('error');
        }
    }

    public function updateCharge(Request $request)
    {
        request()->validate([
            'chargeName' => 'required',
        ], [
            'chargeName.required' => 'Please Enter Charge Membership',
        ]);

        Charges::where('id', $request->id)
            ->update([
                'user_id' => Auth::id(),
                'charge' => $request->chargeName,
            ]);

        return $request;
    }

    public function destroyCharge(Request $request)
    {
        Charges::where('id', $request->id)->delete();

        return $request;
    }

    /*** Rank ***/
    public function getRank()
    {
        return Rank::all('id', 'rank');
    }

    public function storeRank(Request $request)
    {
        $validatedData = request()->validate([
            'rank' => 'required',
        ], [
            'rank.required' => 'Please Enter Rank',
        ]);

        if ($validatedData) {
            $rank = new Rank;
            $rank->user_id = Auth::id();
            $rank->rank = $request->rank;
            $rank->save();

            return redirect()->route('list')->withInput($request->all)->with('rank', 'New Rank Added');
        } else {
            return redirect()->route('list')->withInput($request->all)->with('error');
        }
    }

    public function updateRank(Request $request)
    {
        request()->validate([
            'rankName' => 'required',
        ], [
            'rankName.required' => 'Please Enter Rank',
        ]);

        Rank::where('id', $request->id)
            ->update([
                'user_id' => Auth::id(),
                'rank' => $request->rankName,
            ]);

        return $request;
    }

    public function destroyRank(Request $request)
    {
        Rank::where('id', $request->id)->delete();

        return $request;
    }
}
