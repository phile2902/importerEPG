@extends('layout.master')

@section('content')
<div class="notification">
    @if ($success)
        <div class="alert alert-success" role="alert">
            {{$message}}
        </div>
    @else    
        <div class="alert alert-danger" role="alert">
            {{$message}}
        </div>    
    @endif
</div>  
@endsection

