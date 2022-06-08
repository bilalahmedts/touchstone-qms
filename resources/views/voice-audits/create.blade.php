@extends('layouts.user')

@section('title', $voice_evaluation->name ?? 'New Evaluation')


@section('content')

    <div class="back-area mb-3">
        <a href="{{ route('voice-audits.index', $voice_evaluation->id) }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-2"></i> Go Back</a>
    </div>


    <form action="{{ route('voice-audits.store') }}" method="post" autocomplete="off">
        @csrf

        <div class="timer-area" style="float: right;">
            <div class="timer" id="timer" style="font-size: 18px;"></div>
            <input type="hidden" name="evaluation_time" class="timer" id="evaluation_time">
        </div>

        <input type="hidden" name="voice_evaluation_id" value="{{ $voice_evaluation->id }}" required>

        <div class="search-area">
            <div class="row">

                <div class="col-md-6">
                    <h4 class="mb-0">New {{ $voice_evaluation->name ?? 'Evaluation' }}</h4>
                </div>

            </div>
        </div>

        <!-- Default box -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Information (Step 1 of 3)</h3>

            </div>
            <div class="card-body">

                <div class="row">

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Record ID <span>*</span></label>

                            @if($campaign->database_name)
                                <input type="hidden" class="form-control" name="record_id" value="{{ $record['record_id'] }}" required>
                                <input type="text" class="form-control" name="" value="{{ $record['record_id'] }}" disabled>
                            @else
                                <input type="text" class="form-control" name="record_id" @if(isset($_GET['record_id'])) value="{{ $_GET['record_id'] }}" @endif placeholder="Enter Record ID" required>
                            @endif

                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Campaign Name</label>
                            <input type="hidden" class="form-control" name="campaign_id" value="{{ $campaign->id }}" required>
                            <input type="text" class="form-control" id="campaign" name="" value="{{ $campaign->name ?? '' }}" disabled>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Call Date <span>*</span></label>

                            @if($campaign->database_name)
                                <input type="hidden" class="form-control" name="call_date" value="{{ $record['date'] }}" required>
                                <input type="text" class="form-control" name="" value="{{ $record['date'] }}" disabled>
                            @else
                                <input type="text" class="form-control datetimepicker-input datepicker" name="call_date" data-toggle="datetimepicker" data-target=".datepicker" required>
                            @endif

                        </div>
                        @error('call_date')
                            <div class="validate-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Agent Name <span>*</span></label>

                            @if($campaign->database_name)
                                <input type="hidden" class="form-control" name="associate_id" value="{{ $user->id }}" required>
                                <input type="text" class="form-control" name="" value="{{ $user->name }}" disabled>
                            @else
                                <select name="associate_id" id="agent" class="form-control select2" required>
                                    <option value="">Select Option</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            @endif

                        </div>
                        @error('associate_id')
                            <div class="validate-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Reporting To</label>

                            @if($campaign->database_name)
                                <input type="hidden" class="form-control" id="reporting_id" value="{{ $user->supervisor->id ?? '' }}" name="team_lead_id">
                                <input type="text" class="form-control" id="reporting" name="" value="{{ $user->supervisor->name ?? '' }}" disabled>
                            @else
                                <input type="hidden" class="form-control" id="reporting_id" value="" name="team_lead_id">
                                <input type="text" class="form-control" id="reporting" name="" value="" disabled>
                            @endif


                        </div>
                    </div>

                    <!-- optional fields -->

                    @foreach ($voice_evaluation->customFieldsTop as $item)
                        @php
                            $campaign_ids = $item->campaigns->pluck('id')->toArray();
                        @endphp
                        @if(count($campaign_ids) == 0 || in_array($campaign->id, $campaign_ids))
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">{{ $item->label }} @if($item->required == 1) <span>*</span> @endif</label>

                                    @if($item->type == 'text')
                                        <input type="text" class="form-control" name="customfield-{{ $item->id }}" placeholder="{{ $item->placeholder }}" @if($item->required == 1) required @endif>
                                    @elseif($item->type == 'dropdown')
                                        @php
                                            $options = explode(',', $item->options)
                                        @endphp
                                        <select name="customfield-{{ $item->id }}" class="form-control select2" @if($item->required == 1) required @endif>
                                            <option value="">Select Option</option>
                                            @foreach ($options as $option)
                                                <option value="{{ trim($option) }}">{{ trim($option) }}</option>
                                            @endforeach
                                        </select>
                                    @elseif($item->type == 'textarea')
                                        <textarea name="customfield-{{ $item->id }}" rows="3" class="form-control" placeholder="{{ $item->placeholder }}" @if($item->required == 1) required @endif></textarea>
                                    @endif
                                </div>
                            </div>
                        @endif
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
            </div>

            <div class="card-body">

                @php $points = 0; @endphp
                @if (count($categories) > 0)
                    @foreach ($categories as $category)

                        @php
                            $campaign_ids = $category->campaigns->pluck('id')->toArray();
                        @endphp

                        @if(count($campaign_ids) == 0 || in_array($campaign->id, $campaign_ids))
                            <div class="category">
                                <div class="title">
                                    <h4>{{ $category->name }}</h4>
                                </div>

                                @if (count($category->datapoints) > 0)
                                    <div class="data-points">
                                        <table class="table table-hover">
                                            @foreach ($category->datapoints as $item)
                                                @php $points++; @endphp
                                                <tr>
                                                    <td width="25%">{{ $item->name }}</td>
                                                    <td>{{ $item->question }}</td>
                                                    <td class="radios">
                                                        <label class="radio-inline qrating"><input type="radio"
                                                                class="radio" value="1"
                                                                name="answer-{{ $item->id }}">Yes</label>
                                                        <label class="radio-inline qrating"><input type="radio"
                                                                class="radio" value="0"
                                                                name="answer-{{ $item->id }}">No</label>
                                                        <label class="radio-inline qrating"><input type="radio"
                                                                class="radio" value="6"
                                                                name="answer-{{ $item->id }}" checked>N/A</label>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>

                                @else
                                    <h5 class="text-center">No data points found!</h5>
                                @endif

                            </div>
                        @endif
                    @endforeach
                @else
                    <h4 class="text-center">No records found</h4>
                @endif

            </div>

        </div>
        <!-- /.card -->

        <!-- Default box -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Evaluation (Step 3 of 3)</h3>
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

                            @if($campaign->database_name)
                                <input type="hidden" class="form-control" name="customer_name" value="{{ $record['customer_first_name'] }} {{ $record['customer_last_name'] }}" required>
                                <input type="text" name="" class="form-control" value="{{ $record['customer_first_name'] }} {{ $record['customer_last_name'] }}" disabled>
                            @else
                                <input type="text" name="customer_name" class="form-control" placeholder="Enter Customer Name" required>
                            @endif


                        </div>
                        @error('customer_name')
                            <div class="validate-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Customer Phone <span>*</span></label>

                            @if($campaign->database_name)
                                <input type="hidden" class="form-control" name="customer_phone" value="{{ $record['phone'] }}" required>
                                <input type="text" name="" class="form-control" value="{{ $record['phone'] }}" disabled>
                            @else
                                <input type="text" name="customer_phone" class="form-control" placeholder="Enter Customer Phone" required>
                            @endif


                        </div>
                        @error('customer_phone')
                            <div class="validate-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Recording Duration <span>*</span></label>
                            <input type="text" name="recording_duration" placeholder="HH:MM:SS" id="duration" class="form-control" required>
                        </div>
                        @error('recording_duration')
                            <div class="validate-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Recording Link</label>
                            <input type="text" name="recording_link" class="form-control">
                        </div>
                        @error('recording_link')
                            <div class="validate-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- optional fields -->

                    @foreach ($voice_evaluation->customFieldsBottom as $item)
                        @php
                            $campaign_ids = $item->campaigns->pluck('id')->toArray();
                        @endphp
                        @if(count($campaign_ids) == 0 || in_array($campaign->id, $campaign_ids))
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">{{ $item->label }} @if($item->required == 1) <span>*</span> @endif</label>

                                    @if($item->type == 'text')
                                        <input type="text" class="form-control" name="customfield-{{ $item->id }}" placeholder="{{ $item->placeholder }}" @if($item->required == 1) required @endif>
                                    @elseif($item->type == 'dropdown')
                                        @php
                                            $options = explode(',', $item->options)
                                        @endphp
                                        <select name="customfield-{{ $item->id }}" class="form-control select2" @if($item->required == 1) required @endif>
                                            <option value="">Select Option</option>
                                            @foreach ($options as $option)
                                                <option value="{{ trim($option) }}">{{ trim($option) }}</option>
                                            @endforeach
                                        </select>
                                    @elseif($item->type == 'textarea')
                                        <textarea name="customfield-{{ $item->id }}" rows="3" class="form-control" placeholder="{{ $item->placeholder }}" @if($item->required == 1) required @endif></textarea>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach

                    <!-- end optional fields -->

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Notes <span>*</span></label>
                            <textarea name="notes" rows="3" class="form-control" required></textarea>
                        </div>
                        @error('notes')
                            <div class="validate-error">{{ $message }}</div>
                        @enderror
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
                        @error('outcome')
                            <div class="validate-error">{{ $message }}</div>
                        @enderror
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
                        @error('billable_status')
                            <div class="validate-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <div class="custom-control custom-switch custom-switch-md mt-3">
                            <input type="checkbox" value="1" name="review_priority" class="custom-control-input" id="customSwitch1">
                            <label class="custom-control-label" for="customSwitch1">Send Critical Alert</label>
                        </div>
                    </div>

                </div>

            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            <!-- /.card-footer-->

        </div>
        <!-- /.card -->

    </form>

@endsection


@section('scriptfiles')
    <script type='text/javascript' src="{{ asset('assets/plugins/timer/timer.jquery.min.js') }}"></script>
    <script type='text/javascript' src="{{ asset('assets/js/jquery.idle.js') }}"></script>
@endsection



@section('scripts')

    <script>
        $(function() {

            //start a timer
            var timer = $('.timer');

            timer.timer({
                format: '%H:%M:%S'
            });

            $('.datepicker').datetimepicker({
                format: 'L',
                format: 'DD-MM-YYYY',
                keepInvalid: false
            });

            $('#agent').on('change', function() {
                var user_id = this.value;

                if (user_id != "") {
                    $.ajax({
                        url: `{{ route('main') }}/get-user-detail/${user_id}`,
                        type: 'GET',
                        dataType: 'json', // added data type
                        success: function(res) {
                            $("#reporting_id").val(res.reporting_id);
                            $("#reporting").val(res.reporting_to);
                        }
                    });
                }
            });

            $(".qrating input").click(function percentage() {
                let percentage = 0;
                let total = {{ $points }};
                var checkedButtons = $("input[type='radio']:checked");
                checkedButtons.each(function(chkdButton) {
                    if ($(this).val() == "1" || $(this).val() == "6") {
                        percentage = percentage + 1;
                    }
                });

                resultPercentage = (percentage / total) * 100;
                $(".percentage").val(resultPercentage.toFixed(2));
            });

            $("#duration").inputmask({
                mask: '99:99:99',
                placeholder: ' ',
                showMaskOnHover: false,
                showMaskOnFocus: false,
                onBeforePaste: function(pastedValue, opts) {
                    var processedValue = pastedValue;

                    //do something with it

                    return processedValue;
                }
            });

            $(document).idle({
                onIdle: function(){
                    window.location.href = `{{ route('main') }}/logout`;
                },
                onActive: function(){

                },
                idle: 60000 * 1
            });


        });

    </script>

@endsection
