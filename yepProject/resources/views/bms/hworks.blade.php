@extends('layouts.app_bms')

@section('content')
<div class="container-fluid">
    <h5><i class="fa fa-wind"></i> {{ __('strings.lb_bms_title') }} &gt; {{ __('strings.lb_bms_hworks_manage') }}</h5>

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
        <div class="mt-3">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <label for="up_sg_id" class="form-label">{{ __('strings.lb_grade_name') }}</label>
                        <select name="up_sg_id" id="up_sg_id" class="form-control form-control-sm form-select form-select-sm">
                            <option value="">{{ __('strings.fn_select_item') }}</option>
                            @foreach($schoolGrades as $schoolGrade)
                                <option value="{{ $schoolGrade->id }}">{{ $schoolGrade->scg_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <h6>{{ __('strings.lb_subject') }} <i class="fa fa-spinner fa-spin d-none" id="load_subjects"></i></h6>
                        <div class="list-group" id="ls_subjects">
                        </div>
                    </div>
                </div>
                <div class="col">
                    <h6>{{ __('strings.fn_inputs') }}</h6>
                    <div class="form-inline" id="formButtons">
                    </div>
                    <form name="upFrm" id="upFrm" method="post" action="/bms/hworkStore">
                        @csrf
                        <input type="hidden" name="up_subject_id" id="up_subject_id"/>
                        <input type="hidden" name="saved_sg_id" id="saved_sg_id"/>
                        <div class="list-group mt-3" id="input_boxs">
                            <div class="list-group-item">
                                <label class="form-label">{{ __('strings.lb_bms_school_class') }}</label>
                                <input type="text" class="form-control form-control-sm fn_item" name="up_content" id="up_content"/>
                                <span class="bg-warning fn_tmps" id="tmp_content"></span>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label">{{ __('strings.lb_bms_dt') }}</label>
                                <input type="text" class="form-control form-control-sm fn_item" name="up_dt" id="up_dt"/>
                                <span class="bg-warning fn_tmps" id="tmp_dt"></span>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label">{{ __('strings.lb_bms_books') }}</label>
                                <input type="text" class="form-control form-control-sm fn_item" name="up_book" id="up_book"/>
                                <span class="bg-warning fn_tmps" id="tmp_book"></span>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label">{{ __('strings.lb_bms_output_first') }}</label>
                                <input type="text" class="form-control form-control-sm fn_item" name="up_output_first" id="up_output_first"/>
                                <span class="bg-warning fn_tmps" id="tmp_output_first"></span>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label">{{ __('strings.lb_bms_output_second') }}</label>
                                <input type="text" class="form-control form-control-sm fn_item" name="up_output_second" id="up_output_second"/>
                                <span class="bg-warning fn_tmps" id="tmp_output_second"></span>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label">[{{ __('strings.lb_study_book') }}] {{ __('strings.lb_bms_dt') }}</label>
                                <input type="text" class="form-control form-control-sm fn_item" name="up_opt1" id="up_opt1"/>
                                <span class="bg-warning fn_tmps" id="tmp_opt1"></span>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label">[{{ __('strings.lb_study_book') }}] {{ __('strings.lb_bms_books') }}</label>
                                <input type="text" class="form-control form-control-sm fn_item" name="up_opt2" id="up_opt2"/>
                                <span class="bg-warning fn_tmps" id="tmp_opt2"></span>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label">[{{ __('strings.lb_study_book') }}] {{ __('strings.lb_bms_output_first') }}</label>
                                <input type="text" class="form-control form-control-sm fn_item" name="up_opt3" id="up_opt3"/>
                                <span class="bg-warning fn_tmps" id="tmp_opt3"></span>
                            </div>
                            <div class="list-group-item">
                                <label class="form-label">[{{ __('strings.lb_study_book') }}] {{ __('strings.lb_bms_output_second') }}</label>
                                <input type="text" class="form-control form-control-sm fn_item" name="up_opt4" id="up_opt4"/>
                                <span class="bg-warning fn_tmps" id="tmp_opt4"></span>
                            </div>
                        </div>
                    </form>
                    <button id="btn_save" class="btn btn-primary mt-3"><i class="fa fa-save"></i> {{ __('strings.fn_save') }}</button>
                    <i class="fa fa-spin fa-spinner d-none" id="fn_loading"></i>
                </div>
            </div>
        </div>

    </div>

</div>

<div class="modal fade" id="infoModalCenter" tabindex="-1" role="dialog" aria-labelledby="infoModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLongTitle">{{ __('strings.lb_bms_hworks_information') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

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
    <script id="subjectForm" type="text/x-jquery-tmpl">
        <div class="list-group-item">
            <button data-function="${subject_function}" class="btn btn-primary btn-sm btn-block ls_subject_item" data-id="${id}">${subject_title}</button>
        </div>
    </script>

    <script type="text/javascript">
        let _codes = @json($codes);
        let _nowListIndex = -1; // 현재 선택된 과목 순서 인덱스
        let _nowDataSet = [];   // 현재 가져온 데이터 셋트
        let _targetInput;   // 버튼이 입력되는 대상 박스
        // 학년을 선택할 경우.
        $(document).on("change","#up_sg_id",function(){
            event.preventDefault();
            _nowDataSet = [];   // initial

            $("#load_subjects").removeClass("d-none");
            $.ajax({
                type:"POST",
                url:"/bms/getHworkSubjects",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "_sgId":$("#up_sg_id").val()
                },
                success:function(msg){
                    //
                    $("#ls_subjects").empty();
                    $("#load_subjects").addClass("d-none");
                    $("#subjectForm").tmpl(msg.data).appendTo($("#ls_subjects"));
                    $.each(msg.items,function(i,obj){
                        _nowDataSet.push({"id":obj.clsId,"sets":obj.hdata});
                    });
                    $("#saved_sg_id").val($("#up_sg_id").val());
                },
                error:function(e1,e2,e3){
                    showAlert(e2);
                    $("#load_subjects").addClass("d-none");
                    return;
                }
            })
        });

        // 과목 이름 아이템을 클릭할 때
        $(document).on("click",".ls_subject_item",function (){
            event.preventDefault();
            $(".ls_subject_item").removeClass("active");
            _nowListIndex = $(".ls_subject_item").index($(this));

            $(".ls_subject_item").eq(_nowListIndex).addClass("active");

            printData();
        });

        // 해당 과목에 대한 정보를 각 요소에 프린트 한다.
        function printData(){
            let _nowId = $(".ls_subject_item").eq(_nowListIndex).data("id");

            let _nowData;
            $.each(_nowDataSet,function (i,obj){
                if (obj.id === _nowId){
                    _nowData = obj.sets;
                    return false;
                }
            });

            $("#up_subject_id").val(_nowId);
            $("#up_content").val(_nowData.hwork_content);
            $("#up_dt").val(_nowData.hwork_dt);
            $("#up_book").val(_nowData.hwork_book);
            $("#up_output_first").val(_nowData.hwork_output_first);
            $("#up_output_second").val(_nowData.hwork_output_second);
            $("#up_opt1").val(_nowData.hwork_opt1);
            $("#up_opt2").val(_nowData.hwork_opt2);
            $("#up_opt3").val(_nowData.hwork_opt3);
            $("#up_opt4").val(_nowData.hwork_opt4);

            codeChanges();
        }

        // input 박스를 선택할 때.
        $(document).on("click",".fn_item",function (){
            _targetInput = $(this).attr("id");
            let _input_index = $(".fn_item").index($(this));
            $("#input_boxs").children("div").removeClass("active");
            $("#input_boxs").find("div").eq(_input_index).addClass("active");
        });


        // save
        $(document).on("click","#btn_save",function (){
            //
            event.preventDefault();
            if ($("#saved_sg_id").val() === ""){
                showAlert("{{ __('strings.lb_select_school_grade') }}");
                return;
            }

            if ($("#up_subject_id").val() === ""){
                showAlert("{{ __('strings.lb_select_subject') }}");
                return;
            }

            if ($("#up_content").val() === ""){
                showAlert("{{ __('strings.lb_insert_content') }}");
                return;
            }

            if ($("#up_dt").val() === ""){
                showAlert("{{ __('strings.lb_insert_dt') }}");
                return;
            }

            if ($("#up_books").val() === ""){
                showAlert("{{ __('strings.lb_insert_books') }}");
                return;
            }

            if ($("#up_output_first").val() === ""){
                showAlert("{{ __('strings.lb_insert_output_first') }}");
                return;
            }

            if ($("#up_output_second").val() === ""){
                showAlert("{{ __('strings.lb_insert_output_second') }}");
                return;
            }

            $("#fn_loading").removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/bms/hworkStore",
                dataType:"json",
                data:$("#upFrm").serialize(),
                success:function(msg){
                    //
                    if(msg.result === "true"){
                        showAlert("{{ __('strings.fn_save_complete') }}");
                        $("#fn_loading").addClass("d-none");

                        $.each(_nowDataSet,function(i,obj){
                            if (obj.id === $(".ls_subject_item").eq(_nowListIndex).data("id")){
                                obj.sets.hwork_content = $("#up_content").val();
                                obj.sets.hwork_dt = $("#up_dt").val();
                                obj.sets.hwork_book = $("#up_book").val();
                                obj.sets.hwork_output_first = $("#up_output_first").val();
                                obj.sets.hwork_output_second = $("#up_output_second").val();
                                obj.sets.hwork_opt1 = $("#up_opt1").val();
                                obj.sets.hwork_opt2 = $("#up_opt2").val();
                                obj.sets.hwork_opt3 = $("#up_opt3").val();
                                obj.sets.hwork_opt4 = $("#up_opt4").val();

                            }
                        })

                        return;
                    }else{
                        showAlert("{{ __('strings.fn_save_false') }}")
                        $("#fn_loading").addClass("d-none");
                        return;
                    }
                }
            })
        });

        $(document).ready(function (){
            codeChanges();
            showButtons();
        });

        // 기본 입력을 위한 버튼들을 그리기.
        function showButtons(){
            $.each(_codes,function(i,obj){
                $("<button data-tag='" + obj.tag + "' class='btn btn-outline-primary btn-sm mr-1 mb-1 fn_code'>" + obj.title + "</button>").appendTo($("#formButtons"));
            });
        }

        // 입력 버튼을 클릭 할 때.
        $(document).on("click",".fn_code",function (){
            event.preventDefault();

            if ($("#up_sg_id").val() === ""){
                showAlert("{{ __('strings.str_select_grades') }}");
                return;
            }

            if (_nowListIndex < 0){
                showAlert("{{ __('strings.lb_select_subject') }}");
                return;
            }

            let _start = 0;
            let _end = 0;
            let _nowTag = $(this).data("tag");

            if ($("#" + _targetInput).val().length -1 !== $("#" + _targetInput).prop("selectionStart") ){
                _start = $("#" + _targetInput).val().substr(0,$("#" + _targetInput).prop("selectionStart"));
                _end = $("#" + _targetInput).val().substring($("#" + _targetInput).prop("selectionStart"));
                $("#" + _targetInput).val(_start + " " + _nowTag + " " + _end);
            }else{
                $("#" + _targetInput).val($("#" + _targetInput).val() + " " + _nowTag);
            }

            // 다 처리 후 코드를 시각화 함.
            codeChanges();
        });

        function codeChanges(){
            $.each($(".fn_item"),function (i,obj){
                let _txt = $(obj).val();
                _txt = codeChange(_txt);
                $(".fn_tmps").eq(i).html(_txt);
            });
        }

        function codeChange(txt){
            $.each(_codes, function (i,obj){
                let regEx = new RegExp(obj.tag, "gi");

                txt = txt.replace(regEx,"<span class='text-primary'>" + obj.title + "</span>");
            });
            return txt;
        }

        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
