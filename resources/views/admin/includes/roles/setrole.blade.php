@extends('admin.admin')

@section('title','set Role ')

@section('html-head')
{{-- all css and js linked file goes here --}}
@endsection


@section('content')
	<div class="row">
	    <div class="col-lg-12">
	        <h1 class="page-header">Add Users Role</h1>
	        <p class="text-center text-danger">{{ Session::get('msg')}}</p>
	    </div>
	</div>

<div class="row">
    <table class="table table-striped">
        <thead>
            <tr>
            	<th scope="col"> name</th>
                <th scope="col">email</th>
                <th scope="col">roles</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{$user->name}} </td>
                <td>{{ $user->email}}</td>
                <td>
                	<form action="{{ url('/dashboard/setrole/'.$user->id)}}" method="post">
                		@csrf
                	
	                	<div class="form-group col-md-4">
	                        <label for="roles_id" class="">App name</label>
	                        <select class="form-control" id="roles_id" name="roles_id" required>
	                            <option disabled selected>select one</option>
	                            @foreach($roles as $role)
	                                <option value="{{$role->id}}"
	                                	@if($user->roles_id == $role->id) 
	                                		selected 
	                                	@endif>
	                                	{{$role->name}}
	                                </option>
	                            @endforeach
	                        </select>
	                    </div>

	                    <div class="form-group col-md-3">
	                        <label class="" style=""> clickto submit</label>
	                        <input type="submit" class="form-control" value="update">
	                    </div>
                    </form>
                </td>

            </tr>
            @endforeach

        </tbody>
    </table
</div>

@endsection