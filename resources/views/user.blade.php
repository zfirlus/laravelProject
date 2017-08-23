<!-- user.blade.php -->
@extends('master')
@section('content')
  <div class="container">
    <table class="table table-striped">
    <thead>
      <tr>
        <th>email</th>
        <th>IsAdmin</th>
      </tr>
    </thead>
    <tbody>
      
      @foreach($users as $post)
      <tr>
        <td>{{$post['email']}}</td>
        <td>{{$post['isadmin']}}</td>
      </tr>
      @endforeach
      
    </tbody>
  </table>
  </div>
@endsection