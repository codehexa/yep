@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h5>{{ __('strings.lb_comment_manage') }} </h5>
    <div class="mt-3 btn-group">
        <a href="/home" class="btn btn-outline-secondary btn-sm"><i class="fa fa-home"></i> {{ __("strings.fn_home") }}</a>
        <button id="btn_add" name="btn_add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> {{ __('strings.fn_add') }}</button>
        <button id="btn_del" name="btn_del" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> {{ __('strings.fn_delete') }}</button>
    </div>
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
            @endswitch
        @endforeach
    @endif

    <div class="mt-3 form-group">
        <div class="form-inline">
            <label for="section_grades" class="form-label">{{ __('strings.lb_section_grades') }}</label>
            <select name="section_grades" id="section_grades" class="form-select ml-3">
                <option value="">{{ __('strings.fn_all') }}</option>
                @foreach($grades as $grade)
                    <option value="{{ $grade->id }}"
                            @if ($rGrade != '' && $rGrade == $grade->id)
                                selected
                            @endif
                    >{{ $grade->scg_name }}</option>
                @endforeach
            </select>

            <label for="section_subject" class="form-label ml-3">{{ __('strings.lb_subject') }}</label>
            <select name="section_subject" id="section_subject" class="form-select ml-3">
                <option value="">{{ __('strings.lb_select_subject') }}</option>
                @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}"
                        @if ($rSjId == $subject->id)
                            selected
                        @endif
                    >{{ $subject->curri_id == "0" ? "": $subject->Curriculum->curri_name."_" }}
                    {{ $subject->sj_title }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mt-3">
        <table class="mt-3 table table-striped">
            <thead>
                <tr class="text-center">
                    <th scope="col">#</th>
                    <th scope="col">{{ __('strings.lb_section_grades') }}</th>
                    <th scope="col">{{ __('strings.lb_subject') }}</th>
                    <th scope="col">{{ __('strings.lb_min_score_title') }}</th>
                    <th scope="col">{{ __('strings.lb_max_score_title') }}</th>
                    <th scope="col">{{ __('strings.lb_comment_context') }}</th>
                    <th scope="col">{{ __('strings.lb_function') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $datum)
                <tr class="text-center">
                    <th scope="row">{{ $datum->id }}</th>
                    <td>{{ is_null($datum->SchoolGrade)? '-':$datum->SchoolGrade->scg_name }}</td>
                    <td>{{ $datum->Subject->sj_title }}</td>
                    <td>{{ $datum->min_score }}</td>
                    <td>{{ $datum->max_score }}</td>
                    <td>{{ $datum->opinion }}</td>
                    <td><a href="#" class="btn btn-primary btn-sm fn_item" fn_id="{{ $datum->id }}">{{ __('strings.lb_btn_manage') }}</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if (!is_null($data) && sizeof($data) <= 0)
            <div class="text-secondary">{{ __('strings.str_there_is_no_data')}}</div>
        @endif
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
                    <input type="hidden" name="sj_id" id="sj_id" value="{{ $rSjId }}"/>
                    <input type="hidden" name="sg_id" id="sg_id" value="{{ $rGrade }}"/>
                    <div class="form-group d-flex">
                        <input type="text" name="info_gap" id="info_gap" class="form-control" placeholder="{{ __('strings.str_insert_comment_gap') }}"/>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <i id="fn_loading" class="fa fa-spin fa-spinner mr-3 d-none"></i>
                <button type="button" class="btn btn-primary" id="btnCmSubmit" ><i class="fa fa-save"></i> {{ __('strings.fn_okay') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="opinionModalCenter" tabindex="-1" role="dialog" aria-labelledby="opinionModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="opinionModalLongTitle">{{ __('strings.lb_alert') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="frmOpinion" id="frmOpinion" method="post" action="/storeComment">
                    @csrf
                    <input type="hidden" name="up_cm_id" id="up_cm_id"/>
                    <input type="hidden" name="up_sg_id" id="up_sg_id" value="{{ $rGrade }}"/>
                    <input type="hidden" name="up_sj_id" id="up_sj_id" value="{{ $rSjId }}"/>
                    <div class="form-group">
                        <label for="in_opinion">{{ __('strings.lb_comment_opinion') }}</label>
                        <input type="text" name="in_opinion" id="in_opinion" class="form-control" placeholder="{{ __('strings.str_insert_opinion') }}"/>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <span id="opinion_spin" class="d-none"><i class="fa fa-spin fa-spinner"></i> </span>
                <button type="button" class="btn btn-primary" id="btn_store_opinion"><i class="fa fa-save"></i> {{ __('strings.fn_okay') }}</button>
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
                <form name="delFrm" id="delFrm" method="post" action="/delTestArea">
                    @csrf
                    <input type="hidden" name="del_id" id="del_id"/>
                </form>
            </div>
            <div class="modal-footer">
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

            if (curVal === ''){
                location.href = "/comments";
            }else{
                location.href = "/comments/" + curVal;
            }
        });

        $(document).on("change","#section_subject",function (){
            let curVal = $(this).val();
            let grade = $("#section_grades").val();

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
            $("#sjFrm").attr({"action":"/setComments"});
        });

        // pre code
        $(document).on("click","#btnCmSubmit",function (){
            event.preventDefault();

            let inGap = $("#info_gap").val();

            if (parseInt(inGap) <= 0){
                showAlert("{{ __('strings.str_must_zero_over') }}");
                return;
            }

            if (parseInt($("#info_gap").val()) >= {{ $score }}){
                showAlert("{{ __('strings.str_must_max_under',["MAX"=>$score]) }}");
                return;
            }

            $("#cmFrm").submit();
        });


        $(document).on("click","#btnTaDelete",function (){
            event.preventDefault();
            $("#confirmModalCenter").modal("show");
        });

        $(document).on("click","#btnDeleteDo",function (){
            event.preventDefault();
            $("#delFrm").submit();
        });

        $(document).on("click",".fn_item",function (){
            event.preventDefault();
            $("#opinionModalCenter").modal("show");
            $("#btnCmDelete").removeClass("d-none");
            $("#opinion_spin").removeClass("d-none");
            $("#frmOpinion").attr({"action":"/storeComment"});

            let clId = $(this).attr("fn_id");
            $("#del_id").val(clId);
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
                        $("#in_opinion").val(msg.data.opinion);
                    }else{
                        showAlert("{{ __('strings.err_get_info') }}");
                        return;
                    }
                    $("#opinion_spin").addClass("d-none");
                },
                error:function(e1,e2,e3){
                    showAlert(e2);
                }
            })
        });


        $(document).on("click","#btn_store_opinion",function (){
            event.preventDefault();
            if ($("#in_opinion").val() === ""){
                showAlert("{{ __('strings.str_insert_opinion') }}");
                return;
            }

            $("#frmOpinion").submit();
        });



        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
