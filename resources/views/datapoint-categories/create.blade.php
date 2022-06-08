
@extends('layouts.user')

@section('title', 'Create Category')


@section('content')

<div class="back-area mb-3">
    <a href="{{ route('voice-evaluations.show', $voice_evaluation) }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-2"></i> Go Back</a>
</div>

<!-- Default box -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Create Datapoint Category</h3>
    </div>

    <form action="{{ route('datapoint-categories.store') }}" method="post" autocomplete="off">
        @csrf
        <div class="card-body">
            <input type="hidden" name="voice_evaluation_id" value="{{ $voice_evaluation->id }}" required>
            <div class="form-group">
                <label for="exampleInputEmail1">Datapoint Category Name <span>*</span></label>
                <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Enter Datapoint Category Name" required>
            </div>
            @error('name')
                <div class="validate-error">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="">Select Campaigns (Optional)</label>
                <select name="campaigns[]" id="campaigns" class="form-control select2" multiple>
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
                <label for="exampleInputEmail1">Sort Order <span>*</span></label>
                <input type="text" class="form-control" name="sort" value="{{ old('sort', 1) }}" placeholder="Enter Sort Order" required>
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
