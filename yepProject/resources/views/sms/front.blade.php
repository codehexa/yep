@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h5>{{ __('strings.lb_sms_work_manage') }} </h5>
    <div class="mt-3 btn-group">
        <a href="/home" class="btn btn-outline-secondary btn-sm"><i class="fa fa-home"></i> {{ __("strings.fn_home") }}</a>
<!--        <button id="btn_add" name="btn_add" class="btn btn-sm btn-primary"><i class="fa fa-file-excel"></i> {{ __('strings.fn_add') }}</button>-->
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
                @case ("FAIL_CAUSE_SAVING")
                <h4 class="text-center text-danger"> {{ __('strings.err_cannot_saving') }}</h4>
                @break
                @case ("FAIL_CAUSE_SENT")
                <h4 class="text-center text-danger"> {{ __('strings.err_cannot_sent') }}</h4>
                @break
                @case ("FAIL_TO_ADD")
                <h4 class="text-center text-danger"> {{ __('strings.err_add_test_in_sms_paper') }}</h4>
                @break
                @case ("ONLY_MY_CLASS")
                <h4 class="text-center text-danger"> {{ __('strings.err_show_only_my_class') }}</h4>
                @break
            @endswitch
        @endforeach
    @endif

    <div class="mt-3">
        <div class="form-inline">
            <div class="form-group">
                <label for="section_academy" class="form-label">{{ __('strings.lb_academy_label') }}</label>
                <select name="section_academy" id="section_academy" class="form-select ml-1 form-control form-control-sm">
                    <option value="">{{ __('strings.fn_all') }}</option>
                    @foreach($academies as $academy)
                        <option value="{{ $academy->id }}"
                                @if (!is_null($RacId) && $academy->id == $RacId)
                                    selected
                                @endif
                        >{{ $academy->ac_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="section_grade" class="form-label ml-3">{{ __('strings.lb_grade_name') }}</label>
                <select name="section_grade" id="section_grade" class="form-select ml-1 form-control form-control-sm">
                    <option value="">{{ __('strings.fn_all') }}</option>
                    @foreach($schoolGrades as $schoolGrade)
                        <option value="{{ $schoolGrade->id }}"
                                @if (!is_null($RsgId) && $RsgId == $schoolGrade->id)
                                    selected
                                @endif
                        >{{ $schoolGrade->scg_name }}</option>
                    @endforeach
                </select>
                <i class="fa fa-spin fa-spinner d-none ml-2 mt-1" id="loadAcademy"></i>
            </div>

            <span id="fn_class_loader" class="d-none"><i class="fa fa-spin fa-spinner"></i> </span>

            <div class="form-group">
                <label for="section_class" class="form-label ml-3">{{ __('strings.lb_class_name') }}</label>
                <select name="section_class" id="section_class" class="form-select ml-1 form-control form-control-sm">
                    <option value="">{{ __('strings.fn_all') }}</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}"
                                @if (!is_null($RclId) && $RclId == $class->id)
                                    selected
                                @endif
                        >{{ $class->class_name }}</option>
                    @endforeach
                </select>
            </div>
<!--

            <div class="form-group">
                <label for="section_forms" class="form-label ml-3">{{ __('strings.lb_test_title') }}</label>
                <select name="section_forms" id="section_forms" class="form-select ml-1">
                    <option value="">{{ __('strings.fn_all') }}</option>
                </select>
            </div>
-->

            <div class="form-group">
                <label for="section_year" class="form-label ml-3">{{ __('strings.lb_year') }}</label>
                <select name="section_year" id="section_year" class="form-select ml-1 form-control form-control-sm">
                    <option value="">{{ __('strings.fn_all') }}</option>
                    @for($y = date('Y') + 1; $y > date('Y') -3;$y--)
                        <option value="{{ $y }}"
                                @if (!is_null($Ryear) && $Ryear == $y)
                                    selected
                                @endif
                        >{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div class="form-group">
                <label for="section_hakgi" class="form-label ml-3">{{ __('strings.lb_hakgi') }}</label>
                <select name="section_hakgi" id="section_hakgi" class="form-select ml-1 form-control form-control-sm">
                    <option value="">{{ __('strings.fn_select_item') }}</option>
                    @if (!is_null($RHakgis))
                        @foreach ($RHakgis as $RHakgi)
                            <option value="{{ $RHakgi->id }}"
                            @if ($RHakgi->id == $RhgId && !is_null($RhgId))
                                selected
                            @endif
                            >{{ $RHakgi->hakgi_name }}</option>
                        @endforeach
                    @endif
                </select>
                <i class="fa fa-spinner fa-spin d-none ml-2 mt-1" id="loadHakgi"></i>
            </div>

            <div class="form-group">
                <label for="section_weeks" class="form-label ml-3">{{ __('strings.lb_weeks') }}</label>
                <select name="section_weeks" id="section_weeks" class="form-select ml-1 form-control form-control-sm">
                    <option value="">{{ __('strings.fn_select_item') }}</option>
                    @if (!is_null($MaxWeeks) && $MaxWeeks > 0)

                        @for ($i=1; $i <= $MaxWeeks; $i++)
                            <option value="{{ $i }}"
                            @if (!is_null($Rweek) && $Rweek == $i)
                                selected
                            @endif
                            >{{ $i }} {{ __('strings.lb_weeks') }}</option>
                        @endfor
                    @endif
                </select>
            </div>

        </div>
        <div class="btn-group mt-3">
            <button class="btn btn-primary btn-sm " id="btnLoad"><i class="fa fa-arrow-alt-circle-down"></i>
                {{ __('strings.fn_load') }}
            </button>

            <button class="btn btn-outline-primary btn-sm" id="btnFormLoad"><i class="fa fa-cloud-download-alt"></i>
                {{ __('strings.lb_get_test_forms') }}
            </button>
        </div>
    </div>
    <div class="mt-3">

        <form name="smsFrm" id="smsFrm" method="post" action="/sendSms">
            @csrf
            <table class="mt-3 table table-striped table-bordered">
                <thead>
                    <tr class="text-center">
                        <th scope="col">#</th>
                        <th scope="col">{{ __('strings.lb_academy_name') }}</th>
                        <th scope="col">{{ __('strings.lb_grade_name') }}</th>
                        <th scope="col">{{ __('strings.lb_class_name') }}</th>
                        <th scope="col">{{ __('strings.lb_year_name') }}</th>
                        <th scope="col">{{ __('strings.lb_weeks') }}</th>
                        <th scope="col">{{ __('strings.lb_test_title') }}</th>
                        <th scope="col">{{ __('strings.lb_btn_manage') }}</th>
                    </tr>
                </thead>
                <tbody>
                @if (!is_null($data) && sizeof($data) > 0)
                    @foreach($data as $datum)
                    <tr>
                        <th scope="col" class="text-center">{{ $datum->id }}</th>
                        <td class="text-center">{{ $datum->Academy->ac_name }}</td>
                        <td class="text-center">{!!  isset($datum->Grade) ? $datum->Grade->scg_name :"<span class='text-danger'>Deleted</span>" !!}</td>
                        <td class="text-center">{!! is_null($datum->ClassObj)? __('strings.err_delete_class'):  $datum->ClassObj->class_name !!}</td>
                        <td class="text-center">{{ $datum->year }}</td>
                        <td class="text-center">{{ $datum->week }} {{ __('strings.lb_weeks') }}</td>
                        <td >{!!  isset($datum->TestForm) ? $datum->TestForm->form_title : "<span class='text-danger'>Deleted</span>" !!}</td>
                        <td class="text-center btn-group">
                            @switch($datum->sp_status)
                                @case(\App\Models\Configurations::$SMS_STATUS_READY)
                                {{ __('strings.lb_sms_paper_ready') }}
                                <a href="/SmsJobInput/{{ $datum->id }}" class="ml-1 btn btn-sm btn-primary"><i class="fa fa-keyboard"></i> {{ __('strings.lb_input') }}</a>
                                @break
                                @case(\App\Models\Configurations::$SMS_STATUS_SENT)
                                <a href="#" class="btn btn-sm btn-success fn_detail_view" data-code="{{ $datum->id }}" ><i class="fa fa-tv"></i> {{ __('strings.fn_preview') }}</a>
                                <span class="text-danger mr-1"> {{ __('strings.lb_sms_paper_sent') }}</span>
                                @if (isset($datum->TestForm))
                                <a href="/SmsExcelDownload/{{ $datum->id }}" class="ml-1 btn btn-sm btn-success fn_excel"><i class="fa fa-file-excel"></i> {{ __('strings.sms_excel_download') }}</a>
                                @endif
                                @break
                                @case(\App\Models\Configurations::$SMS_STATUS_SAVING)
                                <span class="text-primary"> {{ __('strings.lb_sms_paper_saving') }} </span>
                                <a href="/SmsJobInput/{{ $datum->id }}" class="ml-1 btn btn-sm btn-primary"><i class="fa fa-keyboard"></i> {{ __('strings.lb_input') }}</a>
                                @break
                                @case(\App\Models\Configurations::$SMS_STATUS_ABLE)
                                <button class="btn btn-primary btn-sm fn_item" fn_code="{{ $datum->sp_code }}"><i class="fa fa-paper-plane"></i> {{ __('strings.lb_sms_paper_able') }} </button>
                                <a href="/SmsJobInput/{{ $datum->id }}" class="ml-1 btn btn-sm btn-outline-primary"><i class="fa fa-keyboard"></i> {{ __('strings.fn_modify') }}</a>
                                @break
                            @endswitch
                            <button class="btn btn-info btn-sm fn_add_item" fn_code="{{ $datum->id }}"><i class="fa fa-plus-circle"></i> {{ __('strings.lb_add_paper') }}</button>
                            <button class="btn btn-danger btn-sm fn_del_item" fn_code="{{ $datum->id }}"><i class="fa fa-trash"></i> {{ __('strings.fn_delete') }}</button>
                        </td>
                    </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </form>
        @if (sizeof($data) <= 0)
            <h5 class="text-dark mt-3">{{ __('strings.str_there_is_no_data') }}</h5>
        @endif
        <div class="mt-3">{{ $data->links('pagination::bootstrap-4') }}</div>
    </div>
</div>

<div class="modal fade" id="infoModalCenter" tabindex="-1" role="dialog" aria-labelledby="infoModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLongTitle">{{ __('strings.lb_test_forms_match') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="opFrm" id="opFrm" method="post" action="/saveMatching">
                    @csrf
                    <input type="hidden" name="up_academy" id="up_academy"/>
                    <input type="hidden" name="up_grade" id="up_grade"/>
                    <input type="hidden" name="up_class" id="up_class"/>
                    <input type="hidden" name="up_year" id="up_year"/>
                    <input type="hidden" name="up_hakgi" id="up_hakgi"/>
                    <input type="hidden" name="up_week" id="up_week"/>
                    <div class="row pl-1 pr-1">
                        <div class="col border mr-1" style="height: 300px">
                            <h6> {{ __('strings.lb_select_classes') }} <input type="checkbox" id="class_cbox" class="form-check-input ml-3"/></h6>
                            <div class="list-group overflow-auto list-group-flush overflow-scroll" id="ls_classes" style="height:250px;"></div>
                        </div>
                        <div class="col border mr-1" style="height: 300px;">
                            <h6>{{ __('strings.lb_test_title') }}</h6>
                            <div class="list-group overflow-auto list-group-flush overflow-scroll" id="ls_forms" style="height:250px;"></div>
                        </div>
                        <div class="col border ml-1" style="height: 300px;">
                            <h6>{{ __('strings.lb_subjects') }}</h6>
                            <div class="list-group overflow-auto list-group-flush" id="ls_subjects" style="height:250px;"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <i id="fn_loading" class="fa fa-spin fa-spinner mr-3 d-none"></i>
                <button type="button" class="btn btn-primary" id="btnSubmit" ><i class="fa fa-save"></i> {{ __('strings.fn_save') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
                <button type="button" class="btn btn-danger d-none" id="btnOpDelete"><i class="fa fa-trash"></i> {{ __('strings.fn_delete') }}</button>
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
                <form name="delFrm" id="delFrm" method="post" action="/delSmsFront">
                    @csrf
                    <input type="hidden" name="del_id" id="del_id"/>
                </form>
            </div>
            <div class="modal-footer">
                <span id="fn_confirm_loading" class="d-none"><i class="fa fa-spinner fa-spin"></i> </span>
                <button type="button" class="btn btn-primary" id="btnDeleteDo"><i class="fa fa-check-circle"></i> {{ __('strings.fn_okay') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="loaderModalCenter" tabindex="-1" role="dialog" aria-labelledby="loaderModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h1 id="fn_confirm_body"><i class="fa fa-spin fa-spinner"></i> {{ __('strings.lb_loading') }}</h1>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="paperModalCenter" tabindex="-1" role="dialog" aria-labelledby="paperModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLongTitle">{{ __('strings.lb_add_paper') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5><i class="fa fa-plus-circle"></i> {{ __('strings.lb_select_test_to_add') }}</h5>
                <form name="frmAdd" id="frmAdd" method="post">
                    @csrf
                    <input type="hidden" name="up_sp_id" id="up_sp_id"/>
                    <div class="form-group mt-3">
                        <label for="up_sel_test" >{{ __('strings.lb_title') }}</label>
                        <select name="up_sel_test" id="up_sel_test" class="form-select form-control"></select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <span id="fn_paper_loading" class="d-none"><i class="fa fa-spin fa-spinner"></i> </span>
                <button type="button" class="btn btn-primary" id="btnAddPaper"><i class="fa fa-check-circle"></i> {{ __('strings.fn_okay') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="sendConfirmModalCenter" tabindex="-1" role="dialog" aria-labelledby="sendConfirmModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendConfirmModalLongTitle">{{ __('strings.str_sms_send_confirm') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="sendFrm" id="sendFrm" method="post">
                    @csrf
                    <input type="hidden" name="up_sp_code" id="up_sp_code"/>
                    <input type="hidden" name="up_send_able" id="up_send_able"/>
                    <div class="mt-3" id="sendFrmText">{{ __('strings.lb_sms_send_now') }}</div>
                </form>
            </div>
            <div class="modal-footer">
                <span id="fn_paper_send_loading" class="d-none"><i class="fa fa-spin fa-spinner"></i> </span>
                <button type="button" class="btn btn-primary d-none" id="btnSendCancelPaper" data-dismiss="modal"><i class="fa fa-check"></i> {{ __('strings.fn_okay') }}</button>
                <button type="button" class="btn btn-primary" id="btnSendPaper"><i class="fa fa-paper-plane"></i> {{ __('strings.fn_okay') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
            </div>
        </div>
    </div>
</div>

<div aria-live="polite" aria-atomic="true" class="d-flex justify-content-center align-items-center">
    <div role="alert" aria-live="assertive" aria-atomic="true" class="toast" data-autohide="true" >
        <div class="toast-header">
            <strong class="mr-auto">Excel Download</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            {{ __('strings.wait_to_download_and_get_bottom') }}
        </div>
    </div>
</div>


<div class="modal fade" id="previewModalCenter" tabindex="-1" role="dialog" aria-labelledby="previewModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLongTitle">{{ __('strings.fn_preview') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="preview_panel"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

    <script type="text/javascript">
        // preview
        $(document).on("click",".fn_detail_view",function (){
            event.preventDefault();
            event.stopImmediatePropagation();

            let spCode = $(this).data("spcode");
            let pId = $(this).data("code");

            $("#previewModalCenter").modal("show");

            $.ajax({
                url:"/getTempPage",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "pId":pId,
                },
                type:"POST",
                dataType:"json",
                success:function(msg){
                    $("#preview_panel").load("/sms/views",[
                        {"_token":$("#input[name='_token']").val()},
                        {"up_code":spCode},
                        {"up_parent_tel":msg.data.tel}
                    ]);
                }
            });
        });
        // 폼 매칭 반선택 전체 선택, 해제
        $(document).on("click","#class_cbox",function (){
            let box = $(this);
            $.each($("input[name='up_classes[]']"),function (i,obj){
                $(obj).prop("checked",box.prop("checked"));
            });
        });
        //
        // excel download
        $(document).on("click",".fn_excel",function(){
            event.preventDefault();
            event.stopImmediatePropagation();

            let linkUrl = $(this).attr("href");

            location.href = linkUrl;

            $('.toast').toast({
                delay: 4000
            }).toast("show");
        });

        let hakgiData = [];
        let selInput;
        // 년도 변경 시
        $(document).on("change","#section_year",function (){
            event.stopImmediatePropagation();
            event.preventDefault();

            if ($(this).val() === ""){
                showAlert("{{ __('strings.str_select_year') }}");
                return;
            }

            if ($("#section_grade").val() === ""){
                showAlert("{{ __('strings.str_select_hakyon') }}");
                return;
            }

            $("#loadHakgi").removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/getHakgisAllJson",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "grade_id":$("#section_grade").val(),
                    "year":$("#section_year").val()
                },
                success:function(msg){
                    //
                    hakgiData = [];
                    $("#section_weeks").empty();
                    $("#section_hakgi").empty();

                    $("<option value=''>{{ __('strings.fn_select_item') }}</option>").appendTo($("#section_hakgi"));
                    $("<option value=''>{{ __('strings.fn_select_item') }}</option>").appendTo($("#section_weeks"));

                    $.each(msg.data,function (i,obj){
                        hakgiData.push({"id":obj.id,"weekCnt":obj.weeks});
                        $("<option value='" + obj.id + "'>" + obj.hakgi_name + "</option>").appendTo($("#section_hakgi"));
                    });

                    $("#loadHakgi").addClass("d-none");
                },
                error:function (e1,e2,e3){
                    showAlert(e2);
                    $("#loadHakgi").addClass("d-none");
                    return;
                }
            });
        });

        // delete
        $(document).on("click",".fn_del_item",function (){
            event.preventDefault();
            let nowCode = $(this).attr("fn_code");
            $("#del_id").val(nowCode);
            $("#confirmModalCenter").modal("show");
            console.log(nowCode);
        });

        // 시험추가
        $(document).on("click",".fn_add_item",function (){
            //
            event.preventDefault();
            $("#paperModalCenter").modal("show");
            let codeVal = $(this).attr("fn_code");
            $("#up_sp_id").val(codeVal);

            $.ajax({
                type:"POST",
                url:"/getTestPapersJson",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "sp_id":codeVal
                },
                dataType:"json",
                success:function(msg){
                    //
                    $("#up_sel_test").empty();
                    $("<option value=''>{{ __('strings.lb_select_test_to_add') }}</option>").appendTo($("#up_sel_test"));
                    $.each(msg.data,function (i,obj){
                        //
                        $("<option value='" + obj.id + "'>" + obj.form_title + "</option>").appendTo($("#up_sel_test"));
                    });
                },
                error:function (e1,e2,e3){
                    showAlert(e2);
                    return;
                }
            })
        });

        // 시험 추가 저장
        $(document).on("click","#btnAddPaper",function (){
            //
            if ($("up_sel_test").val() === ""){
                showAlert("{{ __('strings.lb_select_test_to_add') }}");
                return;
            }

            $("#fn_paper_loading").removeClass("d-none");
            $("#frmAdd").prop({"action":"/addSmsPapers"}).submit();
        });

        $(document).on("keyup",".fn_input",function (){
            //console.log("row Total (Y or N) : " + $(this).attr("fn_total") + " / group : " + $(this).attr("fn_group"));
            let grpId = $(this).attr("fn_group");
            let isTotal = $(this).attr("fn_total");
            let nowRow = $(this).attr("fn_row");
            let nowVal = $(this).val();
            let maxScore = $(this).attr("max");
            let minScore = $(this).attr("min");

            if ($(this).val() > $(this).attr("max")){
                $(this).val(maxScore);
                return;
            }

            if ($(this).val() < $(this).attr("min")){
                $(this).val(minScore);
                return;
            }

            selInput = $(this);
            let itemsArray = [];
            for (let i =0; i < $(".fn_input").length; i++){
                console.log("root group id :" + grpId + " , cur group : " + $(".fn_input").eq(i).attr("fn_group"));
                if ($(".fn_input").eq(i).attr("fn_group") === grpId && $(".fn_input").eq(i).attr("fn_row") === nowRow){
                    itemsArray.push($(".fn_input").eq(i));
                }
            }

            if (itemsArray.length > 1){
                let sumVal = 0;
                for (let j=0; j < itemsArray.length -1; j++){
                    sumVal += parseInt(itemsArray[j].val());
                }
                itemsArray[itemsArray.length -1].val(sumVal);
            }
            //console.log("items count ; " + itemsArray.length);
        });

        $(document).on("focus",".fn_input",function (){
            $(this).select();
        });

        // 의견 폼을 클릭할 때 이벤트
        let opinionRowId;
        $(document).on("click",".fn_opinion",function (){
            let curOpinion = $(this).val();
            let curId = $(this).attr("fn_op_id");
            opinionRowId = $(this).attr("id");
            $("#infoModalCenter").modal("show");
            $("#info_id").val(curId);
            $("#info_name").val(curOpinion).focus();
        });

        // 모달에서 저장하기를 클릭할 때 이벤트
        $(document).on("click","#btnOpSubmit",function (){
            $.ajax({
                type:"POST",
                url:"/saveOpinion",
                dataType:"json",
                data:$("#opFrm").serialize(),
                success:function (msg){
                    if (msg.result === "true"){
                        $("#" + opinionRowId).val($("#info_name").val());
                        $("#infoModalCenter").modal("hide");
                        $("#info_name").val("");
                    }else{
                        showAlert("{{ __('strings.err_fail_to_save') }}");
                        return;
                    }
                }
            })

        });

        $(document).on("click","#btnTest",function (){
            console.log($(".fn_input").length);
        });


        $(document).on("click","#btnAllSend",function (){
            event.preventDefault();
            $("#smsFrm").submit();
        });

        $(document).on("change","#section_academy,#section_grade",function (){
            event.preventDefault();
            if ($("#section_academy").val() === ""){
                showAlert("{{ __('strings.str_select_academy') }}");
                return;
            }

            $("#loadAcademy").removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/getClassesJson",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "section_academy":$("#section_academy").val(),
                    "section_grade":$("#section_grade").val(),
                    "section_year":$("#section_year").val(),
                },
                success:function (msg){
                    $("#section_class").empty();
                    $("<option value=''>{{ __('strings.fn_all') }}</option>").appendTo($("#section_class"));
                    $.each(msg.data,function (i,obj){
                        $("<option value='" + obj.id + "'>" + obj.class_name + "</option>").appendTo($("#section_class"));
                    });

                    $("#section_hakgi").empty();
                    hakgiData = [];
                    $("<option value=''>{{ __('strings.fn_all') }}</option>").appendTo($("#section_hakgi"));
                    $.each(msg.hakgi,function(i,obj){
                        $("<option value='" + obj.id + "'>" + obj.hakgi_name + "</option>").appendTo($("#section_hakgi"));
                        hakgiData.push({"id":obj.id,"weekCnt":obj.weeks});
                        //console.log("add week : " + obj.weeks);
                    });

                    $("#loadAcademy").addClass("d-none");
                },
                error:function (e1,e2,e3){
                    showAlert(e2);
                    $("#loadAcademy").addClass("d-none");
                    return;
                }
            })
        });

        // 학기를 선택할 때
        $(document).on("change","#section_hakgi",function (){
            if ($(this).val() === ""){
                return;
            }

            let nowSelHakgi = $(this).val();
            let maxWeek = 0;
            for(let i=0; i < hakgiData.length; i++){
                let nowHakgiDataId = hakgiData[i].id;
                if (parseInt(nowSelHakgi) === nowHakgiDataId){
                    maxWeek = hakgiData[i].weekCnt;
                    break;
                }
            }

            if (maxWeek > 0){
                $("#section_weeks").empty();
                $("<option value=''>{{ __('strings.fn_select_item') }}</option>").appendTo($("#section_weeks"));
                for (let i=1; i <= maxWeek; i++){
                    $("<option value='" + i + "'>" + i + " {{ __('strings.lb_weeks')}}</option>").appendTo($("#section_weeks"));
                }
            }
        });

        // 불러오기 버튼 클릭 시
        $(document).on("click","#btnLoad",function (){
            event.preventDefault();
            let loadURL = "/SmsFront";

            if ($("#section_academy").val() !== ""){
                loadURL = loadURL + "/" + $("#section_academy").val();
            }else{
                loadURL = loadURL + "/ALL";
            }


            if ($("#section_grade").val() !== ""){
                loadURL = loadURL + "/" + $("#section_grade").val();
            }else{
                loadURL = loadURL + "/ALL";
            }

            if ($("#section_class").val() !== ""){
                loadURL = loadURL + "/" + $("#section_class").val();
            }else{
                loadURL = loadURL + "/ALL";
            }

            if ($("#section_year").val() !== ""){
                loadURL = loadURL + "/" + $("#section_year").val();
            }else{
                loadURL = loadURL + "/ALL";
            }

            if ($("#section_hakgi").val() !== ""){
                loadURL = loadURL + "/" + $("#section_hakgi").val();
            }else{
                loadURL = loadURL + "/ALL";
            }

            location.href = loadURL;
            //location.href = "/SmsFront/" + $("#section_academy").val() + "/" + $("#section_grade").val() + "/" + $("#section_class").val() +
                "/" + $("#section_year").val() + "/" + $("#section_hakgi").val() + "/" + $("#section_weeks").val();
        });



        // 기존에 있는 지 체크
        function checkHasIndex(arr,val){
            let chk = arr.findIndex(x=>x.Id === val);
            if (chk >= 0){
                return true;
            }
            return false;
        }

        $(document).on("click","#btnTfSubmit",function (){
            event.preventDefault();
            if ($("#info_name").val() === ""){
                showAlert("{{ __('strings.str_insert_form_title') }}");
                return;
            }

            if ($(".fn_saved").prop("checked",true).length <= 0){
                showAlert("{{ __('strings.str_select_saved_subjects') }}");
                return;
            }

            $("#fn_loading").removeClass("d-none");

            $("#up_ac_id").val($("#section_academy").val());
            $("#up_grade_id").val($("#section_grade").val());
            $("#up_cl_id").val($("#section_class").val());


            $("#tfFrm").submit();
        });

        // 폼 매칭하기 클릭
        $(document).on("click","#btnFormLoad",function (){
            if ($("#section_grade").val() === ""){
                showAlert("{{__('strings.str_select_grades')}}");
                return;
            }
            $("#infoModalCenter").modal("show");
            loadCurrentForms();
        });

        let testFormsData = [];

        function loadCurrentForms(){
            $("#fn_loading").removeClass("d-none");
            $("#ls_classes").empty();

            $.each($("#section_class").find("option"),function (i,obj){
                if ($(obj).val() != ""){
                    $("<div class='list-group-item'><input type='checkbox' name='up_classes[]' id='up_classes_id_" + i + "' value='" + $(obj).val() + "' class='form-check-input'/><label for='up_classes_id_" + i + "'>" + $(obj).text() + "</label></div>").appendTo($("#ls_classes"));
                }
            });

            $.ajax({
                type:"POST",
                url:"/getTestFormsInSmsJson",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "up_grade_id":$("#section_grade").val()
                },
                success:function (msg){
                    $("#ls_forms").empty();
                    testFormsData = [];
                    let sObjs = [];
                    $.each(msg.data,function (i,obj){
                        let sArray = obj.subjects;
                        sObjs = [];
                        $.each(sArray,function (j,obj2){
                            sObjs.push({"title":obj2.sj_title});
                        });
                        testFormsData.push({"tfId":obj.tf_id,"tfName":obj.form_title,"tfSubjects":sObjs});
                        if (obj.exam === "Y"){
                            $("<div class='list-group-item ml-1 mr-1'><input type='checkbox' name='tf_ids[]' value='" + obj.id + "' class='form-check-input'/> <label class='form-check-label fn_in_tf'><i class='fa fa-star'></i> " + obj.form_title + " ({{ __('strings.lb_test_count') }} : " + obj.items_count + ")</label></div>").appendTo($("#ls_forms"));
                        }else{
                            $("<div class='list-group-item ml-1 mr-1'><input type='checkbox' name='tf_ids[]' value='" + obj.id + "' class='form-check-input'/> <label class='form-check-label fn_in_tf'>" + obj.form_title + " ({{ __('strings.lb_test_count') }} : " + obj.items_count + ")</label></div>").appendTo($("#ls_forms"));
                        }

                    });
                    $("#fn_loading").addClass("d-none");
                },
                error:function (e1,e2,e3){
                    showAlert(e2);
                    $("#fn_loading").addClass("d-none");
                    return;
                }
            });
        }

        // 시험 이름을 클릭할 때.
        $(document).on("click",".fn_in_tf",function (){
            event.preventDefault();
            let selIndex = $(".fn_in_tf").index($(this));
            let subjects = testFormsData[selIndex].tfSubjects;
            $("#ls_subjects").empty();

            $("#ls_forms > div").removeClass("active");
            $("#ls_forms > div").eq(selIndex).addClass("active");

            $.each(subjects,function (i,obj){
                $("<div class='list-group-item'>" + obj.title + "</div>").appendTo($("#ls_subjects"));
            });
        });

        // 관리 클릭 시...
        $(document).on("click",".fn_item",function (){
            //
            event.preventDefault();
            let spId = $(this).attr("fn_code");

            $("#del_id").val(spId);

            $.ajax({
                type:"POST",
                url:"/SmsCheckToSend",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "spId":spId
                },
                success:function (msg){
                    //
                    if (msg.result === "true"){
                        $("#sendConfirmModalCenter").modal("show");
                        $("#sendFrmText").html("{{ __('strings.lb_sms_send_now') }}");

                        $("#up_sp_code").val(spId);   // code
                        $("#up_send_able").val("true");
                        $("#btnSendPaper").removeClass("d-none");
                        $("#btnSendCancelPaper").addClass("d-none");
                    }else{
                        $("#sendConfirmModalCenter").modal("show");
                        $("#up_send_able").val("false");
                        $("#sendFrmText").html("{{ __('strings.str_sms_send_unable') }}");
                        $("#btnSendPaper").addClass("d-none");
                        $("#btnSendCancelPaper").removeClass("d-none");
                    }
                },
                error:function (e1,e2,e3){
                    showAlert(e2);
                    return;
                }
            });
        });

        // 신규 팝업에서 보내기 버튼 클릭 시
        $(document).on("click",'#btnSendPaper',function (){
            event.preventDefault();
            if ($("#up_send_able").val() === "false"){
                showAlert("{{ __('strings.str_sms_send_unable') }}");
                return;
            }else{
                $("#fn_paper_send_loading").removeClass("d-none");
                $("#sendFrm").prop({"action":"/sendSms"}).submit();
            }
        });

        $(document).on("click","#btnSubmit",function (){
            //
            event.preventDefault();
            /*if ($("#section_class").val() === ""){
                showAlert("{{ __('strings.str_select_class') }}");
                return;
            }*/
            var classesIds = [];
            $.each($("input[name='up_classes[]']:checked"),function (i,obj){
                classesIds.push($(obj).val());
            });

            if (classesIds.length <= 0){
                showAlert("{{ __('strings.str_select_class') }}");
                return;
            }

            if ($("#section_year").val() === ""){
                showAlert("{{ __('strings.str_select_year') }}");
                return;
            }

            if ($("#section_hakgi").val() === ""){
                showAlert("{{ __('strings.str_select_hakgi') }}");
                return;
            }

            if ($("#section_weeks").val() === ""){
                showAlert("{{ __('strings.str_select_week') }}");
                return;
            }

            $("#fn_loading").removeClass("d-none");
            $("#up_academy").val($("#section_academy").val());
            $("#up_grade").val($("#section_grade").val());
            $("#up_class").val(classesIds.toString());
            $("#up_year").val($("#section_year").val());
            $("#up_hakgi").val($("#section_hakgi").val());
            $("#up_week").val($("#section_weeks").val());

            $("#opFrm").submit();
        });

        $(document).on("click","#btnTfDelete",function (){
            event.preventDefault();

            $("#confirmModalCenter").modal("show");
        });

        $(document).on("click","#btnDeleteDo",function (){
            $("#delFrm").submit();
            $("#fn_confirm_loading").removeClass("d-none");
        });


        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
