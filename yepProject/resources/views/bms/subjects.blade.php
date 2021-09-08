@extends('layouts.app_bms')

@section('content')
<div class="container-fluid">
    <h5><i class="fa fa-wind"></i> {{ __('strings.lb_bms_title') }} &gt; {{ __('strings.lb_subject_manage') }}</h5>

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

    <div class="mt-3 form-group form-inline">
        <label for="up_sgrade_id">{{ __('strings.lb_grade_name') }} </label>
        <select name="up_sgrade_id" id="up_sgrade_id" class="form-control form-control-sm ml-2">
            <option value="">{{ __('strings.fn_select_item') }}</option>
            @foreach($sgrades as $sgrade)
                <option value="{{ $sgrade->id }}"
                @if ($sgrade->id == $rsgid)
                    selected
                @endif
                >{{ $sgrade->scg_name }}</option>
            @endforeach
        </select>

        <button id="btnSearch" class="btn btn-sm btn-primary ml-2"><i class="fa fa-search"></i> {{ __('strings.fn_select') }}</button>
        <button id="btnNew" class="btn btn-sm btn-primary ml-2"><i class="fa fa-plus"></i> {{ __('strings.fn_add') }}</button>
        <button id="btnSaveOrder" class="btn btn-sm btn-info ml-2"><i class="fa fa-sort"></i> {{ __('strings.fn_sort_save') }}</button>
        <span class="d-none mt-2 ml-3" id="loadSort"><i class="fa fa-spin fa-spinner"></i></span>
    </div>

    <div class="mt-3 list-group" id="listItems">
        @foreach($data as $datum)
            <div class="list-group-item d-flex justify-content-between">
                <input type="hidden" name="up_sj_id[]" value="{{ $datum->id }}"/>
                <span class="text-nowrap mr-2">{{ $datum->SchoolGradeObj->scg_name }}</span>
                <input type="text" name="up_subject_title[]" class="form-control form-control-sm fn_item_title mr-2" value="{{ $datum->subject_title }}"/>
                <select name="up_subject_function[]" class="form-control form-control-sm w-25 fn_item_function">
                    @foreach($sfunction as $sf)
                        <option value="{{ $sf->value }}"
                        @if ($sf->value == $datum->subject_function)
                            selected
                        @endif
                        >{{ $sf->title }}</option>
                    @endforeach
                </select>
                <div class="btn-group ml-2">
                    <button class="btn btn-primary btn-sm fn_save text-nowrap" data-id="{{ $datum->id }}"><i class="fa fa-save"></i> {{ __('strings.fn_save') }} </button>
                    <button class="btn btn-danger btn-sm fn_delete text-nowrap" data-id="{{ $datum->id }}"><i class="fa fa-trash"></i> {{ __('strings.fn_delete') }} </button>
                </div>
            </div>
        @endforeach
    </div>

</div>

<div class="modal fade" id="infoModalCenter" tabindex="-1" role="dialog" aria-labelledby="infoModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLongTitle">{{ __('strings.lb_subject_info') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="subjectFrm" id="subjectFrm" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="up_sg_id">{{ __('strings.lb_grade_name') }}</label>
                        <select name="up_sg_id" id="up_sg_id" class="form-control form-control-sm">
                            <option value="">{{ __('strings.fn_select_item') }}</option>
                            @foreach($sgrades as $sgrade)
                                <option value="{{ $sgrade->id }}">{{ $sgrade->scg_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mt-2">
                        <label for="up_sj_title">{{ __('strings.lb_subject') }}</label>
                        <input type="text" name="up_sj_title" id="up_sj_title" class="form-control form-control-sm"/>
                    </div>
                    <div class="form-group mt-2">
                        <label for="up_sj_function">{{ __('strings.lb_function') }}</label>
                        <select name="up_sj_function" id="up_sj_function" class="form-control form-control-sm">
                            <option value="">{{ __('strings.fn_select_item') }}</option>
                            @foreach($sfunction as $sf)
                                <option value="{{ $sf->value }}">{{ $sf->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <i id="fn_loading" class="fa fa-spin fa-spinner mr-3 d-none"></i>
                <button type="button" class="btn btn-primary" id="btnSjSubmit" ><i class="fa fa-save"></i> {{ __('strings.fn_okay') }}</button>
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
    <script id="subjectForm" type="text/x-jquery-tmpl">
        <div class="list-group-item d-flex justify-content-between">
            <input type="hidden" name="up_sj_id[]" value="${id}"/>
            <span class="text-nowrap mr-2">${scgTitle}</span>
            <input type="text" name="up_subject_title[]" class="form-control form-control-sm fn_item_title mr-2" value="${subject_title}"/>
            <select name="up_subject_function[]" class="form-control form-control-sm w-25 fn_item_function">
                @foreach($sfunction as $sf)
                    <option value="{{ $sf->value }}"
                        @{{if subject_function == {!! $sf->value !!} }}
                            selected
                        @{{/if}}
                    >{{ $sf->title }}</option>
                @endforeach
            </select>
            <div class="btn-group ml-2">
                <button class="btn btn-primary btn-sm fn_save text-nowrap" data-id="${id}"><i class="fa fa-save"></i> {{ __('strings.fn_save') }} </button>
                <button class="btn btn-danger btn-sm fn_delete text-nowrap" data-id="${id}"><i class="fa fa-trash"></i> {{ __('strings.fn_delete') }} </button>
            </div>
        </div>
    </script>

    <script type="text/javascript">
        let _index = -1;
        $(document).on("click","#btnNew",function (){
            event.preventDefault();

            $("#infoModalCenter").modal("show");
        });

        // save
        $(document).on("click","#btnSjSubmit",function (){
            event.preventDefault();
            if ($("#up_sg_id").val() === ""){
                showAlert("{{ __('strings.str_select_grades') }}");
                return;
            }

            if ($("#up_sj_title").val() === ""){
                showAlert("{{ __('strings.str_insert_class_name') }}");
                return;
            }

            if ($("#up_sj_function").val() === ""){
                showAlert("{{ __('strings.lb_select_function') }}");
                return;
            }

            $("#fn_loading").removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/bms/addSubjectJson",
                dataType:"json",
                data:$("#subjectFrm").serialize(),
                success:function(msg){
                    if (msg.result === "true"){
                        //
                        showAlert("{{ __('strings.fn_save_complete') }}");
                        $("#subjectForm").tmpl(msg.data).appendTo($("#listItems"));
                    }else{
                        //
                        showAlert("{{ __('strings.fn_save_false') }}");
                    }
                    $("#fn_loading").addClass("d-none");
                    $("#infoModalCenter").modal("hide");
                },
                error:function(e1,e2,e3){
                    showAlert(e2);
                    $("#fn_loading").addClass("d-none");
                }
            })
        });

        // delete
        $(document).on("click",".fn_delete",function (){
            _index = $(".fn_delete").index($(this));
            let _id = $(this).data("id");

            $("#del_id").val(_id);

            $("#confirmModalCenter").modal("show");
        });

        $(document).on("click","#btnDeleteDo",function (){
            $("#confirm_spin").removeClass("d-none");

            console.log("index : " + _index);

            $.ajax({
                type:"POST",
                url:"/bms/delSubjectJson",
                dataType:"json",
                data:$("#delFrm").serialize(),
                success:function(msg){
                    if(msg.result === "true"){
                        $("#confirm_spin").addClass("d-none");
                        $("#listItems").children("div").eq(_index).remove();
                        $("#confirmModalCenter").modal("hide");
                    }else{
                        showAlert("{{ __('strings.fn_delete_fail') }}");
                        $("#confirm_spin").addClass("d-none");
                        $("#confirmModalCenter").modal("hide");
                    }
                },
                error:function(e1,e2,e3){
                    showAlert(e2);
                    $("#confirm_spin").addClass("d-none");
                }
            })
        });

        // save
        $(document).on("click",".fn_save",function (){
            event.preventDefault();
            _index = $(".fn_save").index($(this));
            if ($(".fn_item_title").eq(_index).val() === ""){
                showAlert("{{ __('strings.str_insert_class_name') }}");
                return;
            }

            $.ajax({
                type:"POST",
                url:"/bms/storeSubjectJson",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "upSubject":$(".fn_item_title").eq(_index).val(),
                    "upFunction":$(".fn_item_function").eq(_index).val(),
                    "upId":$("input[name='up_sj_id[]']").eq(_index).val()
                },
                success:function(msg){
                    if (msg.result === "true"){
                        showAlert("{{ __('strings.fn_save_complete') }}");
                        return;
                    }else{
                        showAlert("{{ __('strings.fn_save_false') }}");
                        return;
                    }
                },
                error:function(e1,e2,e3){
                    showAlert(e2);
                    return;
                }
            })
        });

        // ordering
        $(document).on("click","#btnSaveOrder",function (){
            event.preventDefault();

            $("#loadSort").removeClass();

            let _indexes = [];

            $("input[name='up_sj_id[]']").each(function(i,obj){
                //
                _indexes.push($(obj).val());
            });

            $.ajax({
                type:"POST",
                url:"/bms/saveOrderSubject",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "bs_ids":_indexes.toString()
                },
                success:function(msg){
                    if (msg.result === "true"){
                        showAlert("{{ __('strings.fn_save_complete') }}");
                        $("#loadSort").addClass("d-none");
                    }else{
                        showAlert("{{ __('strings.fn_save_false') }}");
                        $("#loadSort").addClass("d-none");
                    }
                }
            })
        })

        $(document).ready(function (){
            $("#listItems").sortable();
        });

        $(document).on("click","#btnSearch",function (){
            let _val = $("#up_sgrade_id").val();
            location.href = "/bms/subjects/" + _val;
        });

        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
