@extends('layout.master')

@section('content')
    @if ($success)
        <div class="alert alert-success" role="alert">
            {{$message}}
        </div>
    @else    
        <div class="alert alert-danger" role="alert">
            {{$message}}
        </div>    
    @endif
@endsection

