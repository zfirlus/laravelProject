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
            if (checksy[i].getAttribute('disabled') !== 'disabled') {
                checksy[i].checked = true;
            }
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
                url: '{{url::to("deleteExpenses")}}',
                async: true,
                data: {
                    data: zaz2
                },
                success: function (ret) {
                    if(ret == 'success'){
                    alert('Wydatki zostały usunięte pomyślnie!');}
                },
                complete: function () {
                    location.reload();
                },
                error: function (jqXHR, errorText, errorThrown) {
                    alert('error!');
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
                <div class="panel-heading">Wydatki</div>

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
                                            @if (Auth::check()&& (Auth::user()->hasAnyRole(['admin', 'user'])))
                                            <li id="usun"><a id="usunZaznaczone" href="#">Usuń zaznaczone</a></li>
                                            @endif
                                        </ul>
                                    </div></th>
                                <th>Nazwa</th>
                                <th>Kwota ogółem</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (Auth::check()&& Auth::user()->hasAnyRole(['admin','user']) && Auth::user()->hasPermissionTo('view expenses'))    
                            @foreach($expenses as $post)
                            <tr>
                                <td>@if($post->status === 'block')<input type="checkbox" name="check" id="check{{$post['expenses_id']}}" disabled="disabled"/>
                                    @else <input type="checkbox" name="check" id="check{{$post['expenses_id']}}"/>@endif
                                </td>
                                <td>{{$post['name']}}</td>
                                <td>{{$post['amount']}}</td>
                                <td>@if (Auth::check()&& (Auth::user()->hasAnyRole(['admin','user'])))
                                    <a href="{{ route('editExpense',$post->expenses_id)}}"> Edytuj </a>@endif
                                </td>
                                <td><a href="{{ route('payment',$post->expenses_id) }}"> Przejdź do płatności </a></td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>

                    </table>
                    <div>{{ $pagination }} </div>
                    @if (Auth::check()&& Auth::user()->hasRole('user'))
                    <div>
                        <button type="submit" onClick="location.href = '{{ url('newExpense') }}'" class="btn btn-primary" style="position: absolute; margin-left:75%; margin-top:-7.5%;" >Dodaj wydatek</button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
