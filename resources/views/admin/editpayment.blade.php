@extends('layouts.app')


@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Edycja</div>
                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ url('adminEditPayment') }}">
                        {{ csrf_field() }}
                        <input id="payment_id" type="hidden" name="payment_id" value="{{ $payment['payment_id']}}" required autofocus>
                        <div class="form-group{{ $errors->has('client') ? ' has-error' : '' }}">
                            <label for="client" class="col-md-4 control-label">Odbiorca</label>

                            <div class="col-md-6">
                                <input id="client" type="text" class="form-control" name="client" value="{{ $payment['client'],old('client') }}">

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
                                <input id="amount" type="number" step="any" class="form-control" name="amount" value="{{$payment['amount'], old('amount')}}">

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
                                    @if($post->expenses_id === $payment->expenses_id){
                                    <option value={{$post['expenses_id']}}>{{$post['name']}}</option>
                                    }@endif}
                                    @endforeach
                                    @foreach($expenses as $post){
                                    @if($post->expenses_id !== $payment->expenses_id){
                                    <option value={{$post['expenses_id']}}>{{$post['name']}}</option>
                                    }@endif}
                                    @endforeach
                                </select>
                                @if ($errors->has('expenses'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('expenses') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        @if (Auth::check()&& Auth::user()->hasRole('admin') && Auth::user()->hasPermissionTo('accept payment'))
                        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                            <label for="status" class="col-md-4 control-label">Status</label>

                            <div class="col-md-6">
                                <select class="form-control" name="status" id="status">
                                    @foreach($status as $post){
                                    @if($post->status_id === $payment->status_id){
                                    <option value={{$post['status_id']}}>{{$post['name']}}</option>
                                    }@endif}
                                    @endforeach
                                    @foreach($status as $post){
                                    @if($post->status_id !== $payment->status_id){
                                    <option value={{$post['status_id']}}>{{$post['name']}}</option>
                                    }@endif}
                                    @endforeach
                                </select>
                                @if ($errors->has('status'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('status') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        @endif
                        
                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Zapisz
                                </button>
                            </div>
                        </div>
                    </form>
                    <div style="">
                        <button type="submit" onClick="location.href = '{{ url('adminPayment',$payment->expenses_id) }}'" class="btn btn-primary" style="position:absolute; margin-left:67%; margin-top:-6.5%;">Anuluj</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
