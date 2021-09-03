@extends('layouts.app_bms')

@section('content')
<div class="container-fluid">
    <h5><i class="fa fa-wind"></i> {{ __('strings.lb_bms_bbs') }} </h5>
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
        <a href="/bms/bbs" class="btn btn-primary btn-sm"><i class="fa fa-list"></i> {{ __('strings.fn_go_first') }}</a>
        <a href="{{ url()->previous() }}" class="btn btn-primary btn-sm"><i class="fa fa-backward"></i> {{ __('strings.fn_backward') }}</a>
    </div>

    <div class="mt-3">
        <div class="list-group">
            <div class="list-group-item">
                <h5>{!! $data->bbs_type == \App\Models\Configurations::$BBS_TYPE_ALL? "<i class='fa fa-flag'></i> ":"" !!}{{ $data->bbs_title }}</h5>
            </div>
            <div class="list-group-item d-flex justify-content-between">
                <label>{{ $data->us_name }} <span class="text-secondary">{{ $data->updated_at }}</span></label>
                <span>{{ $data->academy_id == "0"? __('strings.lb_bbs_public'):$data->Academy->ac_name }}</span>
                <span>{{ __('strings.lb_id_and_hits',["ID"=>$data->id,"HITS"=>$data->bbs_hits]) }}</span>
            </div>
            <div class="list-group-item" style="min-height: 40rem;">
                {!! nl2br($data->bbs_content) !!}
            </div>
        </div>
        @if ($data->us_id == \Illuminate\Support\Facades\Auth::user()->id)
            <a href="/bms/bbsModify/{{ $data->id }}" id="btnModify" class="btn btn-primary btn-sm mt-3"><i class="fa fa-edit"></i> {{ __('strings.fn_modify') }}</a>
        @endif
        @if ($data->us_id == \Illuminate\Support\Facades\Auth::user()->id || \Illuminate\Support\Facades\Auth::user()->power != \App\Models\Configurations::$USER_POWER_TEACHER)
            <button id="btnDelete" class="btn btn-danger btn-sm mt-3"><i class="fa fa-trash"></i> {{ __('strings.fn_delete') }}</button>
        @endif
    </div>

    <!-- comments -->
    <div class="mt-3">
        <h5>{{ __('strings.lb_add_comment') }}</h5>
        <div class="mt-2 d-flex justify-content-between">
            <input type="text" name="up_comment" id="up_comment" class="form-control form-control-sm"/>
            <button id="btnAddDone" class="btn btn-primary btn-sm ml-1 text-nowrap">
                <i class="fa fa-spin fa-spinner d-none" id="loadAdd"></i>
                {{ __('strings.fn_okay') }}
            </button>
        </div>
        <div class="list-group mt-2" id="listMents">
            @foreach ($added as $ad)
                <div class="list-group-item">
                    <div class="d-flex justify-content-between">
                        <h6>{{ $ad->bc_comment }}</h6>
                        @if (\Illuminate\Support\Facades\Auth::user()->id == $ad->us_id || \Illuminate\Support\Facades\Auth::user()->power != \App\Models\Configurations::$USER_POWER_TEACHER)
                        <a href="#" class="pe-auto fn_del" data-id="{{ $ad->id }}"><i class="fa fa-times " data-id="{{ $ad->id }}"></i></a>
                        @endif
                    </div>

                    <div class="d-flex justify-content-between">
                        <small>{{ $ad->us_name }}</small>
                        <small class="text-secondary">{{ $ad->updated_at }}</small>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-2">{{ $added->links('pagination::bootstrap-4') }}</div>
    </div>
</div>

<div class="modal fade" id="infoModalCenter" tabindex="-1" role="dialog" aria-labelledby="infoModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLongTitle">{{ __('strings.lb_comment_setting') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="cmFrm" id="cmFrm" method="post" action="/setComments">
                    @csrf
                    <input type="hidden" name="cm_id" id="cm_id" value=""/>

                    <div class="form-group">
                        <label for="up_min_score">{{ __('strings.lb_min_score_title') }}</label>
                        <input type="number" name="up_min_score" id="up_min_score" class="form-control" placeholder="{{ __('strings.lb_insert_min_score') }}"/>
                    </div>
                    <div class="form-group">
                        <label for="up_min_score">{{ __('strings.lb_max_score_title') }}</label>
                        <input type="number" name="up_max_score" id="up_max_score" class="form-control" placeholder="{{ __('strings.lb_insert_max_score') }}"/>
                    </div>
                    <div class="form-group">
                        <label for="up_min_score">{{ __('strings.lb_comment_context') }}</label>
                        <textarea name="up_comments" id="up_comments" class="form-control" placeholder="{{ __('strings.lb_insert_comments') }}"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <i id="fn_loading" class="fa fa-spin fa-spinner mr-3 d-none"></i>
                <button type="button" class="btn btn-primary" id="btnCmSubmit" ><i class="fa fa-save"></i> {{ __('strings.fn_okay') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
                <button type="button" class="btn btn-danger d-none" id="btnDelete"><i class="fa fa-trash"></i> {{ __('strings.fn_delete') }}</button>
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
                <form name="delFrm" id="delFrm" method="post" action="/bms/delAddedMent">
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
    <script id="mentTmpl" type="text/x-jquery-tmpl">
        <div class="list-group-item">
            <div class="d-flex justify-content-between">
                <h6>${bc_comment}</h6>
                @if (\Illuminate\Support\Facades\Auth::user()->id == $ad->us_id || \Illuminate\Support\Facades\Auth::user()->power != \App\Models\Configurations::$USER_POWER_TEACHER)
                <a href="#" class="pe-auto fn_del" data-id="${id}"><i class="fa fa-times " data-id="${id}"></i></a>
                @endif
            </div>
            <div class="d-flex justify-content-between">
                <small>${us_name}</small>
                <small class="text-secondary">${updated_at}</small>
            </div>
        </div>
    </script>
    <script type="text/javascript">
        //post
        $(document).on("click","#btnAddDone",function (){
            event.preventDefault();

            if ($("#up_comment").val() === ""){
                showAlert("{{ __('strings.lb_input_added') }}");
                return;
            }

            $("#loadAdd").removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/bms/addMent",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "_txt":$("#up_comment").val(),
                    "_parent":"{{ $data->id }}"
                },
                success:function (msg){
                    if (msg.result === "true"){
                        $("#loadAdd").addClass("d-none");
                        $("#mentTmpl").tmpl(msg.data).prependTo($("#listMents"));
                        $("#up_comment").val("");
                    }else{
                        showAlert("{{ __('strings.fn_save_false') }}");
                        $("#loadAdd").addClass("d-none");
                        return;
                    }
                },
                error:function(e1,e2,e3){
                    $("#loadAdd").addClass("d-none");
                    showAlert(e2);
                }
            })
        });

        // comment delete
        $(document).on("click",".fn_del",function (){
            //
            event.preventDefault();
            $("#del_id").val($(this).data("id"));
            $("#confirmModalCenter").modal("show");
        });

        $(document).on("click","#btnDeleteDo",function (){
            $("#confirm_spin").removeClass("d-none");
            $.ajax({
                type:"POST",
                url:"/bms/delAddedMent",
                dataType:"json",
                data:$("#delFrm").serialize(),
                success:function(msg){
                    if (msg.result === "true"){
                        $(".fn_del").each(function(i,obj){
                            if ($(obj).data("id") === msg.data.id){
                                $(obj).parent().parent().remove();
                            }
                        });
                    }else{
                        showAlert("{{ __('strings.fn_save_false') }}");
                    }
                    $("#confirmModalCenter").modal("hide");
                    $("#confirm_spin").addClass("d-none");
                },
                error:function(e1,e2,e3){
                    showAlert(e2);
                }
            })
        });

        // add

        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
