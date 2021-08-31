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
                            <option value="{{ $academy->id }}" data-tel="{{ $academy->ac_tel }}">{{ $academy->ac_name }}</option>
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
<!--                <button id="btnSave" class="btn btn-primary btn-sm mt-1">
                    <i class="fa fa-save"></i> {{ __('strings.fn_save') }}
                </button>
                <button id="btnMake" class="btn btn-light btn-sm mt-1">
                    <i class="fa fa-plus"></i> {{ __('strings.fn_add') }}
                </button>
                <button id="btnDelete" class="btn btn-danger btn-sm mt-1">
                    <i class="fa fa-trash"></i> {{ __('strings.fn_delete') }}
                </button>-->
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

<div class="modal fade " id="infoModalCenter" tabindex="-1" role="dialog" aria-labelledby="infoModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLongTitle">{{ __('strings.lb_send_title') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="cmFrm" id="cmFrm" method="post" action="/bms/saveToBmsSend">
                    @csrf
                    <input type="hidden" name="up_info_title" id="up_info_title"/>
                    <div class="row">
                        <div class="col">
                            <div class="list-group">
                                <div class="list-group-item">{{ __('strings.lb_target_class_name') }} : <span class="text-primary" id="target_class_name"></span> </div>
                                <div class="list-group-item " >
                                    <div class="list-group overflow-auto" id="target_class_users" style="height: 40rem;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div id="infoText" class="h6"></div>
                            <input type="hidden" name="infoTextVal" id="infoTextVal"/>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="btn-group btn-group-sm">
                    <i class="fa fa-users"></i> <span id="lbCount" class="mr-2">0</span>
                    <button type="button" class="btn btn-outline-primary" id="chkAll"><i class="fa fa-check-square"></i> {{ __('strings.fn_all_check') }}</button>
                    <button type="button" class="btn btn-outline-secondary" id="unChkAll"><i class="fa fa-square"></i> {{ __('strings.fn_all_uncheck') }}</button>
                    <button type="button" class="btn btn-outline-info" id="chkReverse"><i class="fa fa-dot-circle"></i> {{ __('strings.fn_toggle') }}</button>
                    <i id="fn_loading" class="fa fa-spin fa-spinner mr-3 d-none"></i>
                    <button type="button" class="btn btn-primary" id="btnCmSubmit" ><i class="fa fa-save"></i> {{ __('strings.fn_okay') }}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
                </div>
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
    <script id="usForm" type="text/x-jquery-tmpl">
        <div class="list-group-item pl-1">
            <div class="form-check">
                <input type="checkbox" name="up_us_id[]" value="${parent_hp}" class="form-check-input fn_students" checked/>
                <label class="ml-2 form-check-label">${student_name} / ${parent_hp}</label>
            </div>
        </div>
    </script>

    <script id="bmsForm" type="text/x-jquery-tmpl">
        <div class="list-group-item fn_forms_list mb-3">
            <h6>{{ __('strings.lb_class_title') }} : <span class="text-primary fn_class_name">${className}</span></h6>
            <div class="row">

                <div class="col list-group">
                    <div class="list-group-item">
                        <label for="up_comment">{{ __('strings.lb_class_comment') }}</label>
                        <textarea name="up_comment" class="form-control form-control-sm fn_up_comment">${comments}</textarea>
                        <input type="hidden" name="saved_sheet_info_id[]" value="${id}" />
                        <input type="hidden" name="saved_class_id[]" value="${classId}" />
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <div class="form-group form-inline">
                                <label for="">{{ __('strings.lb_study') }}</label>
                                <select name="up_study" class="form-control form-control-sm ml-2 fn_up_study" >
                                    <option value="">{{ __('strings.fn_select_item') }}</option>
                                @foreach($studyTypes as $studyType)
                                    <option value="{{ $studyType->id }}" data-zoom="{{ $studyType->show_zoom }}" @{{if studyType == {!! $studyType->id !!}}}  selected @{{/if}}>{{ $studyType->study_title }}</option>
                                @endforeach
                                </select>
                            </div>

                            <div class="form-group form-inline">
                                <label for="">{{ __('strings.lb_curriculums') }}</label>
                                <select name="up_curri" class="form-control form-control-sm ml-2 fn_up_curri" >
                                    <option value="">{{ __('strings.fn_select_item') }}</option>
                                @foreach($curriculums as $curriculum)
                                    <option value="{{ $curriculum->id }}" @{{if curriId == {!! $curriculum->id !!}}}  selected @{{/if}}>{{ $curriculum->bcur_title }}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <div class="form-group form-inline">
                                <label for="">{{ __('strings.lb_study_days') }}</label>
                                <select name="up_days" class="form-control form-control-sm ml-2 fn_up_days" >
                                    <option value="">{{ __('strings.fn_select_item') }}</option>
                                @foreach($stdDays as $stdDay)
                                    <option value="{{ $stdDay->id }}" data-len="{{ $stdDay->days_count }}" @{{if studyDays == {!! $stdDay->id !!}}}  selected @{{/if}}>{{ $stdDay->days_title }}</option>
                                @endforeach
                                </select>
                            </div>

                            <div class="form-group form-inline">
                                <label for="">{{ __('strings.lb_study_times') }}</label>
                                <select name="up_study_times" class="form-control form-control-sm ml-2 fn_up_study_times" >
                                    <option value="">{{ __('strings.fn_select_item') }}</option>
                                    @foreach($stdTimes as $stdTime)
                                    <option value="{{ $stdTime->id }}" @{{if studyTimes == {!! $stdTime->id !!}}}  selected @{{/if}}>{{ $stdTime->time_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <div class="form-group form-inline">
                                <div class="form-check">
                                    <input type="checkbox" name="chk_sdl" class="form-check-input fn_chk_sdl" value="Y" @{{if sdl_use == 'Y' }} checked @{{/if}}/>
                                    <label for="">{{ __('strings.lb_give_sdl') }}</label>
                                </div>
                                <select name="sel_sdl" class="form-control form-control-sm ml-2 fn_sel_sdl">
                                    <option value=''>{{ __("strings.fn_select_item") }}</option>
                                    @foreach($sdls as $sdl)
                                    <option value="{{ $sdl->id }}" data-direct={{ $sdl->bs_direct }} data-code={{ $sdl->bs_code }} @{{if sdl == {!! $sdl->id !!} }} selected @{{/if}}>{{ $sdl->bs_title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group form-inline">
                                <div class="form-check">
                                    <input type="checkbox" name="chk_workbook" class="form-check-input" value="Y" @{{if workbook_use == 'Y' }}checked@{{/if}}/>
                                    <label for="">{{ __('strings.lb_workbook') }}</label>
                                </div>
                                <select name="sel_workbook" class="form-control form-control-sm ml-2 fn_sel_workbook">
                                    <option value=''>{{ __("strings.fn_select_item") }}</option>
                                    @foreach($workbooks as $workbook)
                                    <option value="{{ $workbook->id }}" @{{if workbook == {!! $workbook->id !!} }} selected @{{/if}}>{{ $workbook->bw_title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group form-inline">
                                <div class="form-check">
                                    <input type="checkbox" name="chk_dt" class="form-check-input fn_chk_dt" value="Y" @{{if dt_use == 'Y' }}checked@{{/if}}/>
                                    <label for="">{{ __('strings.lb_bms_dt') }}</label>
                                </div>
                                <select name="sel_dt" class="form-control form-control-sm ml-2 fn_sel_dt">
                                    <option value=''>{{ __("strings.fn_select_item") }}</option>
                                    @foreach($dts as $dt)
                                    <option value="{{ $dt->id }}" data-txt="{{ $dt->dt_text }}" @{{if dt == {!! $dt->id !!} }} selected @{{/if}}>{{ $dt->dt_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="list-group-item">
                        <h6>{{ __('strings.lb_set_outputs_by_day') }}</h6>
                        <div class="mt-2 fn_classes list-group"></div>
                    </div>

                    <div class="list-group-item">
                        <h6>{{ __('strings.lb_pre_week_class') }}</h6>
                        <div class="form-inline">
                            <div class="form-group">
                                <label>{{ __('strings.lb_first_class') }}</label>
                                <select name="up_pre_week_first" class="form-control form-control-sm fn_pre_week_first ml-2">
                                    <option value=''>{{ __('strings.fn_select_item') }}</option>
                                </select>
                            </div>
                            <div class="form-group ml-3">
                                <label>{{ __('strings.lb_second_class') }}</label>
                                <select name="up_pre_week_second" class="form-control form-control-sm fn_pre_week_second ml-2">
                                    <option value=''>{{ __('strings.fn_select_item') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="list-group-item">
                        <div class="mt-1 btn-group btn-group-sm">
                            <button class="btn btn-outline-primary fn_make_class"><i class="fa fa-bread-slice"></i> {{ __('strings.lb_make_class') }}</button>
                            <button class="btn btn-outline-primary fn_save"><i class="fa fa-save"></i> {{ __('strings.fn_save') }}</button>
                            <button class="btn btn-outline-primary fn_preview"><i class="fa fa-binoculars"></i> {{ __('strings.fn_preview') }}</button>
                            <i class="fa fa-spin fa-spinner d-none excute_loader"></i>
                        </div>

                    </div>

                </div>

                <div class="col list-group">
                    <div class="list-group-item">
                        <h6>Show Panel</h6>
                        <div class="mt-3 fn_draw_panel">
                            <textarea class="form-control form-control-sm fn_draw_panel_ta" style="height:40rem"></textarea>
                        </div>
                        <div class="mt-1">
                            <button class="btn btn-primary btn-sm fn_sms_send"><i class="fa fa-share"></i> {{ __('strings.lb_sending') }}</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
</script>

    <script id="yoilForm" type="text/x-jquery-tmpl">
        <div class="list-group-item fn_forms_list_inner_child">
            <div class="d-flex justify-content-between">
                <label ><span class="fn_yoil_name">${dayName}</span> {{ __('strings.lb_yoil_title') }}</label>
                <div class="form-group form-inline">
                    <div class="form-check mr-2">
                        <input type="checkbox" class="form-check-input fn_zoom_check"/>
                        <label for="">{{ __('strings.lb_zoom_check') }}</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input fn_yoil_check "/>
                        <label for="">{{ __('strings.lb_closed_day') }}</label>
                    </div>
                </div>
            </div>

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

                <div class="form-group">
                    <label>{{ __('strings.lb_second_class') }} </label>
                    <select class="form-control form-control-sm fn_up_class_second_subject">
                        <option value="">{{ __('strings.fn_select_item') }}</option>
                    </select>
                    <select class="form-control form-control-sm fn_up_class_second_teacher">
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

                <div class="form-group ml-2">
                    <label>{{ __('strings.fn_direct_input') }}</label>
                    <textarea name="output_text" class="form-control fn_dt_direct_text">${bms_sii_dt_direct}</textarea>
                </div>
            </div>
        </div>
    </script>

    <script type="text/javascript">
        let targetPanel = $("#formPanel");  // 신규 등록 이나 아이템을 불러오면 보여지는 페널.
        let targetStudyDays;    // 수업 요일 셀렉트
        let targetInnerList;    // 수업요일에 대한 결과를 프린트하는 대상 .
        let nowPanelIndex = -1; // 현재 실행하는 아이템 순번.
        let targetPreview;  // Preview 가 보여지는 대상

        let panels = [
            {"bsi_std_type":"","bsi_workbook":"","bsi_cls_id":"","bsi_days":"","bsi_std_times":"","bsi_pre_subject_1":"","bsi_pre_subject_2":"","sheetInfoItems":[]}
        ];    // 기본 그리는 패널들 정보 배열.

        let dataArray = []; // 데이터를 담는 그릇.
        let itemsArray = [];    // 수업 정보를 담는 그릇.
        let subjects = [];  // 수업 리스트
        let teachers = [];  // 동일한 학원에 속한 선생님 리스트
        let basicInfos = [];  // 기초 데이터 폼
        let subject_function;  // 교재 과제 나 DT 범위 등등 함수를 가져오는 정의.

        let _nowWeekTitle, _preWeekTitle;

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
                $("<option value='" + _nowWeek + "'>{{ __('strings.lb_week_name') }} " + _nowWeek + "</option>").appendTo($("#up_now_week, #up_ex_week"));
            }
        });

        // 이번 주를 선택하면 지난 주를 자동 선택하기.
        $(document).on("change","#up_now_week",function (){
            if ($(this).val() !== "" && $("#up_now_week option").index($("#up_now_week option:selected")) > 1){
                $("#up_ex_week option").eq($(this).find("option").index($("#up_now_week option:selected")) -1).attr("selected","selected");
            }
        });

        // 사용 안함.
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
            if ($(".fn_classes").eq(nowPanelIndex).find("div").length <= 0){
                showAlert("{{ __('strings.str_must_has_classes') }}");
            }

            let innerCls = [];

            for(let innerI = 0; innerI < $(".fn_classes").eq(nowPanelIndex).children(".fn_forms_list_inner_child").length; innerI++){
                if ($(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_first_subject").eq(innerI).val() === ""){
                    showAlert("{{ __('strings.err_first_subject') }}");
                    return;
                }
                if ($(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_first_teacher").eq(innerI).val() === ""){
                    showAlert("{{ __('strings.err_first_teacher') }}");
                    return;
                }
                if ($(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_second_subject").eq(innerI).val() === ""){
                    showAlert("{{ __('strings.err_second_subject') }}");
                    return;
                }
                if ($(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_second_teacher").eq(innerI).val() === ""){
                    showAlert("{{ __('strings.err_second_teacher') }}");
                    return;
                }
                if ($(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_dt").eq(innerI).val() === ""){
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
                        "firstClass":$(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_first_subject").eq(innerI).val(),
                        "firstTeacher":$(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_first_teacher").eq(innerI).val(),
                        "secondClass":$(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_second_subject").eq(innerI).val(),
                        "secondTeacher":$(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_second_teacher").eq(innerI).val(),
                        "dt":$(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_dt").eq(innerI).val(),
                        "dtDirect":$(".fn_classes").eq(nowPanelIndex).find(".fn_dt_direct_text").eq(innerI).val(),
                    }
                );
            }

            $(".excute_loader").eq(nowPanelIndex).removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/bms/basicInfoSave",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "comment":$(".fn_up_comment").eq(nowPanelIndex).val(),
                    "std_type":$(".fn_up_study").eq(nowPanelIndex).val(),
                    "curriculum":$(".fn_up_curri").eq(nowPanelIndex).val(),
                    "days":$(".fn_up_days").eq(nowPanelIndex).val(),
                    "std_time":$(".fn_up_study_times").eq(nowPanelIndex).val(),
                    "sdl_use":$("input[name='chk_sdl']").eq(nowPanelIndex).val(),
                    "sdl":$(".fn_sel_sdl").eq(nowPanelIndex).val(),
                    "workbook_use":$("input[name='chk_workbook']").eq(nowPanelIndex).val(),
                    "workbook":$(".fn_sel_workbook").eq(nowPanelIndex).val(),
                    "studybook_use":$("input[name='chk_studybook']").eq(nowPanelIndex).val(),
                    "studybook":$(".fn_sel_studybook").eq(nowPanelIndex).val(),
                    "dt_use":$("input[name='chk_dt']").eq(nowPanelIndex).val(),
                    "dt":$(".fn_sel_dt").eq(nowPanelIndex).val(),

                    "classId":$("input[name='saved_class_id[]']").eq(nowPanelIndex).val(),
                    "sheetInfoId":$("input[name='saved_sheet_info_id[]']").eq(nowPanelIndex).val(),

                    "pre_week_first":$(".fn_pre_week_first").eq(nowPanelIndex).val(),
                    "pre_week_second":$(".fn_pre_week_second").eq(nowPanelIndex).val(),
                    "class_array":innerCls,
                    "cur_class_index":nowPanelIndex,
                    "now_week":$("#up_now_week").val(),
                    "pre_week":$("#up_ex_week").val()
                },
                success:function (msg){
                    if (msg.result === "true"){
                        showAlert("{{ __('strings.fn_save_complete') }}");
                        //$(".fn_status_now").eq(nowPanelIndex).html("{{ __("strings.fn_now_saved") }}");
                        $(".excute_loader").eq(nowPanelIndex).addClass("d-none");
                    }else{
                        showAlert("{{ __('strings.err_fail_to_save') }}");
                        $(".excute_loader").eq(nowPanelIndex).addClass("d-none");
                        return;
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
                    "upAcId":$("#up_academy").val()
                },
                success:function (msg){
                    //
                    console.log(msg);
                    $.each(msg.data,function(i,obj){
                        dataArray.push(
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
                                "sdl":obj.bsi_sdl,
                                "sdl_use":obj.bsi_sdl_use,
                                "workbook":obj.bsi_workbook,
                                "workbook_use":obj.bsi_workbook_use,
                                "studybook":obj.bsi_studybook,
                                "studybook_use":obj.bsi_studybook_use,
                                "dt":obj.bsi_dt,
                                "dt_use":obj.bsi_dt_use,
                                "comments":obj.bsi_comment,
                                "preSubject1":obj.bsi_pre_subject_1,
                                "preSubject2":obj.bsi_pre_subject_2,
                                "preWeek":obj.bsi_pre_week,
                                "nowWeek":obj.bsi_now_week,
                                "subItems":obj.ShItems
                            }
                        );
                    });

                    subjects = msg.subjects;
                    teachers = msg.teachers;
                    subject_function = msg.functions;
                    drawLists();
                    getSmsBasic();  // 기초 정보를 가져오는 함수
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
            $("#formPanel").empty();

            for (let i=0; i < dataArray.length; i++){
                $("#bmsForm").tmpl(dataArray[i]).appendTo($("#formPanel"));
                let nowObj = dataArray[i];
                if (nowObj.subItems !== undefined){
                    $("#yoilForm").tmpl(nowObj.subItems).appendTo($(".fn_classes").eq(i));
                }
            }

            $(".fn_pre_week_first, .fn_pre_week_second").empty();
            $("<option value=''>{{ __('strings.fn_select_item') }}</option>").appendTo($(".fn_pre_week_first"));
            $("<option value=''>{{ __('strings.fn_select_item') }}</option>").appendTo($(".fn_pre_week_second"));

            $.each(subjects, function (i,obj){
                $("<option value='" + obj.id + "' data-code='" + obj.subject_function + "'>" + obj.subject_title + "</option>").appendTo($(".fn_pre_week_first"));
                $("<option value='" + obj.id + "' data-code='" + obj.subject_function + "'>" + obj.subject_title + "</option>").appendTo($(".fn_pre_week_second"));
            });


            $(".fn_up_class_first_subject, .fn_up_class_second_subject").empty();
            $("<option value=''>{{ __('strings.fn_select_item') }}</option>").appendTo($(".fn_up_class_first_subject"));
            $("<option value=''>{{ __('strings.fn_select_item') }}</option>").appendTo($(".fn_up_class_second_subject"));

            $.each(subjects,function (i,obj){
                $("<option value='" + obj.id + "' data-code='" + obj.subject_function + "'>" + obj.subject_title + "</option>").appendTo($(".fn_up_class_first_subject"));
                $("<option value='" + obj.id + "' data-code='" + obj.subject_function + "'>" + obj.subject_title + "</option>").appendTo($(".fn_up_class_second_subject"));
            });

            $(".fn_up_class_first_teacher, .fn_up_class_second_teacher").empty();
            $("<option value=''>{{ __('strings.fn_select_item') }}</option>").appendTo($(".fn_up_class_first_teacher"));
            $("<option value=''>{{ __('strings.fn_select_item') }}</option>").appendTo($(".fn_up_class_second_teacher"));

            $.each(teachers,function (i,obj){
                $("<option value='" + obj.id + "' data-tel='" + obj.zoom_id + "'>" + obj.name + "</option>").appendTo($(".fn_up_class_first_teacher"));
                $("<option value='" + obj.id + "' data-tel='" + obj.zoom_id + "'>" + obj.name + "</option>").appendTo($(".fn_up_class_second_teacher"));
            });

            // 최종적으로 UI 를 그린 후 데이터를 지정한다.
            for (let i=0; i < dataArray.length; i++){
                let yoilNameText = $(".fn_up_days").eq(i).find("option:selected").text();
                for (let s=0; s < yoilNameText.length; s++){
                    $(".fn_forms_list").eq(i).find(".fn_yoil_name").eq(s).text(yoilNameText.substr(s,1));
                }   // 요일 프린트
                if (dataArray[i]["subItems"] !== undefined){
                    for (let j=0; j < dataArray[i]["subItems"].length; j++){
                        $(".fn_forms_list").eq(i).find(".fn_forms_list_inner_child").find(".fn_up_class_first_subject").eq(j).val(dataArray[i]["subItems"][j].bms_sii_first_class);
                        $(".fn_forms_list").eq(i).find(".fn_forms_list_inner_child").find(".fn_up_class_first_teacher").eq(j).val(dataArray[i]["subItems"][j].bms_sii_first_teacher);
                        $(".fn_forms_list").eq(i).find(".fn_forms_list_inner_child").find(".fn_up_class_second_subject").eq(j).val(dataArray[i]["subItems"][j].bms_sii_second_class);
                        $(".fn_forms_list").eq(i).find(".fn_forms_list_inner_child").find(".fn_up_class_second_teacher").eq(j).val(dataArray[i]["subItems"][j].bms_sii_second_teacher);
                    }
                }   // 각 요일에 대한 값들 지정.
                $(".fn_pre_week_first").eq(i).val(dataArray[i].preSubject1);
                $(".fn_pre_week_second").eq(i).val(dataArray[i].preSubject2);
                /*console.log("now week : " + dataArray[i].nowWeek + " / pre week : " + dataArray[i].preWeek);
                $("#up_now_week").val(dataArray[i].nowWeek);
                $("#up_ex_week").val(dataArray[i].preWeek);*/
            }
        }

        // 수업 변경 시 .. 특히 ZOOM 처리.
        $(document).on("change",".fn_up_study",function (){
            nowPanelIndex = $(".fn_up_study").index($(this));
            let nowZoomVal = $(".fn_up_study").eq(nowPanelIndex).children("option:selected").data("zoom");
            if (nowZoomVal === "Y"){
                $(".fn_classes").eq(nowPanelIndex).find(".fn_zoom_check").attr("checked",true);
            }else{
                $(".fn_classes").eq(nowPanelIndex).find(".fn_zoom_check").attr("checked",false);
            }
        });

        // 수업 만들기 클릭
        $(document).on("click",".fn_make_class",function (){
            event.preventDefault();
            nowPanelIndex = $(".fn_make_class").index($(this));

            if ($(".fn_up_days").eq(nowPanelIndex).val() === ""){
                showAlert("{{ __('strings.lb_select_study_days') }}");
                return;
            }

            targetInnerList = $(".fn_classes").eq(nowPanelIndex);

            let daysLen = $(".fn_up_days").eq(nowPanelIndex).find("option:selected").data("len");
            let dayStrs = $(".fn_up_days").eq(nowPanelIndex).find("option:selected").text();

            targetInnerList.empty();

            for (let i=0; i < daysLen; i++){
                let nTmplData = {
                    "dayName":dayStrs.substr(i,1),
                    "bms_sii_first_class":"",
                    "bms_sii_first_teacher":"",
                    "bms_sii_second_class":"",
                    "bms_sii_second_teacher":"",
                    "bms_sii_dt":""
                };
                $("#yoilForm").tmpl(nTmplData).appendTo(targetInnerList);
            }

            classNteacherSet();
        });

        function classNteacherSet(){
            let innerPanel = $(".fn_classes").eq(nowPanelIndex);
            innerPanel.find(".fn_up_class_first_subject,.fn_up_class_second_subject,.fn_up_class_first_teacher,.fn_up_class_second_teacher, .fn_pre_week_first, .fn_pre_week_second").empty();
            $("<option value=''>{{ __('strings.fn_select_item') }}</option>").appendTo(innerPanel.find(".fn_up_class_first_subject,.fn_up_class_second_subject,.fn_up_class_first_teacher,.fn_up_class_second_teacher, .fn_pre_week_first, .fn_pre_week_second"));

            $.each(subjects,function(i,obj){
                $("<option value='" + obj.id + "'>" + obj.subject_title + "</option>").appendTo(innerPanel.find(".fn_up_class_first_subject,.fn_up_class_second_subject"));
            });

            $.each(subjects,function(i,obj){
                $("<option value='" + obj.id + "'>" + obj.subject_title + "</option>").appendTo($(".fn_pre_week_first").eq(nowPanelIndex));
            });

            $.each(subjects,function(i,obj){
                $("<option value='" + obj.id + "'>" + obj.subject_title + "</option>").appendTo($(".fn_pre_week_second").eq(nowPanelIndex));
            });

            $.each(teachers,function(i,obj){
                $("<option value='" + obj.id + "' data-zoom='" + obj.zoom_id + "'>" + obj.name + "</option>").appendTo(innerPanel.find(".fn_up_class_first_teacher,.fn_up_class_second_teacher"));
            });
        }

        // preview click
        $(document).on("click",".fn_preview",function (){
            event.preventDefault();
            nowPanelIndex = $(".fn_preview").index($(this));
            console.log('now panel index');
            targetPreview = $(".fn_draw_panel").eq(nowPanelIndex);
            console.log('target preview');

            // 지금 주, 이전 주
            _nowWeekTitle = $("#up_now_week option:selected").text();
            _preWeekTitle = $("#up_ex_week option:selected").text();

            // zoom 관련 박스 점검.
            if ($(".fn_up_study").eq(nowPanelIndex).find("option:selected").data("zoom") === "Y" && $(".fn_classes").eq(nowPanelIndex).find(".fn_zoom_check:checked").length <= 0){
                showAlert("{{ __('strings.err_zoom_check_again') }}");
                return;
            }

            // DT 범위 관련
            if ($(".fn_chk_dt").eq(nowPanelIndex).is(":checked") && $(".fn_sel_dt").eq(nowPanelIndex).val() === ""){
                showAlert("{{ __('strings.err_dt_area_check') }}");
                return;
            }

            printPage();
        });

        // print page logic
        function printPage(){
            // first ment
            let _greeting = replaceContext("greeting",basicInfos.find(element => element.tagItem === '{{ \App\Models\Configurations::$BMS_SMS_TAG_GREETING }}').tagText);
            let _notice = basicInfos.find(element => element.tagItem === '{{ \App\Models\Configurations::$BMS_SMS_TAG_NOTICE }}').tagText;
            let _academyTel = replaceContext("academyTel",basicInfos.find(element => element.tagItem === '{{ \App\Models\Configurations::$BMS_SMS_TAG_ACADEMY_INFO }}').tagText);
            let _bookWork = basicInfos.find(element => element.tagItem === '{{ \App\Models\Configurations::$BMS_SMS_TAG_BOOK_WORK }}').tagText;
            let _outputWork = basicInfos.find(element => element.tagItem === '{{ \App\Models\Configurations::$BMS_SMS_TAG_OUTPUT_WORK }}').tagText;

            let _drawingText = "";

            // 1st, 인사말 작성.
            _drawingText += _greeting + "\r\n\r\n";

            // 2nd. 선생님 말씀 붙이기.
            _drawingText += $(".fn_up_comment").eq(nowPanelIndex).val() + "\r\n\r\n";

            // 3rd. 학원 요일 시간
            _drawingText += "[" + $("#up_academy option:selected").text() + " " +
                $(".fn_up_days option:selected").eq(nowPanelIndex).text() + " " +
                $(".fn_up_study_times option:selected").eq(nowPanelIndex).text() +
                "]\r\n\r\n";

            // 4th. 요일, 수업내용, DT범위, 과제 (교재과제, 제출과제) 배열 처리
            let subItems = dataArray[nowPanelIndex].subItems;

            $.each(subItems,function (i,obj){
                // 내부 폼 작성
                // 요일
                _drawingText += "[" + $(".fn_up_days").eq(nowPanelIndex).find("option:selected").text().substr(i,1) + "{{ __('strings.lb_yoil_title') }}" + "]\r\n";
                _drawingText += "1. {{ __('strings.lb_bms_class') }}: ";
                // 휴원 처리
                if ($(".fn_classes").eq(nowPanelIndex).find(".fn_yoil_check").eq(i).is(":checked")){
                    _drawingText += "{{ __('strings.lb_nothing') }}\r\n";
                }else{
                    _drawingText += getClassContext($(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_first_subject").eq(i).find("option:selected").text()) + "_" + _nowWeekTitle;  // 1 교시
                    // zoom 수업 여부
                    if ($(".fn_up_study").eq(nowPanelIndex).find("option:selected").data("zoom") === "Y" && $(".fn_classes").eq(nowPanelIndex).find(".fn_zoom_check").eq(i).is(":checked") === true){
                        _drawingText += "(" + $(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_first_teacher").eq(i).find("option:selected").text(); // 선생님 이름
                        _drawingText += $(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_first_teacher").eq(i).find("option:selected").data("tel"); // 선생님 zoom id
                        _drawingText += ")" ;   // 1교시 줌 내용.
                    }

                    // 2교시
                    _drawingText += " / ";

                    _drawingText += getClassContext($(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_second_subject").eq(i).find("option:selected").text()) + "_" + _nowWeekTitle;  // 2 교시
                    if ($(".fn_up_study").eq(nowPanelIndex).find("option:selected").data("zoom") === "Y" && $(".fn_classes").eq(nowPanelIndex).find(".fn_zoom_check").eq(i).is(":checked") === true){
                        _drawingText += "(" + $(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_second_teacher").eq(i).find("option:selected").text(); // 선생님 이름
                        _drawingText += $(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_second_teacher").eq(i).find("option:selected").data("tel"); // 선생님 zoom id
                        _drawingText += ")" ;   // 2교시 줌 내용.
                    }
                }

                _drawingText += "\r\n";

                // DT 범위
                _drawingText += "2. {{ __('strings.lb_bms_dt') }} : ";
                if ($(".fn_classes").eq(nowPanelIndex).find(".fn_yoil_check").eq(i).is(":checked")){
                    // 1교시 영역
                    _drawingText += "{{ __('strings.lb_nothing') }}\r\n";
                }else{
                    _drawingText += getDtArea(i,$(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_first_subject").eq(i).val(),$(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_first_subject").eq(i).find("option:selected").text());
                    // 2교시 영역
                    _drawingText += " / ";
                    _drawingText += getDtArea(i,$(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_second_subject").eq(i).val(),$(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_second_subject").eq(i).find("option:selected").text());
                    _drawingText += "\r\n";
                }



                // 과제 : 교재과제
                _drawingText += "3. {{ __('strings.lb_bms_hworks') }}\r\n{{ __('strings.lb_bms_books_print') }}";
                if ($(".fn_classes").eq(nowPanelIndex).find(".fn_yoil_check").eq(i).is(":checked")){
                    _drawingText += "{{ __('strings.lb_nothing') }}\r\n";
                }else{
                    // 1교시 영역
                    _drawingText += getHwork($(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_first_subject").eq(i).val(),$(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_first_subject").eq(i).find("option:selected").text());
                    // 2교시 영역
                    _drawingText += " / ";
                    _drawingText += getHwork($(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_second_subject").eq(i).val(),$(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_second_subject").eq(i).find("option:selected").text());
                    _drawingText += "\r\n";
                }


                // 과제 : 제출과제
                _drawingText += "{{ __('strings.lb_bms_output_work_print') }}";
                if ($(".fn_classes").eq(nowPanelIndex).find(".fn_yoil_check").eq(i).is(":checked") ||
                    (checkLastWeek() && $(".fn_chk_sdl").eq(nowPanelIndex).is(":checked") && $(".fn_sel_sdl").eq(nowPanelIndex).find("option:selected").data("code") === "{{ \App\Models\Configurations::$BMS_BS_CODE_NEXT }}")
                ){  // 휴원이거나 마지막 주 일 경우.
                    _drawingText += "{{ __('strings.lb_nothing') }}\r\n";
                }else {
                    // 1교시 영역
                    if ($(".fn_chk_sdl").eq(nowPanelIndex).is(":checked") && $(".fn_sel_sdl").eq(nowPanelIndex).find("option:selected").data("code") === "{{ \App\Models\Configurations::$BMS_BS_CODE_DIRECT }}"){
                        _drawingText += outputWork(i,$(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_first_subject").eq(i).val(), $(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_first_subject").eq(i).find("option:selected").text());
                    }else{
                        _drawingText += outputWork(i,$(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_first_subject").eq(i).val(), $(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_first_subject").eq(i).find("option:selected").text());
                        _drawingText += " / ";
                        _drawingText += outputWork(i,$(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_second_subject").eq(i).val(), $(".fn_classes").eq(nowPanelIndex).find(".fn_up_class_second_subject").eq(i).find("option:selected").text());
                    }
                }
                _drawingText += "\r\n\r\n";
            });
            _drawingText += "\r\n";

            // 5th. 공지
            _drawingText += _notice + "\r\n\r\n";

            // 6th. 전화번호
            _drawingText += _academyTel;

            $(".fn_draw_panel_ta").eq(nowPanelIndex).val(_drawingText);

        }   // 여기까지 내부 영역 텍스트 그리기

        // 마지막 주 점검.
        function checkLastWeek(){
            let _maxWeek = $("#up_now_week option:last").val();
            if ($("#up_now_week").val() === _maxWeek){
                return true;
            }
            return false;
        }

        // 수업 내용 가져오기
        function getClassContext(txt){
            let _chIndex = -1;
            _chIndex = txt.indexOf("(");
            if (_chIndex > -1){
                return txt.substr(txt,_chIndex);
            }
            return txt;
        }

        // DT 정보 가져오기. subject_function 에 의거...
        function getDtArea(sibling,subjectId,subjectName){    //
            let nowFunction;
            $.each(subject_function,function (i,obj){
                if (String.valueOf(obj.id) === String.valueOf(subjectId)){
                    nowFunction = obj;
                    return false;
                }
            });

            let toChangeText = $(".fn_sel_dt").eq(nowPanelIndex).find("option:selected").data("txt");
            let resultText = nowFunction.hwork_dt;

            if (resultText === undefined) return;

            let subjectReg = new RegExp("{{ \App\Models\Configurations::$BMS_PAGE_FUNCTION_KEYS[3]["tag"] }}"); // SubjectName
            let nowWeekReg = new RegExp("{{ \App\Models\Configurations::$BMS_PAGE_FUNCTION_KEYS[9]["tag"] }}");    // now Week
            let preWeekReg = new RegExp("{{ \App\Models\Configurations::$BMS_PAGE_FUNCTION_KEYS[10]["tag"] }}");    // pre Week
            let dtReg = new RegExp("{{ \App\Models\Configurations::$BMS_PAGE_FUNCTION_KEYS[24]["tag"] }}");    // DT Area Week


            subjectName = getClassContext(subjectName);
            resultText = resultText.replace(subjectReg,subjectName);
            resultText = resultText.replace(nowWeekReg,_nowWeekTitle);
            resultText = resultText.replace(preWeekReg,_preWeekTitle);
            resultText = resultText.replace(dtReg,toChangeText);

            return resultText;
        }

        // 교재 과제 가져오기
        function getHwork(subjectId,subjectName){
            let nowFunction;
            $.each(subject_function,function (i,obj){
                if (String.valueOf(obj.id) === String.valueOf(subjectId)){
                    nowFunction = obj;
                    return false;
                }
            });
            let resultText = nowFunction.hwork_dt;

            if (resultText === undefined) return;

            let subjectReg = new RegExp("{{ \App\Models\Configurations::$BMS_PAGE_FUNCTION_KEYS[3]["tag"] }}"); // SubjectName
            let nowWeekReg = new RegExp("{{ \App\Models\Configurations::$BMS_PAGE_FUNCTION_KEYS[9]["tag"] }}");    // now Week
            let preWeekReg = new RegExp("{{ \App\Models\Configurations::$BMS_PAGE_FUNCTION_KEYS[10]["tag"] }}");    // pre Week

            subjectName = getClassContext(subjectName);

            resultText = resultText.replace(subjectReg,subjectName);
            resultText = resultText.replace(nowWeekReg,_nowWeekTitle);
            resultText = resultText.replace(preWeekReg,_preWeekTitle);

            return resultText;
        }

        // 제출과제
        function outputWork(sibling,subjectId,subjectName){
            let nowFunction;
            $.each(subject_function,function (i,obj){
                if (String.valueOf(obj.id) === String.valueOf(subjectId)){
                    nowFunction = obj;
                    return false;
                }
            });
            let resultText = nowFunction.hwork_dt;

            if (resultText === undefined) return;

            let subjectReg = new RegExp("{{ \App\Models\Configurations::$BMS_PAGE_FUNCTION_KEYS[3]["tag"] }}"); // SubjectName
            let nowWeekReg = new RegExp("{{ \App\Models\Configurations::$BMS_PAGE_FUNCTION_KEYS[9]["tag"] }}");    // now Week
            let preWeekReg = new RegExp("{{ \App\Models\Configurations::$BMS_PAGE_FUNCTION_KEYS[10]["tag"] }}");    // pre Week

            subjectName = getClassContext(subjectName);

            if ($(".fn_chk_sdl").eq(nowPanelIndex).is(":checked")){
                if ($(".fn_sel_sdl").eq(nowPanelIndex).val() === ""){
                    showAlert("{{ __('strings.err_select_sdl_code') }}");
                    return;
                }
                let _sdlCode = $(".fn_sel_sdl").eq(nowPanelIndex).find("option:selected").data("code");

                switch(_sdlCode){
                    case "{{ \App\Models\Configurations::$BMS_BS_CODE_DIRECT }}":
                        return resultText = $(".fn_classes").eq(nowPanelIndex).find(".fn_dt_direct_text").eq(sibling).val();
                        break;
                    case "{{ \App\Models\Configurations::$BMS_BS_CODE_BOOK }}":
                        break;
                    default:
                        resultText = resultText.replace(subjectReg,subjectName);
                        resultText = resultText.replace(nowWeekReg,_nowWeekTitle);
                        resultText = resultText.replace(preWeekReg,_preWeekTitle);
                        return resultText;
                        break;
                }
            }
        }

        // 전송하기 버튼 클릭 시
        $(document).on("click",".fn_sms_send",function (){
            event.preventDefault();
            nowPanelIndex = $(".fn_sms_send").index($(this));
            let nowText = $(".fn_draw_panel_ta").eq(nowPanelIndex).val();

            $("#infoModalCenter").modal("show");

            $("#infoText").html(nowText.replace(/(?:\r\n|\r|\n)/g,'<br/>'));
            $("#infoTextVal").val(nowText);

            $("#target_class_name").html(dataArray[nowPanelIndex].className);

            $.ajax({
                url:"/bms/getStudentsJson",
                type:"POST",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "clId":dataArray[nowPanelIndex].classId
                },
                success:function(msg){
                    //target_class_users
                    $("#target_class_users").empty();
                    $("#usForm").tmpl(msg.data).appendTo($("#target_class_users"));
                    printCount();
                },
                error:function (e1,e2,e3){
                    showAlert(e2);
                }
            });
        });


        // replace context
        function replaceContext(flag,srcText){
            let academyNameReg = new RegExp("{{ \App\Models\Configurations::$BMS_PAGE_FUNCTION_KEYS[0]["tag"]}}"); // AcademyName
            let classNameReg = new RegExp("{{ \App\Models\Configurations::$BMS_PAGE_FUNCTION_KEYS[1]["tag"]}}"); // ClassName
            let teacherNameReg = new RegExp("{{ \App\Models\Configurations::$BMS_PAGE_FUNCTION_KEYS[2]["tag"]}}"); // TeacherName
            let semesterReg = new RegExp("{{ \App\Models\Configurations::$BMS_PAGE_FUNCTION_KEYS[4]["tag"]}}"); // Semester.학기
            let curriReg = new RegExp("{{ \App\Models\Configurations::$BMS_PAGE_FUNCTION_KEYS[5]["tag"]}}"); // Curriculum
            let nowWeekReg = new RegExp("{{ \App\Models\Configurations::$BMS_PAGE_FUNCTION_KEYS[9]["tag"]}}"); // Now Week
            let studyTypeReg = new RegExp("{{ \App\Models\Configurations::$BMS_PAGE_FUNCTION_KEYS[11]["tag"]}}"); // Now Week
            let academyTelReg = new RegExp("{{ \App\Models\Configurations::$BMS_PAGE_FUNCTION_KEYS[15]["tag"] }}"); // Academy Tel
            let academyPresidentTelReg = new RegExp("{{ \App\Models\Configurations::$BMS_PAGE_FUNCTION_KEYS[16]["tag"] }}"); // Academy President Tel

            switch (flag){
                case "greeting":
                    srcText = srcText.replace(classNameReg,$(".fn_class_name").eq(nowPanelIndex).text());
                    srcText = srcText.replace(teacherNameReg,$("#up_teacher").find("option:selected").text());
                    srcText = srcText.replace(semesterReg,$("#up_hakgi").find("option:selected").text());
                    srcText = srcText.replace(curriReg,$(".fn_up_curri").eq(nowPanelIndex).find("option:selected").text());
                    srcText = srcText.replace(nowWeekReg,$("#up_now_week").find("option:selected").text());
                    srcText = srcText.replace(studyTypeReg,$(".fn_up_study").eq(nowPanelIndex).find("option:selected").text());
                    return srcText;
                    break;

                case "notice":
                    break;

                case "academyTel":
                    srcText = srcText.replace(academyNameReg,$("#up_academy option:selected").text());
                    srcText = srcText.replace(academyTelReg,$("#up_academy option:selected").data("tel"));
                    srcText = srcText.replace(academyPresidentTelReg,"{{ $presidentCall }}");
                    return srcText;
                    break;
            }
        }

        // basic information call
        function getSmsBasic(){
            basicInfos = [];
            $.ajax({
                type:"POST",
                url:"/bms/getBasicPageJson",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val()
                },
                success:function(msg){
                    $.each(msg.data,function(i,obj){
                        basicInfos.push({tagText:obj.field_function,tagItem:obj.field_tag});
                    });
                },
                error:function (e1,e2,e3){
                    showAlert(e2);
                }
            });
        }

        // info modal check
        $(document).on("click","#chkAll",function (){
            event.preventDefault();

            $(".fn_students").each(function(i,obj){
                $(obj).prop("checked",true);
            });
            printCount();
        });

        $(document).on("click","#unChkAll",function (){
            event.preventDefault();
            $(".fn_students").each(function(i,obj){
                $(obj).prop("checked",false);
            });
            printCount();
        });

        $(document).on("click","#chkReverse",function (){
            event.preventDefault();
            $(".fn_students").each(function(i,obj){
                $(obj).prop("checked",!$(obj).prop("checked"));
            });
            printCount();
        });

        function printCount(){
            let _n = 0;
            $(".fn_students").each(function(i,obj){
                if ($(obj).is(":checked")){
                    _n++;
                }
            })
            $("#lbCount").html(_n);
        }

        // 최종 보내기 클릭 시
        $(document).on("click","#btnCmSubmit",function (){
            event.preventDefault();
            if ($("#lbCount").text() === "0"){
                showAlert("{{ __('strings.err_no_date_to_send') }}");
                return;
            }

            let _title = $(".fn_class_name").eq(nowPanelIndex).text();

            $("#up_info_title").val(_title);

            $("#fn_loading").removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/bms/saveToBmsSend",
                dataType:"json",
                data:$("#cmFrm").serialize(),
                success:function (msg){
                    if (msg.result === "true"){
                        showAlert("{{ __('strings.fn_save_complete') }}");
                        $("#fn_loading").addClass("d-none");
                        return;
                    }else{
                        showAlert("{{ __('strings.fn_save_false') }}");
                        $("#fn_loading").addClass("d-none");
                        return;
                    }
                }
            })
        })

        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
        }

    </script>
@endsection
