<div class="row">

    <div class="col-md-4">
        <div class="form-group">
            <label for="">Record ID <span>*</span></label>

            @if($voice_audit->campaign->database_name)
                <input type="hidden" class="form-control" name="record_id" value="{{ $voice_audit->record_id }}" required>
                <input type="text" class="form-control" name="" value="{{ $voice_audit->record_id }}" disabled>
            @else
                <input type="text" class="form-control" name="record_id" value="{{ $voice_audit->record_id }}" placeholder="Enter Record ID" required>
            @endif

        </div>
        @error('record_id')
            <div class="validate-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="">Campaign Name</label>
            <input type="text" class="form-control" id="campaign" value="{{ $voice_audit->associate->campaign->name ?? '' }}" name="" disabled>
        </div>
        @error('campaign_id')
            <div class="validate-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="">Call Date <span>*</span></label>

            @if($voice_audit->campaign->database_name)
                <input type="hidden" class="form-control" name="call_date" value="{{ $voice_audit->call_date }}" required>
                <input type="text" class="form-control" value="{{ $voice_audit->call_date }}" name="call_date" disabled>
            @else
                <input type="text" class="form-control datetimepicker-input datepicker" name="call_date" data-toggle="datetimepicker" data-target=".datepicker" value="{{ $voice_audit->call_date }}" required>
            @endif

        </div>
        @error('call_date')
            <div class="validate-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="">Agent Name<span>*</span></label>

            @if($voice_audit->campaign->database_name)
                <input type="hidden" class="form-control" name="associate_id" value="{{ $voice_audit->associate_id }}" required><input type="text" class="form-control" id="reporting" name="" value="{{ $voice_audit->associate->name }}" disabled>
            @else
                <select name="associate_id" id="agent" class="form-control select2" required>
                    <option value="">Select Option</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @if($user->id == $voice_audit->associate_id) selected @endif>{{ $user->name }}</option>
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
            <input type="hidden" class="form-control" id="reporting_id" value="{{ $voice_audit->team_lead_id ?? '' }}" name="team_lead_id">
            <input type="text" class="form-control" id="reporting" value="{{ $voice_audit->associate->supervisor->name ?? '' }}" name="" disabled>
        </div>
        @error('reporting')
            <div class="validate-error">{{ $message }}</div>
        @enderror
    </div>

    <!-- optional fields -->

    @foreach ($voice_audit->fields as $field)
        @php
            $item = $field->field;
        @endphp

        @if($item->position == 'top')

            <div class="col-md-4">
                <div class="form-group">
                    <label for="">{{ $item->label }} @if($item->required == 1) <span>*</span> @endif</label>

                    @if($item->type == 'text')
                        <input type="text" class="form-control" name="customfield-{{ $item->id }}" value="{{ $field->answer }}" placeholder="{{ $item->placeholder }}" @if($item->required == 1) required @endif>
                    @elseif($item->type == 'dropdown')
                        @php
                            $options = explode(',', $item->options)
                        @endphp

                        <select name="customfield-{{ $item->id }}" class="form-control select2" @if($item->required == 1) required @endif>
                            <option value="">Select Option</option>
                            @foreach ($options as $option)
                                <option value="{{ trim($option) }}" @if($field->answer == trim($option)) selected @endif>{{ trim($option) }}</option>
                            @endforeach
                        </select>
                    @elseif($item->type == 'textarea')
                        <textarea name="customfield-{{ $item->id }}" rows="3" class="form-control" placeholder="{{ $item->placeholder }}" @if($item->required == 1) required @endif>{{ $field->answer }}</textarea>
                    @endif

                </div>
            </div>

        @endif

    @endforeach

    <!-- end optional fields -->

</div>
