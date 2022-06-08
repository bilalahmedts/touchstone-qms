<?php

namespace App\Http\Controllers\Reports;

use App\Models\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Campaign;

class VoiceReportController extends Controller
{

    public function timesheet(Request $request){

        $query = new User;

        $query = $query->where('campaign_id', 135);

        $query = $query->when($request, function ($query, $request) {
            $query->search($request);
        });

        $query = $query->whereHas('voiceAudits', function($query) use ($request){
            $query = $query->when($request, function ($query, $request) {
                $query->search($request);
            });
        });

        $query = $query->with('voiceAudits', function($query) use ($request){
            $query = $query->when($request, function ($query, $request) {
                $query->search($request);
            });
        });

        $query = $query->orderBy('name', 'asc');

        $user_evaluations = $query->paginate(15);

        $users = User::where('campaign_id', 135)->orderBy('name', 'asc')->get();
        return view('voice-reports.timesheet')->with(compact('users', 'user_evaluations'));
    }


    public function evaluators(Request $request){

        $query = new User;

        $query = $query->where('campaign_id', 135);

        $query = $query->when($request, function ($query, $request) {
            $query->search($request);
        });

        $query = $query->whereHas('voiceAudits', function($query) use ($request){
            $query = $query->when($request, function ($query, $request) {
                $query->search($request);
            });
        });

        $query = $query->with('voiceAudits', function($query) use ($request){
            $query = $query->when($request, function ($query, $request) {
                $query->search($request);
            });
        });

        $query = $query->orderBy('name', 'asc');

        $user_evaluations = $query->paginate(15);

        $users = User::where('campaign_id', 135)->orderBy('name', 'asc')->get();
        return view('voice-reports.evaluators')->with(compact('users', 'user_evaluations'));
    }

    public function campaigns(Request $request){

        $query = new Campaign;

        $query = $query->when($request, function ($query, $request) {
            $query->search($request);
        });

        $query = $query->whereHas('voiceAudits', function($query) use ($request){
            $query = $query->when($request, function ($query, $request) {
                $query->search($request);
            });
        });

        $query = $query->with('voiceAudits', function($query) use ($request){
            $query = $query->when($request, function ($query, $request) {
                $query->search($request);
            });
        });

        $query = $query->orderBy('name', 'asc');

        $campaign_evaluations = $query->paginate(15);

        $campaigns = Campaign::where('status', 'active')->orderBy('name', 'asc')->get();
        return view('voice-reports.campaigns')->with(compact('campaigns', 'campaign_evaluations'));
    }

    public function teamLeads(Request $request){
        $query = new User;

        $query = $query->role('Team Lead');

        $query = $query->when($request, function ($query, $request) {
            $query->search($request);
        });

        $query = $query->whereHas('teamLeadVoiceAudits', function($query) use ($request){
            $query = $query->when($request, function ($query, $request) {
                $query->search($request);
            });
        });

        $query = $query->with('teamLeadVoiceAudits', function($query) use ($request){
            $query = $query->when($request, function ($query, $request) {
                $query->search($request);
            });
        });

        $query = $query->orderBy('name', 'asc');

        $user_evaluations = $query->paginate(15);

        $users = User::role('Team Lead')->orderBy('name', 'asc')->get();

        return view('voice-reports.team-leads')->with(compact('users', 'user_evaluations'));
    }


    public function associates(Request $request){
        $query = new User;

        $query = $query->role('Associate');

        $query = $query->when($request, function ($query, $request) {
            $query->search($request);
        });

        $query = $query->whereHas('associateVoiceAudits', function($query) use ($request){
            $query = $query->when($request, function ($query, $request) {
                $query->search($request);
            });
        });

        $query = $query->with('associateVoiceAudits', function($query) use ($request){
            $query = $query->when($request, function ($query, $request) {
                $query->search($request);
            });
        });

        $query = $query->orderBy('name', 'asc');

        $user_evaluations = $query->paginate(15);

        $users = User::role('Associate')->where('status', 'active')->orderBy('name', 'asc')->get();

        return view('voice-reports.associates')->with(compact('users', 'user_evaluations'));
    }
}
