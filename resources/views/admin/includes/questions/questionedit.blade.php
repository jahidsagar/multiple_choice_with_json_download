@extends('admin.admin')

@section('title','edit questions ')

@section('html-head')
{{-- all css and js linked file goes here --}}
<link href="{{ asset('tags/assets/apps.css')}}" rel="stylesheet">
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
        <h1 class="page-header">edit Questions</h1>
    </div>
</div>
<div class="panel panel-default col-lg-8">
    {{-- <div class="panel-heading">edit element</div> --}}
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12">
                <form method="post" action="{{ url('/dashboard/questions/edit/'.$data->id)}}" name="ques_edit" onkeypress="return event.keyCode != 13;" id="ques_edit">
                    @csrf
                    <input type="hidden" name="previous_url" value="{{ URL::previous()}}">
                    <div class="form-group col-md-12">
                        <label for="question" class="">question</label>
                        <input type="text" class="form-control " id="question" name="question" value="{{$data->question}}" placeholder="add questions" required="">
                        {{-- <p class="help-block ">do you know who is the creator of universe?</p> --}}
                        @if($errors->has('question'))
                        <p class=" text-danger">{{ $errors->first('question') }}</p>
                        @endif
                    </div>

                    
                    <?php 
                        $var = array();
                        foreach($data->tags as $tagName){
                            $var[] = $tagName->name;
                        }
                        $val = implode(",", $var);
                    ?>


                    {{-- tags input --}}
                    {{-- have to do something --}}
                    <div class="chosen-select">
                        <label for="tags" class="">tags</label>
                        <select name="tags[]" id="another" multiple data-placeholder="Select some tags">
                            @foreach($Alltags as $singletag)
                                <option value="{{ $singletag->name }}">{{ $singletag->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <br>
                    {{-- <div class="form-group col-md-12">
                        <label for="tags" class="">tags</label>
                        <input type="text" id="tags" name="tags" value="{{$val}} " data-role="tagsinput" style="display: none;">
                        @if($errors->has('question'))
                        <p class=" text-danger">{{ $errors->first('tags') }}</p>
                        @endif
                    </div> --}}



                    {{-- options filed  --}}
                    <div class="form-group col-md-12">
                        <label for="option1" class="">option1</label>
                        <input type="text" class="form-control " id="option1" name="option1" value="{{$data->op1}}" placeholder="add options" required="">
                        @if($errors->has('option1'))
                        <p class=" text-danger">{{ $errors->first('option1') }}</p>
                        @endif
                    </div>
                    <div class="form-group col-md-12">
                        <label for="option2" class="">option2</label>
                        <input type="text" class="form-control " id="option2" name="option2" value="{{$data->op2}}" placeholder="add options" required="">
                        @if($errors->has('option2'))
                        <p class=" text-danger">{{ $errors->first('option2') }}</p>
                        @endif
                    </div>
                    <div class="form-group col-md-12">
                        <label for="option3" class="">option3</label>
                        <input type="text" class="form-control " id="option3" name="option3" value="{{$data->op3}}" placeholder="add options" required="">
                        @if($errors->has('option3'))
                        <p class=" text-danger">{{ $errors->first('option3') }}</p>
                        @endif
                    </div>
                    <div class="form-group col-md-12">
                        <label for="option4" class="">option4</label>
                        <input type="text" class="form-control " id="option4" name="option4" value="{{$data->op4}}" placeholder="add options" required="">
                        @if($errors->has('option4'))
                        <p class=" text-danger">{{ $errors->first('option4') }}</p>
                        @endif
                    </div>
                    {{-- end options field ============================ --}}

                    <div class="form-group col-md-3">
                        <label for="ans" class="">ans</label>
                        <select name="ans" class="form-control" id="ans" required="">
                            <option value="1">option 1</option>
                            <option value="2">option 2</option>
                            <option value="3">option 3</option>
                            <option value="4">option 4</option>
                        </select>
                        {{-- <input type="number" min="1" max="4" class="form-control " id="ans" name="ans" value="{{$data->ans}}" placeholder="add answer value" required=""> --}}
                        @if($errors->has('ans'))
                        <p class=" text-danger">{{ $errors->first('ans') }}</p>
                        @endif
                    </div>
                    <div class="form-group col-md-3">
                        <label for="difficulty" class="">difficulty</label>
                        <select name="difficulty" id="difficulty" class="form-control" required="">
                            <option value="1">Easy</option>
                            <option value="2">Medium</option>
                            <option value="3">Hard</option>
                            option
                        </select>
                        {{-- <input type="number" min="1" max="3" class="form-control " id="difficulty" name="difficulty" value="{{$data->difficulty}}" placeholder="add difficulty value" required=""> --}}
                        @if($errors->has('difficulty'))
                        <p class=" text-danger">{{ $errors->first('difficulty') }}</p>
                        @endif
                    </div>

                    <div class="form-group col-md-3">
                        <label for="app_id" class="">App name</label>
                        <select class="form-control" id="app_id" name="app_id" required>
                            <option disabled selected>select one</option>
                            @foreach($App as $tag)
                                <option value="{{$tag->id}}">{{$tag->package_name}} ({{$tag->title}})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="package_name" class="">Categories</label>
                        <select class="form-control" id="categories_id" name="categories_id" required>
                            <option disabled selected>select one</option>
                        </select>
                    </div>


                    <div class="form-group col-md-2" style="margin-left: 40%;">
                        {{-- <label class="" style=""> click here to submit</label> --}}
                        <input type="submit" class="btn btn-success" value="update">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row panel card">
    <div class="col-lg-4">
        <div class="col-lg-12">
                <form action="{{ url('/dashboard/tags/save')}}" method="post" onkeypress="return event.keyCode != 13;"  id="tag_ajax">
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    
                    <div class="form-group">
                        <label for="tags" class="">add new tags here : </label>
                        <input type="text" class="form-control" id="tags" name="tags" value="" data-role="tagsinput" style="display: none;">
                    </div>
                    
                    <input type="submit" id="post_ajax" value="add new " class="btn btn-success ">
                </form>
                <div class="col-lg-12" style="margin-top: 1%;">
                    <div class="" id="session_alert">
                        <strong id="strong_text"></strong>
                    </div>
                </div>
            </div>
    </div>
</div>
{{-- <script type="text/javascript">
    document.forms['ques_edit'].elements['app_id'].value = {{ $data->app_id }};
</script> --}}
@endsection
@section('html_fooler')
<script src="{{ asset('tags/bootstrap-tagsinput.min.js')}}"></script>
<script src="{{ asset('tags/assets/app.js')}}"></script>
<script src="{{ asset('tags/assets/app_bs3.js')}}"></script>
<script src="{{ asset('chosen/chosen.jquery.js')}}"></script>
<script src="{{ asset('jquery_validate/jquery.validate.js')}}"></script>
<script>
    $("#ques_edit").validate();
    var $SelectCategory = $('#categories_id');
    var categoryId = {{$data->categories_id}};
    var allTag = <?php echo $data->tags ?>;
    // console.log(allTag);
//loading the related category
    $('#app_id').change(function(argument) {
        var CategoryValue = $('#app_id').val();
        // console.log(CategoryValue);
        //============ have replace when we upload in server 
        //============and uncomment below variable
        // var apiUrl = window.location.protocol + "//" + window.location.host + "/"+"api/category/"+ CategoryValue;
        //and comment this variable
        var apiUrl = '{{ url('api/category/')}}'+'/'+CategoryValue;

        $($SelectCategory).empty().append('<option disabled >select one</option>');

        $.ajax({
            type:'GET',
            url: apiUrl ,
            success:function(categories){
                // console.log('success',categories);
                $.each(categories,function(i,category){
                    // console.log(category.name);
                    // console.log(category.id);
                    if (categoryId == category.id) {
                        $SelectCategory.append('<option value="'+ category.id +'" selected>'+ category.name +' </value>');
                    }else{
                        $SelectCategory.append('<option value="'+ category.id +'">'+ category.name +' </value>');
                    }
                    
                });
            }
          
        });
    });

//javascript ready function 
    $( document ).ready(function() {
        
        var categoryId = {{$data->categories_id}};
        var ans = {{ $data->ans}};
        var diffic = {{ $data->difficulty }};
        var appsId = {{ $Rcate->apps_id}}; 
        $("#app_id option[value='" + appsId + "']").attr("selected","selected");
        $("#ans option[value='" + ans + "']").attr("selected","selected");
        $("#difficulty option[value='" + diffic + "']").attr("selected","selected");
        
        //set the tags as selctable
        $.each(allTag , function(key,value){
            // console.log(value.name);
            $("#another option[value='" + value.name + "']").attr("selected","selected");
        });
        //========================
        
        //trigger the chosen
        $('#another').chosen({width: "95%"});
        //====================
        
        //trigger the change event to load categories
        $("#app_id").trigger("change");
        //==================
    });

//this is used for submitting new tag using ajax
    $( "#tag_ajax" ).submit(function( event ) {
 
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
            $.each(data,function(i,tagfromajax){
                if (tagfromajax != null ) {
                    $('#another').append('<option value="'+tagfromajax+'">'+ tagfromajax +' </value>');
                }
            });
            //empty the tags input
            $("#tags").tagsinput('removeAll')
            //if i dont trigger , than it wont update
            $('#another').trigger('chosen:updated');

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
</script>
@endsection