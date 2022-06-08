<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\Datapoint;
use App\Models\VoiceAudit;
use App\Models\VoiceAuditPoint;
use App\Models\VoiceAuditAppeal;
use App\Models\DatapointCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\VoiceAuditCustomField;

/**
 *
 */
Class VoiceAuditService
{
    // insert audit data points
    public function insertAuditPoints($request, $voice_audit){

        foreach($request->all() as $key => $item){
            $key = explode("-",$key);

            if(count($key) > 1){
                if($key[0] == "answer"){
                    $datapoint = Datapoint::find($key[1]);
                    VoiceAuditPoint::create([
                        "voice_audit_id" => $voice_audit->id,
                        "datapoint_category_id" => $datapoint->datapoint_category_id,
                        "datapoint_id" => $datapoint->id,
                        "answer" => $item
                    ]);
                }
            }
        }

        $this->updateScore($voice_audit);

    }

    // insert audit data points
    public function updateAuditPoints($request, $voice_audit){

        foreach($request->all() as $key => $item){
            $key = explode("-",$key);

            if(count($key) > 1){
                if($key[0] == "answer"){
                    $ev_point = VoiceAuditPoint::find($key[1]);
                    $ev_point->update([ "answer" => $item ]);
                }
            }
        }

        $this->updateScore($voice_audit);

    }

    public function updateScore($voice_audit){
        $categories = $this->getAuditCategories($voice_audit);

        foreach($categories as $category => $points){
            $column_name = str_replace(' ', '_', strtolower($category));

            $score = 0;

            foreach($points as $point){
                if($point->answer == 1){
                    $score++;
                }
            }

            $score = $score / count($points) * 100;

            $voice_audit->$column_name = $score;
            $voice_audit->save();

        }
    }

    public function getAuditCategories($voice_audit){
        $datapoint_categories = DatapointCategory::orderBy('sort', 'desc')->get();

        // get evaluation categories
        $categories = array();
        foreach($datapoint_categories as $category){
            $ev_points = VoiceAuditPoint::with('datapoint')->where('datapoint_category_id', $category->id)->where('voice_audit_id', $voice_audit->id)->orderBy('id', 'asc')->get();

            $categories[$category->name] = $ev_points;
        }

        return $categories;
    }

    public function insertCustomFields($request, $voice_audit){
        foreach ($request->all() as $key => $item) {
            # code...
            $key_array = explode('-', $key);
            if($key_array[0] == 'customfield'){
                VoiceAuditCustomField::create([
                    'voice_custom_field_id' => (int)$key_array[1],
                    'voice_audit_id' => $voice_audit->id,
                    'answer' => $item
                ]);
            }

        }
    }

    public function updateCustomFields($request, $voice_audit){
        foreach ($request->all() as $key => $item) {
            # code...
            $key_array = explode('-', $key);
            if($key_array[0] == 'customfield'){
                VoiceAuditCustomField::where('voice_audit_id', $voice_audit->id)->where('voice_custom_field_id', (int)$key_array[1])->update(['answer' => $item]);
            }

        }
    }

    public function fetchCallRecord($request){
        if(!$request->has('record_id') && !$request->has('campaign_id')){
            abort(404);
        }

        $campaign = Campaign::findOrFail($request->campaign_id);

        if($campaign->database_name == NULL){
            return [
                'success' => false,
                'message' => 'Campaign does not have a database name!',
                'data' => NULL
            ];
        }

        $audit = VoiceAudit::where('record_id', $request->record_id)->count();

        if($audit > 0){
            return [
                'success' => false,
                'message' => 'An evaluation is already exists for this record id!',
                'data' => NULL
            ];
        }

        $response = Http::get(env('RECORDS_API_URL') . "/get-lead-detail?access_key=Touch786&databaseName={$campaign->database_name}&recordId={$request->record_id}")->body();

        $response = json_decode($response, true);

        $response = $response ? $response : [];

        if(count($response) == 0){
            return [
                'success' => false,
                'message' => 'Call Record not found!',
                'data' => NULL
            ];
        }

        return [
            'success' => true,
            'message' => '',
            'data' => $response[0]
        ];
    }


    public function updateAppeal($voice_audit){
        if($voice_audit->appeal && $voice_audit->outcome == 'accepted'){
            $appeal = VoiceAuditAppeal::findOrFail($voice_audit->appeal->id);
            $appeal->status = 'accepted';
            $appeal->save();

            $voice_audit->status = 'appeal accepted';
            $voice_audit->save();

            return true;
        }

        return false;
    }


    public function auditShowAccess(){
        $access = false;
        if(in_array(Auth::user()->roles[0]->name, ['Director', 'Manager', 'Team Lead', 'Associate']) && Auth::user()->campaign_id == 135){
            $access = true;
        }
        elseif(in_array(Auth::user()->roles[0]->name, ['Super Admin', 'Director'])){
            $access = true;
        }

        if($access == false){
            abort(403);
        }

        return true;
    }

    public function auditEditAccess($voice_audit){
        $access = false;
        if(in_array(Auth::user()->roles[0]->name, ['Director', 'Manager', 'Team Lead', 'Associate']) && Auth::user()->campaign_id == 135){
            if(Auth::user()->roles[0]->name == 'Associate' && $voice_audit->user_id == Auth::user()->id){
                $diff = $voice_audit->created_at->diffInHours(now());
                if($diff < 24){
                    $access = true;
                }
            }
            elseif(in_array(Auth::user()->roles[0]->name, ['Director', 'Manager', 'Team Lead'])){
                $access = true;
            }
        }
        elseif(in_array(Auth::user()->roles[0]->name, ['Super Admin'])){
            $access = true;
        }

        if($access == false){
            abort(403);
        }

        return true;
    }

    public function auditDeleteAccess($voice_audit){
        $access = false;

        if(in_array(Auth::user()->roles[0]->name, ['Super Admin'])){
            $access = true;
        }

        if($access == false){
            abort(403);
        }

        return true;
    }


}


