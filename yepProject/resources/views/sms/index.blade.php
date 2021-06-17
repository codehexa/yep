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

            <span id="fn_class_loader" class="d-none"><i class="fa fa-spin fa-spinner"></i> </span>

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

            <label for="section_weeks" class="form-label ml-3">{{ __('strings.lb_weeks') }}</label>
            <select name="section_weeks" id="section_weeks" class="form-select ml-1">
                @for ($w = 1; $w <= 52; $w++)
                    <option value="{{ $w }}"
                            @if (($rW != '' && $rW == $w) || ($rW == "" && $w == date("W")))
                            selected
                        @endif
                    >{{ $w }}</option>
                @endfor
            </select>

            <div class="btn-group ml-2">
                <button class="btn btn-primary btn-sm" id="btnLoad"><i class="fa fa-arrow-alt-circle-down"></i>
                    {{ __('strings.fn_load') }}
                </button>
            </div>
        </div>
    </div>
    <div class="mt-3">
        <table class="mt-3 table table-striped">
            <thead>
                @if (is_null($testForm))
                    <tr class="text-center">
                        <th scope="col">#</th>
                        <th scope="col">{{ __('strings.lb_student_name') }}</th>
                        <th scope="col">{{ __('strings.lb_school_name') }}</th>
                        <th scope="col">{{ __('strings.lb_grade_name') }}</th>
<!--                        <th scope="col">{{ __('strings.lb_class_name') }}</th>-->
                        <th scope="col">{{ __('strings.lb_teacher_name') }}</th>
                        <th scope="col">{{ __('strings.lb_subjects') }}</th>
                        <th scope="col">{{ __('strings.lb_comment') }}</th>
                        <th scope="col">{{ __('strings.lb_btn_manage') }}</th>
                    </tr>
                @else
                    <tr class="text-center">
                        <th scope="col" @if ($prerow > 1)rowspan="2" @endif>#</th>
                        <th scope="col" @if ($prerow > 1)rowspan="2" @endif>{{ __('strings.lb_student_name') }}</th>
                        <th scope="col" @if ($prerow > 1)rowspan="2" @endif>{{ __('strings.lb_school_name') }}</th>
                        <th scope="col" @if ($prerow > 1)rowspan="2" @endif>{{ __('strings.lb_grade_name') }}</th>
<!--                        <th scope="col" rowspan="2">{{ __('strings.lb_class_name') }}</th>-->
                        <th scope="col" @if ($prerow > 1)rowspan="2" @endif>{{ __('strings.lb_teacher_name') }}</th>
                        @for($i=0; $i < sizeof($header0); $i++)
                            <th scope="col"
                                @if ($header0[$i]['cols'] == 0)
                                    rowspan="{{ $prerow }}"
                                @else
                                    colspan="{{ $header0[$i]['cols'] }}"
                                @endif
                            >{{ $header0[$i]['title'] }}</th>
                        @endfor
                        <th scope="col" @if ($prerow > 1)rowspan="2" @endif>{{ __('strings.lb_comment') }}</th>
                        <th scope="col" @if ($prerow > 1)rowspan="2" @endif>{{ __('strings.lb_btn_manage') }}</th>
                    </tr>
                    @if (sizeof($header1) > 0)
                        <tr>
                            @for($i=0; $i < sizeof($header1); $i++)
                                <th>{{ $header1[$i]['title'] }}</th>
                            @endfor
                        </tr>
                    @endif

                @endif
            </thead>
            <tbody>
            @foreach($data as $datum)
                <tr class="text-center">
                    <th scope="row" class="d-flex justify-content-center"><input type="checkbox" name="ss_id[]" id="ss_id_{{ $datum->id }}" value="{{ $datum->id }}" class="form-check-input" checked/> </th>
                    <td>{{ is_null($datum->Student) ? "":$datum->Student->student_name }}</td>
                    <td>{{ is_null($datum->Student) ? "":$datum->Student->school_name }}</td>
                    <td>{{ is_null($datum->Student) ? "":$datum->Student->school_grade }}</td>
<!--                    <td>{{ is_null($datum->Class) ? "":$datum->Class->class_name }}</td>-->
                    <td>{{ is_null($datum->Student) ? "":$datum->Student->teacher_name }}</td>
                    @for ($i=0; $i < sizeof($context); $i++)
                        <td>
                            @if ($context[$i]['sj_id'] == "T")
                                <input type="text" name="ss_{{ $i }}[]" id="" value="T" class="form-control in_number_field"/>
                            @else
                                <input type="hidden" name="hidden[]" value="{{ $datum->id }}_{{$i}}"/>
                                <input type="text" name="ss_{{ $i }}[]" id="" value="{{ $datum->$$fieldName.$$i }}" class="form-control in_number_field"/>
                            @endif

                        </td>
                    @endfor
                    <td><input type="text" name="ss_opinion[]" id="ss_opinion_{{ $datum->id }}" value="{{ $datum->opinion }}" class="form-control"/> </td>
                    <td>
                        @if ($datum->sent == "N")
                            <a href="#" class="btn btn-primary btn-sm fn_item" fn_id="{{ $datum->id }}">{{ __('strings.lb_save_and_send') }}</a>
                        @else
                            <span class="btn btn-secondary btn-sm">{{ __('strings.lb_sent') }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{--@if (sizeof($data) <= 0)
            <div class="text-secondary">{{ __('strings.str_there_is_no_data')}}</div>
        @endif--}}
    </div>

</div>

<div class="modal fade" id="infoModalCenter" tabindex="-1" role="dialog" aria-labelledby="infoModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLongTitle">{{ __('strings.lb_test_form_info') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="tfFrm" id="tfFrm" method="post" action="/storeTestForm">
                    @csrf
                    <input type="hidden" name="info_id" id="info_id"/>
                    <input type="hidden" name="up_ac_id" id="up_ac_id"/>
                    <input type="hidden" name="up_grade_id" id="up_grade_id"/>
                    <input type="hidden" name="up_cl_id" id="up_cl_id"/>
                    <div class="form-group">
                        <label for="info_name">{{ __('strings.lb_test_title') }}</label>
                        <input type="text" name="info_name" id="info_name" placeholder="{{ __('strings.str_insert_form_title') }}" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="info_count">{{ __('strings.lb_test_count') }}</label>
                        <input type="text" name="info_count" id="info_count" value="0" readonly class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="info_desc">{{ __('strings.lb_test_desc') }}</label>
                        <input type="text" name="info_desc" id="info_desc" placeholder="{{ __('strings.str_insert_form_desc') }}" class="form-control"/>
                    </div>
                    <div class="mt-3 d-flex justify-content-center">
                        <div class="btn-group">
                            <button class="btn btn-sm btn-primary" id="btnToLeft"><i class="fa fa-arrow-circle-left"></i> </button>
                            <button class="btn btn-sm btn-danger" id="btnToRight"><i class="fa fa-arrow-circle-right"></i> </button>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <div class="form-group border rounded w-100 p-1 mr-1">
                            <label for="tf_has_items">{{ __('strings.lb_has_items') }} </label>
                            <div class="list-group overflow-auto" style="height: 10rem" id="tf_has_items"></div>
                        </div>
                        <div class="form-group border rounded w-100 p-1 ml-1">
                            <label for="tf_all_items">{{ __('strings.lb_all_items') }} (<span id="lb_all_items" class="text-primary">0</span>)</label>
                            <div class="list-group overflow-auto" style="height: 10rem" id="tf_all_items"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <i id="fn_loading" class="fa fa-spin fa-spinner mr-3 d-none"></i>
                <button type="button" class="btn btn-primary" id="btnTfSubmit" ><i class="fa fa-save"></i> {{ __('strings.fn_okay') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
                <button type="button" class="btn btn-danger d-none" id="btnTfDelete"><i class="fa fa-trash"></i> {{ __('strings.fn_delete') }}</button>
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
    <script type="text/x-jquery-tmpl" id="subjectTemplate">
        <div class="list-group-item">
            <div class="form-check">
                <input type="checkbox" class="form-check-input fn_subjects" name="tfAllItems[]" id="tf_${Id}" value="${Id}" checked/>
                <label for="tf_${Id}" class="form-check-label">
                    ${Title}
                </label>
            </div>
        </div>
    </script>

    <script type="text/x-jquery-tmpl" id="savedTemplate">
        <div class="list-group-item">
            <div class="form-check">
                <input type="checkbox" class="form-check-input fn_saved" name="tfSavedItems[]" id="svtf_${Id}" value="${Id}" checked/>
                <label for="svtf_${Id}" class="form-check-label">
                    ${Title}
                </label>
            </div>
        </div>
    </script>

    <script type="text/javascript">
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
                },
                success:function (msg){
                    $("#section_class").empty();
                    $("<option value=''>{{ __('strings.fn_all') }}</option>").appendTo($("#section_class"));
                    $.each(msg.data,function (i,obj){
                        $("<option value='" + obj.id + "'>" + obj.class_name + "</option>").appendTo($("#section_class"));
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
                "/" + $("#section_forms").val() + "/" + $("#section_year").val() + "/" + $("#section_weeks").val();
        });




        // 배정시키기.
        $(document).on("click","#btnToLeft",function (){
            event.preventDefault();
            let items = $("#tf_all_items").find(".fn_subjects");
            let forDels = [];
            for (var i=0; i < items.length; i++){
                if (items.eq(i).prop("checked") === true){
                    let selObj = subjectDataSet[i];
                    if (!checkHasIndex(savedDataSet, selObj.Id)){
                        savedDataSet.push(selObj);
                    }
                    forDels.push(i);
                }
            }

            for (let j=forDels.length -1; j >= 0; j--){
                subjectDataSet.splice(j,1);
            }
            setTmpl();
        });

        // 미배정시키기
        $(document).on("click","#btnToRight",function (){
            event.preventDefault();
            let items = $("#tf_has_items").find(".fn_saved");
            let forDels = [];
            for (var i=0; i < items.length; i++){
                if (items.eq(i).prop("checked") === true){
                    let selObj = savedDataSet[i];
                    if(!checkHasIndex(subjectDataSet,selObj.Id)){
                        subjectDataSet.push(selObj);

                    }
                    forDels.push(i);
                }
            }

            for (let j=forDels.length -1; j >= 0; j--){
                savedDataSet.splice(j,1);
            }

            setTmpl();
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

            $("#del_id").val(tfId);

            $("#infoModalCenter").modal("show");

            $("#fn_loading").removeClass("d-none");
            $("#btnTfDelete").removeClass("d-none");
            $.ajax({
                type:"POST",
                url:"/getTestFormJson",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "tfId":tfId
                },
                success:function (msg){
                    //
                    $("#info_id").val(tfId);
                    if (msg.result === "true"){
                        $("#info_name").val(msg.tfData.form_title);
                        $("#info_count").val(msg.tfData.subjects_count);
                        $("#info_desc").val(msg.tfData.tf_desc);

                        $("#tf_has_items").empty();
                        $("#tf_all_items").empty();
                        subjectDataSet = [];    // initial
                        savedDataSet = [];  // initail

                        if (msg.tfData.ac_id === 0){
                            $("#section_academy")[0].selectedIndex = 0;
                        }else{
                            $("#section_academy").val(msg.tfData.ac_id);
                        }

                        if (msg.tfData.grade_id === 0){
                            $("#section_grade")[0].selectedIndex = 0;
                        }else{
                            $("#section_grade").val(msg.tfData.grade_id);
                        }

                        if (msg.tfData.class_id === 0){
                            $("#section_class")[0].selectedIndex = 0;
                        }else{
                            $("#section_class").val(msg.tfData.class_id);
                        }

                        $.each(msg.tfItems,function (i,obj){
                            savedDataSet.push({"Id":obj.id,"Title":obj.title});
                        });

                        loadSubjects();

                        $("#fn_loading").addClass("d-none");
                    }else{
                        showAlert("{{ __('strings.err_get_info') }}");
                        $("#infoModalCenter").modal("hide");
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
