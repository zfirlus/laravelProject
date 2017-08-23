<!-- index.blade.php -->
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
@extends('master')
@section('content')
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style>
            html, body {
                margin:0;
                padding:0;
                height:100%;
            }
            #container {
                min-height:100%;
                position:relative;
            }
            #header {
                padding:10px;
            }
            #body {
                padding:10px;
                padding-bottom:60px;   /* Height of the footer */
            }
            .row{
                font-family: times, Times New Roman, times-roman, georgia, serif;
                font-size: 120%;
            }
            .buttons{
                width: 30%;
            }
        </style>
    </head>
    <body>
        <div id="container" style="margin-top:5%;" >
            <div id="body">
                <div class="row">

                    <div class="col-md-3" ></div>
                    <div class="col-md-6" >

                        {!! Form::open(array('url' => 'login', 'class' => 'form')) !!}
                        <div class="form-group">
                            {!! Form::label('Podaj adres email') !!}
                            {!! Form::text('email', null, 
                            array('required', 
                            'class'=>'form-control', 
                            'placeholder'=>'example@gmail.com')) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('Podaj hasło') !!}
                            {!! Form::password('password',['class' => 'form-control'], null, 
                            array('required', 
                             
                            'placeholder'=>'')) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::submit('OK', 
                            array('class'=>'btn buttons')) !!}
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <div class="col-md-3" ></div>
                </div>
            </div>
        </div>
    </body>
</html>
@endsection