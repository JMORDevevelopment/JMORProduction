@extends('layouts.dashboard')

@section('content')
@include('dashboard.partials.giftcard_invoice_body')
@endsection

@push('scripts')
    <script>
        function myFunction() {
            window.print();
        }
    </script>
@endpush

