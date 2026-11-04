@extends('layouts.header-sidebar')

@section('title', 'Create Activity Report')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h3 class="font-weight-bold">Create Activity Report</h3>

        <p class="text-muted">
            Record the actual results and evaluation of a completed activity.
        </p>

    </div>


    <div class="card shadow-sm">

        <div class="card-header bg-white">
            <h5 class="mb-0">Activity Report Information</h5>
        </div>

        <div class="card-body">

            @if($errors->any())

                <div class="alert alert-danger">

                    <strong>Please correct the following errors:</strong>

                    <ul class="mb-0 mt-2">

                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif


            <form action="{{ route('proposal.activity-reports.store') }}"
                  method="POST">

                @csrf

                @include('proposal.activity-reports._form')

                <hr>

                <div class="d-flex justify-content-between">

                    <a href="{{ route('proposal.activity-reports.index') }}"
                       class="btn btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-danger">
                        Save Activity Report
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
