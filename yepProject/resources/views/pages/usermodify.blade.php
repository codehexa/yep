@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h5>{{ __('strings.lb_settings') }} &gt; {{ __('strings.lb_user_manage') }} &gt; {{ __('strings.lb_user_modify') }}</h5>
    <div class="mt-3"><a href="/userManage" class="btn btn-outline-secondary btn-sm"><i class="fa fa-arrow-alt-circle-left"></i> {{ __("strings.fn_list") }}</a></div>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            @switch($error)
                @case ("FAIL_TO_UPDATE")
                <h4 class="text-center text-danger"> {{ __('strings.err_fail_to_update') }}</h4>
                @break
            @endswitch
        @endforeach
    @endif

    <div class="mt-3">
        <form name="frm" id="frm" method="post" action="/userUpdate">
            @csrf
            <input type="hidden" name="up_id" id="up_id" value="{{ $data->id }}"/>
            <div class="list-group">
                <div class="list-group-item list-group-item-action ">
                    <div class="form-group">
                        <label for="up_name" class="text-secondary">{{ __('strings.lb_name') }}</label>
                        <input type="text" name="up_name" class="form-control" id="up_name" value="{{ $data->name }}"/>
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
                            <button id="btn_change_pw" class="btn btn-sm btn-primary"><i class="fa fa-lock"></i> {{ __('strings.lb_change_password') }}</button>
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
                <div class="list-group-item list-group-item-action ">
                    <div class="form-group">
                        <label for="up_power" class="text-secondary">{{ __('strings.lb_power') }}</label>
                        <select name="up_power" id="up_power" class="form-control">
                            @for ($i=0; $i < sizeof($powers) ; $i++)
                                <option value="{{ $powers[$i]["value"] }}"
                                @if ($powers[$i]["value"] == $data->power)
                                    selected
                                @endif
                                >{{ $powers[$i]["name"] }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="list-group-item list-group-item-action">
                    <div class="form-group">
                        <label for="up_live" class="text-secondary">{{ __('strings.lb_live') }}</label>
                        <select name="up_live" id="up_live" class="form-control">
                            @for ($i=0; $i < sizeof($lives); $i++)
                                <option value="{{ $lives[$i]["value"] }}"
                                @if ($lives[$i]["value"] == $data->live)
                                    selected
                                @endif
                                >{{ $lives[$i]["name"] }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="list-group-item list-group-item-action form-inline">
                    <div class="d-flex justify-content-between">
                        <label for="up_drop_date" class="text-secondary">{{ __('strings.lb_drop_date') }}</label>
                        <div class="ml-3" id="up_drop_date">{{ $data->drop_date }}</div>
                    </div>
                </div>
                <div class="list-group-item list-group-item-action">
                    <div class="form-group">
                        <label for="up_academy_id" class="text-secondary">{{ __('strings.lb_academy_name') }}</label>
                        <select name="up_academy_id" id="up_academy_id" class="form-control">
                            <option value="0">{{ __('strings.lb_no_academy_register') }}</option>
                            @foreach($academies as $academy)
                                <option value="{{ $academy->id }}"
                                @if ($academy->id == $data->academy_id)
                                    selected
                                @endif
                                >{{ $academy->ac_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="list-group-item list-group-item-action form-inline">
                    <div class="d-flex justify-content-between">
                        <label for="up_last_login" class="text-secondary">{{ __('strings.lb_last_login') }}</label>
                        <div class="ml-3" id="up_last_login">{{ $data->last_login }}</div>
                    </div>
                </div>
            </div>
        </form>

    </div>

    <div class="mt-3 btn-group">
        <button id="btnSubmit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> {{ __('strings.fn_update') }}</button>
    </div>
</div>


<div class="modal fade" id="confirmModalCenter" tabindex="-1" role="dialog" aria-labelledby="confirmModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLongTitle">{{ __('strings.lb_confirm') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>{{ __('strings.lb_confirm_update_again') }}</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_confirm_submit" ><i class="fa fa-save"></i> {{ __('strings.fn_submit') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="functionModalCenter" tabindex="-1" role="dialog" aria-labelledby="functionModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="functionModalLongTitle">{{ __('strings.lb_change_password') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="pwFrm" id="pwFrm" method="post" action="/userPasswordChange">
                    @csrf
                    <input type="hidden" name="up_new_id" id="up_new_id" value="{{ $data->id }}"/>
                    <h4>{{ __('strings.lb_change_password_title') }}</h4>
                    <div class="form-group">
                        <label for="up_new_password">{{ __('strings.str_insert_new_password') }}</label>
                        <input type="password" name="up_new_password" id="up_new_password" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="up_new_password_2">{{ __('strings.str_insert_re_password') }}</label>
                        <input type="password" name="up_new_password_2" id="up_new_password_2" class="form-control"/>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <span id="cw_spin" class="d-none"><i class="fa fa-spin fa-spinner"></i> </span>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
                <button type="button" class="btn btn-primary" id="btn_change_password"><i class="fa fa-save"></i> {{ __('strings.fn_submit') }}</button>
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
        $(document).on("click","#btn_change_pw",function (){
            event.preventDefault();
            $("#functionModalCenter").modal("show");
        });

        $(document).on("click","#btn_change_password",function (){
            event.preventDefault();
            if ($("#up_new_password").val() !== $("#up_new_password_2").val()){
                showAlert("{{ __('strings.err_no_match_password') }}");
                return;
            }

            $("#cw_spin").toggleClass();

            $("#pwFrm").submit();
        })

        $(document).on("click","#btnSubmit",function (){
            event.preventDefault();
            $("#confirmModalCenter").modal("show");
        });

        $(document).on("click","#btn_confirm_submit",function(){
            event.preventDefault();
            $("#frm").submit();
        });

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
