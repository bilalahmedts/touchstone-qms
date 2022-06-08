@extends('layouts.app')

@section('title', 'Add Category')


@section('content')

    <div class="back-area mb-3">
        <a href="{{ route('voice-evaluations.index') }}" class="btn btn-secondary btn-sm"><i
                class="fas fa-arrow-left mr-2"></i> Go Back</a>
    </div>

    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Add Category</h3>
        </div>

        <form action="{{ route('voice-evaluations.categories.store') }}" method="post" autocomplete="off">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="exampleInputEmail1">Category Name</label>
                    <input type="text" class="form-control" name="name" placeholder="Enter Category Name" required>
                </div>
                @error('name')
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
