@extends('layouts.admin')

@section('title', 'Edit ' . $resourceName)

@section('content')
<div class="container py-4 fade-in">
    <h1>Edit {{ $resourceName }}</h1>
    <!-- Admin edit {{ strtolower($resourceName) }} form goes here -->
    <p>This is a placeholder for the admin {{ strtolower($resourceName) }} edit view.</p>
</div>
@endsection
