@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h5>{{ __('strings.lb_student_manage') }} </h5>
    <div class="mt-3 btn-group">
        <a href="/home" class="btn btn-outline-secondary btn-sm"><i class="fa fa-home"></i> {{ __("strings.fn_home") }}</a>
        <button id="btn_add_excel" name="btn_add_excel" class="btn btn-sm btn-primary"><i class="fa fa-file-excel"></i> {{ __('strings.lb_upload_excel') }}</button>
        <a href="#" class="btn btn-secondary btn-sm"><i class="fa fa-user-slash"></i> {{ __('strings.lb_except_class') }}</a>
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
                @case ("NO_TEACHER_DATA")
                <h4 class="text-center text-danger"> {{ __('strings.no_teacher_data') }}</h4>
                @break
            @endswitch
        @endforeach
    @endif

    <div class="mt-3 form-group">
        <div class="form-inline">
            <label for="section_academy" class="form-label">{{ __('strings.lb_academy_label') }}</label>
            <select name="section_academy" id="section_academy" class="form-select ml-1 form-control form-control-sm">
                <option value="">{{ __('strings.fn_all') }}</option>
                @foreach ($academies as $academy)
                    <option value="{{ $academy->id }}"
                    @if ($rAcId != '' && $rAcId == $academy->id)
                        selected
                    @endif
                    >{{ $academy->ac_name }}</option>
                @endforeach
            </select>
            <i class="fa fa-spinner fa-spin d-none ml-1" id="cls_loader"></i>
            <label for="section_class" class="form-label ml-3">{{ __('strings.lb_class_name') }}</label>
            <select name="section_class" id="section_class" class="form-select ml-1 form-control form-control-sm">
                <option value="">{{ __('strings.fn_all') }}</option>
                @foreach ($classes as $class)
                    <option value="{{ $class->id }}"
                            @if ($rClsId != '' && $rClsId == $class->id)
                            selected
                        @endif
                    >{{ $class->class_name }}</option>
                @endforeach
            </select>

            <button class="btn btn-primary btn-sm ml-2" id="btnLoad"><i class="fa fa-arrow-alt-circle-down"></i>
                {{ __('strings.fn_load') }}
            </button>

            <label class="ml-4">{{ __('strings.lb_search_condition') }}</label>
            <select name="up_search_field" id="up_search_field" class="form-control form-control-sm ml-2">
                <option value="student_name"
                        @if (isset($field) && $field == "name")
                            selected
                        @endif
                >{{ __('strings.lb_name') }}</option>
                <option value="parent_hp"
                        @if (isset($field) && $field == "parent_hp")
                            selected
                        @endif
                >{{ __('strings.lb_parent_hp') }}</option>
            </select>
            <input type="text" name="up_search_key" id="up_search_key" class="form-control form-control-sm ml-2" value="{{ $key ?? '' }}"/>
            <button class="btn btn-primary btn-sm ml-2" id="btnSearch"><i class="fa fa-search"></i>
                {{ __('strings.fn_search') }}
            </button>
        </div>
    </div>

    <div class="mt-3">
        <table class="mt-3 table table-striped">
            <thead>
                <tr class="text-center">
                    <th scope="col">{{ __('strings.lb_name') }}</th>
                    <th scope="col">{{ __('strings.lb_student_tel') }}</th>
                    <th scope="col">{{ __('strings.lb_student_hp') }}</th>
                    <th scope="col">{{ __('strings.lb_parent_hp') }}</th>
                    <th scope="col">{{ __('strings.lb_school_name') }}</th>
                    <th scope="col">{{ __('strings.lb_school_grade') }}</th>
                    <th scope="col">{{ __('strings.lb_class_name') }}</th>
                    <th scope="col">{{ __('strings.lb_teacher_name') }}</th>
                    <th scope="col">{{ __('strings.lb_function') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $datum)
                <tr class="text-center">
                    <th scope="row">{{ $datum->student_name }}</th>
                    <td>{{ $datum->student_tel }}</td>
                    <td>{{ $datum->student_hp }}</td>
                    <td>{{ $datum->parent_hp }}</td>
                    <td>{{ $datum->school_name }}</td>
                    <td>{{ $datum->school_grade }}</td>
                    <td>@if (is_null($datum->ClassObj))
                            <span class="text-danger">{{ ('미지정')  }}</span>
                        @else
                            {{ $datum->ClassObj->class_name }}
                        @endif
                    </td>
                    <td>{{ $datum->teacher_name }}</td>
                    <td><a href="#" class="btn btn-primary btn-sm fn_item" fn_id="{{ $datum->id }}">{{ __('strings.lb_btn_manage') }}</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        {{--@if (sizeof($data) <= 0)
            <div class="text-secondary">{{ __('strings.str_there_is_no_data')}}</div>
        @endif--}}
    </div>

    {{ $data->links('pagination::bootstrap-4') }}

</div>

<div class="modal fade" id="infoModalCenter" tabindex="-1" role="dialog" aria-labelledby="infoModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLongTitle">{{ __('strings.lb_student_info') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="stFrm" id="stFrm" method="post" action="/storeStudent">
                    @csrf
                    <input type="hidden" name="tmp_ac_id" id="tmp_ac_id"/>
                    <input type="hidden" name="tmp_cl_id" id="tmp_cl_id"/>
                    <input type="hidden" name="info_id" id="info_id"/>
                    <div class="form-group">
                        <label for="info_name">{{ __('strings.lb_student_name') }}</label>
                        <input type="text" name="info_name" id="info_name" placeholder="{{ __('strings.str_insert_student_name') }}" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="info_tel">{{ __('strings.lb_tel') }}</label>
                        <input type="text" name="info_tel" id="info_tel" placeholder="{{ __('strings.str_insert_tel') }}" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="info_hp">{{ __('strings.lb_hp') }}</label>
                        <input type="text" name="info_hp" id="info_hp" placeholder="{{ __('strings.str_insert_hp') }}" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="info_parent_hp">{{ __('strings.lb_parent_hp') }}</label>
                        <input type="text" name="info_parent_hp" id="info_parent_hp" placeholder="{{ __('strings.str_insert_parent_hp') }}" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="info_school_name">{{ __('strings.lb_school_name') }}</label>
                        <input type="text" name="info_school_name" id="info_school_name" placeholder="{{ __('strings.str_insert_school_name') }}" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="info_school_grade">{{ __('strings.lb_school_grade') }}</label>
                        <input type="text" name="info_school_grade" id="info_school_grade" placeholder="{{ __('strings.str_insert_school_grade') }}" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="info_abs_id">{{ __('strings.lb_abs_id') }}</label>
                        <input type="text" name="info_abs_id" id="info_abs_id" placeholder="{{ __('strings.str_insert_abs_id') }}" class="form-control" readonly/>
                    </div>
                    <div class="form-group">
                        <label for="info_class_id">{{ __('strings.lb_class_name') }}</label>
                        <select name="info_class_id" id="info_class_id" class="form-control">
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}"
                                        @if ($rClsId != '' && $rClsId == $class->id)
                                        selected
                                    @endif
                                >{{ $class->class_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="info_teacher_name">{{ __('strings.lb_teacher_name') }}</label>
                        <input type="text" name="info_teacher_name" id="info_teacher_name" placeholder="{{ __('strings.str_insert_teacher_name') }}" class="form-control"/>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <i id="fn_loading" class="fa fa-spin fa-spinner mr-3 d-none"></i>
                <button type="button" class="btn btn-primary" id="btnStSubmit" ><i class="fa fa-save"></i> {{ __('strings.fn_okay') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
                <button type="button" class="btn btn-danger d-none" id="btnTaDelete"><i class="fa fa-trash"></i> {{ __('strings.fn_delete') }}</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="uploadModalCenter" tabindex="-1" role="dialog" aria-labelledby="uploadModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLongTitle">{{ __('strings.fn_upload') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="excelFrm" id="excelFrm" method="post" enctype="multipart/form-data" action="/excelFileUpload">
                    @csrf
                    <input type="hidden" name="up_ac_id" id="up_ac_id"/>
                    <input type="hidden" name="up_cl_id" id="up_cl_id"/>
                    <div class="form-group">
                        <label for="up_file_name" class="form-label">{{ __('strings.lb_excel_file') }}</label>
                        <input type="file" name="up_file_name" id="up_file_name" class="form-control-file form-control"/>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <span id="upload_spin" class="d-none"><i class="fa fa-spinner fa-spin"></i> </span>
                <button type="button" class="btn btn-primary" id="btn_upload" ><i class="fa fa-save"></i> {{ __('strings.fn_okay') }}</button>
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
        // 관 선택을 변경했을 때 이벤트
        $(document).on("change","#section_academy",function (){
            //
            if ($(this).val() !== ""){
                $("#cls_loader").removeClass("d-none");
                let acId = $(this).val();
                $.ajax({
                    type:"POST",
                    url:"/getClassesJsonInStudent",
                    dataType:"json",
                    data:{
                        "_token":$("input[name='_token']").val(),
                        "acId":acId
                    },
                    success:function (msg){
                        $("#section_class").empty();
                        $("<option value=''>{{ __('strings.fn_select_item') }}</option>").appendTo($("#section_class"));
                        $.each(msg.data,function(i,obj){
                            $("<option value='" + obj.id + "'>" + obj.class_name + "</option>").appendTo($("#section_class"));
                        });
                        $("#cls_loader").addClass("d-none");
                    }
                })
            }
        });

        // 검색
        $(document).on("click","#btnSearch",function (){
            searchData();
        });

        $(document).on("keypress","#up_search_key",function (){
            if (event.keyCode === 13){
                searchData();
            }
        });

        function searchData(){
            if ($("#up_search_key").val() === ""){
                showAlert("{{ __('strings.str_insert_key_value') }}");
                return;
            }

            location.href = "/studentsSearch/" + $("#up_search_field").val() + "/" + $("#up_search_key").val();
        }

        $(document).on("click","#btn_add_excel",function (){
            event.preventDefault();
            /*if ($("#section_academy").val() === ""){
                showAlert("{{ __('strings.str_select_academy') }}");
                return;
            }

            if ($("#section_class").val() === ""){
                showAlert("{{ __('strings.str_select_class') }}");
                return;
            }*/
            $("#uploadModalCenter").modal("show");
            $("#up_file_name").val("");
        });

        $(document).on("click","#btn_upload",function (){
            //
            if ($("#up_file_name").val() === ""){
                showAlert("{{ __('strings.str_select_excel_file') }}");
                return;
            }

            $("#up_ac_id").val($("#section_academy").val());
            $("#up_cl_id").val($("#section_class").val());

            $("#upload_spin").removeClass("d-none");
            $("#excelFrm").submit();
        });

        // 불러오기
        $(document).on("click","#btnLoad",function (){
            event.preventDefault();
            let acId = $("#section_academy").val();
            let clId = $("#section_class").val();

            if (acId !== "" && clId !== ""){
                location.href = "/students/" + acId + "/" + clId;
            }else if (acId !== "" && clId === ""){
                location.href = "/students/" + acId;
            }else {
                location.href = "/students";
            }
        });

        // 관리 클릭 시...
        $(document).on("click",".fn_item",function (){
            //
            event.preventDefault();
            let studentId = $(this).attr("fn_id");

            $("#tmp_ac_id").val($("#section_academy").val());
            $("#tmp_cl_id").val($("#section_class").val());

            $("#infoModalCenter").modal("show");

            $("#fn_loading").removeClass("d-none");
            $.ajax({
                type:"POST",
                url:"/getStudentInfoJson",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "stId":studentId
                },
                success:function (msg){
                    //
                    $("#info_id").val(studentId);
                    if (msg.result === "true"){
                        $("#info_name").val(msg.data.student_name);
                        $("#info_tel").val(msg.data.student_tel);
                        $("#info_hp").val(msg.data.student_hp);
                        $("#info_parent_hp").val(msg.data.parent_hp);
                        $("#info_school_name").val(msg.data.school_name);
                        $("#info_school_grade").val(msg.data.school_grade);
                        $("#info_abs_id").val(msg.data.abs_id);
                        $("#info_class_id").val(msg.data.class_id);
                        $("#info_teacher_name").val(msg.data.teacher_name);

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


        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
