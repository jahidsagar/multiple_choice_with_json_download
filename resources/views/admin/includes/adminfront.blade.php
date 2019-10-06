@extends('admin.admin')



@section('title','this is front page')

@section('html-head')
{{-- all css and js linked file goes here --}}
<style>
    .btn{
        font-size: 14px;
        padding: 3px 15px;
    }
    .down{
        margin-top: calc(30vh);
    }
</style>
@endsection


@section('content')
<div class="row">
    <div class="col-lg-12">
            <h1 class="col-md-10">All Packages</h1>
            <button class="btn btn-success" style="margin-top: 20px;" id="toggle_button">Add Package</button>
        
    </div>
    <div class="col-lg-12" style="margin-top: 1%;">
        <div class="text-center" id="session_alert">
            <strong id="strong_text"></strong>
        </div>
    </div>
</div>
    <!-- /.col-lg-12 -->
    {{-- ========================== start form  ======================--}}
    <div class="row" id="toggle" style="display: none;">
        <div class="panel panel-default" id="toggle">
            {{-- <div class="panel-heading">Add new element</div> --}}
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form method="post" action="{{ url('/dashboard/app')}}" id="main_form">
                            @csrf
                            <div class="form-group col-md-10">
                                <label for="title" class="">Package Title</label>
                                <input type="text" class="form-control " id="title" name="title" value="{{ old('title') }}" placeholder="Add package title" required="">
                                {{-- <p class="help-block ">something or international.</p> --}}
                                @if($errors->has('title'))
                                <p class=" text-danger">{{ $errors->first('title') }}</p>
                                @endif
                            </div>
                            <div class="form-group col-md-10">
                                <label for="package_name" class="">Package Name</label>
                                <input type="text" class="form-control " id="package_name" name="package_name" value="{{ old('package_name') }}" placeholder="Add your package name." required="">
                                {{-- <p class="help-block ">something.ecxample.com</p> --}}
                                @if($errors->has('package_name'))
                                <p class=" text-danger">{{ $errors->first('package_name') }}</p>
                                @endif
                            </div>

                            

                            <div class="form-group col-md-2">
                                <label class="" style=""> click here to add package</label>
                                <input type="submit" class="form-control" value="add new">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- ==========================end form============================== --}}
    <div class="container-fluid">
        <div class="row">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Title</th>
                        <th scope="col">Package name</th>
                        <th scope="col">Edit</th>
                        <th scope="col">Plain json</th>
                        <th scope="col">Encrypted json</th>
                        <th scope="col">Import</th>
                    </tr>
                </thead>
                <tbody>

                <?php $i = 1; ?>
                @foreach($Apps as $tag)
                    <tr>
                        <th scope="row">{{$i++}}</th>
                        <td> {{ $tag->title}}</td>
                        <td> <a href="{{ url('/package/')}}/{{$tag->id}}/{{ $tag->package_name}}"> {{ $tag->package_name}} </a></td>
                        <td>
                        <a href="{{ url('/dashboard/app/edit/')}}/{{$tag->id}}" class="btn btn-success"><i class="glyphicon glyphicon-eye-open" ></i></a>
                        </td>
                        <td>
                        <a href="{{ url('/download/').'/'.$tag->package_name }}" class="btn btn-info" title="download all questions of this package"> <i class="glyphicon glyphicon-download" ></i></a>
                        </td>
                        <td>
                        <a href="{{ url('/edownload/').'/'.$tag->package_name }}" class="btn btn-primary" title="download all encrypted questions of this package"> <i class="glyphicon glyphicon-download" ></i></a>
                        </td>
                        <td>
                            
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter{{$tag->id}}">
                                <i class="glyphicon glyphicon-import"></i>
                            </button>

                            <!-- Modal -->
                            <div class="modal fade down" id="exampleModalCenter{{ $tag->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-center">Upload Your File</h5>
                                        </div>
                                    <div class="modal-body " style="margin-left: 30%;">
                                        <div class="row">
                                            <form enctype="multipart/form-data" method="post" action="{{ url('/importv3') }}">
                                            @csrf
                                                <div class="form-group col-md-10">
                                                    <input type="hidden" name="package_id" value="{{ $tag->id }}">
                                                    <input type="file" class="form-control-file" id="" name="json_file" accept=".json" style="float: left;" required="">
                                                </div>
                                                <div class="form-group col-md-3" style="margin-left: 20%;">
                                                    <input type="submit" class="form-control btn-success" value="submit">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>

                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('html_fooler')
<script src="{{ asset('jquery_validate/jquery.validate.js')}}"></script>
<script type="text/javascript">
    $("#main_form").validate();
    var changeText = false;
    $('#toggle_button').click(function(){
        $('#toggle').toggle();
        if (changeText == false) {
            $('#toggle_button').text('Dissmiss Form');
            $('#toggle_button').removeClass('btn-success');
            $('#toggle_button').addClass('btn-danger');
            changeText = true;
        }else{
            $('#toggle_button').text('Add Package');
            $('#toggle_button').removeClass('btn-danger');
            $('#toggle_button').addClass('btn-success');
            changeText = false;
        }
    });

    var SessionMessage = '{{ Session::get('msg')}}';
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