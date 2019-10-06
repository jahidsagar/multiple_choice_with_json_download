@extends('admin.admin')

@section('title','package related category')

@section('html-head')
{{-- all css and js linked file goes here --}}
    <style>
        .btn{
            font-size: 14px;
            padding: 3px 15px;
        }
    </style>
@endsection


@section('content')
	<div class="row">

        <div class="container-fluid" style="margin-top: 3px;">
            <div class="row">
                <div class="panel panel-primary text-center panel-body">
                  <h2 class="display-4" style="font-family: sans;">App Name :  {{ $packageName[0]->title }} <span class="text-success">( {{ $packageName[0]->package_name }} )</span></h2>
                </div>
            </div>
        </div>

	     <div class="col-lg-12">
	        <div class="col-md-5"></div>
	        <div class="col-lg-2">
	        	<a class="btn btn-success" id="toggle-button" >Add Category</a>
	        </div>
	    </div>

        {{-- for json import --}}
        {{-- <div class="col-lg-12" style="margin-top: 1%;">
            <div class="col-md-4"></div>
            <div class="col-lg-4" style="border: 1px solid #8ed4a4;padding: 10px 0px 10px 20px;">
                <form enctype="multipart/form-data" method="post" action="@{{ url('/import') }}">
                    @csrf
                    {{-- <div class="form-group"> --}}
                        {{-- <input type="hidden" name="package_id" value="@{{ $packageName[0]->id }}">
                        <input type="file" class="form-control-file" id="" name="json_file" accept=".json" style="float: left;" required="">
                        <butto --}}{{-- n type="submit" class="btn btn-success" value="Submit">Submit</button> --}}
                    {{-- </div> --}}
                {{-- </form>
            </div>
        </div> --}} 
	    {{-- <p class="text-center text-danger">{{ Session::get('msg')}}</p> --}}
        <div class="col-lg-12" style="margin-top: 1%;">
            <div class="text-center" id="session_alert">
                <strong id="strong_text"></strong>
            </div>
        </div>

	    {{-- form for this package --}}
        <div class="col-lg-12 panel panel-default" id="toogle" style="display: none;">
            <form method="post" action="{{ url('/dashboard/category')}}" id="main_form" >
                @csrf
                <input type="hidden" name="previous_url" value="{{ URL::current() }}">
                <div class="form-group col-md-10">
                    <label for="name" class="" style="margin-top: 1%;">Name</label>
                    <input type="text" class="form-control " id="name" name="name" value="{{ old('name') }}" placeholder="add category name" required="">
                    {{-- <p class="help-block ">Example sample.</p> --}}
                    @if($errors->has('name'))
                    <p class=" text-danger">{{ $errors->first('name') }}</p>
                    @endif
                </div>

                <div class="form-group col-md-10">
                    <label for="weight" class="">Weight</label>
                    <input type="number" min="0" class="form-control " id="weight" name="weight" value="0" placeholder="add category weight">
                    {{-- <p class="help-block ">Example 1 or 100.</p> --}}
                    @if($errors->has('weight'))
                    <p class=" text-danger">{{ $errors->first('weight') }}</p>
                    @endif
                </div>





                <div class="form-group col-md-3">
                    <label for="app_id" class="">App name</label>
                    <input type="text" readonly class="form-control" id="staticEmail" value="{{ $packageName[0]->package_name }} ( {{$packageName[0]->title}} )">
                    <select class="form-control" id="app_id" name="app_id" required style="visibility: hidden;">
                            <option value="{{$packageName[0]->id}}">{{ $packageName[0]->package_name }} ( {{$packageName[0]->title}} )</option>
                    </select>
                    @if($errors->has('app_id'))
                    <p class=" text-danger">{{ $errors->first('app_id') }}</p>
                    @endif
                </div>

                <div class="form-group col-md-3">
                    <label for="parent_catid" class="">Parent Category</label>
                    <select class="form-control" id="parent_catid" name="parent_catid" >
                    	<option value="-1" selected>select one</option>
                    	@if($categories != null)
	                    	@foreach($categories as $category)
	                        	<option value="{{ $category['id'] }}"> {{ $category['name'] }}</option>
	                        @endforeach
                        @endif
                    </select>
                    @if($errors->has('parent_catid'))
                    <p class=" text-danger">{{ $errors->first('parent_catid') }}</p>
                    @endif
                </div>

                

                <div class="form-group col-md-2">
                    <label class="" style=""> Click here to submit</label>
                    <input type="submit" class="form-control" value="add new">
                </div>
            </form>
        </div>
	    {{-- end form --}}
	</div>
	
	<div class="row" style="margin-top: 2%;">
	    <table class="table table-striped" id="table_id">
	        <thead>
	            <tr>
	                <th scope="col">No</th>
	                <th scope="col">Name</th>
	                <th scope="col">Weight</th>
	                <th scope="col">Parent category name</th>
	                <th>Edit</th>
	            </tr>
	        </thead>
	        <tbody>

	           @if($categories != null)
	            <?php $i = 1; ?>
	            @foreach($categories as $tag)
	            <tr>
	                <th scope="row">{{$i++}}</th>
	                {{-- <td>{{ $tag['id'] }} </td> --}}
	                <td><a href=" {{ url('/dashboard/category_show/')}}/{{ $tag['id'] }}/{{$tag['name']}} " > {{ $tag['name'] }} </a></td>
	                <td>{{ $tag['weight']}}</td>
	                <td>{{ $tag['parent_category_name'] }}</td>
	                <td>
	                    <a href="{{ url('/dashboard/category/edit/')}}/{{ $tag['id'] }}" class="btn btn-success"><i class="glyphicon glyphicon-eye-open" ></i></a>&nbsp;
	                    
	                </td>

	            </tr>
	            @endforeach
	       @endif

	        </tbody>
	    </table>
	    <hr>
	</div>
@endsection

@section('html_fooler')
<script src="{{ asset('jquery_validate/jquery.validate.js')}}"></script>
<script>

    $("#main_form").validate();
    var changeText = false;
    $('#toggle-button').click(function(){
    	$('#toogle').toggle();
    	if (changeText == false) {
    		$('#toggle-button').text('Dissmiss Form');
    		$('#toggle-button').removeClass('btn-success');
    		$('#toggle-button').addClass('btn-danger');
    		changeText = true;
    	}else{
    		$('#toggle-button').text('add category');
    		$('#toggle-button').removeClass('btn-danger');
    		$('#toggle-button').addClass('btn-success');
    		changeText = false;
    	}
    	
    });
    var SessionMessage = '{{ Session::get('msg')}}';
    // console.log(SessionMessage.length);
    if (SessionMessage.length > 0) {
        $('#session_alert').addClass('alert alert-success');
        $('#strong_text').html(SessionMessage);
        setTimeout(function(){ 
            $('#session_alert').removeClass('alert alert-success');
            $('#session_alert').hide();
            SessionMessage = '{{ Session::forget('msg')}}';
        }, 3000);
    }else{
        $('#session_alert').removeClass('alert alert-success');
    }

</script>

@endsection