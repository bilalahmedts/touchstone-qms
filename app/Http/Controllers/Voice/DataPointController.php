<?php

namespace App\Http\Controllers\Voice;

use App\Models\Campaign;
use App\Models\Datapoint;

use Illuminate\Http\Request;
use App\Models\DatapointCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\DataPointRequest;

class DataPointController extends Controller
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
    public function create(DatapointCategory $datapoint_category)
    {
        return view('datapoints.create')->with(compact('datapoint_category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DataPointRequest $request)
    {
        $datapoint = Datapoint::create($request->all());
        return redirect()->route('voice-evaluations.show', $datapoint->evaluation)->with('success', 'Data Point created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Datapoint $datapoint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Datapoint $datapoint)
    {
        return view('datapoints.edit')->with(compact('datapoint'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DataPointRequest $request, Datapoint $datapoint)
    {
        $datapoint->update($request->all());
        return redirect()->route('voice-evaluations.show', $datapoint->evaluation)->with('success', 'Data Point updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Datapoint $datapoint)
    {
        $datapoint->delete();
        return redirect()->back()->with('success', 'Data Point deleted successfully!');
    }

}
