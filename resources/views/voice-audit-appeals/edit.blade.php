@extends('layouts.user')

@section('title', 'Edit Voice Audit')


@section('content')

    <div class="back-area mb-3">
        <a href="{{ route('voice-audit-appeals.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-2"></i> Go Back</a>
    </div>

    <div class="search-area">
        <div class="row">

            <div class="col-md-6">
                <h4 class="mb-0">Edit Voice Audit Appeal <small>(ID: {{ $voice_audit->id }})</small></h4>
            </div>

        </div>
    </div>


    <form action="{{ route('voice-audits.update', $voice_audit) }}" method="post" autocomplete="off">
        @csrf
        @method('put')

        <input type="hidden" name="voice_evaluation_id" value="{{ $voice_evaluation->id }}" required>

        <!-- Default box -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Information (Step 1 of 3)</h3>
            </div>
            <div class="card-body">

                @include('shared.voice-audit-edit-information', ['voice_audit' => $voice_audit])

            </div>

        </div>
        <!-- /.card -->

        <!-- Default box -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Data Points (Step 2 of 3)</h3>
            </div>

            <div class="card-body">

                @php
                    $points = $voice_audit->points->count();
                @endphp

                @include('shared.voice-audit-edit-datapoints', ['categories' => $categories])

            </div>

        </div>
        <!-- /.card -->

        <!-- Default box -->
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Evaluation (Step 3 of 3)</h3>
            </div>
            <div class="card-body">

                @include('shared.voice-audit-edit-evaluation', ['voice_audit' => $voice_audit])

            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            <!-- /.card-footer-->

        </div>
        <!-- /.card -->

    </form>

@endsection



@section('scripts')

    <script>
        $(function() {

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
                            $("#reporting").val(res.reporting_to);
                            $("#campaign").val(res.campaign);
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

        });
    </script>

@endsection
