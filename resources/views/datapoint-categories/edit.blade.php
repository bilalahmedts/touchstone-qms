
@extends('layouts.user')

@section('title', 'Edit Category')


@section('content')

<div class="back-area mb-3">
    <a href="{{ route('voice-evaluations.show', $datapoint_category->evaluation) }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-2"></i> Go Back</a>
</div>

<!-- Default box -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Datapoint Category</h3>
    </div>

    <form action="{{ route('datapoint-categories.update', $datapoint_category) }}" method="post" autocomplete="off">
        @csrf
        @method('put')
        <div class="card-body">
            <input type="hidden" name="voice_evaluation_id" value="{{ $datapoint_category->voice_evaluation_id }}" required>
            <div class="form-group">
                <label for="exampleInputEmail1">Datapoint Category Name <span>*</span></label>
                <input type="text" class="form-control" name="name" value="{{ old('name', $datapoint_category->name) }}" placeholder="Enter Datapoint Category Name" required>
            </div>
            @error('name')
                <div class="validate-error">{{ $message }}</div>
            @enderror

            @php
                $campaign_ids = $datapoint_category->campaigns->pluck('id')->toArray();
            @endphp

            <div class="form-group">
                <label for="">Select Campaigns (Optional)</label>
                <select name="campaigns[]" id="campaigns" class="form-control select2" multiple>
                    <option value="">Select Option</option>
                    @foreach ($campaigns as $campaign)
                        <option value="{{ $campaign->id }}" @if(in_array($campaign->id, $campaign_ids)) selected @endif>{{ $campaign->name }}</option>
                    @endforeach
                </select>
            </div>
            @error('campaign_id')
                <div class="validate-error">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="exampleInputEmail1">Sort Order <span>*</span></label>
                <input type="text" class="form-control" name="sort" value="{{ old('sort', $datapoint_category->sort) }}" placeholder="Enter Sort Order" required>
            </div>
            @error('sort')
                <div class="validate-error">{{ $message }}</div>
            @enderror
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        <!-- /.card-footer-->

    </form>
</div>
<!-- /.card -->

@endsection
