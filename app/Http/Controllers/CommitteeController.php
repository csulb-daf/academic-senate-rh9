<?php

namespace App\Http\Controllers;

use App\Committees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommitteeController extends Controller
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
        return view('committee');
    }

    public function displayCommitteeAssignments()
    {
        return $this->getCommitteeChargeCount();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('committee-form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validatedData = request()->validate(
            [
                'commName' => 'required',
                'meetTime' => 'required',
            ],

            [
                'commName.required' => 'Please Enter the Committee Name',
                'meetTime.required' => 'Please Enter the Meeting Time and Location',
            ]
        );

        if ($validatedData) {
            $committees = new Committees;
            $committees->user_id = Auth::id();
            $committees->committeename = $request->commName;
            $committees->meetingtimes_locations = $request->meetTime;
            $committees->notes = $request->notes;
            $committees->save();

            return redirect()->route('committee')->withInput($request->all)->with('committee', 'New Committee Added');
        } else {
            return back()->withInput($request->all)->with('error');
        }
    }

    public function update(Request $request)
    {
        request()->validate(
            ['commName' => 'required'],
            ['commName.required' => 'Committee Name is required']
        );

        Committees::where('id', $request->id)
            ->update([
                'user_id' => Auth::id(),
                'committeename' => $request->commName,
            ]);

        return $request;
    }

    public function destroy(Request $request)
    {
        Committees::where('id', $request->id)->update(['user_id' => Auth::id()]);
        Committees::where('id', $request->id)->delete();

        return $request;
    }
}
