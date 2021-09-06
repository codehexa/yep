@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h5>{{ __('strings.lb_test_manage') }} </h5>
    <div class="mt-3 btn-group">
        <a href="/home" class="btn btn-outline-secondary btn-sm"><i class="fa fa-home"></i> {{ __("strings.fn_home") }}</a>
        <button id="btn_add" name="btn_add" class="btn btn-sm btn-outline-primary"><i class="fa fa-plus"></i> {{ __('strings.lb_add_subject') }}</button>
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

    <div class="mt-3">
        <h6 class="text-info">{{ __('strings.str_must_make_total') }}</h6>
    </div>

    <div class="mt-3 form-group">
        <div class="form-inline">
            <label for="section_grades" class="form-label">{{ __('strings.lb_section_grades') }}</label>
            <select name="section_grades" id="section_grades" class="form-select ml-3 form-control">
                <option value="">{{ __('strings.fn_all') }}</option>
                @foreach($grades as $grade)
                    <option value="{{ $grade->id }}"
                            @if ($rGrade != '' && $rGrade == $grade->id)
                                selected
                            @endif
                    >{{ $grade->scg_name }}</option>
                @endforeach
            </select>
            <span class="d-none ml-2" id="selectLoader"><i class="fa fa-spin fa-spinner"></i> </span>
        </div>
    </div>

    <div class="mt-3">
        <ul id="subjects" class="nav flex-column">
            @foreach ($data as $datum)
                <li class="nav nav-pills flex-column mb-2 my-1" id="{{ $datum->id }}">
                    <div class="d-flex justify-content-between border p-2">
                        <h5>
                            @if ($datum->sj_type == "N")
                                <i class="fa fa-cube"></i>
                            @else
                                <i class="fa fa-sign-in-alt"></i>
                            @endif
                            {{ $datum->sj_title }} ({{$datum->sj_desc}})
                        </h5>

                        <div class="d-flex">
                            <span class="badge badge-primary badge-pill align-self-center">
                                {{ $datum->sj_max_score }}</span>
                            <div class="btn-group btn-group-sm ml-2">
                                <button class="btn btn-sm btn-primary fn_add_child" fn_parent="{{ $datum->id }}"><i class="fa fa-plus"></i> Add</button>
                                <button class="btn btn-sm btn-info fn_edit_node" fn_id="{{ $datum->id }}"><i class="fa fa-edit"></i> Modify</button>
                                <button class="btn btn-sm btn-danger fn_delete_node" fn_id="{{ $datum->id }}"><i class="fa fa-trash"></i> Delete</button>
                            </div>
                        </div>
                    </div>

                    @if ($datum->has_child == "Y")
                        <ul class="nav flex-column fn_sortable">
                            @foreach($datum->children as $child)
                                <li class="nav-link ml-3 my-1 border" id="{{ $child->id }}">
                                    <div class="d-flex justify-content-between">
                                        <h6>
                                            @if ($child->sj_type == "N")
                                                <i class="fa fa-cube"></i>
                                            @else
                                                <i class="fa fa-sign-in-alt"></i>
                                            @endif
                                            {{ $child->sj_title }} ({{ $child->sj_desc }})
                                        </h6>
                                        <div class="d-flex">
                                            <span class="badge badge-primary badge-pill align-self-center ">{{ $child->sj_max_score }}</span>
                                            <div class="btn-group btn-group-sm ml-2">
                                                <button class="btn btn-sm btn-info fn_edit_node" fn_id="{{ $child->id }}"><i class="fa fa-edit"></i> Modify</button>
                                                <button class="btn btn-sm btn-danger fn_delete_node" fn_id="{{ $child->id }}"><i class="fa fa-trash"></i> Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
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
                <form name="sjFrm" id="sjFrm" method="post" action="/addSubject">
                    @csrf
                    <input type="hidden" name="info_id" id="info_id"/>
                    <input type="hidden" name="info_parent" id="info_parent"/>
                    <input type="hidden" name="info_depth" id="info_depth"/>
                    <input type="hidden" name="info_has_child" id="info_has_child"/>
                    <div class="form-group">
                        <label for="info_school_grade_id">{{ __('strings.lb_section_grades') }}</label>
                        <select name="info_school_grade_id" id="info_school_grade_id" class="form-control">
                            <option value="">{{ __('strings.lb_select') }}</option>
                            @foreach ($grades as $grade)
                                <option value="{{ $grade->id }}" >{{ $grade->scg_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="info_name">{{ __('strings.lb_subject') }}</label>
                        <input type="text" name="info_name" id="info_name" class="form-control" placeholder="{{ __('strings.str_insert_subject') }}"/>
                    </div>

                    <div class="form-group">
                        <label for="info_score">{{ __('strings.lb_max_score') }}</label>
                        <input type="text" name="info_score" id="info_score" class="form-control" placeholder="{{ __('strings.str_insert_score') }}" value="{{ $maxScore }}"/>
                    </div>

                    <div class="form-group">
                        <label for="info_desc">{{ __('strings.lb_subject_desc') }}</label>
                        <input type="text" name="info_desc" id="info_desc" class="form-control" placeholder="{{ __('strings.str_insert_subject_desc') }}"/>
                    </div>

                    <div class="form-group">
                        <label for="info_type">{{ __('strings.lb_total_type') }}</label>
                        <select name="info_type" id="info_type" class="form-control">
                            <option value="N">{{ __('strings.lb_normal_subject') }}</option>
                            <option value="T">{{ __('strings.lb_total_subject') }}</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <i id="fn_loading" class="fa fa-spin fa-spinner mr-3 d-none"></i>
                <button type="button" class="btn btn-primary" id="btnSjSubmit" ><i class="fa fa-save"></i> {{ __('strings.fn_okay') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
                <button type="button" class="btn btn-danger d-none" id="btnSjDelete"><i class="fa fa-trash"></i> {{ __('strings.fn_delete') }}</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="groupModalCenter" tabindex="-1" role="dialog" aria-labelledby="groupModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="groupModalLongTitle">{{ __('strings.lb_add_curriculum') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="groupFrm" id="groupFrm" method="post" action="">
                    @csrf
                    <div class="form-group">
                        <label for="up_group_title" class="form-label">{{ __('strings.lb_add_curriculum') }}</label>
                        <input type="text" name="up_group_title" id="up_group_title" class="form-control" placeholder="{{ __('strings.str_insert_subject_group') }}"/>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <span id="fn_group_spin" class="d-none"><i class="fa fa-spin fa-spinner"></i> </span>
                <button type="button" class="btn btn-primary" id="btn_group_submit"><i class="fa fa-save"></i> {{ __('strings.fn_okay') }}</button>
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
                <p id="fn_confirm_body">{!! __('strings.str_confirm_delete_subjects') !!}</p>
                <form name="delFrm" id="delFrm" method="post" action="/delSubject">
                    @csrf
                    <input type="hidden" name="del_id" id="del_id"/>
                </form>
            </div>
            <div class="modal-footer">
                <span id="fn_loading_del" class="d-none"><i class="fa fa-spinner fa-spin"></i> </span>
                <button type="button" class="btn btn-primary" id="btnDeleteDo"><i class="fa fa-check-circle"></i> {{ __('strings.fn_okay') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="loadingModalCenter" tabindex="-1" role="dialog" aria-labelledby="loadingModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loadingModalLongTitle">{{ __('strings.lb_loading_update') }}</h5>
            </div>
            <div class="modal-body">
                <div class="align-self-center"> <i class="fa fa-spin fa-spinner"></i> {{ __('strings.str_waiting_to_update') }}</div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script type="text/javascript">

        $(document).ready(function (){
            $("#subjects, .fn_sortable").sortable({
                cursor:"move",
                update:function(event, ui){
                    let nowOrder = $(this).sortable("toArray").toString();
                    //console.log("update ... order :" + nowOrder);
                    updateOrderNow(nowOrder);
                    $("#loadingModalCenter").modal("show");
                }
            });
        });

        function updateOrderNow(orders){
            $.ajax({
                type:"post",
                url:"/updateOrderSubjects",
                dataType:"JSON",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "orders":orders
                },
                success:function(msg){
                    if (msg.result === "true"){
                        $("#loadingModalCenter").modal('hide');
                    }else{
                        $("#loadingModalCenter").modal('hide');
                        showAlert("{{ __('strings.err_fail_to_update') }}");
                        return;
                    }
                },
                error:function (e1,e2,e3){
                    $("#loadingModalCenter").modal("hide");
                    showAlert(e2);
                    return;
                }
            })
        }

        $(document).on("change","#section_grades",function (){
            let curVal = $(this).val();

            $("#selectLoader").removeClass("d-none");

            if (curVal === ''){
                location.href = "/subjects";
            }else{
                location.href = "/subjects/" + curVal;
            }
        });

        $(document).on("click","#btn_add",function (){
            event.preventDefault();

            $("#infoModalCenter").modal("show");
            $("#sjFrm").attr({"action":"/addSubject"});
            $("#btnSjDelete").addClass("d-none");

            $("#info_school_grade_id").val($("#section_grades").val());

            $("#info_name").val("");
            $("#info_desc").val("");
            $("#info_parent").val("0");
            $("#info_depth").val("0");
            $("#info_has_child").val("N");
            $("#info_type").val("N");

        });

        // 하위 과목 만들기 클릭
        $(document).on("click",".fn_add_child",function (){
            event.preventDefault();

            let nowParentId = $(this).attr("fn_parent");

            $("#infoModalCenter").modal("show");
            $("#sjFrm").attr({"action":"/addSubject"});
            $("#btnSjDelete").addClass("d-none");

            $("#info_school_grade_id").val($("#section_grades").val());

            $("#info_name").val("");
            $("#info_desc").val("");
            $("#info_parent").val(nowParentId);
            $("#info_depth").val("1");
            $("#info_has_child").val("N");
            $("#info_type").prop("N");
        });

        // parent info edit
        $(document).on("click",".fn_edit_node",function (){
            event.preventDefault();

            $("#infoModalCenter").modal("show");
            $("#fn_loading").removeClass("d-none");

            let nowId = $(this).attr("fn_id");

            $.ajax({
                type:"POST",
                url:"/getSubjectJson",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "cid":nowId
                },
                success:function (msg){
                    //
                    $("#fn_loading").addClass("d-none");
                    $("#info_school_grade_id").val(msg.data.sg_id);
                    $("#info_name").val(msg.data.sj_title);
                    $("#info_desc").val(msg.data.sj_desc);
                    $("#info_id").val(nowId);
                    $("#info_parent").val(msg.data.parent_id);
                    $("#info_depth").val(msg.data.depth);
                    $("#info_has_child").val(msg.data.has_child);
                    $("#info_score").val(msg.data.sj_max_score);
                    $("#info_type").val(msg.data.sj_type);
                    $("#sjFrm").prop({"action":"/storeSubject"});
                },
                error:function(e1,e2,e3){
                    $("#fn_loading").addClass("d-none");
                    showAlert(e2);
                    return;
                }
            })
        });

        // delete form
        $(document).on("click",".fn_delete_node",function (){
            event.preventDefault();
            $("#confirmModalCenter").modal("show");
            let delId = $(this).attr("fn_id");

            $("#del_id").val(delId);
        });

        // 작은 버튼 안에서 클릭할 때.
        $(document).on("click","#add_subject_group_in",function (){
            event.preventDefault();

            $("#groupModalCenter").modal("show");
            $("#up_group_title").val("");
            $("#groupFrm").attr({"action":"/addSubjectGroupJson"});
        });

        // save button click
        $(document).on("click","#btn_group_submit",function (){
            if ($("#up_group_title").val() === ""){
                showAlert("{{ __('strings.str_insert_subject_group') }}");
                return;
            }

            $("#fn_group_spin").removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/addSubjectGroupJson",
                dataType:"json",
                data: $("#groupFrm").serialize(),
                success:function (msg){
                    //
                    $("#fn_group_spin").addClass("d-none");
                    if (msg.result === "false"){
                        if (msg.msg === "FAIL_TO_SAVE"){
                            showAlert("{{ __('strings.err_fail_to_save') }}");
                            return;
                        } else if (msg.msg === "FAIL_ALREADY_HAS"){
                            showAlert("{{ __('strings.err_has_already') }}");
                            return;
                        }
                    }else{
                        $("<option value='" + msg.data.id + "'>" + msg.data.curri_name + "</option>").appendTo($("#info_curri_id"));
                        $("#groupModalCenter").modal("hide");
                        $("#info_curri_id").val(msg.data.id);
                    }
                },
                error:function(e1,e2,e3){
                    $("#fn_group_spin").addClass("d-none");
                    showAlert(e2);
                }
            })
        });

        $(document).on("click","#btnSjDelete",function (){
            event.preventDefault();
            $("#confirmModalCenter").modal("show");
        });

        $(document).on("click","#btnDeleteDo",function (){
            event.preventDefault();
            $("#fn_loading_del").removeClass("d-none");
            $("#delFrm").submit();
        });

        $(document).on("click",".fn_item",function (){
            event.preventDefault();
            $("#infoModalCenter").modal("show");
            $("#btnSjDelete").removeClass("d-none");
            $("#fn_loading").removeClass("d-none");
            $("#sjFrm").attr({"action":"/storeSubject"});

            let clId = $(this).attr("fn_id");
            $("#del_id").val(clId);
            $("#info_id").val(clId);

            // 여기까지 작업 중.

            $.ajax({
                type:"POST",
                url:"/getSubjectJson",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "cid":clId
                },
                success:function (msg){
                    if (msg.result === "true"){
                        $("#info_id").val(clId);
                        $("#info_name").val(msg.data.sj_title);
                        $("#info_school_grade_id").val(msg.data.sg_id);
                        $("#info_curri_id").val(msg.data.curri_id);
                        $("#info_score").val(msg.data.sj_max_score);
                        $("#info_desc").val(msg.data.sj_desc);
                        $("#info_type").val(msg.data.sj_type);
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


        $(document).on("click","#btnSjSubmit",function (){
            event.preventDefault();
            if ($("#info_school_grade_id").val() === ""){
                showAlert("{{ __('strings.str_select_school_grade') }}");
                return;
            }

            if ($("#info_name").val() === ""){
                showAlert("{{ __('strings.str_insert_subject') }}");
                return;
            }

            if ($("#info_max_score").val() === ""){
                showAlert("{{ __('strings.str_insert_max_score') }}");
                return;
            }

            $("#fn_loading").removeClass("d-none");

            $("#sjFrm").submit();
        });

        $(document).on("click","#btn_add_curri",function (){
            event.preventDefault();
            $("#groupModalCenter").modal("show");
        });



        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
