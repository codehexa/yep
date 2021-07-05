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
        <div class="list-group">
            <a href="{{ route('userManage') }}" class="list-group-item list-group-item-action d-flex justify-content-between">
                <div class="btn btn-primary">
                    <i class="fa fa-user-cog"></i> {{ __('strings.lb_user_manage') }}
                </div>
                <p>최고 관리자, 운영자, 선생님의 정보를 관리하는 기능.</p>
            </a>
            <a href="{{ route('academyManage') }}" class="list-group-item list-group-item-action d-flex justify-content-between">
                <div class="btn btn-primary">
                    <i class="fa fa-school"></i> {{ __('strings.lb_academy_manage') }}
                </div>
                <p>본 원에서 운영하는 각 학원 (관) 을 관리하는 기능.</p>
            </a>
            <a href="{{ route('hakgis') }}" class="list-group-item list-group-item-action d-flex justify-content-between">
                <div class="btn btn-primary">
                    <i class="fa fa-calendar-alt"></i> {{ __('strings.lb_hakgi_manage') }}
                </div>
                <p>학기를 관리하는 기능.</p>
            </a>
            <a href="{{ route('schoolGrades') }}" class="list-group-item list-group-item-action d-flex justify-content-between">
                <div class="btn btn-primary">
                    <i class="fa fa-graduation-cap"></i> {{ __('strings.lb_grade_manage') }}
                </div>
                <p>유치원, 초등학교, 중등학교, 고등학교, 대학교 등 학년의 기본 값을 정의하는 기능.</p>
            </a>
            <a href="{{ route('options') }}" class="list-group-item list-group-item-action d-flex justify-content-between">
                <div class="btn btn-primary">
                    <i class="fa fa-filter"></i> {{ __('strings.lb_options_manage') }}
                </div>
                <p>옵션을 설정하는 기능. 코드는 프로그램 제작자의 의도에 따라 설정된 것이라 운영자는 값 만 변경 가능.</p>
            </a>
            <a href="{{ route('logsView') }}" class="list-group-item list-group-item-action d-flex justify-content-between">
                <div class="btn btn-primary">
                    <i class="fa fa-archive"></i> {{ __('strings.lb_logs_manage') }}
                </div>
                <p>전체 로그를 확인할 수 있습니다.</p>
            </a>
        </div>

    </div>
</div>
@endsection
