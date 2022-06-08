
@extends('layouts.user')

@section('title', 'Voice Evaluation')


@section('content')

<div class="back-area mb-3">
    <a href="{{ route('voice-evaluations.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-2"></i> Go Back</a>
</div>

<h4>Setup - {{ $voice_evaluation->name }}</h4>



<!-- Default box -->
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Information (Step 1 of 3)</h3>
        @if(Auth::user()->roles[0]->name == 'Super Admin')
        <div class="card-tools">
            <a href="{{ route('voice-custom-fields.create', $voice_evaluation) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Create Custom Field
            </a>
        </div>
        @endif
    </div>
    <div class="card-body">

        <div class="row">

            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Agent Name <span>*</span></label>
                    <select name="associate_id" id="agent" class="form-control select2">
                        <option value="">Select Option</option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Reporting To</label>
                    <input type="text" class="form-control" id="reporting" name="" disabled>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Campaign Name</label>
                    <input type="text" class="form-control" id="campaign" name="" disabled>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Record ID <span>*</span></label>
                    <input type="text" class="form-control" name="record_id" placeholder="Enter Record ID" required>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Call Date <span>*</span></label>
                    <input type="text" class="form-control" name="call_date"placeholder="Enter Call Date" required>
                </div>
            </div>

            <!-- optional fields -->

            @foreach ($voice_evaluation->customFieldsTop as $item)
                @php
                    $campaign_names = $item->campaigns->pluck('name')->toArray();
                    $campaign_names = implode(", ", $campaign_names);
                @endphp
                <div class="@if($item->type == 'textarea') col-md-12 @else col-md-4 @endif">
                    <div class="form-group">
                        <label for="">
                            {{ $item->label }}
                            @if($item->required == 1) <span>*</span> @endif

                            @if (count($item->campaigns) > 0)
                                <small>({{ $campaign_names }})</small>
                            @endif

                            <a href="{{ route('voice-custom-fields.edit', $item) }}" style="color: #000;" class="ml-3"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('voice-custom-fields.destroy', $item) }}" style="display: inline-block;" method="post">
                                @csrf
                                @method('delete')
                                <button type="submit" style="color: #000; border: none; background-color: transparent;" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </label>

                        @if($item->type == 'text')
                            <input type="text" class="form-control" name="custom-{{ $item->id }}" placeholder="{{ $item->placeholder }}">
                        @elseif($item->type == 'dropdown')
                            @php
                                $options = explode(',', $item->options)
                            @endphp

                            <select name="custom-{{ $item->id }}" class="form-control select2">
                                <option value="">Select Option</option>
                                @foreach ($options as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                        @elseif($item->type == 'textarea')
                            <textarea name="custom-{{ $item->id }}" rows="3" class="form-control" placeholder="{{ $item->placeholder }}"></textarea>
                        @endif

                    </div>
                </div>
            @endforeach

            <!-- end optional fields -->


        </div>

    </div>

</div>
<!-- /.card -->





<!-- Default box -->
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Data Points (Step 2 of 3)</h3>
        @if(Auth::user()->roles[0]->name == 'Super Admin')
        <div class="card-tools">
            <a href="{{ route('datapoint-categories.create', $voice_evaluation) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Create Category
            </a>
        </div>
        @endif
    </div>

    <div class="card-body">

        @if(count($categories) > 0)
            @foreach ($categories as $category)

                @php
                    $campaign_names = $category->campaigns->pluck('name')->toArray();
                    $campaign_names = implode(", ", $campaign_names);
                @endphp

                <div class="category">
                    <div class="title">
                        <h4>
                            {{ $category->name }}
                            @if (count($category->campaigns) > 0)
                                <small style="font-size: 12px;">({{ $campaign_names }})</small>
                            @endif
                        </h4>
                        <ul>
                            <li>
                                <a href="{{ route('datapoints.create', $category) }}" class="btn btn-xs btn-success"><i class="fas fa-plus"></i></a>
                            </li>
                            <li>
                                <a href="{{ route('datapoint-categories.edit', $category) }}" class="btn btn-xs btn-primary"><i class="fas fa-edit"></i></a>
                            </li>
                            <li>
                                <form action="{{ route('datapoint-categories.destroy', $category) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </li>
                        </ul>
                    </div>

                    @if(count($category->datapoints) > 0)

                        <div class="data-points">
                            <table class="table">
                                @foreach ($category->datapoints as $item)
                                    <tr>
                                        <td width="25%">{{ $item->name }} @if($item->course_id) <small>(Course ID: {{ $item->course_id }})</small> @endif</td>
                                        <td>{{ $item->question }}</td>
                                        <td>{{ $item->campaign->name ?? '' }}</td>
                                        <td class="action">
                                            <a href="{{ route('datapoints.edit', $item) }}" class="btn btn-primary btn-xs"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('datapoints.destroy', $item) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>

                    @else
                        <h5 class="text-center">No data points found!</h5>
                    @endif

                </div>

            @endforeach
        @else
            <h4 class="text-center">No records found</h4>
        @endif

    </div>

    @if($categories->total() > 15)
        <!-- /.card-body -->
        <div class="card-footer clearfix">
            {{ $categories->appends(request()->input())->links() }}
        </div>
        <!-- /.card-footer-->
    @endif
</div>
<!-- /.card -->






<!-- Default box -->
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Evaluation (Step 3 of 3)</h3>
        @if(Auth::user()->roles[0]->name == 'Super Admin')
        <div class="card-tools">
            <a href="{{ route('voice-custom-fields.create', $voice_evaluation) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Create Custom Field
            </a>
        </div>
        @endif
    </div>
    <div class="card-body">

        <div class="row">

            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Percentage <span>*</span></label>
                    <input type="hidden" name="percentage" value="100.00" class="form-control percentage">
                    <input type="text" name="" value="100.00" class="form-control percentage" disabled>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Customer Name <span>*</span></label>
                    <input type="text" name="customer_name" class="form-control" required>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Customer Phone <span>*</span></label>
                    <input type="text" name="customer_phone" class="form-control" required>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Recording Duration <span>*</span></label>
                    <input type="text" name="recording_duration" placeholder="HH:MM:SS" id="duration" class="form-control" required>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Recording Link <span>*</span></label>
                    <input type="text" name="recording_link" class="form-control" required>
                </div>
            </div>



            <!-- optional fields -->

            @foreach ($voice_evaluation->customFieldsBottom as $item)
                @php
                    $campaign_names = $item->campaigns->pluck('name')->toArray();
                    $campaign_names = implode(", ", $campaign_names);
                @endphp
                <div class="@if($item->type == 'textarea') col-md-12 @else col-md-4 @endif">
                    <div class="form-group">
                        <label for="">
                            {{ $item->label }}
                            @if($item->required == 1) <span>*</span> @endif

                            @if (count($item->campaigns) > 0)
                                <small>({{ $campaign_names }})</small>
                            @endif

                            <a href="{{ route('voice-custom-fields.edit', $item) }}" style="color: #000;" class="ml-3"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('voice-custom-fields.destroy', $item) }}" style="display: inline-block;" method="post">
                                @csrf
                                @method('delete')
                                <button type="submit" style="color: #000; border: none; background-color: transparent;" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </label>

                        @if($item->type == 'text')
                            <input type="text" class="form-control" name="custom-{{ $item->id }}" placeholder="{{ $item->placeholder }}">
                        @elseif($item->type == 'dropdown')
                            @php
                                $options = explode(',', $item->options)
                            @endphp

                            <select name="custom-{{ $item->id }}" class="form-control select2">
                                <option value="">Select Option</option>
                                @foreach ($options as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                        @elseif($item->type == 'textarea')
                            <textarea name="custom-{{ $item->id }}" rows="3" class="form-control" placeholder="{{ $item->placeholder }}"></textarea>
                        @endif

                    </div>
                </div>
            @endforeach

            <!-- end optional fields -->

            <div class="col-md-12">
                <div class="form-group">
                    <label for="">Notes</label>
                    <textarea name="notes" rows="3" class="form-control"></textarea>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Outcome <span>*</span></label>
                    <select name="outcome" class="form-control select2" required>
                        <option value="">Select Option</option>
                        <option value="accepted">Accepted</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Billable Status <span>*</span></label>
                    <select name="billable_status" id="" class="form-control select2" required>
                        <option value="">Select Option</option>
                        <option value="billable">Billable</option>
                        <option value="non-billable">Non Billable</option>
                    </select>
                </div>
            </div>

        </div>

    </div>

</div>
<!-- /.card -->


@endsection
