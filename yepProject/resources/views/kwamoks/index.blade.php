@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h5>{{ __('strings.lb_test_manage') }} </h5>
    <div class="mt-3 btn-group">
        <a href="/home" class="btn btn-outline-secondary btn-sm"><i class="fa fa-home"></i> {{ __("strings.fn_home") }}</a>
        <button id="btn_add_curri" name="btn_add_curri" class="btn btn-sm btn-outline-primary"><i class="fa fa-plus"></i> {{ __('strings.lb_add_curriculum') }}</button>
        <button id="btn_add_subject" name="btn_add_subject" class="btn btn-sm btn-outline-primary"><i class="fa fa-plus"></i> {{ __('strings.lb_add_subject') }}</button>
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
            <label for="section_grades" class="form-label">{{ __('strings.lb_section_grades') }}</label>
            <select name="section_grades" id="section_grades" class="form-select ml-3">
                <option value="">{{ __('strings.fn_all') }}</option>
                @foreach($grades as $grade)
                    <option value="{{ $grade->id }}"
                            @if ($rGrade != '' && $rGrade == $grade->id)
                                selected
                            @endif
                    >{{ $grade->scg_name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mt-3">
        <table class="mt-3 table table-striped">
            <thead>
                <tr class="text-center">
                    <th scope="col">#</th>
                    <th scope="col">{{ __('strings.lb_section_grades') }}</th>
                    <th scope="col">{{ __('strings.lb_parent_subject') }}</th>
                    <th scope="col">{{ __('strings.lb_subject') }}</th>
                    <th scope="col">{{ __('strings.lb_max_score') }}</th>
                    <th scope="col">{{ __('strings.lb_children_count') }}</th>
                    <th scope="col">{{ __('strings.lb_function') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $datum)
                <tr class="text-center">
                    <th scope="row">{{ $datum->id }}</th>
                    <td>{{ is_null($datum->SchoolGrades)? '-':$datum->SchoolGrades->scg_name }}</td>
                    <td>{{ !is_null($datum->testParent) ? $datum->testParent->ta_name : "-"}}</td>
                    <td>{{ $datum->ta_name }}</td>
                    <td>{{ $datum->ta_max_score }}</td>
                    <td>{{ $datum->ta_child_count }}</td>
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
                        <label for="info_parent_id">{{ __('strings.lb_parent_subject') }}</label>
                        <select name="info_parent_id" id="info_parent_id" class="form-control">
                            <option value="">{{ __('strings.lb_no_parent') }}</option>
                        </select>
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

        $(document).on("change","#section_grades",function (){
            let curVal = $(this).val();

            if (curVal === ''){
                location.href = "/testAreas";
            }else{
                location.href = "/testAreas/" + curVal;
            }
        });

        $(document).on("click","#btn_add",function (){
            event.preventDefault();

            $("#infoModalCenter").modal("show");
            $("#taFrm").attr({"action":"/addTestArea"});
            $("#btnTaDelete").addClass("d-none");

            $("#info_name").val("");
            $("#info_parent_id").val("");
            $("#info_code").val("");

        });

        // pre code
        $(document).on("change","#info_parent_id",function (){
            let nCode = $(this).find("option:selected").data("sub");
            $("#info_code").val(nCode + "_");
        });


        $(document).on("click","#btnTaDelete",function (){
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
            $("#btnTaDelete").removeClass("d-none");
            $("#fn_loading").removeClass("d-none");
            $("#taFrm").attr({"action":"/storeTestArea"});

            let clId = $(this).attr("fn_id");
            $("#del_id").val(clId);
            $("#info_id").val(clId);

            // 여기까지 작업 중.

            $.ajax({
                type:"POST",
                url:"/testAreaJson",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "cid":clId
                },
                success:function (msg){
                    if (msg.result === "true"){
                        $("#info_parent_id").empty();
                        $.each(msg.parents,function(i,obj){
                            $("<option value='" + obj.id + "' data-sub='" + obj.ta_code + "'>" + obj.ta_name + "</option>").appendTo($("#info_parent_id"));
                        });

                        $("#info_id").val(clId);
                        $("#info_name").val(msg.data.ta_name);
                        $("#info_parent_id").val(msg.data.parent_id);
                        $("#info_code").val(msg.data.ta_code);
                        $("#info_max_score").val(msg.data.ta_max_score);
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


        $(document).on("click","#btnTaSubmit",function (){
            event.preventDefault();
            if ($("#info_name").val() === ""){
                showAlert("{{ __('strings.str_insert_subject') }}");
                return;
            }

            if ($("#info_code").val() === ""){
                showAlert("{{ __('strings.str_insert_code') }}");
                return;
            }

            if ($("#info_max_score").val() === ""){
                showAlert("{{ __('strings.str_insert_max_score') }}");
                return;
            }

            $("#taFrm").submit();
        });



        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
