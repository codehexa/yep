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
                @case ("CALL_TO_DEV")
                <h4 class="text-center text-danger"> {{ __('strings.err_call_to_dev',["CODE"=>"FILE_EXCEL_FAIL"]) }}</h4>
                @break
            @endswitch
        @endforeach
    @endif

    <div class="mt-3 form-group">
        <div class="form-inline">
            <div class="form-group">
                <label for="section_academy" class="form-label">{{ __('strings.lb_academy_label') }}</label>
                <select name="section_academy" id="section_academy" class="form-select ml-1">
                    <option value="">{{ __('strings.fn_all') }}</option>
                    @foreach ($academies as $academy)
                        <option value="{{ $academy->id }}"
                                @if ($rAcId != '' && $rAcId == $academy->id)
                                selected
                            @endif
                        >{{ $academy->ac_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="section_grade" class="form-label ml-3">{{ __('strings.lb_grade_title') }}</label>
                <select name="section_grade" id="section_grade" class="form-select ml-1">
                    <option value="">{{ __('strings.fn_all') }}</option>
                    @foreach ($schoolGrades as $schoolGrade)
                        <option value="{{ $schoolGrade->id }}"
                                @if ($rGradeId != '' && $rGradeId == $schoolGrade->id)
                                selected
                            @endif
                        >{{ $schoolGrade->scg_name }}</option>
                    @endforeach
                </select>
            </div>

            <span id="fn_class_loader" class="d-none"><i class="fa fa-spin fa-spinner"></i> </span>

            <div class="form-group">
                <label for="section_class" class="form-label ml-3">{{ __('strings.lb_class_name') }}</label>
                <select name="section_class" id="section_class" class="form-select ml-1">
                    <option value="">{{ __('strings.fn_all') }}</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->id }}"
                                @if ($rClId != '' && $rClId == $class->id)
                                selected
                            @endif
                        >{{ $class->class_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="section_forms" class="form-label ml-3">{{ __('strings.lb_test_title') }}</label>
                <select name="section_forms" id="section_forms" class="form-select ml-1">
                    <option value="">{{ __('strings.fn_all') }}</option>
                    @foreach($testForms as $tf)
                        <option value="{{ $tf->id }}"
                                @if ($rTfId != "" && $rTfId == $tf->id)
                                selected
                            @endif
                        >{{ $tf->form_title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="section_year" class="form-label ml-3">{{ __('strings.lb_year') }}</label>
                <select name="section_year" id="section_year" class="form-select ml-1">
                    @for ($y = date("Y"); $y > date("Y") -5; $y--)
                        <option value="{{ $y }}"
                                @if ($rY != '' && $rY == $y)
                                selected
                            @endif
                        >{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <div class="form-group">
                <label for="section_hakgi" class="form-label ml-3">{{ __('strings.lb_hakgi') }}</label>
                <select name="section_hakgi" id="section_hakgi" class="form-select ml-1">
                    <option value="">{{ __('strings.fn_select_item') }}</option>
                    @if (sizeof($hakgis) > 0)
                        @foreach ($hakgis as $hk)
                            <option value="{{ $hk->id }}"
                            @if ($hk->id == $rHakgi)
                                selected
                            @endif
                            >{{ $hk->hakgi_name }}</option>
                        @endforeach
                    @endif

                </select>
            </div>


            <div class="form-group">
                <label for="section_weeks" class="form-label ml-3">{{ __('strings.lb_weeks') }}</label>
                <select name="section_weeks" id="section_weeks" class="form-select ml-1">
                    <option value="">{{ __('strings.fn_select_item') }}</option>
                    @if ($maxHakgi > 0)
                        @for ($i = 0; $i < $maxHakgi; $i++)
                            <option value="{{ $i + 1 }}"
                                    @if ($i + 1 == $rW)
                                        selected
                                    @endif
                            >{{ $i + 1 }} {{ __('strings.lb_weeks') }}</option>
                        @endfor
                    @endif
                </select>
            </div>

        </div>

        <div class="btn-group mt-2">
            <button class="btn btn-primary btn-sm" id="btnLoad"><i class="fa fa-arrow-alt-circle-down"></i>
                {{ __('strings.fn_load') }}
            </button>
            <button class="btn btn-outline-primary btn-sm" id="btnAllSave"><i class="fa fa-save"></i>
                {{ __('strings.fn_all_save') }}
            </button>
            <button class="btn btn-info btn-sm" id="btnAllSend"><i class="fa fa-paper-plane"></i>
                {{ __('strings.fn_all_send') }}
            </button>
        </div>

    </div>
    <div class="mt-3">
        <form name="smsFrm" id="smsFrm" method="post" action="/sendSms">
            @csrf
            <input type="hidden" name="saved_tf_id" id="saved_tf_id" value="{{ $rTfId }}"/>
            <input type="hidden" name="saved_sg_id" id="saved_sg_id" value="{{ $rGradeId }}"/>
            <table class="mt-3 table table-striped table-bordered">
                <thead>
                    @if (is_null($testForm))
                        <tr class="text-center">
                            <th scope="col">#</th>
                            <th scope="col">{{ __('strings.lb_student_name') }}</th>
                            <th scope="col">{{ __('strings.lb_school_name') }}</th>
                            <th scope="col">{{ __('strings.lb_grade_name') }}</th>
                            <th scope="col">{{ __('strings.lb_teacher_name') }}</th>
                            <th scope="col">{{ __('strings.lb_subjects') }}</th>
                            <th scope="col">{{ __('strings.lb_comment') }}</th>
                            <th scope="col">{{ __('strings.lb_btn_manage') }}</th>
                        </tr>
                    @else
                        <tr class="text-center">
                            <th scope="col" rowspan="2" class="text-center">#</th>
                            <th scope="col" rowspan="2" class="text-center">{{ __('strings.lb_student_name') }}</th>
                            <th scope="col" rowspan="2" class="text-center">{{ __('strings.lb_school_name') }}</th>
                            <th scope="col" rowspan="2" class="text-center">{{ __('strings.lb_grade_name') }}</th>
                            <th scope="col" rowspan="2" class="text-center">{{ __('strings.lb_teacher_name') }}</th>
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
                    <tr class="text-center">
                        <th scope="row">
                            <div class="form-check align-self-center">
<!--                                <input type="checkbox" name="ss_id[]" id="ss_id_{{ $data[$i]["id"] }}" value="{{ $data[$i]["id"] }}" class="form-check-input" checked/>-->
                                {{ $i + 1 }}
                            </div>
                        </th>
                        <td class="text-center">{{ $data[$i]["studentItem"]->student_name }}</td>
                        <td class="text-center">{{ $data[$i]["studentItem"]->school_name }}</td>
                        <td class="text-center">{{ $data[$i]["studentItem"]->school_grade }}</td>
                        <td class="text-center">{{ $data[$i]["studentItem"]->teacher_name }}</td>

                        @php ($num = 0)
                        @foreach ($tItems as $item)
                            @if ($item->sj_has_child == "N")
                                @php ($field_name = "score_".$num)
                                <td class="form-group">
                                        <input type="number"
                                               name="score_{{ $num }}[]" id="f_{{ $num }}_{{ $i }}_{{ $data[$i]["id"] }}" value="{{ $data[$i][$field_name] }}"
                                               max="{{ $item->sj_max_score }}" min="0"
                                               fn_group="{{ $item->sj_parent_id }}"
                                               fn_row="{{ $i }}"
                                               @if ($item->sj_type == "T")
                                                   fn_total="Y"
                                               @else
                                                   fn_total="N"
                                               @endif
                                               class="form-control form-control-sm text-center fn_input "/>
                                </td>
                                @php ($num++)
                            @endif
                        @endforeach
                        <td><input type="text" name="ss_opinion[]" id="ss_opinion_{{ $data[$i]["id"] }}" value="{{ $data[$i]["opinion"] }}" fn_op_id="{{ $data[$i]["id"] }}" class="form-control fn_opinion"/> </td>
                        <td>
                            @if ($data[$i]["sent"] == "N")
                                <div class="btn-group-sm btn-group">
                                    <a href="#" class="btn btn-primary btn-sm fn_item" fn_id="{{ $data[$i]["id"] }}" fn_item_row="{{ $i }}">
                                        <span class="fa fa-spin fa-spinner fn_fa_{{ $i }} d-none"></span> {{ __('strings.fn_save') }}</a>
                                    <a href="#" class="btn btn-outline-primary btn-sm fn_item_send" fn_id="{{ $data[$i]["id"] }}">{{ __('strings.fn_send') }}</a>
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
        //
        let hakgiData = [];
        let selInput;
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

        // ?????? ?????? ????????? ??? ?????????
        let opinionRowId;
        $(document).on("click",".fn_opinion",function (){
            let curOpinion = $(this).val();
            let curId = $(this).attr("fn_op_id");
            opinionRowId = $(this).attr("id");
            $("#infoModalCenter").modal("show");
            $("#info_id").val(curId);
            $("#info_name").val(curOpinion).focus();
        });

        // ???????????? ??????????????? ????????? ??? ?????????
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

        // ????????? ????????? ???
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

        // ???????????? ?????? ?????? ???
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



        // ????????? ?????? ??? ??????
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

        // ?????? ?????? ???...
        $(document).on("click",".fn_item",function (){
            //
            event.preventDefault();
            let tfId = $(this).attr("fn_id");
            let curRow = $(this).attr("fn_item_row");

            $("#del_id").val(tfId);

            let curId = $(this).attr("fn_id");
            let scoresArray = [];
            $.each($("input[type='number']"),function (i,obj){
                if ($(obj).attr("fn_row") === curRow){
                    scoresArray.push($(obj).val());
                }
            });

            let curOpinion = $("input[name='ss_opinion[]'").eq(curRow).val();

            $(".fn_fa_" + curRow).removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/saveSmsEach",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "scId":curId,
                    "scores":scoresArray.toString(),
                    "opinion":curOpinion
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
