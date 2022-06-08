
@extends('layouts.user')

@section('title', 'Associates Report')


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

    <form action="{{ route('voice-reports.associates') }}" method="get" autocomplete="off">
        <input type="hidden" name="search" value="1">
        @php
            $search_id = -1;
            $from_date = '';
            $to_date = '';
            $from_time= '';
            $to_time = '';

            if(isset($_GET['search'])){
                $search_id = $_GET['search_id'];

                if (!empty($_GET['from_date'])) {
                    $from_date = $_GET['from_date'];
                }
                if (!empty($_GET['to_date'])) {
                    $to_date = $_GET['to_date'];
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
                        <label for="">Select Associate</label>
                        <select name="search_id" class="form-control select2">
                            <option value="-1">Select Option</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @if($user->id == $search_id) selected @endif>{{ $user->name }}</option>
                            @endforeach
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
                <a href="{{ route('voice-reports.associates') }}" class="ml-5">Clear Search</a>
            </div>
        </div>
    </form>

</div>

<!-- Default box -->
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Associates Report</h3>
    </div>

    <div class="card-body">

        <table class="table table-bordered">
            <thead>
              <tr>
                <th>Associate Name</th>
                <th class="text-center">Accepted</th>
                <th class="text-center">Rejected</th>
                <th class="text-center">Total</th>
                <th class="text-center">Total Score</th>
                <th class="text-center">Communication</th>
                <th class="text-center">Sales</th>
                <th class="text-center">Compliance</th>
                <th class="text-center">Customer Service</th>
                <th class="text-center">Product Presentation</th>
                <th class="action">Action</th>
              </tr>
            </thead>
            <tbody>

                @if(count($user_evaluations) > 0)
                    @foreach ($user_evaluations as $item)
                        @php
                            $accepted = 0;
                            $rejected = 0;
                            $total_percentage = 0;
                            $communication = 0;
                            $sales = 0;
                            $compliance = 0;
                            $customer_service = 0;
                            $product_presentation = 0;

                            if(count($item->associateVoiceAudits) > 0){
                                foreach($item->associateVoiceAudits as $audit){
                                    ($audit->outcome == 'accepted') ? $accepted++ : $rejected++;
                                    $communication = $communication + $audit->communication;
                                    $sales = $sales + $audit->sales;
                                    $compliance = $compliance + $audit->compliance;
                                    $customer_service = $customer_service + $audit->customer_service;
                                    $product_presentation = $product_presentation + $audit->product_presentation;
                                }

                                $communication = $communication / count($item->associateVoiceAudits);
                                $sales =  $sales / count($item->associateVoiceAudits);
                                $compliance =  $compliance / count($item->associateVoiceAudits);
                                $customer_service =  $customer_service / count($item->associateVoiceAudits);
                                $product_presentation =  $product_presentation / count($item->associateVoiceAudits);

                                $total_percentage = $communication + $sales + $compliance + $customer_service + $product_presentation;

                                $total_percentage =  $total_percentage / 5;
                            }


                        @endphp
                        <tr>
                            <td>
                                {{ $item->name }}
                                <br>
                                ({{ $item->campaign->name }})
                            </td>
                            <td class="text-center">{{ $accepted }}</td>
                            <td class="text-center">{{ $rejected }}</td>
                            <td class="text-center">{{ count($item->associateVoiceAudits) }}</td>
                            <td class="text-center">{{ round($total_percentage) }}%</td>
                            <td class="text-center">{{ round($communication) }}%</td>
                            <td class="text-center">{{ round($sales) }}%</td>
                            <td class="text-center">{{ round($compliance) }}%</td>
                            <td class="text-center">{{ round($customer_service) }}%</td>
                            <td class="text-center">{{ round($product_presentation) }}%</td>
                            <td class="action">
                                <a href="{{ route('voice-audits.index', 1) }}?search=1&record_id=&user_id=&associate_id={{ $item->id }}&campaign_id=&outcome=&from_date={{ $from_date }}&to_date={{ $to_date }}&from_time={{ $from_time }}&to_time={{ $to_time }}&review=" class="btn btn-success btn-sm" target="_blank"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="10" class="text-center">No records found!</td></tr>
                @endif

            </tbody>
        </table>

    </div>

    @if($user_evaluations->total() > 15)
        <!-- /.card-body -->
        <div class="card-footer clearfix">
            {{ $user_evaluations->appends(request()->input())->links() }}
        </div>
        <!-- /.card-footer-->
    @endif
</div>
<!-- /.card -->



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
