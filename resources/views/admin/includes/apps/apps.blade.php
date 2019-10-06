@extends('admin.admin')

@section('title','Add Tags ')

@section('html-head')
{{-- all css and js linked file goes here --}}
@endsection


@section('content')
	<div class="row">
        <div class="col-lg-12">
            <div class="col-lg-10"></div>
            <div class="col-lg-2">
                <button type="button" class="btn btn-success" id="toggle_button">Add Package</button>
            </div>
        </div>
	    <div class="col-lg-12">
	        <h1 class="page-header">Add Package</h1>
	        <p class="text-center text-danger">{{ Session::get('msg')}}</p>
	    </div>
	</div>

	<div class="panel panel-default" id="toggle">
    <div class="panel-heading">Add new element</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <form method="post" action="{{ url('/dashboard/app')}}">
                    @csrf
                    <div class="form-group col-md-10">
                        <label for="title" class="">Title</label>
                        <input type="text" class="form-control " id="title" name="title" value="{{ old('title') }}" placeholder="add your title" required="">
                        <p class="help-block ">something or international.</p>
                        @if($errors->has('title'))
                        <p class=" text-danger">{{ $errors->first('title') }}</p>
                        @endif
                    </div>
                    <div class="form-group col-md-10">
                        <label for="package_name" class="">Package Name</label>
                        <input type="text" class="form-control " id="package_name" name="package_name" value="{{ old('package_name') }}" placeholder="add Your package name." required="">
                        <p class="help-block ">something.ecxample.com</p>
                        @if($errors->has('package_name'))
                        <p class=" text-danger">{{ $errors->first('package_name') }}</p>
                        @endif
                    </div>

                    

                    <div class="form-group col-md-2">
                        <label class="" style=""> click here to submit</label>
                        <input type="submit" class="form-control" value="add new">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<hr>
@endsection
@section('html_fooler')
<script type="text/javascript">
    $('#toggle_button').click(function(){
        $('#toggle').toggle();
    });
</script>
@endsection