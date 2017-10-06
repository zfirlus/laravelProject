@extends('layouts.app')


@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edycja</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ url('editExpense') }}">
                        {{ csrf_field() }}
                        <input id="expenses_id" type="hidden" name="expenses_id" value="{{ $expense['expenses_id']}}" required autofocus>
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Nazwa</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ $expense['name'],old('name') }}">

                                @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Zapisz
                                </button>
                            </div>
                        </div>
                    </form>
                    <div style="">
                        <button type="submit" onClick="location.href = '{{ url('/') }}'" class="btn btn-primary" style="position:absolute; margin-left:67%; margin-top:-6%;">Anuluj</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
