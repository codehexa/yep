@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h5>{{ __('strings.lb_sms_work_manage') }} </h5>
    <div class="mt-3 btn-group">
        <a href="/home" class="btn btn-outline-secondary btn-sm"><i class="fa fa-home"></i> {{ __("strings.fn_home") }}</a>
        <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-backward"></i> {{ __('strings.fn_backward') }} </a>
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
                @case ("CALL_TO_DEV")
                <h4 class="text-center text-danger"> {{ __('strings.err_call_to_dev',["CODE"=>"FILE_EXCEL_FAIL"]) }}</h4>
                @break
                @case ("CANT_SET_DONE")
                <h4 class="text-center text-danger"> {{ __('strings.err_set_sms_paper_done') }}</h4>
                @break
            @endswitch
        @endforeach
    @endif

    <h6 class="mt-3">{{ __('strings.lb_sms_work_input') }} <i class="fa fa-chevron-right"></i> {{ $paperInfo->ClassObj->class_name }} {{ __('strings.lb_class') }}
        {{ __('strings.lb_year_string',["YEAR"=>$paperInfo->year]) }}
        {{ $paperInfo->Hakgi->hakgi_name }}
        {{ __('strings.lb_week_string',["WEEK"=>$paperInfo->week]) }}
        <i class="fa fa-chevron-right"></i>
        {{ isset($paperInfo->TestForm) ? $paperInfo->TestForm->form_title : "Deleted"}}
    </h6>

    <div class="mt-3 btn-group">
        <button id="btnAllSave" class="btn btn-primary"><i class="fa fa-spinner fa-spin d-none" id="fn_loading_save"></i> <i class="fa fa-save"></i> {{ __('strings.lb_save_all') }}</button>
        <button id="btnSetDone" class="btn btn-warning"><i class="fa fa-spinner fa-spin d-none" id="fn_loading_set_done"></i> <i class="fa fa-clipboard-check"></i> {{ __('strings.lb_set_done') }}</button>
        <button id="btnSetDoneSave" class="btn btn-outline-primary"><i class="fa fa-spinner fa-spin d-none" id="fn_loading_set_save_done"></i> <i class="fa fa-arrow-circle-up"></i> {{ __('strings.lb_save_send_set') }}</button>
        <div class="form-check form-switch ml-2 mt-2">
            <input type="checkbox" name="chk_max" id="chk_max" class="form-check-input"/>
            <label for="chk_max" class="form-check-label">{{ __('strings.lb_max_100points') }}</label>
        </div>
        <span class="d-none text-danger mt-2 ml-2" id="guide_100">{{ __('strings.lb_if_you_use_100') }}</span>
    </div>
    <div class="mt-3 overflow-auto">
        <form name="smsFrm" id="smsFrm" method="post" action="/SmsJobSave">
            @csrf
            <input type="hidden" name="saved_sp_id" id="saved_sp_id" value="{{ $spId }}"/>
            <input type="hidden" name="saved_auto" id="saved_auto" value="N"/>
            <table class="mt-3 table table-striped table-bordered">
                <thead>
                    @if (is_null($testForm))
                        <tr class="text-center">
<!--                            <th scope="col">#</th>-->
                            <th scope="col">{{ __('strings.lb_student_name') }}</th>
                            <th scope="col">{{ __('strings.lb_school_name') }}</th>
                            <th scope="col">{{ __('strings.lb_grade_name') }}</th>
                            <th scope="col">{{ __('strings.lb_teacher_name') }}</th>
                            <th scope="col">{{ __('strings.lb_subjects') }}</th>
                            <th scope="col">{{ __('strings.lb_comment') }}</th>
                            <th scope="col">{{ __('strings.lb_wordian_title') }}</th>
                            <th scope="col">{{ __('strings.lb_btn_manage') }}</th>
                        </tr>
                    @else
                        <tr class="text-center">
<!--                            <th scope="col" rowspan="2" class="text-center">#</th>-->
                            <th scope="col" rowspan="2" class="text-center text-nowrap">{{ __('strings.lb_student_name') }}</th>
                            <th scope="col" rowspan="2" class="text-center text-nowrap">{{ __('strings.lb_school_name') }}</th>
                            <th scope="col" rowspan="2" class="text-center text-nowrap">{{ __('strings.lb_grade_name') }}</th>
                            <th scope="col" rowspan="2" class="text-center text-nowrap">{{ __('strings.lb_teacher_name') }}</th>
                            @foreach ($tItems as $tItem)
                                @if ($tItem->sj_depth == "0")
                                    <th scope="col"
                                        @if ($tItem->sj_has_child == "Y")
                                            colspan="{{ $tItem->child_size }}"
                                        @else
                                            rowspan="2"
                                        @endif
                                        class="text-center">{{ $tItem->sj_title }}</th>
                                @endif
                            @endforeach
                            <th scope="col" rowspan="2"  class="text-center">{{ __('strings.lb_comment') }}</th>
                            <th scope="col" rowspan="2" class="text-center">{{ __('strings.lb_wordian') }}</th>
                            <th scope="col" rowspan="2"  class="text-center">{{ __('strings.lb_btn_manage') }}</th>
                        </tr>
                        @if ($hasDouble == "Y")
                            @foreach($tItems as $tItem)
                                @if ($tItem->sj_depth == "1")
                                    <th scope="col" class="text-center">{{ $tItem->sj_title }}</th>
                                @endif
                            @endforeach
                        @endif
                    @endif
                </thead>
                <tbody>
                @for($i=0; $i < sizeof($data); $i++)
                    <tr class="text-center fn_tbody_tr">
<!--                        <th scope="row">
                            <div class="form-check align-self-center">
&lt;!&ndash;                                <input type="checkbox" name="ss_id[]" id="ss_id_{{ $data[$i]["id"] }}" value="{{ $data[$i]["id"] }}" class="form-check-input" checked/>&ndash;&gt;
                                {{ $i + 1 }}

                            </div>
                        </th>-->
                        <input type="hidden" name="ss_id[]" id="ss_id_{{ $data[$i]['id'] }}" value="{{ $data[$i]['id'] }}"/>
                        <td class="text-center">{{ $data[$i]["studentItem"]->student_name }}</td>
                        <td class="text-center">{{ $data[$i]["studentItem"]->school_name }}</td>
                        <td class="text-center">{{ $data[$i]["studentItem"]->school_grade }}</td>
                        <td class="text-center">{{ $data[$i]["studentItem"]->teacher_name }}</td>

                        @php ($num = 0)
                        @foreach ($tItems as $item)
                            @if ($item->sj_has_child == "N")
                                @php ($field_name = "score_".$num)
                                <td class="form-group">
                                        <input type="text"
                                               name="score_{{ $num }}[]" id="f_{{ $num }}_{{ $i }}_{{ $data[$i]["id"] }}" value="{{ $data[$i][$field_name] }}"
                                               max="{{ $item->sj_max_score }}" min="0"
                                               data-sjid="{{ $item->sj_id }}"
                                               data-group="{{ $item->sj_parent_id }}"
                                               data-depth="{{ $item->sj_depth }}"
                                               fn_row="{{ $i }}"
                                               data-haschild="{{ $item->sj_has_child }}"
                                               @if ($item->sj_type == "T")
                                                   fn_total="Y"
                                               @else
                                                   fn_total="N"
                                               @endif
                                               class="form-control form-control-sm text-center fn_input fn_scores"/>
                                </td>
                                @php ($num++)
                            @endif
                        @endforeach
                        <td><input type="text" name="ss_opinion[]" id="ss_opinion_{{ $data[$i]["id"] }}" fn_row="{{ $i }}" value="{{ $data[$i]["opinion"] }}" fn_op_id="{{ $data[$i]["id"] }}" class="form-control fn_opinion fn_input "/> </td>
                        <td><input type="text" name="ss_wordian[]" id="ss_wordian_{{ $data[$i]["id"] }}" fn_row="{{ $i }}" value="{{ isset($data[$i]["wordian"]) ? $data[$i]["wordian"]:"" }}" fn_wd_id="{{ $data[$i]["id"] }}" class="form-control fn_wordian fn_input "/> </td>
                        <td>
                            @if ($data[$i]["sent"] == "N")
                                <div class="btn-group-sm btn-group">
                                    <a href="#" class="btn btn-primary btn-sm fn_item" fn_id="{{ $data[$i]["id"] }}" fn_item_row="{{ $i }}">
                                        <span class="fa fa-spin fa-spinner fn_fa_{{ $i }} d-none"></span> {{ __('strings.fn_save') }}</a>
                                </div>
                            @else
                                <span class="btn btn-secondary btn-sm">{{ __('strings.lb_sent') }}</span>
                            @endif
                        </td>
                    </tr>
                @endfor
                </tbody>
            </table>
        </form>
    </div>
</div>

<div class="modal fade" id="infoModalCenter" tabindex="-1" role="dialog" aria-labelledby="infoModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLongTitle">{{ __('strings.lb_opinion_write') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="opFrm" id="opFrm" method="post" action="">
                    @csrf
                    <input type="hidden" name="info_id" id="info_id"/>
                    <div class="form-group">
                        <label for="info_name">{{ __('strings.lb_comment') }}</label>
                        <textarea type="text" name="info_name" id="info_name" placeholder="{{ __('strings.str_input_opinion') }}" class="form-control">
                        </textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <i id="fn_loading" class="fa fa-spin fa-spinner mr-3 d-none"></i>
                <button type="button" class="btn btn-primary" id="btnOpSubmit" ><i class="fa fa-save"></i> {{ __('strings.fn_save') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
                <button type="button" class="btn btn-danger d-none" id="btnOpDelete"><i class="fa fa-trash"></i> {{ __('strings.fn_delete') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="wordianModalCenter" tabindex="-1" role="dialog" aria-labelledby="wordianModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="wordianModalLongTitle">{{ __('strings.lb_insert_wordian') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="wordianFrm" id="wordianFrm" method="post" action="">
                    @csrf
                    <input type="hidden" name="wordian_info_id" id="wordian_info_id"/>
                    <div class="form-group">
                        <label for="info_wordian">{{ __('strings.lb_wordian') }}</label>
                        <textarea type="text" name="info_wordian" id="info_wordian" placeholder="{{ __('strings.lb_insert_wordian') }}" class="form-control">
                        </textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <i id="fn_loading" class="fa fa-spin fa-spinner mr-3 d-none"></i>
                <button type="button" class="btn btn-primary" id="btnWordianSubmit" ><i class="fa fa-save"></i> {{ __('strings.fn_save') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
                <button type="button" class="btn btn-danger d-none" id="btnWordianDelete"><i class="fa fa-trash"></i> {{ __('strings.fn_delete') }}</button>
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
                <form name="delFrm" id="delFrm" method="post" action="/delTestForms">
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
        let chkMax = false; // 기본 65 점제로 함. 만약 체크 되어 있다면, 100 점제로 환산하여 표시함.
        $(document).on("click","#chk_max",function (){
            chkMax = $(this).prop("checked");
            if (chkMax){
                $("#guide_100").removeClass("d-none");
            }else {
                $("#guide_100").addClass("d-none");
            }
            //alert(chkMax);
        })
        // 저장 후 전송 준비 완료 클릭 시
        $(document).on("click","#btnSetDoneSave",function (){
            event.preventDefault();
            $("#fn_loading_set_save_done").removeClass("d-none");
            $("#saved_auto").val("Y");
            $("#smsFrm").submit();
        });

        // 전송 준비 완료 클릭 시
        $(document).on("click","#btnSetDone",function (){
            event.preventDefault();
            $("#fn_loading_set_done").removeClass("d-none");
            $("#saved_auto").val("N");
            $("#smsFrm").attr({"action":"/SmsPaperSetDone"}).submit();
        });

        // 전송 준비 저장 완료

        let hakgiData = [];
        let selInput;

        $(document).on("keydown",".fn_input",function(){
            let nowRow = $(this).attr("fn_row");
            let nowHorizontalCols = $(".fn_tbody_tr").eq(nowRow);   // 현재 포커스된 인풋이 포함된 로우 tr
            let nowFnItemIndex = $(nowHorizontalCols).find(".fn_input").index($(this));

            if (event.keyCode === {{ \App\Models\Configurations::$KEY_CODE_UP }} ){
                if (nowRow <= 0) return;
                $(".fn_tbody_tr").eq(nowRow -1).find(".fn_input").eq(nowFnItemIndex).focus();
                $(".fn_tbody_tr").eq(nowRow -1).find(".fn_input").eq(nowFnItemIndex).select();
                return;
            }

            if (event.keyCode === {{ \App\Models\Configurations::$KEY_CODE_DOWN }}){
                if (parseInt(nowRow) >= $(".fn_tbody_tr").length -1) return;
                $(".fn_tbody_tr").eq(parseInt(nowRow) +1).find(".fn_input").eq(nowFnItemIndex).focus();
                $(".fn_tbody_tr").eq(parseInt(nowRow) +1).find(".fn_input").eq(nowFnItemIndex).select();
            }

            if (event.keyCode === {{ \App\Models\Configurations::$KEY_CODE_LEFT }}){
                if ($(nowHorizontalCols).find(".fn_input").index($(this)) <= 0) return;
                if ($(this).prop("selectionStart") <= 0){
                    $(nowHorizontalCols).find(".fn_input").eq($(nowHorizontalCols).find(".fn_input").index($(this)) -1).focus();
                    $(nowHorizontalCols).find(".fn_input").eq($(nowHorizontalCols).find(".fn_input").index($(this)) -1).select();
                }
            }

            if (event.keyCode === {{ \App\Models\Configurations::$KEY_CODE_RIGHT }}){
                if ($(nowHorizontalCols).find(".fn_input").index($(this)) >= $(nowHorizontalCols).find(".fn_input").length -1) return;
                if ($(this).prop("selectionEnd") === $(this).val().length){
                    $(nowHorizontalCols).find(".fn_input").eq($(nowHorizontalCols).find(".fn_input").index($(this)) +1).focus();
                    $(nowHorizontalCols).find(".fn_input").eq($(nowHorizontalCols).find(".fn_input").index($(this)) +1).select();
                }
            }
        });

        $(document).on("keyup",".fn_input",function () {
            //console.log("row Total (Y or N) : " + $(this).attr("fn_total") + " / group : " + $(this).attr("fn_group"));
            let isTotal = $(this).attr("fn_total");
            let nowRow = $(this).attr("fn_row");
            let nowVal = $(this).val();
            let maxScore = $(this).attr("max");
            let minScore = $(this).attr("min");
            let nowSubjectId = $(this).data("sjid");
            let nowItem = $(this);


            // 100 점제 환산 처리
            if (!chkMax) {
                if (parseInt(nowVal) > parseInt(maxScore)) {
                    showAlert("{{__('strings.lb_you_input_over_point')}}");
                    $(this).val(maxScore);
                    //console.log("max score : " + chkMax);
                    return;
                }

                if (parseInt(nowVal) < parseInt(minScore)) {
                    showAlert("{{__('strings.lb_you_input_under_point')}}");
                    $(this).val(minScore);
                    return;
                }
            }

            let itemsArray = [];    // group 묶음 아이템들...

            let _start  = "Y";
            for (let i=0; i < $(".fn_tbody_tr").eq(nowRow).find(".fn_input").length; i++){
                let _depth = $(".fn_tbody_tr").eq(nowRow).find(".fn_input").eq(i).data("depth");
                let _total = $(".fn_tbody_tr").eq(nowRow).find(".fn_input").eq(i).attr("fn_total");

                //console.log("_total : " + _total + ", _depth : " + _depth);

                if (_total === "N" && _depth === 0){
                    _total = "Y";
                }

                itemsArray.push({
                    "score":$(".fn_tbody_tr").eq(nowRow).find(".fn_input").eq(i).val(),
                    "depth":_depth,
                    "start":_start,
                    "end":_total
                });
                if (_total === "Y"){
                    _start = "Y";
                }else{
                    _start = "N";
                }
            }   // end for

            let _nowSum = 0;
            for (let i=0; i < itemsArray.length; i++){
                let _item = itemsArray[i];
                if (_item.start === "Y" && _item.end === "N"){
                    _nowSum = parseInt(_item.score);
                } else if (_item.start === "N" && _item.end === "N"){
                    _nowSum += parseInt(_item.score);
                } else if (_item.start === "N" && _item.end === "Y"){
                    $(".fn_tbody_tr").eq(nowRow).find(".fn_input").eq(i).val(_nowSum);
                } else {
                    _nowSum = 0;
                }
            }
        });

        // 100 환산 시
        $(document).on("keydown",".fn_input",function (){
            if (chkMax){
                let hundredMax = 100;
                if (event.keyCode === 13){
                    let nowVal = $(this).val();
                    let maxVal = $(this).attr("max");

                    let tmp = Math.round(parseInt(maxVal)*parseInt(nowVal)/hundredMax);
                    if (parseInt(tmp) > parseInt(maxVal)){
                        showAlert("{{ __('strings.lb_you_input_over_point') }}");
                        $(this).val(maxVal);
                    }else{
                        $(this).val(tmp);
                    }
                }
            }
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

        // wordian click
        $(document).on("click",".fn_wordian",function(){
           let curWordian = $(this).val();
           let curId = $(this).attr("fn_wd_id");
            opinionRowId = $(this).attr("id");
            $("#wordianModalCenter").modal("show");
            $("#wordian_info_id").val(curId);
            $("#info_wordian").val(curWordian).focus();
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

        // wordian 모달에서 저장하기를 클릭할 때 이벤트
        $(document).on("click","#btnWordianSubmit",function (){
            $.ajax({
                type:"POST",
                url:"/saveWordian",
                dataType:"json",
                data:$("#wordianFrm").serialize(),
                success:function (msg){
                    if (msg.result === "true"){
                        $("#" + opinionRowId).val($("#info_wordian").val());
                        $("#wordianModalCenter").modal("hide");
                        $("#info_wordian").val("");
                    }else{
                        showAlert("{{ __('strings.err_fail_to_save') }}");
                        return;
                    }
                }
            });
        });

        $(document).on("click","#btnTest",function (){
            console.log($(".fn_input").length);
        });


        $(document).on("click","#btnAllSave",function (){
            event.preventDefault();
            $("#fn_loading_save").removeClass("d-none");
            $("#saved_auto").val("N");
            $("#smsFrm").submit();
        });

        $(document).on("change","#section_academy,#section_grade",function (){
            event.preventDefault();
            if ($("#section_academy").val() === ""){
                showAlert("{{ __('strings.str_select_academy') }}");
                return;
            }

            $("#fn_class_loader").removeClass("d-none");

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


                    $("#fn_class_loader").addClass("d-none");
                },
                error:function (e1,e2,e3){
                    showAlert(e2);
                    $("#fn_class_loader").addClass("d-none");
                    return;
                }
            })
        });

        $(document).on("change","#section_class",function (){
            event.preventDefault();

            if ($("#section_academy").val() === ""){
                showAlert("{{ __('strings.str_select_academy') }}");
                return;
            }

            if ($("#section_grade").val() === ""){
                showAlert("{{ __('strings.str_select_grade') }}");
                return;
            }

            $.ajax({
                type:"POST",
                url:"/getTestFormsJson",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "section_academy":$("#section_academy").val(),
                    "section_grade":$("#section_grade").val()
                },
                success:function (msg){
                    //
                    $("#section_forms").empty();
                    $("<option value=''>{{ __('strings.fn_select_item') }}</option>").appendTo($("#section_forms"));
                    $.each(msg.data,function (i,obj){
                        $("<option value='" + obj.id + "'>" + obj.form_title + "</option>").appendTo($("#section_forms"));
                    });
                },
                error:function (e1,e2,e3){
                    showAlert(e2);
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
            //console.log(typeof(nowSelHakgi));
            let maxWeek = 0;
            for(let i=0; i < hakgiData.length; i++){
                let nowHakgiDataId = hakgiData[i].id;
                console.log(typeof(nowHakgiDataId));
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
            if ($("#section_academy").val() === ""){
                showAlert("{{ __('strings.str_select_academy') }}");
                return;
            }

            if ($("#section_grade").val() === ""){
                showAlert("{{ __('strings.str_select_grades') }}");
                return;
            }

            if ($("#section_class").val() === ""){
                showAlert("{{ __('strings.str_select_class') }}");
                return;
            }

            if ($("#section_forms").val() === ""){
                showAlert("{{ __('strings.str_select_forms') }}");
                return;
            }

            location.href = "/SmsJob/" + $("#section_academy").val() + "/" + $("#section_grade").val() + "/" + $("#section_class").val() +
                "/" + $("#section_forms").val() + "/" + $("#section_year").val() + "/" + $("#section_hakgi").val() + "/" + $("#section_weeks").val();
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

        // 관리 클릭 시...
        $(document).on("click",".fn_item",function (){
            //
            event.preventDefault();
            let tfId = $(this).attr("fn_id");
            let curRow = $(this).attr("fn_item_row");

            $("#del_id").val(tfId);

            let curId = $(this).attr("fn_id");
            let scoresArray = [];
            $.each($(".fn_scores"),function (i,obj){
                if ($(obj).attr("fn_row") === curRow){
                    scoresArray.push($(obj).val());
                }
            });

            let curOpinion = $("input[name='ss_opinion[]'").eq(curRow).val();
            let curWordian = $("input[name='ss_wordian[]'").eq(curRow).val();

            $(".fn_fa_" + curRow).removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/saveSmsEach",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "scId":curId,
                    "scores":scoresArray.toString(),
                    "opinion":curOpinion,
                    "wordian":curWordian
                },
                success:function (msg){
                    //
                    if (msg.result === "true"){
                        $(".fn_fa_" + curRow).addClass("d-none");
                    }else{
                        showAlert("{{ __('strings.err_fail_to_save') }}");
                        $(".fn_fa_" + curRow).addClass("d-none");
                        return;
                    }
                }
            });
        });

        $(document).on("click","#btnStSubmit",function (){
            //
            event.preventDefault();
            if ($("#info_name").val() === ""){
                showAlert("{{ __('strings.str_insert_student_name') }}");
                return;
            }

            if ($("#info_hp").val() === ""){
                showAlert("{{ __('strings.str_insert_hp') }}");
                return;
            }

            if ($("#info_parent_hp").val() === ""){
                showAlert("{{ __('strings.str_insert_parent_hp') }}");
                return;
            }

            $("#fn_loading").removeClass("d-none");

            $("#stFrm").submit();
        });

        $(document).on("click","#btnTfDelete",function (){
            event.preventDefault();

            $("#confirmModalCenter").modal("show");
        });

        $(document).on("click","#btnDeleteDo",function (){
            $("#delFrm").submit();
        });


        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
