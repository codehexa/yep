@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h5>{{ __('strings.lb_student_manage') }} </h5>
    <div class="mt-3 btn-group">
        <a href="/home" class="btn btn-outline-secondary btn-sm"><i class="fa fa-home"></i> {{ __("strings.fn_home") }}</a>
        <button id="btn_add_excel" name="btn_add_excel" class="btn btn-sm btn-primary"><i class="fa fa-file-excel"></i> {{ __('strings.lb_upload_excel') }}</button>
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
            <label for="section_class" class="form-label ml-3">{{ __('strings.lb_class_name') }}</label>
            <select name="section_class" id="section_class" class="form-select ml-1">
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
        </div>
    </div>

    <div class="mt-3">
        <table class="mt-3 table table-striped">
            <thead>
                <tr class="text-center">
                    <th scope="col">#</th>
                    <th scope="col">{{ __('strings.lb_name') }}</th>
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
                    <th scope="row">{{ $datum->id }}</th>
                    <td>{{ $datum->student_name }}</td>
                    <td>{{ $datum->student_hp }}</td>
                    <td>{{ $datum->parent_hp }}</td>
                    <td>{{ $datum->school_name }}</td>
                    <td>{{ $datum->school_grade }}</td>
                    <td>{{ $datum->abs_id }}</td>
                    <td>{{ $datum->abs_id }}</td>
                    <td><a href="#" class="btn btn-primary btn-sm fn_item" fn_id="{{ $datum->id }}">{{ __('strings.lb_btn_manage') }}</a></td>
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
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLongTitle">{{ __('strings.lb_test_area_info') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="taFrm" id="taFrm" method="post" action="/addTestArea">
                    @csrf
                    <input type="hidden" name="info_id" id="info_id"/>
                    <div class="form-group">
                        <label for="info_school_grade_id">{{ __('strings.lb_section_grades') }}</label>
                        <select name="info_school_grade_id" id="info_school_grade_id" class="form-control">
                            <option value="">{{ __('strings.lb_select') }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="info_name">{{ __('strings.lb_subject') }}</label>
                        <input type="text" name="info_name" id="info_name" class="form-control" placeholder="{{ __('strings.str_insert_subject') }}"/>
                    </div>

                    <div class="form-group">
                        <label for="info_parent_id">{{ __('strings.lb_parent_subject') }}</label>
                        <select name="info_parent_id" id="info_parent_id" class="form-control">
                            <option value="">{{ __('strings.lb_no_parent') }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="info_code">{{ __('strings.lb_test_code') }}</label>
                        <input type="text" name="info_code" id="info_code" class="form-control" placeholder="{{ __('strings.str_insert_code') }}"/>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <i id="fn_loading" class="fa fa-spin fa-spinner mr-3 d-none"></i>
                <button type="button" class="btn btn-primary" id="btnTaSubmit" ><i class="fa fa-save"></i> {{ __('strings.fn_okay') }}</button>
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

        $(document).on("click","#btn_add_excel",function (){
            event.preventDefault();
            if ($("#section_academy").val() === ""){
                showAlert("{{ __('strings.str_select_academy') }}");
                return;
            }

            if ($("#section_class").val() === ""){
                showAlert("{{ __('strings.str_select_class') }}");
                return;
            }
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


        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
