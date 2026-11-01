
@extends('layouts.header-sidebar')

@section('title', 'Edit Activity Report')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h3 class="font-weight-bold">Edit Activity Report</h3>

        <p class="text-muted">
            Update the information and evaluation of the activity.
        </p>

    </div>


    <div class="card shadow-sm">

        <div class="card-header bg-white">
            <h5 class="mb-0">Update Report Information</h5>
        </div>

        <div class="card-body">

            @if($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif


            <form action="{{ route('manage-proposal.activity-reports.update', $activityReport->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                @include('manage-proposal.activity-reports._form')

                <hr>

                <div class="d-flex justify-content-between">

                    <a href="{{ route('manage-proposal.activity-reports.index') }}"
                       class="btn btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-danger">
                        Update Activity Report
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
