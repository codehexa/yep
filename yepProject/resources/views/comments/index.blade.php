@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h5>{{ __('strings.lb_comment_manage') }} </h5>
    <div class="mt-3 btn-group">
        <a href="/home" class="btn btn-outline-secondary btn-sm"><i class="fa fa-home"></i> {{ __("strings.fn_home") }}</a>
        <button id="btn_add" name="btn_add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> {{ __('strings.fn_add') }}</button>
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
                @case ("NOTHING_TO_DELETE")
                <h4 class="text-center text-danger"> {{ __('strings.err_nothing_to_delete') }}</h4>
                @break
            @endswitch
        @endforeach
    @endif

    <div class="mt-3 form-group border p-2 bg-primary">
        <div class="form-inline">
            <label for="section_grades" class="form-label">{{ __('strings.lb_section_grades') }}</label>
            <span id="grade_loader" class="fa fa-spin fa-spinner d-none"></span>
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
            <span id="subject_loader" class="fa fa-spin fa-spinner d-none"></span>
            <select name="section_subject" id="section_subject" class="form-select ml-3">
                <option value="">{{ __('strings.lb_select_subject') }}</option>
                @for($i=0; $i < sizeof($subjects); $i++)
                    <option value="{{ $subjects[$i]["id"] }}"
                        @if ($rSjId == $subjects[$i]["id"])
                            selected
                        @endif
                    >
                    {{ $subjects[$i]["title"] }}</option>
                @endfor
            </select>
        </div>
    </div>

    <div class="mt-3">
        <table class="mt-3 table table-striped">
            <thead>
                <tr class="text-center">
                    <th scope="col">#</th>
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
                    <input type="hidden" name="cm_id" id="cm_id" value=""/>
                    <input type="hidden" name="sj_id" id="sj_id" value="{{ $rSjId }}"/>
                    <input type="hidden" name="sg_id" id="sg_id" value="{{ $rGrade }}"/>
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
