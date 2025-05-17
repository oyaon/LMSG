@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h1 class="text-center mb-4">My Cart</h1>
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Book name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Date Added</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cartItems as $i => $item)
                <tr>
                    <th>{{ ($page - 1) * $perPage + $i + 1 }}</th>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->price }}</td>
                    <td>{{ $item->book_quantity }}</td>
                    <td>{{ $item->date }}</td>
                    <td>
                        <form action="{{ route('cart.remove', $item->id) }}" method="POST" style="display:inline-block">
                            @csrf
                            <button class="btn btn-danger btn-sm">Remove</button>
                        </form>
                        <form action="{{ route('cart.update', $item->id) }}" method="POST" style="display:inline-block">
                            @csrf
                            <input type="number" name="quantity" value="1" min="1" style="width:60px;display:inline-block;">
                            <button class="btn btn-primary btn-sm">Update</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="mt-4">
        <strong>Total Price: </strong> {{ $totalPrice }}
    </div>
    <div class="mt-4">
        <nav>
            <ul class="pagination justify-content-center">
                @for($i = 1; $i <= $totalPages; $i++)
                    <li class="page-item @if($i == $page) active @endif">
                        <a class="page-link" href="?page={{ $i }}">{{ $i }}</a>
                    </li>
                @endfor
            </ul>
        </nav>
    </div>
</div>
@endsection
