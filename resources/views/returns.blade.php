@extends('layouts.navbar')

@section('content')
<!-- Flash Messages -->
<div class="container-fluid mx-auto mt-8">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-red-700">Handle Return Orders</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Search Form -->
    <form id="searchForm" action="{{ route('search.returninvoice') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label for="delivery_note_no">Delivery Note Number</label>
            <input type="text" id="delivery_note_no" name="delivery_note_no" class="form-control" value="{{ old('delivery_note_no') }}" required>
        </div>

        <!-- Buttons aligned side by side -->
        <div class="d-flex justify-content-start gap-2">
            <button type="submit" class="btn btn-primary">Update Return Invoice Number</button>
        </div>
    </form>

    <!-- View Updated Records Form -->
    <form id="viewRecordsForm" action="{{ route('view.updated.records') }}" method="GET">
        @csrf
        <input type="hidden" id="view_delivery_note_number" name="delivery_note_no" value="">
        <button type="submit" class="btn btn-secondary mt-2">View Updated Records</button>
    </form>

<script>
    $(document).ready(function () {
        // Handle View Updated Records Form Submission
        $('#viewRecordsForm').on('submit', function (event) {
            const deliveryNoteno = $('#delivery_note_no').val();
            if (!deliveryNoteno) {
                event.preventDefault();
                alert('Please enter delivery note number.');
                return;
            }
            $('#view_delivery_note_number').val(deliveryNoteno);
        });
    });
</script>
</div>
@endsection
