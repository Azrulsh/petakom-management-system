
@extends('layouts.header-sidebar')

@section('title', 'Edit Proposal')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h3 class="font-weight-bold">Edit Activity Proposal</h3>

        <p class="text-muted">
            Update the information of the selected proposal.
        </p>
    </div>

    <div class="card shadow-sm">

        <div class="card-header bg-white">
            <h5 class="mb-0">Edit Proposal Information</h5>
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

            <form action="{{ route('manage-proposal.proposals.update', $proposal->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                @include('manage-proposal._form')

                <hr>

                <div class="d-flex justify-content-between">

                    <a href="{{ route('manage-proposal.proposals.index') }}"
                       class="btn btn-secondary">
                        Cancel
                    </a>

                    <button type="submit" class="btn btn-danger">
                        Update Proposal
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

@endsection
