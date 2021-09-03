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
                <div class="card-header">{{ __('strings.lb_home_dashboard') }}</div>

                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between">
                            <span>{{ __('strings.lb_name') }}</span>
                            <span class="text-primary">{{ $user->name }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <span>{{ __('strings.lb_email') }}</span>
                            <span class="text-primary">{{ $user->email }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <label>{{ __('strings.lb_power') }}</label>
                            @switch($user->power)
                                @case(\App\Models\Configurations::$USER_POWER_ADMIN)
                                <span class="text-primary">{{ __('strings.lb_administrator') }}</span>
                                @break
                                @case(\App\Models\Configurations::$USER_POWER_MANAGER)
                                <span class="text-primary">{{ __('strings.lb_manager') }}</span>
                                @break
                                @case(\App\Models\Configurations::$USER_POWER_TEACHER)
                                <span class="text-primary">{{ __('strings.lb_teacher') }}</span>
                                @break
                            @endswitch
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <label>{{ __('strings.lb_zoom_id') }}</label>
                            <span class="text-primary">{{ $user->zoom_id }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <label>{{ __('strings.lb_last_login') }}</label>
                            <span class="text-primary">{{ $user->last_login }}</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between">
                            <label>{{ __('strings.lb_show_manual') }}</label>
                            @switch($user->power)
                                @case(\App\Models\Configurations::$USER_POWER_ADMIN)
                                <a href="/manuals/show_manual/0" class="text-primary"><i class="fa fa-download"></i> {{ __('strings.lb_download') }}</a>
                                @break
                                @case(\App\Models\Configurations::$USER_POWER_MANAGER)
                                <a href="/manuals/show_manual/1" class="text-primary"><i class="fa fa-download"></i> {{ __('strings.lb_download') }}</a>
                                @break
                                @case(\App\Models\Configurations::$USER_POWER_TEACHER)
                                <a href="/manuals/show_manual/2" class="text-primary"><i class="fa fa-download"></i> {{ __('strings.lb_download') }}</a>
                                @break
                            @endswitch
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="myInfo" class="btn btn-primary btn-sm"><i class="fa fa-user-edit"></i> {{ __('strings.lb_edit_private') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
