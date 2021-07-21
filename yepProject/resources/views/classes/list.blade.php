@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h5>{{ __('strings.lb_ban_manage') }} </h5>
    <div class="mt-3 btn-group">
        <a href="/home" class="btn btn-outline-secondary btn-sm"><i class="fa fa-home"></i> {{ __("strings.fn_home") }}</a>
        <button id="btn_add" name="btn_add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> {{ __('strings.fn_add') }}</button>
    </div>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            @switch($error)
                @case ("FAIL_ALREADY_HAS")
                <h4 class="text-center text-danger"> {{ __('strings.err_already_has') }}</h4>
                @break
                @case ("ALREADY_HAS")
                <h4 class="text-center text-danger"> {{ __('strings.err_has_already') }}</h4>
                @break
                @case ("FAIL_TO_SAVE")
                <h4 class="text-center text-danger"> {{ __('strings.err_fail_to_save') }}</h4>
                @break
            @endswitch
        @endforeach
    @endif

    @if (\Illuminate\Support\Facades\Auth::user()->power == \App\Models\Configurations::$USER_POWER_ADMIN)
        <div class="mt-3 form-inline">
            <div class="form-group">
                <select name="sel_ac_id" id="sel_ac_id" class="form-control form-control-sm">
                    <option value="">{{ __('strings.fn_all') }}</option>
                    @foreach ($academies as $academy)
                        <option value="{{ $academy->id }}" {{ $acaid == $academy->id ? 'selected':'' }}>{{ $academy->ac_name }}</option>
                    @endforeach
                </select>
                <button name="btn_sel_academy" id="btn_sel_academy" class="btn btn-primary btn-sm ml-3"><i class="fa fa-school"></i> {{ __('strings.fn_select') }}</button>
            </div>

        </div>
    @endif

    <div class="mt-3">
        <table class="mt-3 table table-striped">
            <thead>
                <tr class="text-center">
                    <th scope="col">#</th>
                    <th scope="col">{{ __('strings.lb_academy_name') }}</th>
                    <th scope="col">{{ __('strings.lb_grade_name') }}</th>
                    <th scope="col">{{ __('strings.lb_name') }}</th>
                    <th scope="col">{{ __('strings.lb_home_teacher') }}</th>
                    <th scope="col">{{ __('strings.lb_show') }}</th>
                    <th scope="col">{{ __('strings.lb_desc') }}</th>
                    <th scope="col">{{ __('strings.lb_function') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $datum)
                <tr class="text-center">
                    <th scope="row">{{ $datum->id }}</th>
                    <td>{{ $datum->academy->ac_name }}</td>
                    <td>{{ $datum->school_grade->scg_name }}</td>
                    <td>{{ $datum->class_name }}</td>
                    <td>
                        {{ is_null($datum->teacher) ? '':$datum->teacher->name }}
                        <button class="btn btn-sm fn_set_teacher btn-outline-secondary" fn_id="{{ $datum->id }}" fn_teacher_id="{{ $datum->teacher_id }}">
                            <i class="fa fa-user-circle"></i>{{ __('strings.lb_setting_teacher') }}
                        </button>
                    </td>
                    <td>{{ $datum->show }}</td>
                    <td>{{ $datum->class_desc }}</td>
                    <td><a href="#" class="btn btn-primary btn-sm fn_item" fn_id="{{ $datum->id }}">{{ __('strings.lb_btn_manage') }}</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if (sizeof($data) <= 0)
            <div class="text-secondary">{{ __('strings.str_there_is_no_data')}}</div>
        @endif
    </div>
</div>

<div class="modal fade" id="infoModalCenter" tabindex="-1" role="dialog" aria-labelledby="infoModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLongTitle">{{ __('strings.lb_information') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="classFrm" id="classFrm" method="post" action="/addClass">
                    @csrf
                    <input type="hidden" name="up_id" id="up_id"/>
                    <div class="form-group">
                        <label for="up_ac_id">{{ __('strings.lb_academy_name') }}</label>
                        <select name="up_ac_id" id="up_ac_id" class="form-control">
                            <option value="">{{ __('strings.lb_select') }}</option>
                            @foreach($academies as $academy)
                                <option value="{{ $academy->id }}">{{ $academy->ac_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="up_scg_id">{{ __('strings.lb_grade_name') }}</label>
                        <select name="up_scg_id" id="up_scg_id" class="form-control">
                            <option value="">{{ __('strings.lb_select') }}</option>
                            @foreach($grades as $grade)
                                <option value="{{ $grade->id }}">
                                    {{ $grade->scg_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="up_name">{{ __('strings.lb_name') }}</label>
                        <input type="text" name="up_name" id="up_name" class="form-control" placeholder="{{ __('strings.str_insert_class_name') }}"/>
                    </div>
                    <div class="form-group">
                        <label for="up_show">{{ __('strings.lb_show') }}</label>
                        <select name="up_show" id="up_show" class="form-control">
                            <option value="N">N</option>
                            <option value="Y">Y</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="up_desc">{{ __('strings.lb_desc') }}</label>
                        <input type="text" name="up_desc" id="up_desc" class="form-control" placeholder="{{ __('strings.str_insert_class_desc') }}"/>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <i id="fn_loading" class="fa fa-spin fa-spinner mr-3 d-none"></i>
                <button type="button" class="btn btn-primary" id="btnClassSubmit" ><i class="fa fa-save"></i> {{ __('strings.fn_okay') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
                <button type="button" class="btn btn-danger d-none" id="btnClassDelete"><i class="fa fa-trash"></i> {{ __('strings.fn_delete') }}</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="teacherModalCenter" tabindex="-1" role="dialog" aria-labelledby="teacherModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="teacherModalLongTitle">{{ __('strings.lb_setting_teacher') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="frmSet" id="frmSet" method="post" action="/setClassTeacher">
                    @csrf
                    <input type="hidden" name="up_class_id" id="up_class_id"/>
                    <select name="up_user_id" id="up_user_id" class="form-control">
                    </select>
                </form>
            </div>
            <div class="modal-footer">
                <span id="fn_set_spinner" class="d-none"><i class="fa fa-spin fa-spinner"></i> </span>
                <button type="button" class="btn btn-primary" id="btn_set_teacher"><i class="fa fa-save"></i> {{ __('strings.fn_okay') }} </button>
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.lb_okay') }}</button>
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
                <form name="delFrm" id="delFrm" method="post" action="/delClass">
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

        $(document).on("click","#btn_sel_academy",function (){
            event.preventDefault();

            let acVal = $("#sel_ac_id").val();
            location.href = "/classes/" + acVal;
        });

        $(document).on("click","#btn_set_teacher",function (){
            event.preventDefault();

            if ($("#up_user_id").val() === ""){
                showAlert("{{ __('strings.err_select_teacher') }}");
                return;
            }
            $("#frmSet").submit();
        });

        $(document).on("click",".fn_set_teacher",function (){
            event.preventDefault();
            $("#teacherModalCenter").modal("show");

            let now_class_id = $(this).attr("fn_id");
            let old_teacher_id = $(this).attr("fn_teacher_id");

            $("#fn_set_spinner").removeClass("d-none");

            $("#up_class_id").val(now_class_id);

            $.ajax({
                type:"POST",
                url:"/getTeachersJson",
                dataType:"JSON",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "cls_id":now_class_id,
                },
                success:function(msg){
                    //
                    $("#fn_set_spinner").addClass("d-none");
                    $("#up_user_id").empty();
                    $("<option value=''>{{ __('strings.lb_select') }}</option>").appendTo($("#up_user_id"));

                    $.each(msg.data,function (i,obj){
                        if (obj.id === old_teacher_id){
                            $("<option value='" + obj.id + "' selected>" + obj.name + "</option>").appendTo($("#up_user_id"));
                        }else{
                            $("<option value='" + obj.id + "' >" + obj.name + "</option>").appendTo($("#up_user_id"));
                        }
                    });
                },
                error:function (e1,e2,e3){
                    showAlert(e2);
                    return;
                }
            })
        });

        $(document).on("click","#btn_add",function (){
            event.preventDefault();
            $("#infoModalCenter").modal("show");
            $("#classFrm").attr({"action":"/addClass"});
            $("#up_ac_id").val("");
            $("#up_scg_id").val("");
            $("#up_name").val("");
            $("#up_show").val("N");
            $("#up_desc").val("");
            $("#btnClassDelete").addClass("d-none");
        });

        $(document).on("click","#btnClassDelete",function (){
            event.preventDefault();
            $("#confirmModalCenter").modal("show");
        });

        $(document).on("click","#btnDeleteDo",function (){
            event.preventDefault();
            $("#delFrm").submit();
        });

        $(document).on("click",".fn_item",function (){
            event.preventDefault();
            $("#infoModalCenter").modal("show");
            $("#btnClassDelete").removeClass("d-none");
            $("#fn_loading").removeClass("d-none");
            $("#classFrm").attr({"action":"/storeClass"});

            let clId = $(this).attr("fn_id");

            $("#del_id").val(clId);

            $.ajax({
                type:"POST",
                url:"/classInfoJson",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "cid":clId
                },
                success:function (msg){
                    if (msg.result === "true"){
                        $("#up_id").val(clId);
                        $("#up_ac_id").val(msg.data.ac_id);
                        $("#up_scg_id").val(msg.data.sg_id);
                        $("#up_name").val(msg.data.class_name);
                        $("#up_show").val(msg.data.show);
                        $("#up_desc").val(msg.data.class_desc);
                    }else{
                        showAlert("{{ __('strings.err_get_info') }}");
                        return;
                    }
                    $("#fn_loading").addClass("d-none");
                },
                error:function(e1,e2,e3){
                    showAlert(e2);
                }
            })
        });


        $(document).on("click","#btnClassSubmit",function (){
            event.preventDefault();
            if ($("#up_ac_id").val() === ""){
                showAlert("{{ __('strings.str_select_academy') }}");
                return;
            }

            if ($("#up_scg_id").val() === ""){
                showAlert("{{ __('strings.str_select_grades') }}");
                return;
            }

            if ($("#up_name").val() === ""){
                showAlert("{{ __('strings.str_insert_class_name') }}");
                return;
            }

            $("#classFrm").submit();
        });



        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
