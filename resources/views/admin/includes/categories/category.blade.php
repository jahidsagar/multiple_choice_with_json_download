@extends('admin.admin')

@section('title','Add Category ')

@section('html-head')
{{-- all css and js linked file goes here --}}
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
@endsection


@section('content')
	<div class="row">
	    <div class="col-lg-12">
	        <h1 class="page-header">Add Category</h1>
	        <p class="text-center text-danger">{{ Session::get('msg')}}</p>
            

           {{--  @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif --}}


	    </div>
	</div>

	<div class="panel panel-default">
    <div class="panel-heading">Add new element</div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <form method="post" action="{{ url('/dashboard/category')}}" id="main_form">
                    @csrf
                    <div class="form-group col-md-10">
                        <label for="name" class="">name</label>
                        <input type="text" class="form-control " id="name" name="name" value="{{ old('name') }}" placeholder="add category name" required="">
                        <p class="help-block ">Example sample.</p>
                        @if($errors->has('name'))
                        <p class=" text-danger">{{ $errors->first('name') }}</p>
                        @endif
                    </div>

                    <div class="form-group col-md-10">
                        <label for="weight" class="">weight</label>
                        <input type="number" min="0" class="form-control " id="weight" name="weight" value="0" placeholder="add category weight">
                        <p class="help-block ">Example 1 or 100.</p>
                        @if($errors->has('weight'))
                        <p class=" text-danger">{{ $errors->first('weight') }}</p>
                        @endif
                    </div>





                    <div class="form-group col-md-3">
                        <label for="app_id" class="">App name</label>
                        <select class="form-control" id="app_id" name="app_id" required>
                            <option disabled selected>select one</option>
                            @foreach($App as $appName)
                                <option value="{{$appName->id}}">{{$appName->package_name}} ( {{$appName->title}} )</option>
                            @endforeach
                        </select>
                        @if($errors->has('app_id'))
                        <p class=" text-danger">{{ $errors->first('app_id') }}</p>
                        @endif
                    </div>

                    <div class="form-group col-md-3">
                        <label for="parent_catid" class="">Parent Category</label>
                        <select class="form-control" id="parent_catid" name="parent_catid" >
                            <option value="-1" selected>select one</option>
                        </select>
                        @if($errors->has('parent_catid'))
                        <p class=" text-danger">{{ $errors->first('parent_catid') }}</p>
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
<script src="{{ asset('jquery_validate/jquery.validate.js')}}"></script>
<script>
    $("#main_form").validate();
    var $SelectCategory = $('#parent_catid');
    $('#app_id').change(function(argument) {
        var CategoryValue = $('#app_id').val();
        //============ have replace when we upload in server 
        //============and uncomment below variable
        // var apiUrl = window.location.protocol + "//" + window.location.host + "/"+"api/category/"+ CategoryValue;
        //and comment this variable
        var apiUrl = '{{url('/api/category/')}}'+'/'+CategoryValue;

        $($SelectCategory).empty().append('<option value="-1" selected>select one</option>');

        $.ajax({
            type:'GET',
            url: apiUrl ,
            success:function(categories){
                // console.log('success',data);
                $.each(categories,function(i,category){
                    // console.log(category.name);
                    // console.log(category.id);

                    $SelectCategory.append('<option value="'+category.id+'">'+ category.name +' </value>');
                });
            }
          
        });
    });


    //this is for jquery table
    // $(document).ready( function () {
    //     $('#table_id').DataTable();
    // } );
</script>

@endsection