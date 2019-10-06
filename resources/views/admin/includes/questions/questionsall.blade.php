@extends('admin.admin')

@section('title','all questions ')

@section('html-head')
{{-- all css and js linked file goes here --}}
@endsection


@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">all Questions</h1>
        <p class="text-center text-danger">{{ Session::get('msg')}}</p>
    </div>
</div>

<?php $i = 1; ?>
@foreach($Questions as $question)
	<p><b>{{$i++}}. </b><b>{{$question->question}}</b>  <a href="{{ url('/dashboard/questions/edit/'.$question->id)}}">edit</a> | <a href="{{ url('/dashboard/questions/'.$question->id)}}" onclick="return confirm('are you sure?');">delete</a></p>

{{-- tags listed here --}}
@if(! empty($question->tags))
	<p> Tags : 
	@foreach($question->tags as $tagName)
		<a href="{{ url('/dashboard/tag_show/')}}/{{ $tagName->id }}/{{ $tagName->name }}" target="_blank" style="color: white;"> <button class="btn btn-success rounded-circle"> {{ $tagName->name }}</button></a>
	@endforeach
	</p>
@endif




	<ol>
		<li>{{$question->op1}}</li>
		<li>{{$question->op2}}</li>
		<li>{{$question->op3}}</li>
		<li>{{$question->op4}}</li>
	</ol>
<p>
	ans : <b class="text-danger">{{$question->ans}} </b>, 
	difficulty : <b class="text-danger"><i>
		@if($question->difficulty == 1) easy @endif
		@if($question->difficulty == 2) medium @endif
		@if($question->difficulty == 3) hard @endif </i></b>, 
	category : <b class="text-danger"> {{$question->categories_id}} </b></p>


@endforeach
@endsection