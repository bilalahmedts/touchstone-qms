<?php

namespace App\Http\Controllers\Voice;

use App\Models\DatapointCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\DatapointCategoryRequest;
use App\Models\Campaign;
use App\Models\VoiceEvaluation;

class DatapointCategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(VoiceEvaluation $voice_evaluation)
    {
        $campaigns = Campaign::where('status', 'active')->get();
        return view('datapoint-categories.create')->with(compact('voice_evaluation', 'campaigns'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DatapointCategoryRequest $request)
    {
        $datapoint_category = DatapointCategory::create($request->except('campaigns'));

        $datapoint_category->campaigns()->sync($request->campaigns);

        return redirect()->route('voice-evaluations.show', $datapoint_category->voice_evaluation_id)->with('success', 'Datapoint Category created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(DatapointCategory $datapoint_category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(DatapointCategory $datapoint_category)
    {
        $campaigns = Campaign::where('status', 'active')->get();
        return view('datapoint-categories.edit')->with(compact('datapoint_category', 'campaigns'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DatapointCategoryRequest $request, DatapointCategory $datapoint_category)
    {
        $datapoint_category->update($request->except('campaigns'));

        $datapoint_category->campaigns()->sync($request->campaigns);

        return redirect()->route('voice-evaluations.show', $datapoint_category->voice_evaluation_id)->with('success', 'Datapoint Category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DatapointCategory $datapoint_category)
    {
        $datapoint_category->delete();
        return redirect()->back()->with('success', 'Datapoint Category deleted successfully!');
    }
}
