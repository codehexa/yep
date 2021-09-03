@extends('layouts.app_bms')

@section('content')
<div class="container-fluid">
    <h5><i class="fa fa-wind"></i> {{ __('strings.lb_send_log_title') }} </h5>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            @switch($error)
                @case ("FAIL_ALREADY_HAS")
                <h4 class="text-center text-danger"> {{ __('strings.err_already_has') }}</h4>
                @break
                @case ("FAIL_TO_DELETE")
                <h4 class="text-center text-danger"> {{ __('strings.err_fail_to_delete') }}</h4>
                @break
                @case ("FAIL_TO_MODIFY")
                <h4 class="text-center text-danger"> {{ __('strings.err_fail_to_update') }}</h4>
                @break
                @case ("FAIL_TO_SAVE")
                <h4 class="text-center text-danger"> {{ __('strings.err_fail_to_save') }}</h4>
                @break
                @case ("NOTHING_TO_DELETE")
                <h4 class="text-center text-danger"> {{ __('strings.err_nothing_to_delete') }}</h4>
                @break
            @endswitch
        @endforeach
    @endif

    <div class="mt-3">
        <div class="form-group form-inline">
            <label>{{ __('strings.lb_search_condition') }}</label>
            <select name="up_year" id="up_year" class="form-control form-control-sm ml-2">
                @for($y = date("Y"); $y > date("Y") - 2; $y--)
                    <option value="{{ $y }}"
                    @if ($nYear == $y)
                        selected
                    @endif
                    >{{ $y }} {{ __('strings.lb_year') }}</option>
                @endfor
            </select>
            <select name="up_month" id="up_month" class="form-control form-control-sm ml-2">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}"
                            @if ($nMonth == $m)
                            selected
                        @endif
                    >{{ $m }} {{ __('strings.lb_month') }}</option>
                @endfor
            </select>

            <button id="btn_load" class="btn btn-primary btn-sm ml-2"><i class="fa fa-search"></i> {{ __('strings.fn_select') }}</button>
        </div>
    </div>

    <div class="mt-3">
        <div class="mt-2 list-group">
            @foreach ($data as $datum)
                <div class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5>
                                {{ $datum->bsl_title }}
                            </h5>
                            <span class="badge badge-primary badge-pill ml-2 mb-2">{{ $datum->bsl_result_msg }}</span>
                        </div>
                        <small><span class="text-secondary">{{ $datum->updated_at }}</span> </small>
                    </div>
                    <p class="mb-1 text-truncate fn_target">{{ $datum->bsl_send_text }}</p>
                    <small>{{ __('strings.lb_list_log_guide',[
                        "NAME"=>$datum->bsl_us_name,"SENT"=>$datum->bsl_sent_date == "" ? "-":$datum->bsl_sent_date,
                        "POINT"=>$datum->bls_usage_point == "" ? "-":$datum->bsl_usage_point,
                        "RESULT_CODE"=>$datum->bsl_aligo_result_code == "" ? "-":$datum->bsl_aligo_result_code
                        ]) }}</small>
                </div>
            @endforeach
        </div>

        <div class="mt-2">
            {{ $data->links('pagination::bootstrap-4') }}
        </div>

    </div>
</div>

<div class="modal fade" id="infoModalCenter" tabindex="-1" role="dialog" aria-labelledby="infoModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLongTitle">{{ __('strings.lb_send_log_context') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="text_body"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_okay') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmModalCenter" tabindex="-1" role="dialog" aria-labelledby="confirmModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLongTitle">{{ __('strings.lb_alert') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="fn_confirm_body">{{ __('strings.str_do_you_want_to_delete_cant_recover') }}</p>
                <form name="delFrm" id="delFrm" method="post" action="/delComments">
                    @csrf
                    <input type="hidden" name="del_id" id="del_id"/>
                </form>
            </div>
            <div class="modal-footer">
                <span id="confirm_spin" class="d-none"><i class="fa fa-spinner fa-spin"></i> </span>
                <button type="button" class="btn btn-primary" id="btnDeleteDo"><i class="fa fa-check-circle"></i> {{ __('strings.fn_okay') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script type="text/javascript">

        $(document).on("click","#btn_load",function (){
            event.preventDefault();

            let _year = $("#up_year").val();
            let _month = $("#up_month").val();

            location.href = "/bms/sending/" + _year + "/" + _month;
        });

        $(document).on("click",".fn_target",function (){
            event.preventDefault();
            let _txt = $(this).text();

            $("#infoModalCenter").modal("show");
            $("#text_body").html(_txt.replace(/(\n|\r\n)/g,"<br/>"));
        });

        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
