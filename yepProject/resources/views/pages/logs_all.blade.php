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

    <h3 class="mt-3 pb-3">{{ __('strings.lb_logs_manage') }}</h3>

    <div class="mt-3">
        <div class="list-group">
            <a href="{{ route('logsUsers') }}" class="list-group-item list-group-item-action d-flex justify-content-between">
                <div class="btn btn-primary">
                    <i class="fa fa-user-cog"></i> {{ __('strings.lb_logs_users') }}
                </div>
                <p>최고 관리자, 운영자, 선생님의 정보를 변경 로그.</p>
            </a>
            <a href="{{ route('logsAcademy') }}" class="list-group-item list-group-item-action d-flex justify-content-between">
                <div class="btn btn-primary">
                    <i class="fa fa-school"></i> {{ __('strings.lb_logs_academy') }}
                </div>
                <p>학원정보 변경 로그.</p>
            </a>
            <a href="{{ route('optionLogList') }}" class="list-group-item list-group-item-action d-flex justify-content-between">
                <div class="btn btn-primary">
                    <i class="fa fa-archive"></i> {{ __('strings.lb_logs_options') }}
                </div>
                <p>옵션 정보 변경 로그.</p>
            </a>
        </div>

    </div>
</div>
@endsection
