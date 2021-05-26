@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h5>{{ __('strings.lb_settings') }} &gt; {{ __('strings.lb_user_manage') }} &gt; {{ __('strings.lb_user_view') }}</h5>
    <div class="mt-3"><a href="/userManage" class="btn btn-outline-secondary btn-sm"><i class="fa fa-arrow-alt-circle-left"></i> {{ __("strings.fn_list") }}</a></div>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            @switch($error)
                @case ("NO_HAS_POWER_ADMIN")
                <h4 class="text-center text-danger"> {{ __('strings.err_need_admin_power') }}</h4>
                @break
            @endswitch
        @endforeach
    @endif

    <div class="mt-3">
        <div class="list-group">
            <div class="list-group-item list-group-item-action form-inline">
                <div class="d-flex justify-content-between">
                    <label for="up_name" class="text-secondary">{{ __('strings.lb_name') }}</label>
                    <div class="ml-3" id="up_name">{{ $data->name }}</div>
                </div>
            </div>
            <div class="list-group-item list-group-item-action form-inline">
                <div class="d-flex justify-content-between">
                    <label for="up_email" class="text-secondary">{{ __('strings.lb_email') }}</label>
                    <div class="ml-3" id="up_email">{{ $data->email }}</div>
                </div>
            </div>
            <div class="list-group-item list-group-item-action form-inline">
                <div class="d-flex justify-content-between">
                    <label for="up_password" class="text-secondary">{{ __('strings.lb_password') }}</label>
                    <div class="ml-3" id="up_password">
                        {{ __('strings.lb_crypt_string') }}
                    </div>
                </div>
            </div>
            <div class="list-group-item list-group-item-action form-inline">
                <div class="d-flex justify-content-between">
                    <label for="up_created_at" class="text-secondary">{{ __('strings.lb_created_at') }}</label>
                    <div class="ml-3" id="up_created_at">{{ $data->created_at }}</div>
                </div>
            </div>
            <div class="list-group-item list-group-item-action form-inline">
                <div class="d-flex justify-content-between">
                    <label for="up_updated_at" class="text-secondary">{{ __('strings.lb_updated_at') }}</label>
                    <div class="ml-3" id="up_updated_at">{{ $data->updated_at }}</div>
                </div>
            </div>
            <div class="list-group-item list-group-item-action form-inline">
                <div class="d-flex justify-content-between">
                    <label for="up_power" class="text-secondary">{{ __('strings.lb_power') }}</label>
                    <div class="ml-3" id="up_power">{{ $data->power }}</div>
                </div>
            </div>
            <div class="list-group-item list-group-item-action form-inline">
                <div class="d-flex justify-content-between">
                    <label for="up_live" class="text-secondary">{{ __('strings.lb_live') }}</label>
                    <div class="ml-3" id="up_live">{{ $data->live }}</div>
                </div>
            </div>
            <div class="list-group-item list-group-item-action form-inline">
                <div class="d-flex justify-content-between">
                    <label for="up_drop_date" class="text-secondary">{{ __('strings.lb_drop_date') }}</label>
                    <div class="ml-3" id="up_drop_date">{{ $data->drop_date }}</div>
                </div>
            </div>
            <div class="list-group-item list-group-item-action form-inline">
                <div class="d-flex justify-content-between">
                    <label for="up_academy_id" class="text-secondary">{{ __('strings.lb_academy_name') }}</label>
                    <div class="ml-3" id="up_academy_id">{{ is_null($data->academy) ? trans('strings.lb_no_academy_register') : $data->academy->ac_name }}</div>
                </div>
            </div>
            <div class="list-group-item list-group-item-action form-inline">
                <div class="d-flex justify-content-between">
                    <label for="up_last_login" class="text-secondary">{{ __('strings.lb_last_login') }}</label>
                    <div class="ml-3" id="up_last_login">{{ $data->last_login }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3 btn-group">
        <a href="/userModify/{{ $data->id }}" class="btn btn-secondary btn-sm"><i class="fa fa-user-edit"></i> {{ __('strings.fn_modify') }}</a>
        <a href="#" id="btnStop" class="btn btn-danger btn-sm"><i class="fa fa-stop-circle"></i> {{ __('strings.fn_stop') }}</a>
    </div>
</div>


<div class="modal fade" id="functionModalCenter" tabindex="-1" role="dialog" aria-labelledby="functionModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="functionModalLongTitle">{{ __('strings.lb_function') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-check-circle"></i> {{ __('strings.fn_okay') }}</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="alertModalCenter" tabindex="-1" role="dialog" aria-labelledby="alertModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alertModalLongTitle">{{ __('strings.lb_alert') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="fn_body"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-check-circle"></i> {{ __('strings.fn_okay') }}</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script type="text/javascript">
        // show
        $(document).on("click",".fn_c_show",function (){
            let uid = $(this).attr("fn_c_id");
            location.href = "/userView/" + uid;
        });

        $(document).on("click",".fn_user",function (){
            event.preventDefault();
            $(this).parent().children("div").toggleClass("d-none");
        });

        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
