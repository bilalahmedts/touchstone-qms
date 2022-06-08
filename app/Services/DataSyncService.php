<?php

namespace App\Services;

use App\Models\User;
use App\Models\Campaign;
use App\Models\Designation;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Http;

class DataSyncService
{
    // campaigns
    public function campaigns()
    {

        $response = Http::get('https://api.touchstoneleads.com/MyService.svc/GetCampaignList?Accesskey=4321Touch1234')->body();

        $response = json_decode($response, true);

        if($response["isInserted"]){
            $hrms_campaigns = json_decode($response["DataList"]);
        }

        if(count($hrms_campaigns) > 0){
            foreach($hrms_campaigns as $hrms_campaign){
                if($hrms_campaign->CampaignID > 1){
                    $campaign = Campaign::where('hrms_id', $hrms_campaign->CampaignID)->first();

                    if (!$campaign) {
                        $campaign = new Campaign;
                    }

                    $campaign->hrms_id = $hrms_campaign->CampaignID;
                    $campaign->name = $hrms_campaign->CampaignName;
                    $campaign->status = ($hrms_campaign->Active) ? 'active' : 'disabled';

                    $campaign->save();
                }
            }
        }
    }

    // designations
    public function designations()
    {

        $response = Http::get('https://api.touchstoneleads.com/MyService.svc/GetDesignationsList?Accesskey=4321Touch1234')->body();

        $response = json_decode($response, true);

        if($response["isInserted"]){
            $hrms_designations = json_decode($response["DataList"]);
        }

        if(count($hrms_designations) > 0){
            foreach($hrms_designations as $hrms_designation){
                if($hrms_designation->DesignationID > 1){
                    $designation = Designation::where('hrms_id', $hrms_designation->DesignationID)->first();

                    if (!$designation) {
                        $designation = new Designation;
                    }

                    $designation->hrms_id = $hrms_designation->DesignationID;
                    $designation->name = $hrms_designation->Designation;
                    $designation->status = ($hrms_designation->Active) ? 'active' : 'disabled';

                    $designation->save();
                }
            }
        }
    }

    // users
    public function users()
    {

        $this->makeSuperAdmin();

        $valid_designations = [15, 2055, 2059, 2068, 2101, 2, 4, 14, 25, 31, 34, 36, 38, 40, 2060, 2081, 2082, 3104, 47, 2054, 2064, 2066, 12, 53, 1053, 2056, 2057, 2058, 2061, 2080, 2084, 3153, 2074];

        $response = Http::get('https://api.touchstoneleads.com/MyService.svc/GetEmployeeList?Accesskey=4321Touch1234&IsActive=1')->body();

        $response = json_decode($response, true);

        if($response["isInserted"]){
            $hrms_employees = json_decode($response["DataList"]);
        }

        if(count($hrms_employees) > 0){
            foreach($hrms_employees as $hrms_employee){
                if($hrms_employee->ID > 1 && in_array($hrms_employee->DesignationID, $valid_designations)){

                    $user = User::where('hrms_id', $hrms_employee->ID)->first();

                    if ($user) {
                        if($this->checkEmail($user, $hrms_employee->EmailAddress)){
                            $this->updateUser($hrms_employee, $user);
                        }
                    }
                    else{
                        if($this->checkEmail($user, $hrms_employee->EmailAddress)){
                            $this->addUser($hrms_employee);
                        }
                    }

                    // end
                }

            }
        }
    }

    public function updateUser($hrms_employee, $user){
        $user->name = $hrms_employee->Name;

        $user->email = $hrms_employee->EmailAddress;

        if (env('APP_ENV') == "production") {
            $user->password = Hash::make($hrms_employee->Password);
        } else {
            $user->password = Hash::make("test123");
        }

        // reportingTo
        $supervisor = User::where('hrms_id', $hrms_employee->ReportingTo)->first();

        if($supervisor){
            $user->reporting_to = $supervisor->id;
        }


        $user->status = ($hrms_employee->Active) ? 'active' : 'disabled';

        $user->save();
    }

    public function addUser($hrms_employee){
        $user = new User;
        $check_user = false;

        $user->hrms_id = $hrms_employee->ID;
        $user->ts_hrms_id = $hrms_employee->HRMSID;
        $user->hrms_designation_id = $hrms_employee->DesignationID;
        $user->name = $hrms_employee->Name;
        $user->email = $hrms_employee->EmailAddress;

        // designation
        $designation = Designation::where('hrms_id', $user->hrms_designation_id)->first();
        if ($designation) {
            $user->designation_id = $designation->id;
        }

        // campaign
        $campaign = Campaign::where('hrms_id', $hrms_employee->CampaignID)->first();
        if ($campaign) {
            $user->campaign_id = $campaign->id;
        }

        // set password
        if (env('APP_ENV') == "production") {
            $user->password = Hash::make($hrms_employee->Password);
        } else {
            $user->password = Hash::make("test123");
        }

        // reportingTo
        $supervisor = User::where('hrms_id', $hrms_employee->ReportingTo)->first();

        if($supervisor){
            $user->reporting_to = $supervisor->id;
        }


        $user->status = ($hrms_employee->Active) ? 'active' : 'disabled';

        $user->save();

        if ($check_user == false) {

            // assign role

            $directors = [15, 2055, 2059, 2068, 2101];
            $managers = [2, 4, 14, 25, 31, 36, 38, 40, 2060, 2081, 2082, 3104, 34];
            $team_leads = [47, 2054, 2064, 2066];
            $associates = [12, 53, 1053, 2056, 2057, 2058, 2061, 2080, 2084, 2074];

            if (in_array($user->hrms_designation_id, $directors)) {
                $user->assignRole('Director');
            } elseif (in_array($user->hrms_designation_id, $managers)) {
                $user->assignRole('Manager');
            } elseif (in_array($user->hrms_designation_id, $team_leads)) {
                $user->assignRole('Team Lead');
            } elseif (in_array($user->hrms_designation_id, $associates)) {
                $user->assignRole('Associate');
            }
        }
    }

    public function checkEmail($user, $new_email){

        $user_id = 0;
        if($user == false){
            $old_email = $new_email;
        }
        else{
            $user_id = $user->id;
            $old_email = $user->email;
        }

        $email_counts = User::where('email', $old_email)->where('id', '!=', $user_id)->count();

        if($email_counts == 0){
            return true;
        }
        return false;
    }


    public function inactiveUsers()
    {

        $valid_designations = [15, 2055, 2059, 2068, 2101, 2, 4, 14, 25, 31, 34, 36, 38, 40, 2060, 2081, 2082, 3104, 47, 2054, 2064, 2066, 12, 53, 1053, 2056, 2057, 2058, 2061, 2080, 2084, 3153, 2074];

        $response = Http::get('https://api.touchstoneleads.com/MyService.svc/GetEmployeeList?Accesskey=4321Touch1234&IsActive=0')->body();

        $response = json_decode($response, true);

        if($response["isInserted"]){
            $hrms_employees = json_decode($response["DataList"]);
        }

        if(count($hrms_employees) > 0){
            foreach($hrms_employees as $hrms_employee){
                if($hrms_employee->ID > 1 && in_array($hrms_employee->DesignationID, $valid_designations)){
                    $user = User::where('email', $hrms_employee->EmailAddress)->first();

                    if($user){
                        $user->status = 'disabled';
                        $user->save();
                    }
                    // end
                }

            }
        }
    }

    public function makeSuperAdmin()
    {

        $user = User::where('email', 'admin@touchstone.com.pk')->first();

        if (!$user) {
            $user = new User;
            $user->name = "Adminstrator";
            $user->email = "admin@touchstone.com.pk";
            $user->password = Hash::make("test123");
            $user->status = 'active';
            $user->save();

            $user->assignRole("Super Admin");
        }
    }
}
