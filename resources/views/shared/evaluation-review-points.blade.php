@php $points = 0; @endphp
@if (count($categories) > 0)
    @foreach ($categories as $key => $category)
        @php
            $column_name = str_replace(' ', '_', strtolower($key));
        @endphp
        <div class="category">
            <div class="title">
                <h4>{{ $key }}
                    <small><small>(Score: {{ $voice_audit->$column_name ?? 0 }}%)</small></small>
                </h4>
            </div>

            @if (count($category) > 0)
                <div class="data-points table-responsive">
                    <table class="table table-hover">
                        @foreach ($category as $item)
                            @php $points++; @endphp
                            <tr>
                                <td width="25%">{{ $item->datapoint->name ?? '' }}</td>
                                <td>{{ $item->datapoint->question ?? '' }}</td>
                                <td class="radios">
                                    <label class="radio-inline qrating"><input type="radio" class="radio" value="1" name="answer-{{ $item->id }}" @if($item->answer == 1) checked @else disabled @endif>Yes</label>
                                    <label class="radio-inline qrating"><input type="radio" class="radio" value="0" name="answer-{{ $item->id }}" @if($item->answer == 0) checked @else disabled @endif>No</label>
                                    <label class="radio-inline qrating"><input type="radio" class="radio" value="6" name="answer-{{ $item->id }}" @if($item->answer == 6) checked @else disabled @endif>N/A</label>
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
