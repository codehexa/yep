@extends('layouts.app_bms')

@section('content')
<div class="container-fluid">
    <h5><i class="fa fa-wind"></i> {{ __('strings.lb_bms_title') }} &gt; {{ __('strings.lb_bms_basic') }}</h5>

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
        <div class="bg-secondary overflow-auto p-2">
            <h6 class="text-white">{{ __('strings.lb_default_info_setting') }}</h6>
            <input type="hidden" name="saved_sheet_id" id="saved_sheet_id"/>
            <div class="bg-white rounded list-group list-group-horizontal">

                <div class="list-group-item">
                    <label>{{ __('strings.lb_academy_name') }}</label>
                    <select name="up_academy" id="up_academy" class="form-control form-control-sm">
                        <option value="">{{ __('strings.fn_select_item') }}</option>
                        @foreach($academies as $academy)
                            <option value="{{ $academy->id }}">{{ $academy->ac_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="list-group-item">
                    <label>{{ __('strings.lb_school_grade') }}</label>
                    <span id="loadGrade" class="d-none"><i class="fa fa-spinner fa-spin"></i> </span>
                    <select name="up_school_grade" id="up_school_grade" class="form-control form-control-sm">
                        <option value="">{{ __('strings.fn_select_item') }}</option>
                        @foreach($sgrades as $sgrade)
                            <option value="{{ $sgrade->id }}">{{ $sgrade->scg_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="list-group-item">
                    <div class="form-group">
                        <label>{{ __('strings.lb_hakgi') }}</label>
                        <span id="loadHakgi" class="d-none"><i class="fa fa-spinner fa-spin"></i> </span>
                        <select name="up_hakgi" id="up_hakgi" class="form-control form-control-sm">
                            <option value="">{{ __('strings.fn_select_item') }}</option>
                        </select>
                    </div>
                </div>
                <div class="list-group-item">
                    <label>{{ __('strings.lb_teacher') }}</label>
                    <span id="loadTeacher" class="d-none"><i class="fa fa-spinner fa-spin"></i> </span>
                    <select name="up_teacher" id="up_teacher" class="form-control form-control-sm">
                        <option value="">{{ __('strings.fn_select_item') }}</option>
                    </select>
                </div>
                <div class="list-group-item">
                    <div class="form-inline">
                        <label>{{ __('strings.lb_now_week') }}</label>
                        <select name="up_now_week" id="up_now_week" class="form-control form-control-sm ml-1">
                            <option value="">{{ __('strings.fn_select_item') }}</option>
                        </select>
                    </div>

                    <div class="form-inline mt-sm-1">
                        <label>{{ __('strings.lb_ex_week') }}</label>
                        <select name="up_ex_week" id="up_ex_week" class="form-control form-control-sm ml-1">
                            <option value="">{{ __('strings.fn_select_item') }}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="btn-group">
                <button id="btnLoad" class="btn btn-light btn-sm mt-1">
                    <i class="fa fa-folder-open"></i> {{ __('strings.fn_load') }}
                </button>
                <button id="btnSave" class="btn btn-primary btn-sm mt-1">
                    <i class="fa fa-save"></i> {{ __('strings.fn_save') }}
                </button>
                <button id="btnMake" class="btn btn-light btn-sm mt-1">
                    <i class="fa fa-plus"></i> {{ __('strings.fn_add') }}
                </button>
                <button id="btnDelete" class="btn btn-danger btn-sm mt-1">
                    <i class="fa fa-trash"></i> {{ __('strings.fn_delete') }}
                </button>
            </div>
            <div class="text-info d-none mt-2" id="guide_txt"><i class="fa fa-spin fa-spinner"></i> {{ __('strings.fn_processing') }}</div>
        </div>
        <div class="mt-3">
            <!-- load panel -->
            <h6>폼 리스트</h6>
            <div class="d-none" id="formLoader"><i class="fa fa-spinner fa-spin"></i> {{ __('strings.lb_loading') }}</div>
            <div id="formPanel" class="list-group"></div>
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
    <script id="bmsForm" type="text/x-jquery-tmpl">
        <div class="list-group-item fn_forms_list mb-3">
            <h6>{{ __('strings.lb_class_title') }} : <span class="text-primary">${className}</span></h6>
            <div class="row">

                <div class="col list-group">
                    <div class="list-group-item">
                        <label for="up_comment">{{ __('strings.lb_class_comment') }}</label>
                        <textarea name="up_comment" class="form-control form-control-sm fn_up_comment">${bsi_comment}</textarea>
                        <input type="hidden" name="saved_sheet_info_id[]" value="${id}" />
                    </div>
                    <div class="list-group-item">
                        <div class="form-group form-inline">
                            <label for="">{{ __('strings.lb_study') }}</label>
                            <select name="up_study" class="form-control form-control-sm ml-2 fn_up_study" >
                                <option value="">{{ __('strings.fn_select_item') }}</option>
                            @foreach($studyTypes as $studyType)
                                <option value="{{ $studyType->id }}" data-zoom="{{ $studyType->show_zoom }}" @{{if studyType == {!! $studyType->id !!}}}  selected @{{/if}}>{{ $studyType->study_title }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="form-group form-inline">
                            <label for="">{{ __('strings.lb_curriculums') }}</label>
                            <select name="up_curri" class="form-control form-control-sm ml-2 fn_up_study" >
                                <option value="">{{ __('strings.fn_select_item') }}</option>
                            @foreach($curriculums as $curriculum)
                                <option value="{{ $curriculum->id }}" @{{if curriId == {!! $curriculum->id !!}}}  selected @{{/if}}>{{ $curriculum->bcur_title }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="list-group-item">
                        <div class="form-group form-inline">
                            <label for="">{{ __('strings.lb_study_days') }}</label>
                            <select name="up_days" class="form-control form-control-sm ml-2 fn_up_study" >
                                <option value="">{{ __('strings.fn_select_item') }}</option>
                            @foreach($stdDays as $stdDay)
                                <option value="{{ $stdDay->id }}" @{{if studyDays == {!! $stdDay->id !!}}}  selected @{{/if}}>{{ $stdDay->days_title }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="list-group-item">
                        <div class="form-group form-inline">
                            <label for="">{{ __('strings.lb_study_times') }}</label>
                            <select name="up_study_times" class="form-control form-control-sm ml-2 fn_up_study" >
                                <option value="">{{ __('strings.fn_select_item') }}</option>
                            @foreach($stdTimes as $stdTime)
                                <option value="{{ $stdTime->id }}" @{{if studyTimes == {!! $stdTime->id !!}}}  selected @{{/if}}>{{ $stdTime->time_title }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col list-group">
                    <div class="list-group-item">
                        <h6>Show Panel</h6>
                        <textarea class="form-control"></textarea>
                    </div>
                </div>
            </div>
        </div>
</script>

    <script id="yoilForm" type="text/x-jquery-tmpl">
        <div class="list-group-item fn_forms_list_inner_child">
            <label >${dayName} {{ __('strings.lb_yoil_title') }}</label>
            <div class="list-group list-group-horizontal">
                <div class="form-group">
                    <label>{{ __('strings.lb_first_class') }} </label>
                    <select class="form-control form-control-sm fn_up_class_first_subject">
                        <option value="">{{ __('strings.fn_select_item') }}</option>
                    </select>
                    <select class="form-control form-control-sm fn_up_class_first_teacher">
                        <option value="">{{ __('strings.fn_select_item') }}</option>
                    </select>
                </div>

                <div class="form-group ml-2">
                    <label>{{ __('strings.lb_dt_title') }}</label>
                    <select class="form-control form-control-sm fn_up_class_dt">
                        <option value="" >{{ __('strings.fn_select_item') }}</option>
                        <option value="Y" @{{if bms_sii_dt == 'Y' }} selected @{{/if}}>{{ __('strings.fn_been') }}</option>
                        <option value="N" @{{if bms_sii_dt == 'N' }} selected @{{/if}}>{{ __('strings.fn_not_been') }}</option>
                    </select>
                </div>
            </div>
        </div>
    </script>

    <script type="text/javascript">
        let targetPanel = $("#formPanel");  // 신규 등록 이나 아이템을 불러오면 보여지는 페널.
        let targetStudyDays;    // 수업 요일 셀렉트
        let targetInnerList;    // 수업요일에 대한 결과를 프린트하는 대상 .
        let nowPanelIndex = -1; // 현재 실행하는 아이템 순번.

        let panels = [
            {"bsi_std_type":"","bsi_workbook":"","bsi_cls_id":"","bsi_days":"","bsi_std_times":"","bsi_pre_subject_1":"","bsi_pre_subject_2":"","sheetInfoItems":[]}
        ];    // 기본 그리는 패널들 정보 배열.

        let dataArray = []; // 데이터를 담는 그릇.

        // 학원 변경 시 선생님 정보 가져오기
        $(document).on("change","#up_academy",function (){
            $("#loadTeacher").removeClass("d-none");
            $.ajax({
                type:"POST",
                url:"/bms/getTeachers",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "up_ac_id":$(this).val(),
                },
                success:function(msg){
                    $("#up_teacher").empty();
                    $("<option value=''>{{ __('strings.fn_select_item') }}</option>").appendTo($("#up_teacher"));
                    $.each(msg.data,function (i,obj){
                        $("<option value='" + obj.id + "'>" + obj.name + "</option>").appendTo($("#up_teacher"));
                    });
                    $("#loadTeacher").addClass("d-none");
                },
                error:function(e1,e2,e3){
                    showAlert(e2);
                    $("#loadTeacher").addClass("d-none");
                    return;
                }
            })
        });

        // 학년 변경 시 학기 정보 가져오기
        $(document).on("change","#up_school_grade",function (){
            $("#loadHakgi").removeClass("d-none");
            $.ajax({
                type:"POST",
                url:"/bms/getHakgis",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "up_grade_id":$(this).val(),
                },
                success:function(msg){
                    $("#up_hakgi").empty();
                    $("<option value=''>{{ __('strings.fn_select_item') }}</option>").appendTo($("#up_hakgi"));
                    $.each(msg.data,function (i,obj){
                        $("<option value='" + obj.id + "' data-week='" + obj.weeks + "'>" + obj.hakgi_name + "</option>").appendTo($("#up_hakgi"));
                    });
                    $("#loadHakgi").addClass("d-none");
                },
                error:function(e1,e2,e3){
                    showAlert(e2);
                    $("#loadHakgi").addClass("d-none");
                    return;
                }
            })
        });

        // 학기를 선택할 때, 이번 주, 지난 주의 최고 값을 설정한다.
        $(document).on("change","#up_hakgi",function (){
            let _week = $(this).find("option:selected").data("week");
            $("#up_now_week, #up_ex_week").empty();
            $("<option value=''>{{ __('strings.fn_select_item') }}</option>").appendTo($("#up_now_week,#up_ex_week"));

            for (let i = 0; i < _week; i++){
                let _nowWeek = i + 1;
                $("<option value='" + _nowWeek + "'>" + _nowWeek + " Week</option>").appendTo($("#up_now_week, #up_ex_week"));
            }
        });


        $(document).on("click","#btnMake",function (){
            event.preventDefault();
            makeForm();
        });

        function makeForm(){
            $("#bmsForm").tmpl(panels).appendTo($("#formPanel"));
            LoadClassesAndAdapt();  // 학원에 배정된 반을 불러와서 셀렉트 박스에 붙임.
        }

        // editor scripts

        // 수업 요일 관리 하는 함수. 관리 버튼을 클릭하는 함수
        $(document).on("click",".fn_make",function (){
            //
            nowPanelIndex = $(".fn_make").index($(this));
            targetStudyDays = $(".fn_study_days").eq(nowPanelIndex);
            let nowDays = targetStudyDays.children("option:selected").text();
            targetInnerList = $(".fn_forms_list_inner").eq(nowPanelIndex);
            targetInnerList.empty();    // 해당 이너 폼 클리어.
            makeYoilForm(nowDays);
        });

        // string 으로 요일 만들기
        function makeYoilForm(str){
            let strLen = str.length;
            for (let i=0; i < strLen; i++){
                let nowYoil = str.substr(i,1);
                printYoil(nowYoil);
            }
        }

        // yoil 에 따라 입력 폼 그리기
        function printYoil(str){
            let newTmplData = {"dayName":str,"bms_sii_first_class":"","bms_sii_first_teacher":"","bms_sii_second_class":"","bms_sii_second_teacher":"","bms_sii_dt":""};
            $("#yoilForm").tmpl(newTmplData).appendTo(targetInnerList);
        }

        // 학원에 배정된 반을 불러와서 셀렉트 박스에 붙임.
        function LoadClassesAndAdapt(){
            if ($("#up_academy").val() === "") return;

            $.ajax({
                type:"POST",
                url:"/bms/editorGetClasses",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "up_academy":$("#up_academy").val()
                },
                success:function (msg){
                    for (let r=0; r < $("select[name='up_class_name']").length; r++){
                        if ($("select[name='up_class_name']").eq(r).val() === ""){
                            $("select[name='up_class_name']").eq(r).empty();
                            $("<option value=''>{{ __('strings.fn_select_item') }}</option>").appendTo($("select[name='up_class_name']").eq(r));
                            $.each(msg.data,function (i,obj){
                                $("<option value='" + obj.id + "'>" + obj.class_name + "</option>").appendTo($("select[name='up_class_name']").eq(r));
                            });
                        }
                    }
                },
                error:function (e1,e2,e3){
                    showAlert(e2);
                }
            });
        }

        // sheet save
        $(document).on("click","#btnSave",function (){
            event.preventDefault();

            if ($("#ed_semester").val() === "") {
                $("#ed_semester").focus();
                return;
            }

            if ($("#up_academy").val() === "") {
                $("#up_academy").focus();
                return;
            }

            if ($("#up_school_grade").val() === "") {
                $("#up_school_grade").focus();
                return;
            }

            if ($("#up_teacher").val() === "") {
                $("#up_teacher").focus();
                return;
            }

            if ($("#up_now_week").val() === "") {
                $("#up_now_week").focus();
                return;
            }

            if ($("#up_ex_week").val() === "") {
                $("#up_ex_week").focus();
                return;
            }

            $("#guide_txt").removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/bms/editorAddBasic",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "up_sheet_id":$("#saved_sheet_id").val(),
                    "up_semester":$("#up_semester").val(),
                    "up_academy":$("#up_academy").val(),
                    "up_school_grade":$("#up_school_grade").val(),
                    "up_teacher":$("#up_teacher").val(),
                    "up_now_week":$("#up_now_week").val(),
                    "up_ex_week":$("#up_ex_week").val()
                },
                success:function (msg){
                    $("#guide_txt").addClass("d-none");
                    if (msg.result === "true"){
                        $("#saved_sheet_id").val(msg.data.id);
                        if (msg.shId !== ""){
                            showAlert("{{ __('strings.str_did_update') }}");
                            return;
                        }else{
                            showAlert("{{ __('strings.fn_save_complete') }}");
                        }
                    }else{
                        showAlert("{{ __('strings.err_has_same_data') }}");
                    }
                },
                error:function (e1,e2,e3){
                    $("#guide_txt").addClass("d-none");
                    showAlert(e2);
                }
            })
        });

        // 폼 리스트 아이템을 저장하는 함수
        $(document).on("click",".fn_save",function (){
            event.preventDefault();
            nowPanelIndex = $(".fn_save").index($(this));
            if ($(".fn_forms_list_inner").eq(nowPanelIndex).find("div").length <= 0){
                showAlert("{{ __('strings.str_must_has_classes') }}");
            }

            if ($("#saved_sheet_id").val() === ""){
                showAlert("{{ __('strings.err_save_sheet_id') }}");
                return;
            }

            let innerCls = [];

            for(let innerI = 0; innerI < $(".fn_forms_list_inner").eq(nowPanelIndex).children(".fn_forms_list_inner_child").length; innerI++){
                if ($(".fn_forms_list_inner").eq(nowPanelIndex).find(".fn_up_class_first_subject").eq(innerI).val() === ""){
                    showAlert("{{ __('strings.err_first_subject') }}");
                    return;
                }
                if ($(".fn_forms_list_inner").eq(nowPanelIndex).find(".fn_up_class_first_teacher").eq(innerI).val() === ""){
                    showAlert("{{ __('strings.err_first_teacher') }}");
                    return;
                }
                if ($(".fn_forms_list_inner").eq(nowPanelIndex).find(".fn_up_class_second_subject").eq(innerI).val() === ""){
                    showAlert("{{ __('strings.err_second_subject') }}");
                    return;
                }
                if ($(".fn_forms_list_inner").eq(nowPanelIndex).find(".fn_up_class_second_teacher").eq(innerI).val() === ""){
                    showAlert("{{ __('strings.err_second_teacher') }}");
                    return;
                }
                if ($(".fn_forms_list_inner").eq(nowPanelIndex).find(".fn_up_class_dt").eq(innerI).val() === ""){
                    showAlert("{{ __('strings.err_dt') }}");
                    return;
                }

                if ($("#up_now_week").val() === ""){
                    showAlert("{{ __('strings.err_select_now_week') }}");
                    return;
                }

                innerCls.push(
                    {
                        "index":innerI,
                        "firstClass":$(".fn_forms_list_inner").eq(nowPanelIndex).find(".fn_up_class_first_subject").eq(innerI).val(),
                        "firstTeacher":$(".fn_forms_list_inner").eq(nowPanelIndex).find(".fn_up_class_first_teacher").eq(innerI).val(),
                        "secondClass":$(".fn_forms_list_inner").eq(nowPanelIndex).find(".fn_up_class_second_subject").eq(innerI).val(),
                        "secondTeacher":$(".fn_forms_list_inner").eq(nowPanelIndex).find(".fn_up_class_second_teacher").eq(innerI).val(),
                        "dt":$(".fn_forms_list_inner").eq(nowPanelIndex).find(".fn_up_class_dt").eq(innerI).val()
                    }
                );
            }

            $.ajax({
                type:"POST",
                url:"/bms/editorSheetItemSave",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "sheetId":$("#saved_sheet_id").val(),
                    "comment":$(".fn_up_comment").eq(nowPanelIndex).val(),
                    "std_type":$(".fn_up_study").eq(nowPanelIndex).val(),
                    "workbook":$(".fn_up_workbook").eq(nowPanelIndex).val(),
                    "classId":$(".fn_up_class_name").eq(nowPanelIndex).val(),
                    "days":$(".fn_study_days").eq(nowPanelIndex).val(),
                    "std_time":$(".fn_up_study_time").eq(nowPanelIndex).val(),
                    "pre_week_first":$(".fn_up_first_class").eq(nowPanelIndex).val(),
                    "pre_week_second":$(".fn_up_second_class").eq(nowPanelIndex).val(),
                    "class_array":innerCls,
                    "cur_class_index":nowPanelIndex,
                    "now_week":$("#up_now_week").val(),
                    "pre_week":$("up_ex_week").val()
                },
                success:function (msg){
                    if (msg.result === "true"){
                        showAlert("{{ __('strings.fn_save_complete') }}");
                        //$(".fn_status_now").eq(nowPanelIndex).html("{{ __("strings.fn_now_saved") }}");
                        $("input[name='saved_sheet_info_id[]']").eq(nowPanelIndex).val(msg.shi_id);
                    }else{
                        if (msg.errorcode === "NO_SHEET_ID"){
                            showAlert("{{ __('strings.err_save_sheet_id') }}");
                            return;
                        }else{
                            showAlert("{{ __('strings.err_fail_to_save') }}");
                            return;
                        }
                    }
                },
                error:function (e1,e2,e3){
                    showAlert(e2);
                    return;
                }
            });
        });

        // 불러오기
        $(document).on("click","#btnLoad",function (){
            event.preventDefault();

            nowPanelIndex = -1;

            $("#formLoader").removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/bms/basicLoadSheet",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "upHakgi":$("#up_hakgi").val(),
                    "upSchoolGrade":$("#up_school_grade").val(),
                    "upTeacher":$("#up_teacher").val(),
                },
                success:function (msg){
                    //
                    console.log(msg);
                    $.each(msg.data,function(i,obj){
                        dataArray.push([
                            {
                                "id":obj.id,
                                "classId":obj.class.id,
                                "className":obj.class.class_name,
                                "yoils":obj.bsi_days,
                                "times":obj.bsi_std_times,
                                "studyType":obj.bsi_std_type,
                                "curriId":obj.bsi_curri_id,
                                "studyDays":obj.bsi_days,
                                "studyTimes":obj.bsi_std_times,
                                "comments":obj.bsi_comment,
                                "preSubject1":obj.bsi_pre_subject_1,
                                "preSubject2":obj.bsi_pre_subject_2,
                            }
                        ]);
                        let _inItems = [];
                        $.each(obj.items, function(j,obj2){
                            _inItems.push({"id":obj2.id,"name":obj2.name})
                        });
                        dataArray[i]["items"] = _inItems;
                    });
                    drawLists();
                    $("#formLoader").addClass("d-none");
                },
                error:function (e1,e2,e3){
                    showAlert(e2);
                    $("#formLoader").addClass("d-none");
                }
            });
        });

        // 폼 리스트를 그리는 함수
        function  drawLists(){
            console.log("draw");
            $("#formPanel").empty();
            $.each(dataArray,function(i,obj){
                $("#bmsForm").tmpl(dataArray[i]).appendTo($("#formPanel"));
            })
        }

        // preview click
        $(document).on("click",".fn_preview",function (){
            event.preventDefault();
            nowPanelIndex = $(".fn_preview").index($(this));
            let savedSheetInfoId = $("input[name='saved_sheet_info_id[]'").eq(nowPanelIndex).val();

            $.ajax({
                type:"POST",
                url:"/bms/editorPreviewer",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "saved_sh_info_id":savedSheetInfoId
                },
                success:function(msg){
                    //
                    if (msg.result === "false"){
                        switch(msg.code){
                            case "FIRST_PAGE_SET":
                                showAlert("{{ __('strings.err_save_sheet_id') }}");
                                break;
                        }
                    }
                },
                error:function (e1,e2,e3){
                    showAlert(e2);
                    return;
                }
            })
        });

        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
        }

    </script>
@endsection
