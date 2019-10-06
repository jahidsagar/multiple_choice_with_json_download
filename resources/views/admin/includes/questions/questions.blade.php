@extends('admin.admin')

@section('title','add new questions ')

@section('html-head')
{{-- all css and js linked file goes here --}}
<link href="{{ asset('tags/assets/app.css')}}" rel="stylesheet">
<link href="{{ asset('tags/tags.css')}}" rel="stylesheet">
<link href="{{ asset('chosen/chosen.min.css')}}" rel="stylesheet">

<style type="text/css" media="screen">
    .chosen-select ul{
        border-radius: 6px;
    }
</style>
@endsection


@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Add Questions</h1>
        <p class="text-center text-danger">{{ Session::get('msg')}}</p>
    </div>
</div>
<div class="panel panel-default col-lg-8">
    {{-- <div class="panel-heading">Add new element</div> --}}
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <form method="post" action="{{ url('/dashboard/questions')}}" onkeypress="return event.keyCode != 13;" id="main_form">
                    @csrf
                    <input type="hidden" name="previous_url" value="{{ URL::previous()}}">
                    <div class="form-group col-md-12">
                        <label for="question" class="">Question</label>
                        <input type="text" class="form-control " id="question" name="question" value="" placeholder="add questions" required="">
                        {{-- <p class="help-block ">do you know who is the creator of universe?</p> --}}
                        @if($errors->has('question'))
                        <p class=" text-danger">{{ $errors->first('question') }}</p>
                        @endif
                    </div>

                    {{-- tags using chosen jquery --}}
                    <div class="chosen-select">
                        <label for="tags" class="">Tags</label>
                        <select name="tags[]" id="another" multiple data-placeholder="Select some tags">
                            @foreach($Alltags as $singletag)
                                <option value="{{ $singletag->name }}">{{ $singletag->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <br>
                    {{-- options filed  --}}
                    <div class="form-group col-md-12">
                        <label for="option1" class="">Option1</label>
                        <input type="text" class="form-control " id="option1" name="option1" value="" placeholder="add options" required="">
                        @if($errors->has('option1'))
                        <p class=" text-danger">{{ $errors->first('option1') }}</p>
                        @endif
                    </div>
                    <div class="form-group col-md-12">
                        <label for="option2" class="">Option2</label>
                        <input type="text" class="form-control " id="option2" name="option2" value="" placeholder="add options" required="">
                        @if($errors->has('option2'))
                        <p class=" text-danger">{{ $errors->first('option2') }}</p>
                        @endif
                    </div>
                    <div class="form-group col-md-12">
                        <label for="option3" class="">Option3</label>
                        <input type="text" class="form-control " id="option3" name="option3" value="" placeholder="add options" required="">
                        @if($errors->has('option3'))
                        <p class=" text-danger">{{ $errors->first('option3') }}</p>
                        @endif
                    </div>
                    <div class="form-group col-md-12">
                        <label for="option4" class="">Option4</label>
                        <input type="text" class="form-control " id="option4" name="option4" value="" placeholder="add options" required="">
                        @if($errors->has('option4'))
                        <p class=" text-danger">{{ $errors->first('option4') }}</p>
                        @endif
                    </div>
                    {{-- end options field ============================ --}}

                    <div class="form-group col-md-3">
                        <label for="ans" class="">Answer</label>
                        <select name="ans" class="form-control" id="ans">
                            <option value="1">option 1</option>
                            <option value="2">option 2</option>
                            <option value="3">option 3</option>
                            <option value="4">option 4</option>
                        </select>
                        @if($errors->has('ans'))
                        <p class=" text-danger">{{ $errors->first('ans') }}</p>
                        @endif
                    </div>
                    <div class="form-group col-md-3">
                        <label for="difficulty" class="">Difficulty</label>
                        <select name="difficulty" id="difficulty" class="form-control" required="">
                            <option value="1">Easy</option>
                            <option value="2">Medium</option>
                            <option value="3">Hard</option>
                            option
                        </select>
                        @if($errors->has('difficulty'))
                        <p class=" text-danger">{{ $errors->first('difficulty') }}</p>
                        @endif
                    </div>

                     <div class="form-group col-md-3">
                        <label for="app_id" class="">Package Name</label>
                        <input type="text" readonly class="form-control" id="staticEmail" value="{{ $App->package_name }} ( {{ $App->title }})">
                        <select class="form-control" id="app_id" name="app_id" required style="visibility: hidden;">
                            <option selected value="{{ $App->id }}">{{ $App->package_name }} ( {{ $App->title }})</option>
                        </select>
                        @if($errors->has('app_id'))
                        <p class=" text-danger">{{ $errors->first('app_id') }}</p>
                        @endif
                    </div>

                    <div class="form-group col-md-3">
                        <label for="package_name" class="">Categories</label>
                        <input type="text" readonly class="form-control" id="staticEmail" value="{{ $Category->name }}">
                        <select class="form-control" id="categories_id" name="categories_id" required style="visibility: hidden;"/>
                            <option value=" {{ $Category->id }}" selected>{{ $Category->name }}</option>
                        </select>
                        @if($errors->has('categories_id'))
                        <p class=" text-danger">{{ $errors->first('categories_id') }}</p>
                        @endif
                    </div>

                    <div class="form-group col-sm-2" style="margin-left: 40%;">
                        {{-- <label class="" style=""> click here to submit</label> --}}
                        <input type="submit" class="btn btn-success " value="Add Questions">
                    </div>
                </form>
            </div>

            {{-- this is used for adding tag by ajax request --}}
            <br>
            
    </div>
</div>
</div>
<div class="row panel card">
    <div class="col-lg-4">
        <div class="col-lg-12">
                <form action="{{ url('/dashboard/tags/save')}}" method="post" onkeypress="return event.keyCode != 13;"  id="tag_ajax">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    
                    <div class="form-group">
                        <label for="tags" class="">Add new tags here : </label>
                        <input type="text" class="form-control" id="tags" name="tags" value="" data-role="tagsinput" style="display: none;">
                    </div>
                    
                    <input type="submit" class="btn btn-success" id="post_ajax" value="Add Tags " >
                </form>
                <div class="col-lg-12" style="margin-top: 1%;">
                    <div class="" id="session_alert">
                        <strong id="strong_text"></strong>
                    </div>
                </div>
            </div>
    </div>
</div>

@endsection

@section('html_fooler')

<script src="{{ asset('tags/bootstrap-tagsinput.min.js')}}"></script>
<script src="{{ asset('tags/assets/app.js')}}"></script>
<script src="{{ asset('tags/assets/app_bs3.js')}}"></script>
<script src="{{ asset('chosen/chosen.jquery.js')}}"></script>
<script src="{{ asset('jquery_validate/jquery.validate.js')}}"></script>
<script>
    $("#main_form").validate();
    // var $SelectCategory = $('#categories_id');
    // $('#app_id').change(function(argument) {
    //     var CategoryValue = $('#app_id').val();
    //     //============ have replace when we upload in server 
    //     //============and uncomment below variable
    //     // var apiUrl = window.location.protocol + "//" + window.location.host + "/"+"api/category/"+ CategoryValue;
    //     //and comment this variable

    //     var apiUrl = '{{ url('api/category/')}}'+'/'+CategoryValue;

    //     $($SelectCategory).empty().append('<option value="-1" disabled selected>select one</option>');

    //     $.ajax({
    //         type:'GET',
    //         url: apiUrl ,
    //         success:function(categories){
    //             // console.log('success',categories);
    //             $.each(categories,function(i,category){
    //                 // console.log(category.name);
    //                 // console.log(category.id);

    //                 $SelectCategory.append('<option value="'+ category.id +'">'+ category.name +' </value>');
    //             });
    //         }
          
    //     });
    // });

    //ajax request for tags save
    $( "#tag_ajax" ).submit(function( event ) {
 
        // console.log('working success');
          // Stop form from submitting normally
          event.preventDefault();
         if($("#tags").val() == "") return ;
          // Get some values from elements on the page:
          var $form = $( this ),
            term = $form.find( "input[name='tags']" ).val(),
            url = $form.attr( "action" );

          // Send the data using post
          var posting = $.post( url, { "_token": $('#token').val(), "tags": term } );
         
          // get the return data
          posting.done(function( data ) {
            // console.log(data);
            $.each(data,function(i,tagfromajax){
                if (tagfromajax != null ) {
                    $('#another').append('<option value="'+tagfromajax+'">'+ tagfromajax +' </value>');
                }
            });
            //empty the tags input
            $("#tags").tagsinput('removeAll')
            //if i dont trigger , than it wont update
            $('#another').trigger('chosen:updated');

            //======================here added the notification ===============================
            var SessionMessage = 'tags added successfully';
            // if (SessionMessage.length > 0) {
                $('#session_alert').addClass('alert alert-success');
                $('#strong_text').html(SessionMessage);
                setTimeout(function(){ 
                    $('#session_alert').removeClass('alert alert-success');
                    $('#strong_text').html('');
                    // $('#session_alert').hide();
                    // SessionMessage = '';
                }, 3000);
            // }else{
            //     $('#session_alert').removeClass('alert alert-success');
            // }


          });
        });

    $(document).ready(function(){

        $('#another').chosen({width: "95%"});
    });
    //no need this method
    // $('#another').on('change', function(evt, val) {
    //         console.log(evt);
    //       });
    
</script>
@endsection