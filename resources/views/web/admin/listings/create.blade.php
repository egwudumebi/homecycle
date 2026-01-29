@extends('web.admin.layout')

@section('title', 'Create Listing')
@section('admin_title', 'Create Listing')

@section('admin_content')
    <form method="POST" action="{{ route('admin.listings.store') }}" enctype="multipart/form-data">
        @csrf
        @include('web.admin.listings._form', ['listing' => null, 'listingId' => null])
    </form>
@endsection
