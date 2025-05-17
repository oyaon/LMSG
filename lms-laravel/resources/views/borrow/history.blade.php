@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h1 class="text-center mb-4"><u>Borrow History</u></h1>
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Book name</th>
                <th>Issued date</th>
                <th>Return date</th>
                <th>Remaining days</th>
                <th>Fine</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($history as $i => $data)
                @php
                    $rem_days = 0;
                    if (!empty($data->issue_date)) {
                        $date = \Carbon\Carbon::parse($data->issue_date)->addDays(7);
                        $rem_days = $date->diffInDays(now(), false) * -1;
                    }
                @endphp
                <tr>
                    <th>{{ $history->firstItem() + $i }}</th>
                    <td>{{ $data->book_name }}</td>
                    <td>{{ $data->issue_date }}</td>
                    <td>{{ !empty($data->issue_date) ? \Carbon\Carbon::parse($data->issue_date)->addDays(7)->toDateString() : '' }}</td>
                    <td>{{ $rem_days > 0 ? $rem_days : 0 }} days</td>
                    <td>
                        @if($data->fine == 0)
                            {{ $rem_days < 0 ? (abs($rem_days) + 1) * 2.5 : 0 }} Tk
                        @else
                            {{ $data->fine }} Tk
                        @endif
                    </td>
                    <td>{{ $data->status }}</td>
                    <td>
                        @if($data->status == 'Declined')
                            <a href="#" class="btn btn-success disabled">Re-issue</a>
                        @endif
                        @if($data->status == 'Declined' || $data->status == 'Requested')
                            <a href="#" class="btn btn-outline-danger disabled">Delete request</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">
        {{ $history->links() }}
    </div>
</div>
@endsection
