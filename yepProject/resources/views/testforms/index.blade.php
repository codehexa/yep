@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h5>{{ __('strings.lb_test_form_manage') }} </h5>
    <div class="mt-3 btn-group">
        <a href="/home" class="btn btn-outline-secondary btn-sm"><i class="fa fa-home"></i> {{ __("strings.fn_home") }}</a>
        <button id="btn_add" name="btn_add" class="btn btn-sm btn-primary"><i class="fa fa-file-excel"></i> {{ __('strings.fn_add') }}</button>
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

            <label for="section_grade" class="form-label">{{ __('strings.lb_section_grades') }}</label>
            <select name="section_grade" id="section_grade" class="form-select ml-1 form-control form-control-sm">
                <option value="">{{ __('strings.fn_all') }}</option>
                @foreach ($schoolGrades as $schoolGrade)
                    <option value="{{ $schoolGrade->id }}"
                            @if ($rGradeId != '' && $rGradeId == $schoolGrade->id)
                            selected
                        @endif
                    >{{ $schoolGrade->scg_name }}</option>
                @endforeach
            </select>
            <button id="btnLoad" class="btn btn-primary btn-sm ml-1"><i class="fa fa-search"></i> {{ __('strings.fn_search') }}</button>
        </div>
    </div>

    <div class="mt-3">
        <table class="mt-3 table table-striped">
            <thead>
                <tr class="text-center">
                    <th scope="col">#</th>
                    <th scope="col">{{ __('strings.lb_section_grades') }}</th>
                    <th scope="col">{{ __('strings.lb_test_title') }}</th>
                    <th scope="col">{{ __('strings.lb_test_count') }}</th>
                    <th scope="col">{{ __('strings.lb_test_desc') }}</th>
                    <th scope="col">{{ __('strings.lb_function') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $datum)
                <tr class="text-center">
                    <th scope="row">{{ $datum->id }}</th>
                    <td>{{ is_null($datum->Grades) ? "":$datum->Grades->scg_name }}</td>
                    <td>{{ $datum->form_title }}</td>
                    <td>{{ $datum->items_count }}</td>
                    <td>{{ $datum->tf_desc }}</td>
                    <td><a href="#" class="btn btn-primary btn-sm fn_item" fn_id="{{ $datum->id }}">{{ __('strings.lb_btn_manage') }}</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{--@if (sizeof($data) <= 0)
            <div class="text-secondary">{{ __('strings.str_there_is_no_data')}}</div>
        @endif--}}
    </div>

    {{--{{ $data->links('pagination::bootstrap-4') }}--}}

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
                    <div class="form-check">
                        <input type="checkbox" name="info_exam" id="info_exam" class="form-check-input" value="Y"/>
                        <label for="info_exam" class="form-check-label">{{ __('strings.lb_practice_exam') }}</label>
                    </div>
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
                    <div class="mt-3 d-flex justify-content-between">
                        <input type="checkbox" id="leftCheckAll" class="btn btn-sm btn-outline-primary"/>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-primary" id="btnToLeft"><i class="fa fa-arrow-circle-left"></i> </button>
                            <button class="btn btn-sm btn-danger" id="btnToRight"><i class="fa fa-arrow-circle-right"></i> </button>
                        </div>
                        <input type="checkbox" id="rightCheckAll" class="btn btn-sm btn-outline-primary"/>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <div class="form-group border rounded w-100 p-1 mr-1">
                            <label for="tf_has_items">{{ __('strings.lb_has_items') }} </label>
                            <div class="list-group overflow-auto" style="height: 20rem" id="tf_has_items"></div>
                        </div>
                        <div class="form-group border rounded w-100 p-1 ml-1">
                            <label for="tf_all_items">{{ __('strings.lb_all_items') }} (<span id="lb_all_items" class="text-primary">0</span>)</label>
                            <div class="list-group overflow-auto" style="height: 20rem" id="tf_all_items"></div>
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
                <span id="fn_del_loader" class="d-none"><i class="fa fa-spinner fa-spin"></i> </span>
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
                    ${Title} ${Sub}
                </label>
            </div>
        </div>
    </script>

    <script type="text/x-jquery-tmpl" id="savedTemplate">
        <div class="list-group-item">
            <div class="form-check">
                <input type="checkbox" class="form-check-input fn_saved" name="tfSavedItems[]" id="svtf_${Id}" value="${Id}" checked/>
                <label for="svtf_${Id}" class="form-check-label">
                    ${Title} ${Sub}
                </label>
            </div>
        </div>
    </script>

    <script type="text/javascript">
        // check all
        $(document).on("click","#rightCheckAll",function (){
            let tf = $(this).prop("checked");
            $(".fn_subjects").each(function (i,obj){
                $(obj).prop("checked",tf);
            });
        });

        $(document).on("click","#leftCheckAll",function (){
            let tf = $(this).prop("checked");
            $(".fn_saved").each(function (i,obj){
                $(obj).prop("checked",tf);
            });
        });
        // load
        $(document).on("click","#btnLoad",function (){
            event.preventDefault();

            if ($("#section_grade").val() !== "") {
                location.href = "/testForm/" + $("#section_grade").val();
            }else{
                location.href = "/testForm";
            }
        });


        let subjectDataSet = [];
        let savedDataSet = [];

        $(document).ready(function (){
            $("#tf_has_items").sortable();
        });

        $(document).on("click","#btn_add",function (){
            event.preventDefault();
            if ($("#section_grade").val() === ""){
                showAlert("{{ __('strings.str_select_grades') }}");
                return;
            }

            subjectDataSet = [];
            savedDataSet = [];
            $("#info_id").val("");
            $("#infoModalCenter").modal("show");
            $("#info_name").val("");
            $("#info_count").val("0");
            $("#info_desc").val("");
            $("#tf_has_items").empty();
            $("#tf_all_items").empty();

            $("#fn_loading").removeClass("d-none");

            loadSubjects();
        });

        function loadSubjects(){
            let gradeId = $("#section_grade").val();
            $.ajax({
                type:"POST",
                url:"/testFormSubjectsJson",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "gradeId":gradeId
                },
                success:function (msg){
                    $("#fn_loading").addClass("d-none");
                    $.each(msg.data,function (i,obj){
                        subjectDataSet.push({"Id":obj.id,"Title":obj.sj_title,"Sub":obj.children});
                    });
                    setTmpl();
                },
                error:function (e1,e2,e3){
                    showAlert(e2);
                    $("#fn_loading").addClass("d-none");
                    return;
                }
            })
        }

        function setTmpl(){
            $("#tf_all_items,#tf_has_items").empty();
            $("#subjectTemplate").tmpl(subjectDataSet).appendTo($("#tf_all_items"));
            $("#savedTemplate").tmpl(savedDataSet).appendTo($("#tf_has_items"));
            $("#lb_all_items").html(subjectDataSet.length);
            $("#info_count").val($(".fn_saved").length);
        }

        // 배정시키기.
        $(document).on("click","#btnToLeft",function (){
            event.preventDefault();

            let items = $("#tf_all_items").find(".fn_subjects");
            let forDels = [];
            let toUpdateIds = [];   // 신규 업데이트할 대상을 담는다.
            for (let i=0; i < items.length; i++){
                if (items.eq(i).prop("checked") === true){
                    let selObj = subjectDataSet[i];
                    toUpdateIds.push(selObj.Id);
                    if (!checkHasIndex(savedDataSet, selObj.Id)){   // 과목의 아이디 값을 저장한다.
                        savedDataSet.push(selObj);
                        forDels.push(i);
                    }
                }
            }

            console.log(toUpdateIds);



            for (let j=forDels.length -1; j >= 0; j--){
                subjectDataSet.splice(forDels[j],1);
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
                savedDataSet.splice(forDels[j],1);
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

            if ($("#up_grade_id").val() === ""){
                $("#up_grade_id").val($("#section_grade").val());
            }

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
                        $("#up_grade_id").val(msg.tfData.grade_id);
                        if (msg.tfData.exam === "Y"){
                            $("#info_exam").prop("checked",true);
                        }else{
                            $("#info_exam").prop("checked",false);
                        }

                        $("#tf_has_items").empty();
                        $("#tf_all_items").empty();
                        subjectDataSet = [];    // initial
                        savedDataSet = [];  // initail


                        if (msg.tfData.grade_id === 0){
                            $("#section_grade")[0].selectedIndex = 0;
                        }else{
                            $("#section_grade").val(msg.tfData.grade_id);
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
            $("#fn_del_loader").removeClass("d-none");
            $("#delFrm").submit();
        });


        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
