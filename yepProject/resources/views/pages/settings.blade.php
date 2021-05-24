@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            @switch($error)
                @case ("NO_HAS_POWER_ADMIN")
                <h4 class="text-center text-danger"> {{ __('strings.err_need_admin_power') }}</h4>
                @break
            @endswitch
        @endforeach
    @endif

    <h3 class="mt-3 pb-3">{{ __('strings.lb_settings') }}</h3>

    <div class="mt-3">
        <a href="{{ route('userManage') }}" class="d-inline btn btn-primary p-2"><i class="fa fa-user-cog"></i> {{ __('strings.lb_user_manage') }}</a>
        <a href="{{ route('academyManage') }}" class="d-inline btn btn-primary p-2"><i class="fa fa-school"></i> {{ __('strings.lb_academy_manage') }}</a>
    </div>
</div>
@endsection
