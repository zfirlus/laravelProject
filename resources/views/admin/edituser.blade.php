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
        var button = document.getElementById('role');
        button.addEventListener("click", pokaz);
        function pokaz() {
        $list = document.getElementById('list');
                list.removeAttribute('hidden', true);
                button.innerHTML = 'Zapisz rolę';
                button.removeAttribute('id', 'role');
                button.setAttribute('id', 'save');
                var button2 = document.getElementById('save');
                button2.addEventListener("click", saveRole);
                function saveRole() {
                var idrole = document.getElementById('roleList').value;
                        $.ajax({
                        type: "POST",
                                url: '{{url::to("saveRole")}}',
                                async: true,
                                data: {
                                role: idrole, user: {{ $user['user_id']}}
                                },
                                success: function (ret) {
                                if (ret == 'success'){
                                alert('Rola została dodana pomyślnie!');
                                }
                                },
                                complete: function () {
                                location.reload();
                                },
                                error: function (jqXHR, errorText, errorThrown) {

                                }
                        });
                }
        }



}
);</script>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edycja</div>
               <div class="panel-body">
                    @if (session('message'))
                    <div class="alert alert-danger">
                        {{ session('message') }}
                    </div>
                    @endif
                    <form class="form-horizontal" method="POST" action="{{ url('editUser') }}">
                        {{ csrf_field() }}
                        <input id="user_id" type="hidden" name="user_id" value="{{ $user['user_id']}}" required autofocus>
                        <input type="hidden" name="pass" value="{{ $user['password']}}" required autofocus>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Adres email</label>

                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" name="email" value="{{old('email') }}" placeholder="{{$user['email'] }}">

                                @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('oldpassword') ? ' has-error' : '' }}">
                            <label for="oldpassword" class="col-md-4 control-label">Stare hasło</label>

                            <div class="col-md-6">
                                <input id="oldpassword" type="password" class="form-control" name="oldpassword" value="{{$user['oldpassword']}}">

                                @if ($errors->has('oldpassword'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('oldpassword') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Nowe hasło</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" value="{{$user['newpassword']}}">

                                @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Powtórz hasło</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Zapisz
                                </button>
                            </div>
                        </div>
                    </form>
                    <div>
                        <button type="submit" onClick="location.href = '{{ url('users') }}'" class="btn btn-primary" style="position:absolute; margin-left:67%; margin-top:-6.5%;">Anuluj</button>
                    </div>
                    @if (Auth::check()&& Auth::user()->hasRole('admin'))
                    <div>
                        <button type="submit" onClick="" id="role" class="btn btn-primary" style="position:absolute; margin-left:47%; margin-top:-6.5%;">Przydziel rolę</button>
                    </div>
                    @endif
                    <div id="list" hidden="true">
                        <select class="form-control" name="roleList" id="roleList" style="">
                            <option value="0">Użytkownik</option>
                            <option value="1">Administrator</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
