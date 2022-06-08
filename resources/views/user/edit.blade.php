
@extends('layouts.user')

@section('title', 'Edit User')


@section('content')

<div class="back-area mb-3">
    <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left mr-2"></i> Go Back</a>
</div>

<!-- Default box -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">User Detail</h3>
    </div>

    <form action="{{ route('users.update', $user) }}" method="post" autocomplete="off">
        @csrf
        @method('put')

        <div class="card-body">

            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>User ID</th>
                        <td>{{ $user->id ?? '' }}</td>
                        <th>HRMS ID</th>
                        <td>{{ $user->hrms_id ?? '' }}</td>
                        <th>User Name</th>
                        <td>{{ $user->name ?? '' }}</td>
                    </tr>
                    <tr>
                        <th>Email Address</th>
                        <td>{{ $user->email ?? '' }}</td>
                        <th>User Role</th>
                        <td>{{ $user->roles[0]->name ?? '' }}</td>
                        <th>Campaign</th>
                        <td>{{ $user->campaign->name ?? '' }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="form-group" style="margin-top: 30px;">
                <label for="exampleInputEmail1">Select Campaign</label>
                <select name="campaign_id" id="campaign_id" class="form-control select2" required>
                    <option value="">Select Option</option>
                    @foreach ($campaigns as $campaign)
                        <option value="{{ $campaign->id }}" @if($campaign->id == $user->campaign_id) selected @endif>{{ $campaign->name }}</option>
                    @endforeach
                </select>
            </div>

            @php
                $user_role = $user->roles[0]->name;
            @endphp

            <div class="form-group" style="margin-top: 30px;">
                <label for="exampleInputEmail1">Select User Role</label>
                <select name="role" id="role" class="form-control select2" required>
                    <option value="">Select Option</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" @if($user_role == $role->name) selected @endif>{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        <!-- /.card-footer-->

    </form>

</div>
<!-- /.card -->

@endsection


@section('scripts')
