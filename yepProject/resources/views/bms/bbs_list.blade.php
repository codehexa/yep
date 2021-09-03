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
        <a href="/bms/bbspost" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> {{ __('strings.fn_add') }}</a>
    </div>

    <div class="mt-3">
        <div class="mt-2 list-group">
            @foreach ($data as $datum)
                <a href="/bms/bbsView/{{$datum->id}}" class="text-decoration-none">
                    <div class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5>
                                    @if ($datum->bbs_type == '0')
                                        <i class="fa fa-flag"></i>
                                    @endif
                                    {{ $datum->bbs_title }}
                                </h5>
                                <span class="badge badge-primary badge-pill ml-2 mb-2">{{ $datum->bbs_added_count }}</span>
                            </div>
                            <small>{{ $datum->us_name }} <span class="text-secondary">{{ $datum->updated_at }}</span> </small>
                        </div>
                        <p class="mb-1 text-truncate">{{ $datum->bbs_content }}</p>
                        <small>{{ __('strings.lb_id_and_hits',["ID"=>$datum->id,"HITS"=>$datum->bbs_hits]) }}</small>
                    </div>
                </a>
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

        $(document).on("change","#section_grades",function (){
            let curVal = $(this).val();

            $("#grade_loader").removeClass("d-none");

            if (curVal === ''){
                location.href = "/comments";
            }else{
                location.href = "/comments/" + curVal;
            }
        });

        $(document).on("change","#section_subject",function (){
            let curVal = $(this).val();
            let grade = $("#section_grades").val();

            $("#subject_loader").removeClass("d-none");

            if (curVal === ''){
                location.href = "/comments/" + grade;
            }else{
                location.href = "/comments/" + grade + "/" + curVal;
            }
        });

        $(document).on("click","#btn_add",function (){
            event.preventDefault();

            if ($("#section_grades").val() === ""){
                showAlert("{{ __('strings.str_select_grade') }}");
                return;
            }

            if ($("#section_subject").val() === ""){
                showAlert("{{ __('strings.str_select_subject') }}");
                return;
            }

            $("#infoModalCenter").modal("show");
            $("#btnDelete").addClass("d-none");
            $("#sjFrm").attr({"action":"/setComments"});
        });

        // pre code
        $(document).on("click","#btnCmSubmit",function (){
            event.preventDefault();

            if ($("#up_min_score").val() === ""){
                showAlert("{{ __('strings.lb_insert_min_score') }}");
                return;
            }

            if ($("#up_max_score").val() === ""){
                showAlert("{{ __('strings.lb_insert_max_score') }}");
                return;
            }

            if ($("#up_comments").val() === ""){
                showAlert("{{ __('strings.str_insert_opinion') }}");
                return;
            }

            $("#fn_loading").removeClass("d-none");

            $("#cmFrm").submit();
        });


        $(document).on("click","#btnDelete",function (){
            event.preventDefault();
            $("#del_id").val($("#cm_id").val());
            $("#confirmModalCenter").modal("show");
        });

        $(document).on("click","#btnDeleteDo",function (){
            event.preventDefault();
            $("#delFrm").submit();
            $("#confirm_spin").removeClass("d-none");
        });

        $(document).on("click",".fn_item",function (){
            event.preventDefault();
            $("#infoModalCenter").modal("show");
            $("#btnDelete").removeClass("d-none");
            $("#fn_loading").removeClass("d-none");

            let clId = $(this).attr("fn_id");
            $("#up_cm_id").val(clId);

            // 여기까지 작업 중.

            $.ajax({
                type:"POST",
                url:"/getCommentJson",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "cid":clId
                },
                success:function (msg){
                    if (msg.result === "true"){
                        $("#cm_id").val(msg.data.id);
                        $("#sj_id").val(msg.data.sj_id);
                        $("#sg_id").val(msg.data.scg_id);
                        $("#up_min_score").val(msg.data.min_score);
                        $("#up_max_score").val(msg.data.max_score);
                        $("#up_comments").val(msg.data.opinion);

                        $("#cmFrm").prop({"action":"/storeComment"});

                        $("#fn_loading").addClass("d-none");
                    }else{
                        showAlert("{{ __('strings.err_get_info') }}");
                        $("#fn_loading").addClass("d-none");
                        return;
                    }
                    //$("#opinion_spin").addClass("d-none");
                },
                error:function(e1,e2,e3){
                    showAlert(e2);
                }
            })
        });

        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
