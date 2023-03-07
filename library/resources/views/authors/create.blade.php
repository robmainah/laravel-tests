@extends('layouts.app')

@section('content')
    <div class="w-2/3 bg-gray-200 mx-auto p-6 shadow">
        <form action="/authors" method="post" class="flex flex-col items-center">
            @csrf
            <h1>Add New Author</h1>

            <div class="p-4">
                <input type="text" class="rounded px-4 py-2 w-64" name="name" placeholder="Name">
                @error('name')
                    <p class="text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div class="p-4">
                <input type="date" class="rounded px-4 py-2 w-64" name="dob" placeholder="Dob">
                @if ($errors->has('dob'))
                    <p class="text-red-600">{{ $errors->first('dob') }}</p>
                @endif
            </div>
            <div class="p-4">
                <button class="bg-blue-400 text-white rounded py-2 px-4">Add Author</button>
            </div>
        </form>
    </div>
@endsection
