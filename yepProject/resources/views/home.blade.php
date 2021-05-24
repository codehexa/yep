@extends('layouts.app')

@section('content')
<div class="container">
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            @switch($error)
                @case ("NO_HAS_POWER_ADMIN")
                <h4 class="text-center text-danger"> {{ __('strings.err_need_admin_power') }}</h4>
                @break
            @endswitch
        @endforeach
    @endif

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
