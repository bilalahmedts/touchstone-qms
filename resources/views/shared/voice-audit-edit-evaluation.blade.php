<div class="row">

    <div class="col-md-4">
        <div class="form-group">
            <label for="">Percentage</label>
            <input type="hidden" name="percentage" value="{{ $voice_audit->percentage }}" class="form-control percentage">
            <input type="text" name="" value="{{ $voice_audit->percentage }}" class="form-control percentage" disabled>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="">Customer Name <span>*</span></label>

            @if($voice_audit->campaign->database_name)
                <input type="hidden" class="form-control" name="customer_name" value="{{ $voice_audit->customer_name }}" required>
                <input type="text" name="customer_name" value="{{ $voice_audit->customer_name }}" class="form-control" disabled>
            @else
                <input type="text" name="customer_name" class="form-control" value="{{ $voice_audit->customer_name }}" placeholder="Enter Customer Name" required>
            @endif

        </div>
        @error('customer_name')
            <div class="validate-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="">Customer Phone <span>*</span></label>

            @if($voice_audit->campaign->database_name)
                <input type="hidden" class="form-control" name="customer_phone" value="{{ $voice_audit->customer_phone }}" required>
                <input type="text" name="customer_phone" value="{{ $voice_audit->customer_phone }}" class="form-control" disabled>
            @else
                <input type="text" name="customer_phone" class="form-control" value="{{ $voice_audit->customer_phone }}" placeholder="Enter Customer Phone" required>
            @endif

        </div>
        @error('customer_phone')
            <div class="validate-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="">Recording Duration <span>*</span></label>
            <input type="text" name="recording_duration" value="{{ $voice_audit->recording_duration }}" placeholder="HH:MM:SS" id="duration" class="form-control" required>
        </div>
        @error('recording_duration')
            <div class="validate-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="">Recording Link</label>
            <input type="text" name="recording_link" value="{{ $voice_audit->recording_link }}" class="form-control">
        </div>
        @error('recording_link')
            <div class="validate-error">{{ $message }}</div>
        @enderror
    </div>

    <!-- optional fields -->

    @foreach ($voice_audit->fields as $field)
        @php
            $item = $field->field;
        @endphp

        @if($item->position == 'bottom')

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

    <div class="col-md-12">
        <div class="form-group">
            <label for="">Notes <span>*</span></label>
            <textarea name="notes" rows="3" class="form-control" required>{{ $voice_audit->notes }}</textarea>
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
                <option value="accepted" @if($voice_audit->outcome == 'accepted') selected @endif>Accepted</option>
                <option value="rejected" @if($voice_audit->outcome == 'rejected') selected @endif>Rejected</option>
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
                <option value="billable" @if($voice_audit->billable_status == 'billable') selected @endif>Billable</option>
                <option value="non-billable" @if($voice_audit->billable_status == 'non-billable') selected @endif>Non Billable</option>
            </select>
        </div>
        @error('billable_status')
            <div class="validate-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12">
        <div class="custom-control custom-switch custom-switch-md mt-3">
            <input type="checkbox" value="1" name="review_priority" class="custom-control-input" id="customSwitch1" @if($voice_audit->review_priority == 1) checked @endif>
            <label class="custom-control-label" for="customSwitch1">Send Critical Alert</label>
        </div>
    </div>

</div>
