@extends('admin.admin')

@section('title','edit package ')

@section('html-head')
{{-- all css and js linked file goes here --}}
@endsection


@section('content')
	<div class="row">
	    <div class="col-lg-12">
	        <h1 class="page-header">Edit Package</h1>
	    </div>
	</div>

	<div class="panel panel-default">
    {{-- <div class="panel-heading">edit element</div> --}}
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <form method="post" action="{{ url('/dashboard/app/edit/'.$App->id)}}">
                    @csrf
                    <div class="form-group col-md-10">
                        <label for="title" class="">Package Title</label>
                        <input type="text" class="form-control " id="title" name="title" value="{{ $App->title }}" placeholder="add your package title" required="">
                        {{-- <p class="help-block ">Example International</p> --}}
                        @if($errors->has('title'))
                        <p class=" text-danger">{{ $errors->first('title') }}</p>
                        @endif
                    </div>
                    <div class="form-group col-md-10">
                        <label for="package_name" class="">Package Name</label>
                        <input type="text" class="form-control " id="package_name" name="package_name" value="{{ $App->package_name }}" placeholder="add package name." required="">
                        {{-- <p class="help-block ">Example example.com.something</p> --}}
                        @if($errors->has('package_name'))
                        <p class=" text-danger">{{ $errors->first('package_name') }}</p>
                        @endif
                    </div>

                    

                    <div class="form-group col-md-2">
                        <label class="" style=""> click here to submit</label>
                        <input type="submit" class="form-control" value="update">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection