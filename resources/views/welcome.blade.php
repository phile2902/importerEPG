@extends('layout.master')

@section('content')
    <div class="panel panel-primary content">
        <div class="panel-heading">Welcome to importer EPG Program</div>
        <div class="panel-body">
            <div>
                <form enctype="multipart/form-data" role="form" method="POST" action="{{ route('tvcontent') }}">                    
                    {{ csrf_field() }} 
                    <div class="form-group">
                        <label for="exampleInputFile">Import a file (Support xml,json)</label><br>
                        <input type="file" name="inputFile" id="inputFile">
                        
                        @if ($errors->has('inputFile'))
                        <span class="help-block">
                            <strong>{{ $errors->first('inputFile') }}</strong>
                        </span>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
@endsection     
