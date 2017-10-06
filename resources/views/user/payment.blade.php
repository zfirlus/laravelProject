
@extends('layouts.app')
@section('content')
<meta id="token" name="token" content="{ { csrf_token() } }">
<script src="{{ asset('/js/jquery.min.js') }}"></script>  
<script src="{{ URL::to('js/video.js') }}"></script>
<script src="{{ URL::to('js/videojs-contrib-hls.js') }}"></script>

<script src="{{ URL::to('//code.jquery.com/jquery-2.1.3.min.js') }}"></script>
<script src="{{ URL::to('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js') }}"></script>


<script type="text/javascript">
$(document).ready(function () {
$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});
//zaznacz wszystkie
var guzikWszystkie = document.getElementById('zaznaczWszystkie');
guzikWszystkie.addEventListener('click', zaznaczWszystkie);
function zaznaczWszystkie() {
var checksy = document.getElementsByName('check');
for (var i = 0; i < checksy.length; i++) {
if (checksy[i].getAttribute('disabled') !== 'disabled'){
checksy[i].checked = true; }
}
}
//odznacz
var guzikOdznacz = document.getElementById('odznacz');
guzikOdznacz.addEventListener('click', odznaczZaznaczone);
function odznaczZaznaczone() {
var checksy = document.getElementsByName('check');
for (var i = 0; i < checksy.length; i++) {
if (checksy[i].checked) {
checksy[i].checked = false;
}
}
}
//usuwanie
var guzikUsun = document.getElementById('usunZaznaczone');
guzikUsun.addEventListener('click', usunZaznaczone);
function usunZaznaczone() {
var zaznaczone = new Array();
var j = 0;
var checksy = document.getElementsByName('check');
for (var i = 0; i < checksy.length; i++) {
if (checksy[i].checked) {
zaznaczone[j] = checksy[i].getAttribute('id');
j++;
}
}
if (zaznaczone.length !== 0) {
var zaz2 = new Array();
for (var j = 0; j < zaznaczone.length; j++) {
zaz2[j] = zaznaczone[j].substring(5);
}


$.ajax({
type: "POST",
        url: '{{url::to("deletePayment")}}',
        async: true,
        data: {
        data: zaz2
        },
        success: function (ret) {
        if (ret == 'success'){
        alert('Platność została usunięta pomyślnie!'); }
        },
        complete: function () {
        location.reload();
        },
        error: function (jqXHR, errorText, errorThrown) {

        }
});
}
if (zaznaczone.length === 0) {
alert('nic nie zaznaczyłes!');
}
}
});</script>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Lista płatności</div>

                <div class="panel-body">
                    @if (session('message'))
                    <div class="alert alert-danger">
                        {{ session('message') }}
                    </div>
                    @endif
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><div class="btn-group">
                                        <button type="button" class="btn colour">Opcje</button>
                                        <button type="button" class="btn colour dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="caret"></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a id="odznacz" href="#">Odznacz</a></li>
                                            <li><a id="zaznaczWszystkie" href="#">Zaznacz wszystkie</a></li>
                                            <li role="separator" class="divider"></li>
                                            @if (Auth::check()&& (Auth::user()->hasRole('admin') || Auth::user()->hasRole('user')))
                                            <li id="usun"><a id="usunZaznaczone" href="#">Usuń zaznaczone</a></li>
                                            @endif
                                        </ul>
                                    </div></th>
                                <th>Odbiorca</th>
                                <th>Kwota</th>
                                <th>Data</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (Auth::check()&& Auth::user()->hasAnyRole(['admin','user']) && Auth::user()->hasPermissionTo('view payments'))
                            @foreach($payment as $post)
                            <tr>
                                <td>@if($post->status_id === 1 || $post->status_id === 3)<input type="checkbox" name="check" id="check{{$post['payment_id']}}"/>
                                    @else <input type="checkbox" name="check" id="check{{$post['payment_id']}}"disabled="disabled"/>@endif
                                </td>
                                <td>{{$post['client']}}</td>
                                <td>{{$post['amount']}}</td>
                                <td>{{$post['created_at']}}</td>
                                @if($post->status === 'zatwierdzona')
                                <td style="color:#00ccff;">{{$post['status']}}</td> @else
                                <td>{{$post['status']}}</td>@endif
                                <td>@if(($post->status_id === 1 || $post->status_id === 3) && (Auth::check() && (Auth::user()->hasRole('user') || Auth::user()->hasRole('admin'))))<a href="{{ route('editPayment',$post->payment_id) }}"> Edytuj </a>@endif</td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div>{{ $pagination }} </div>
                    <div>
                        @if(Auth::check() && Auth::user()->hasRole('user'))
                        <button type="submit" onClick="location.href = '{{ url('newPayment') }}'" class="btn btn-primary" >Dodaj płatność</button>
                        @endif
                        <button type="submit" onClick="location.href = '{{ url('/') }}'" class="btn btn-primary" style="margin-left:73.5%;">Wróć</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection



