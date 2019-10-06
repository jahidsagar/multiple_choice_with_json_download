@extends('admin.admin')

@section('title',$title)

@section('html-head')
{{-- all css and js linked file goes here --}}
 <style type="text/css" media="screen">
 	.tags {
	  list-style: none;
	  margin: 0;
	  overflow: hidden; 
	  padding: 0;
	}

	.tags li {
	  float: left; 
	}
	.tags li a{
		text-decoration: none;
	}
	.tag {
	  background: crimson;
	  border-radius: 3px 0 0 3px;
	  color: white;
	  display: inline-block;
	  height: 26px;
	  line-height: 26px;
	  padding: 0 20px 0 23px;
	  position: relative;
	  margin: 0 10px 10px 0;
	  text-decoration: none;
	  -webkit-transition: color 0.2s;
	}

	.tag::before {
	  background: #fff;
	  border-radius: 10px;
	  box-shadow: inset 0 1px rgba(0, 0, 0, 0.25);
	  content: '';
	  height: 6px;
	  left: 10px;
	  position: absolute;
	  width: 6px;
	  top: 10px;
	}

	.tag::after {
	  background: #fff;
	  border-bottom: 13px solid transparent;
	  border-left: 10px solid crimson;
	  border-top: 13px solid transparent;
	  content: '';
	  position: absolute;
	  right: 0;
	  top: 0;
	}

	.tag:hover {
	  background-color: crimson;
	  color: white;
	}

	.tag:hover::after {
	   border-left-color: crimson; 
	}
	/* ================================================ */
	ol {
	  list-style: none;
	  counter-reset: my-awesome-counter;
	  /* display: flex;
	  flex-wrap: wrap; */
	  margin: 0;
	  padding: 0;
	  
	}
	ol li {
	  counter-increment: my-awesome-counter;
	  display: flex;
	  width: 90%;
	  /* font-size: 0.8rem; */
	  margin-bottom: 0.5rem;
	  margin-left: 5%;
	}
	ol li::before {
	  content: "0" counter(my-awesome-counter);
	  background: #81C784;
	  border-radius: 25px;
	  color: #fffafa;
	  padding: 3px;
	  /* font-weight: bold; */
	  font-size: 12px;
	  margin-right: 0.5rem;
	  font-family: 'Abril Fatface', serif;
	  line-height: 1;
	}
	/* ol li::before {
	   content: “0” counter(my-awesome-counter);
	   background: #81C784;
	   border-radius: 25px;
	   padding: 3px;
	   font-size: 14px;
	   margin-right: 0.5rem;
	   font-family: ‘Abril Fatface’, serif;
	   line-height: 1;
	} */
	.left{
		float: right;
		clear: both;
	}
 </style>	
@endsection


@section('content')

	<div class="row">
		<div class="col-lg-12" style="margin-top: 1%;">
			<div class="col-md-5" ></div>
			<div class="col-lg-2">
				<a href="{{ url('/dashboard/questions/add').'/'.$id }}"><button class="btn btn-success" id="toggle-button" type="button">Add New Question</button></a>
			</div>
		</div>
		
	    <div class="col-lg-12">
	        <h1 class="page-header">All Questions <span class="badge" style="font-size: 24px;">{{ count($questions) }}</span></h1>
	    </div>
	    <div class="col-lg-12" style="margin-top: 1%;">
	        <div class="text-center" id="session_alert">
	            <strong id="strong_text"></strong>
	        </div>
	    </div>
	</div>	
		{{-- ============================================================================ --}}
		<div class="container-fluid">
			<div class="jumbotron col-md-4 well">
				Easy <span class="badge" id="easy"></span>&nbsp;
				Medium <span class="badge" id="medium"></span>&nbsp;
				Hard <span class="badge" id="hard"></span>
			</div>
		</div>
		{{-- ============================================================================ --}}
		
		
	{{-- <div class="row"> --}}
		<?php $i = 1; $easy = 0;$medium = 0;$hard = 0; ?>
		@foreach($questions as $question)
		<div class="panel panel-default">
			<div class="panel-body">
			<h3 style="font-family: sans;color: #ed294c;"><span style="color: #000;">Q-{{ $i++ }} : </span>{{$question->question}}</h3>  
			<div class="left">
				<a href="{{ url('/dashboard/questions/edit/'.$question->id)}}">edit</a> | 
				<a href="{{ url('/dashboard/questions/'.$question->id)}}" onclick="return confirm('are you sure?');">delete</a>
			</div>
			@if(! empty($question->tags))
				<div style="margin-top: 1%;">
					<ul class="tags">
						@foreach($question->tags as $tagName)
						<li><a class="tag">{{ $tagName->name }}</a></li>
						@endforeach
					</ul>
				</div>
			@endif
			</div>
			<ol>
				<li class="well well-sm">{{ $question->op1 }}</li>
				<li class="well well-sm">{{ $question->op2 }}</li>
				<li class="well well-sm">{{ $question->op3 }}</li>
				<li class="well well-sm">{{ $question->op4 }}</li>
			</ol>
			<div class="panel-footer">
				Answer : <b class="text-danger">{{$question->ans}} </b>, 
				Difficulty : <b class="text-danger"><i>
					@if($question->difficulty == 1) easy <?php $easy++; ?> @endif
					@if($question->difficulty == 2) medium <?php $medium++; ?>@endif
					@if($question->difficulty == 3) hard <?php $hard++; ?>@endif </i></b>
			</div>
		</div>
		@endforeach

		{{-- ============================================================================ --}}
@endsection

@section('html_fooler')
{{-- goes all js and js cdn here --}}
<script>
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

    $( document ).ready(function(){
    	var easy = {{ $easy }};
    	var medium = {{ $medium }};
    	var hard = {{ $hard }};
    	$('#easy').html(easy);
    	$('#medium').html(medium);
    	$('#hard').html(hard);
    });
</script>
@endsection

  
