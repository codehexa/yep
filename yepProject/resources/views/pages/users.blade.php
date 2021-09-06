@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h5>{{ __('strings.lb_settings') }} &gt; {{ __('strings.lb_user_manage') }}</h5>
    <div class="mt-3"><a href="/settings" class="btn btn-outline-secondary btn-sm"><i class="fa fa-arrow-alt-circle-left"></i> {{ __("strings.fn_backward") }}</a></div>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            @switch($error)
                @case ("FAIL_TO_SAVE_DUP")
                <h4 class="text-center text-danger"> {{ __('strings.err_fail_to_new_add_dup') }}</h4>
                @break
                @case ("FAIL_TO_SAVE")
                <h4 class="text-center text-danger"> {{ __('strings.err_fail_to_save') }}</h4>
                @break
            @endswitch
        @endforeach
    @endif

    <div class="mt-3">
        <form name="searchFrm" id="searchFrm" method="post" action="">
            @csrf
            <div class="form-inline">
                <div class="form-group">
                    <label for="up_key">{{ __('strings.lb_search_key') }}</label>
                    <input type="text" name="up_key" id="up_key" class="form-control form-control-sm ml-3" value="{{ $name }}" placeholder="{{ __('strings.lb_insert_user_name') }}">
                </div>
                <button id="btn_search" name="btn_search" class="btn btn-sm btn-primary ml-3">{{ __('strings.fn_search') }}</button>
                <button id="btn_add" name="btn_add" class="btn btn-sm btn-primary ml-3">{{ __('strings.fn_add') }}</button>
            </div>
        </form>

        <table class="mt-3 table table-striped">
            <thead>
                <tr class="text-center">
                    <th scope="col">#</th>
                    <th scope="col">{{ __('strings.lb_power') }}</th>
                    <th scope="col">{{ __('strings.lb_academy_name') }}</th>
                    <th scope="col">{{ __('strings.lb_name') }}</th>
                    <th scope="col">{{ __('strings.lb_email') }}</th>
                    <th scope="col">{{ __('strings.lb_created_date') }}</th>
                    <th scope="col">{{ __('strings.lb_last_login') }}</th>
                    <th scope="col">{{ __('strings.lb_live') }}</th>
                    <th scope="col">{{ __('strings.lb_function') }}</th>
                    <th scope="col">{{ __('strings.lb_drop_date') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $datum)
                <tr class="text-center">
                    <th scope="row">{{ $datum->id }}</th>
                    <td>{{ $datum->power }}</td>
                    <td>
                        @if (is_null($datum->academy))
                            {{ __('strings.fn_not_set') }}
                        @else
                            {{ $datum->academy->ac_name }}
                        @endif
                    </td>
                    <td>{{ $datum->name }}</td>
                    <td>{{ $datum->email }}</td>
                    <td>{{ $datum->created_at }}</td>
                    <td>{{ $datum->last_login }}</td>
                    <td>
                        @if ($datum->live == "Y")
                            {{ __('strings.fn_live_y') }}
                        @else
                            {{ __('strings.fn_live_n') }}
                        @endif
                    </td>
                    <td><a href="#" class="btn btn-primary btn-sm fn_user" fn_id="{{ $datum->id }}">{{ __('strings.lb_btn_manage') }}</a>
                        <div class="list-group d-none position-fixed float-end">
                            <div class="list-group-item list-group-item-action">
                                <a href="#" class="btn btn-link btn-sm fn_c_show" fn_c_id="{{ $datum->id }}"><i class="fa fa-eye"></i> {{ __('strings.fn_show') }}</a>
                            </div>
                            <div class="list-group-item list-group-item-action">
                                <a href="#" class="btn btn-link btn-sm fn_c_modify" fn_c_id="{{ $datum->id }}"><i class="fa fa-user-edit"></i> {{ __('strings.fn_modify') }}</a>
                            </div>
                        </div>
                    </td>
                    <td>{{ $datum->drop_date }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if (sizeof($data) <= 0)
            <div class="text-secondary">{{ __('strings.str_there_is_no_data')}}</div>
        @endif
        <div class="mt-3">
            {{ $data->links() }}
        </div>
    </div>
</div>



<div class="modal fade" id="infoModalCenter" tabindex="-1" role="dialog" aria-labelledby="infoModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLongTitle">{{ __('strings.fn_add') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="frmUser" id="frmUser" method="post" action="/userAdd">
                    @csrf
                    <div class="list-group">
                        <div class="list-group-item">
                            <label for="up_usname">{{ __('strings.lb_name') }}</label>
                            <input type="text" name="up_usname" id="up_usname" class="form-control" placeholder="{{ __('strings.str_insert_class_name') }}"/>
                        </div>
                        <div class="list-group-item">
                            <label for="up_email">{{ __('strings.lb_email') }}</label>
                            <input type="email" name="up_email" id="up_email" class="form-control" placeholder="{{ __('strings.str_insert_email') }}"/>
                        </div>
                        <div class="list-group-item">
                            <label for="up_password">{{ __('strings.lb_password') }}</label>
                            <input type="password" name="up_password" id="up_password" class="form-control" placeholder="{{ __('strings.str_insert_password') }}"/>
                        </div>
                        <div class="list-group-item">
                            <label for="up_password_2">{{ __('strings.lb_password_2') }}</label>
                            <input type="password" name="up_password_2" id="up_password_2" class="form-control" placeholder="{{ __('strings.str_insert_password_2') }}"/>
                        </div>
                        <div class="list-group-item">
                            <label for="up_zoom_id">{{ __('strings.lb_zoom_id') }}</label>
                            <input type="text" name="up_zoom_id" id="up_zoom_id" class="form-control" placeholder="{{ __('strings.str_insert_zoom_id') }}"/>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <span id="fn_loader" class="d-none"><i class="fa fa-spin fa-spinner"></i> </span>
                <button type="button" class="btn btn-primary" id="btnSave"><i class="fa fa-check-circle"></i> {{ __('strings.fn_okay') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_okay') }}</button>
            </div>
        </div>
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
        // search
        $(document).on("click","#btn_search",function (){
            event.preventDefault();
            if ($("#up_key").val() === ""){
                location.href = "/userManage";
            }else{
                location.href = "/userManage/" + encodeURIComponent($("#up_key").val());
            }
        });
        // 신규 등록 클릭 시
        $(document).on("click","#btn_add",function (){
            event.preventDefault();
            $("#infoModalCenter").modal("show");
            $("#frmUser").reset();

            $("#fn_loader").addClass("d-none");
        });

        // btnSave click
        $(document).on("click","#btnSave",function (){
            if ($("#up_usname").val() === ""){
                showAlert("{{ __('strings.str_insert_class_name') }}");
                return;
            }

            if ($("#up_email").val() === ""){
                showAlert("{{ __('strings.str_insert_email') }}");
                return;
            }

            if ($("#up_password").val() === ""){
                showAlert("{{ __('strings.str_insert_password') }}");
                return;
            }

            if ($("#up_password").val() !== $("#up_password_2").val()){
                showAlert("{{ __('strings.str_insert_password_2') }}");
                return;
            }

            $("#fn_loader").removeClass("d-none");
            $("#frmUser").submit();
        });

        // show
        $(document).on("click",".fn_c_show",function (){
            let uid = $(this).attr("fn_c_id");
            location.href = "/userView/" + uid;
        });

        $(document).on("click",".fn_c_modify",function (){
            let uid = $(this).attr("fn_c_id");
            location.href = "/userModify/" + uid;
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
