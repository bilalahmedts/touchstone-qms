
@extends('layouts.user')

@section('title', $voice_evaluation->name ?? 'Voice Audits')


@section('content')


<div class="search-area">
    <div class="row">

        <div class="col-md-6">
            <h4 class="mb-0">Search</h4>
        </div>
        <div class="col-md-6">
            <div class="button-area">
                <button type="button" id="btn-search" class="btn btn-primary"><i class="fas fa-filter"></i></button>
            </div>
        </div>

    </div>
    @if($voice_evaluation)
        <form action="{{ route('voice-audits.index', $voice_evaluation->id) }}" method="get" autocomplete="off">
    @else
        <form action="{{ route('voice-audits.index') }}" method="get" autocomplete="off">
    @endif
        <input type="hidden" name="search" value="1">
        @php
            $record_id = '';
            $user_id = -1;
            $associate_id = -1;
            $campaign_id = -1;
            $outcome = '';
            $review = '';
            $from_date = '';
            $to_date = '';
            $from_time= '';
            $to_time = '';

            if(isset($_GET['search'])){
                $user_id = $_GET['user_id'];
                $associate_id = $_GET['associate_id'];
                $campaign_id = $_GET['campaign_id'];
                $outcome = $_GET['outcome'];
                $review = $_GET['review'];

                if (!empty($_GET['from_date'])) {
                    $from_date = $_GET['from_date'];
                }
                if (!empty($_GET['to_date'])) {
                    $to_date = $_GET['to_date'];
                }
                if (!empty($_GET['record_id'])) {
                    $record_id = $_GET['record_id'];
                }

                if (!empty($_GET['from_time'])) {
                    $from_time = $_GET['from_time'];
                }
                if (!empty($_GET['to_time'])) {
                    $to_time = $_GET['to_time'];
                }
            }

        @endphp

        <div class="card card-primary card-outline mt-3" id="search" @if(isset($_GET['search'])) style="display: block;" @endif>
            <div class="card-body">
                <div class="row">

                    <div class="form-group col-md-4">
                        <label for="">Record ID</label>
                        <input type="text" class="form-control" name="record_id" value="{{ $record_id }}" />
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">Select Evaluator</label>
                        <select name="user_id" class="form-control select2">
                            <option value="-1">Select Option</option>
                            @foreach ($users as $user)
                                @if($user->campaign_id == 135)
                                    <option value="{{ $user->id }}" @if($user->id == $user_id) selected @endif>{{ $user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">Select Associate</label>
                        <select name="associate_id" class="form-control select2">
                            <option value="-1">Select Option</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @if($user->id == $associate_id) selected @endif>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">Select Campaign</label>
                        <select name="campaign_id" class="form-control select2">
                            <option value="-1">Select Option</option>
                            @foreach ($campaigns as $campaign)
                                <option value="{{ $campaign->id }}" @if($campaign->id == $campaign_id) selected @endif>{{ $campaign->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">Select Outcome</label>
                        <select name="outcome" class="form-control select2">
                            <option value="">Select</option>
                            <option value="accepted" @if($outcome == 'accepted') selected @endif>Accepted</option>
                            <option value="rejected" @if($outcome == 'rejected') selected @endif>Rejected</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">Select Review Status</label>
                        <select name="review" class="form-control select2">
                            <option value="">Select</option>
                            <option value="pending" @if($review == 'pending') selected @endif>Pending</option>
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">From Date</label>
                        <input type="text" class="form-control datetimepicker-input datepicker1" name="from_date"
                            value="{{ $from_date }}" data-toggle="datetimepicker" data-target=".datepicker1" />
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">To Date</label>
                        <input type="text" class="form-control datetimepicker-input datepicker2" name="to_date"
                            value="{{ $to_date }}" data-toggle="datetimepicker" data-target=".datepicker2" />
                    </div>

                    <div class="form-group col-md-4"></div>

                    <div class="form-group col-md-4">
                        <label for="">From Time</label>
                        <input type="text" class="form-control datetimepicker-input datepicker3" name="from_time"
                            value="{{ $from_time }}" data-toggle="datetimepicker" data-target=".datepicker3" />
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">To Time</label>
                        <input type="text" class="form-control datetimepicker-input datepicker4" name="to_time"
                            value="{{ $to_time }}" data-toggle="datetimepicker" data-target=".datepicker4" />
                    </div>

                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Search</button>
                @if($voice_evaluation)
                    <a href="{{ route('voice-audits.index', $voice_evaluation->id) }}" class="ml-5">Clear Search</a>
                @else
                    <a href="{{ route('voice-audits.index') }}" class="ml-5">Clear Search</a>
                @endif
            </div>
        </div>
    </form>

</div>

<!-- Default box -->
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">{{ $voice_evaluation->name ?? 'Voice Audits' }} List</h3>
        @if($voice_evaluation)
            <div class="card-tools">
                @if(in_array(Auth::user()->roles[0]->name, ['Team Lead', 'Manager', 'Associate']) && Auth::user()->campaign_id == 135)
                    <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-audit" data-controls-modal="#modal-audit" data-backdrop="static" data-keyboard="false">
                        <i class="fas fa-plus"></i> Start Audit
                    </a>
                @endif
                <a href="{{ route('export.voice-audits') }}?record_id={{ $record_id }}&user_id={{$user_id}}&associate_id={{ $associate_id }}&campaign_id={{ $campaign_id }}&outcome={{ $outcome }}&review={{ $review }}&from_date={{ $from_date }}&to_date={{ $to_date }}&from_time={{ $from_time }}&to_time={{ $to_time }}" class="btn btn-success btn-sm ml-2" onclick="return confirm('Are you sure?')">Export Report</a>
            </div>
        @endif
    </div>

    <div class="card-body">

        <table class="table table-bordered">
            <thead>
              <tr>
                <th>@sortablelink('call_date', 'Record ID')</th>
                <th>@sortablelink('user_id', 'Evaluator')</th>
                <th>@sortablelink('associate_id', 'Associate')</th>
                <th>Evaluation Time</th>
                <th>Call Time</th>
                <th>Result</th>
                <th>Outcome</th>
                <th>Status</th>
                <th>@sortablelink('created_at', 'Created Time')</th>
                <th class="action">Action</th>
              </tr>
            </thead>
            <tbody>

                @if(count($voice_audits) > 0)

                    @foreach ($voice_audits as $audit)
                        <tr>
                            <td>
                                {{ $audit->record_id ?? 0 }}<br>
                                {{ $audit->call_date }}
                            </td>
                            <td>{{ $audit->user->name ?? 'undefined' }}</td>
                            <td>
                                {{ $audit->associate->name ?? 'undefined' }}
                                <br>({{ $audit->campaign->name ?? 'undefined' }})
                            </td>
                            <td>{{ $audit->evaluation_time }}</td>
                            <td>{{ $audit->recording_duration }}</td>
                            <td>{{ $audit->percentage }}%</td>
                            <td>
                                @if($audit->outcome == 'accepted')
                                    <span class="badge bg-success">Accepted</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            <td>
                                @include('shared.voice-audit-status', ['status' => $audit->status])
                            </td>
                            <td>{{ $audit->created_at->format('d-m-Y g:i:s A') }}</td>
                            <td class="action">
                                <a href="{{ route('voice-audits.show', $audit) }}" class="btn btn-success btn-sm"><i class="fas fa-eye"></i></a>
                                @if(
                                    (in_array(Auth::user()->roles[0]->name, ['Director', 'Team Lead', 'Manager', 'Associate']) && Auth::user()->campaign_id == 135) ||
                                    (in_array(Auth::user()->roles[0]->name, ['Super Admin']))
                                    )

                                    @php
                                        $access = true;
                                        if(Auth::user()->roles[0]->name == 'Associate' && $audit->user_id == Auth::user()->id){
                                            $diff = $audit->created_at->diffInHours(now());
                                            if($diff > 24){
                                                $access = false;
                                            }
                                        }
                                    @endphp

                                    @if($access)
                                        <a href="{{ route('voice-audits.edit', $audit) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                                    @endif

                                    @if(Auth::user()->roles[0]->name == 'Super Admin')
                                        <form action="{{ route('voice-audits.destroy', $audit) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="10" class="text-center">No record found!</td></tr>
                @endif

            </tbody>
        </table>

    </div>

    @if($voice_audits->total() > 15)
        <!-- /.card-body -->
        <div class="card-footer clearfix">
            {{ $voice_audits->appends(request()->input())->links() }}
        </div>
        <!-- /.card-footer-->
    @endif
</div>
<!-- /.card -->




<div class="modal fade" id="modal-audit">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">New Audit</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('voice-audits.create', $voice_evaluation) }}" method="get" autocapitalize="off">
                <div class="modal-body">

                    <div class="form-group">
                        <label for="">Select Campaign <span>*</span></label>
                        <select name="campaign_id" id="campaign_id" class="form-control select2" required>
                            <option value="">Select Option</option>
                            @foreach ($campaigns as $campaign)
                                <option value="{{ $campaign->id }}">{{ $campaign->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('campaign_id')
                        <div class="validate-error">{{ $message }}</div>
                    @enderror

                    <div class="form-group">
                        <label for="">Record ID <span>*</span></label>
                        <input type="text" class="form-control" name="record_id" placeholder="Enter Record ID" required>
                    </div>
                    @error('record_id')
                        <div class="validate-error">{{ $message }}</div>
                    @enderror

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>

            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->




@endsection



@section('scripts')

<script>

    $(function () {
        $("#btn-search").click(function(e){
            e.preventDefault();
            $("#search").slideToggle();
        });

        $('.datepicker1').datetimepicker({
            format: 'L',
            format: 'DD/MM/YYYY',
            keepInvalid: false
        });

        $('.datepicker2').datetimepicker({
            format: 'L',
            format: 'DD/MM/YYYY',
            keepInvalid: false
        });

        $('.datepicker3').datetimepicker({
            format: 'L',
            format: 'hh:mm:ss A',
            keepInvalid: false
        });

        $('.datepicker4').datetimepicker({
            format: 'L',
            format: 'hh:mm:ss A',
            keepInvalid: false
        });

    });

</script>

@endsection
