
@extends('layouts.user')

@section('title', 'Users')


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

    <form action="{{ route('users.index') }}" method="get" autocomplete="off">
        <input type="hidden" name="search" value="1">
        @php
            $name = '';
            $email = '';
            $status = '';
            $campaign_id = -1;

            if(isset($_GET['search'])){
                if(!empty($_GET['name'])){
                    $name = $_GET['name'];
                }
                if(!empty($_GET['email'])){
                    $email = $_GET['email'];
                }
                if(!empty($_GET['status'])){
                    $name = $_GET['status'];
                }
                if(!empty($_GET['campaign_id'])){
                    $campaign_id = $_GET['campaign_id'];
                }
            }

        @endphp

        <div class="card card-primary card-outline mt-3" id="search" @if(isset($_GET['search'])) style="display: block;" @endif>
            <div class="card-body">
                <div class="row">

                    <div class="form-group col-md-4">
                        <label for="">Employee Name</label>
                        <input type="text" name="name" value="{{ $name }}" class="form-control">
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">Email Address</label>
                        <input type="text" name="email" value="{{ $email }}" class="form-control">
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">Campaign</label>
                        <select name="campaign_id" id="campaign_id" class="form-control select2">
                            <option value="">Select Option</option>
                            @foreach ($campaigns as $campaign)
                                <option value="{{ $campaign->id }}" @if($campaign->id == $campaign_id) selected @endif>{{ $campaign->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">Select Status</label>
                        <select name="status" class="form-control select2">
                            <option value="">Select</option>
                            <option value="active" @if($status == 'active') selected @endif>Active</option>
                            <option value="disable" @if($status == 'disable') selected @endif>Disabled</option>
                        </select>
                    </div>

                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="{{ route('users.index') }}" class="ml-5">Clear Search</a>
            </div>
        </div>
    </form>

</div>

<!-- Default box -->
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">Users List</h3>
    </div>

    <div class="card-body">

        <table class="table table-bordered">
            <thead>
              <tr>
                <th>@sortablelink('name', 'Employee Name')</th>
                <th>@sortablelink('email', 'Email Address')</th>
                <th>User Role</th>
                <th>Campaign</th>
                <th>Reporting To</th>
                <th>@sortablelink('status', 'Status')</th>
                <th>Created Time</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>

                @if(count($users) > 0)

                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name ?? 'undefined' }}</td>
                            <td>{{ $user->email ?? 'undefined' }}</td>
                            <td>{{ $user->roles[0]->name ?? '' }}</td>
                            <td>{{ $user->campaign->name ?? '' }}</td>
                            <td>
                                {{ $user->supervisor->name ?? '-' }}
                            </td>
                            <td>
                                @if($user->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Disabled</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('d-m-Y g:i:s A') }}</td>
                            <td>
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr><td colspan="7" class="text-center">No record found!</td></tr>
                @endif

            </tbody>
        </table>

    </div>

    @if($users->total() > 15)
        <!-- /.card-body -->
        <div class="card-footer clearfix">
            {{ $users->appends(request()->input())->links()   }}
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

    });

</script>

@endsection
