@extends('layouts.app')


@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dodaj nową płatność</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ url('addPayment') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('client') ? ' has-error' : '' }}">
                            <label for="client" class="col-md-4 control-label">Odbiorca</label>

                            <div class="col-md-6">
                                <input id="client" type="text" class="form-control" name="client" value="{{ old('client') }}">

                                @if ($errors->has('client'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('client') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                            <label for="amount" class="col-md-4 control-label">Kwota</label>

                            <div class="col-md-6">
                                <input id="amount" type="number" step="any" class="form-control" name="amount">

                                @if ($errors->has('amount'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('amount') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('expenses') ? ' has-error' : '' }}">
                            <label for="expenses" class="col-md-4 control-label">Grupa wydatków</label>

                            <div class="col-md-6">
                                <select class="form-control" name="expenses" id="expenses">
                                    @foreach($expenses as $post){
                                    <option value={{$post['expenses_id']}}>{{$post['name']}}</option>
                                    }
                                    @endforeach
                                </select>
                                @if ($errors->has('expenses'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('expenses') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Dodaj
                                </button>

                            </div>
                        </div>
                    </form>
                    <div>
                        <button type="submit" onClick="location.href = '{{ route('payment',$expense_id) }}'" class="btn btn-primary" style="position:absolute; margin-left:67%; margin-top:-6%;">Anuluj</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
