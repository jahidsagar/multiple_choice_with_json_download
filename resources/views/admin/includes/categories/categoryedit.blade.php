@extends('admin.admin')

@section('title','edit Category ')

@section('html-head')
{{-- all css and js linked file goes here --}}
@endsection


@section('content')
	<div class="row">
	    <div class="col-lg-12">
	        <h1 class="page-header">Edit Category</h1>
	    </div>

       {{--  @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
 --}}


	</div>

	<div class="panel panel-default">
    {{-- <div class="panel-heading">edit element</div> --}}
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <form method="post" action="{{ url('/dashboard/category/edit/'.$eCat->id)}}" id="main_form">
                    @csrf
                    <input type="hidden" name="previous_url" value="{{ URL::previous()}}">
                    <div class="form-group col-md-10">
                        <label for="name" class="">name</label>
                        <input type="text" class="form-control " id="name" name="name" value="{{ $eCat->name }}" placeholder="add category name" required="">
                        {{-- <p class="help-block ">Example admin or user or subscriber etc.</p> --}}
                        @if($errors->has('name'))
                        <p class=" text-danger">{{ $errors->first('name') }}</p>
                        @endif
                    </div>

                    <div class="form-group col-md-10">
                        <label for="weight" class="">weight</label>
                        <input type="number" min="0" class="form-control " id="weight" name="weight" value="{{ $eCat->weight }}" placeholder="add category weight">
                        {{-- <p class="help-block ">Example 1 or 100.</p> --}}
                        @if($errors->has('weight'))
                        <p class=" text-danger">{{ $errors->first('weight') }}</p>
                        @endif
                    </div>

                    <div class="form-group col-md-3">
                        <label for="app_id" class="">App name</label>
                        <select class="form-control" id="app_id" name="app_id" required>
                            <option disabled selected>select one</option>
                            @foreach($App as $appName)
                                <option value="{{$appName->id}}"
                                    @if($eCat->apps_id == $appName->id) 
                                            selected 
                                        @endif>
                                    {{$appName->package_name}} ( {{$appName->title}} )
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="parent_catid" class="">Parent Category</label>
                        <select class="form-control" id="parent_catid" name="parent_catid" >
                            <option value="-1" >select one</option>
                        </select>
                    </div>

                    

                    <div class="form-group col-md-2">
                        <label class="" style=""> click here to submit</label>
                        <input type="submit" class="form-control" value="submit">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('html_fooler')
<script src="{{ asset('jquery_validate/jquery.validate.js')}}"></script>
<script>

    $("#main_form").validate();
    var $SelectCategory = $('#parent_catid');
    $('#app_id').change(function(argument) {
        var AppsId = $('#app_id').val();
        //============ have replace when we upload in server 
        //============and uncomment below variable
        // var apiUrl = window.location.protocol + "//" + window.location.host + "/"+"api/category/editable/"+ +AppsId+'/'+{{$eCat->id}};
        //and comment this variable
        var parentValue = {{ $eCat->apps_id }} ;
        // if (parentValue != $('#app_id').val()) {
        //     alert("you are changing package so all children with itself go under this package !!! careful !!!");
        // }
        var apiUrl = '{{url('/api/category/editable/')}}'+'/'+AppsId+'/'+{{$eCat->id}};
        // console.log(apiUrl);
        $($SelectCategory).empty().append('<option value="-1" >select one</option>');

        $.ajax({
            type:'GET',
            url: apiUrl ,
            success:function(categories){
                CategoryAppend(categories);
            }
          
        });
    });

    //function for category append
    function CategoryAppend(categories){
        var parentValue = {{ $eCat->parent_catid }} ;
        $.each(categories,function(key,value){

            if (parentValue == value.id) {
                $SelectCategory.append('<option value="'+value.id+'" selected>'+ value.name +' </value>');
            }else{
                $SelectCategory.append('<option value="'+value.id+'">'+ value.name +' </value>');
            }
            
            if(value.sub_category != null){
                CategoryAppend(value.sub_category);
            }else return;
        });
    }

    $( document ).ready(function() {
        var parentValue = {{ $eCat->parent_catid }} ;
        $("#app_id").trigger("change");
    });

    
</script>

@endsection