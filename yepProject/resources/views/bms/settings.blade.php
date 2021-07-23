@extends('layouts.app_bms')

@section('content')
<div class="container-fluid">
    <h5><i class="fa fa-wind"></i> {{ __('strings.lb_bms_title') }} &gt; {{ __('strings.lb_bms_setting') }}</h5>

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

    <div class="mt-3">

        <h6>{{ __('strings.lb_study') }}</h6>
        <div class="row">
            <div class="col-sm-6">
                <div class="list-group" id="ls_studies">
                    @foreach ($bmsStudyTypes as $bmsStudyType)
                        <div class="list-group-item d-flex justify-content-between">
                            <input type="hidden" name="stype_ids[]" value="{{ $bmsStudyType->id }}"/>
                            <input type="text" name="up_stype_title[]" class="form-control" value="{{ $bmsStudyType->study_title }}"/>
                            <button class="btn btn-sm btn-outline-info text-nowrap fn_modify ml-2" fn_id="{{ $bmsStudyType->id }}">
                                <i class="fa fa-edit"></i> {{ __('strings.fn_modify') }}
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="form-group row mt-1">
            <div class="col-sm-6">
                <input type="text" name="up_study_type" id="up_study_type" class="form-control form-control-sm"/>
            </div>
            <i class="fa fa-spin fa-spinner d-none mr-2" id="fn_spinner_type"></i>
            <div class="btn-group">
                <a href="#" id="btnAddSt" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus"></i> {{ __('strings.fn_add') }}</a>
                <a href="#" id="btnSaveStSort" class="btn btn-outline-secondary btn-sm"><i class="fa fa-save"></i> {{ __('strings.fn_sort_save') }}</a>
            </div>
        </div>

        <!-- 수업 요일 -->

        <h6 class="mt-3">{{ __('strings.lb_study_days') }}</h6>
        <div class="row">
            <div class="col-sm-6">
                <div class="list-group" id="ls_days">
                    @foreach ($bmsStudyDays as $bmsStudyDay)
                        <div class="list-group-item d-flex justify-content-between">
                            <input type="hidden" name="sday_ids[]" value="{{ $bmsStudyDay->id }}"/>
                            <input type="text" name="up_day_title[]" class="form-control" value="{{ $bmsStudyDay->days_title }}"/>
                            <button class="btn btn-sm btn-outline-info text-nowrap fn_day_modify ml-2" fn_id="{{ $bmsStudyDay->id }}">
                                <i class="fa fa-edit"></i> {{ __('strings.fn_modify') }}
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="form-group row mt-1">
            <div class="col-sm-6">
                <input type="text" name="up_study_day" id="up_study_day" class="form-control form-control-sm"/>
            </div>
            <i class="fa fa-spin fa-spinner d-none mr-2 mt-1" id="fn_spinner_day"></i>
            <a href="#" id="btnAddDay" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus"></i> {{ __('strings.fn_add') }}</a>
            <a href="#" id="btnSaveDaySort" class="btn btn-outline-secondary btn-sm"><i class="fa fa-save"></i> {{ __('strings.fn_sort_save') }}</a>
        </div>

        <!-- 수업 시간 -->

        <h6 class="mt-3">{{ __('strings.lb_study_times') }}</h6>
        <div class="row">
            <div class="col-sm-6">
                <div class="list-group" id="ls_times">
                    @foreach ($bmsStudyTimes as $bmsStudyTime)
                        <div class="list-group-item d-flex justify-content-between">
                            <input type="hidden" name="stime_ids[]" value="{{ $bmsStudyTime->id }}"/>
                            <input type="text" name="up_time_title[]" class="form-control" value="{{ $bmsStudyTime->time_title }}"/>
                            <button class="btn btn-sm btn-outline-info text-nowrap fn_time_modify ml-2" fn_id="{{ $bmsStudyTime->id }}">
                                <i class="fa fa-edit"></i> {{ __('strings.fn_modify') }}
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="form-group row mt-1">
            <div class="col-sm-6">
                <input type="text" name="up_study_time" id="up_study_time" class="form-control form-control-sm"/>
            </div>
            <i class="fa fa-spin fa-spinner d-none mr-2 mt-1" id="fn_spinner_time"></i>
            <a href="#" id="btnAddTime" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus"></i> {{ __('strings.fn_add') }}</a>
            <a href="#" id="btnSaveTimeSort" class="btn btn-outline-secondary btn-sm"><i class="fa fa-save"></i> {{ __('strings.fn_sort_save') }}</a>
        </div>
    </div>

</div>

<div class="modal fade" id="infoModalCenter" tabindex="-1" role="dialog" aria-labelledby="infoModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLongTitle">{{ __('strings.lb_comment_setting') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="cmFrm" id="cmFrm" method="post" action="/setComments">
                    @csrf
                    <input type="hidden" name="cm_id" id="cm_id" value=""/>

                    <div class="form-group">
                        <label for="up_min_score">{{ __('strings.lb_min_score_title') }}</label>
                        <input type="number" name="up_min_score" id="up_min_score" class="form-control" placeholder="{{ __('strings.lb_insert_min_score') }}"/>
                    </div>
                    <div class="form-group">
                        <label for="up_min_score">{{ __('strings.lb_max_score_title') }}</label>
                        <input type="number" name="up_max_score" id="up_max_score" class="form-control" placeholder="{{ __('strings.lb_insert_max_score') }}"/>
                    </div>
                    <div class="form-group">
                        <label for="up_min_score">{{ __('strings.lb_comment_context') }}</label>
                        <textarea name="up_comments" id="up_comments" class="form-control" placeholder="{{ __('strings.lb_insert_comments') }}"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <i id="fn_loading" class="fa fa-spin fa-spinner mr-3 d-none"></i>
                <button type="button" class="btn btn-primary" id="btnCmSubmit" ><i class="fa fa-save"></i> {{ __('strings.fn_okay') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
                <button type="button" class="btn btn-danger d-none" id="btnDelete"><i class="fa fa-trash"></i> {{ __('strings.fn_delete') }}</button>
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
    <script type="text/javascript">
        // 수업 시간
        let _studyTimes = [
            @foreach($bmsStudyTimes as $bmsStudyTime)
            {"id":{{$bmsStudyTime->id}},"title":"{{ $bmsStudyTime->time_title }}","index":{{ $bmsStudyTime->time_index }}},
            @endforeach
        ];

        $(document).on("click","#btnAddTime",function (){
            event.preventDefault();

            if ($("#up_study_time").val() === "")return;

            $("#fn_spinner_time").removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/bms/addStudyTimeJs",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "upTxt":$("#up_study_time").val()
                },
                success:function(msg){
                    if (msg.result === "true"){
                        _studyTimes.push({"id":msg.data.id,"title":msg.data.time_title,"index":msg.data.time_index});
                        reloadPrint("sTime");
                        $("#fn_spinner_time").addClass("d-none");
                        $("#up_study_time").val("");
                        showAlert("{{ __('strings.fn_save_complete') }}");
                    }else{
                        $("#fn_spinner_time").addClass("d-none");
                        showAlert("{{ __('strings.err_fail_to_save') }}");
                    }
                }
            })
        });

        // edit
        $(document).on("click",".fn_time_modify",function (){
            //
            event.preventDefault();
            let nowId = $(this).attr("fn_id");
            let nowTxt = $(this).parent().find("input[name='up_time_title[]']").val();

            $.ajax({
                type:"POST",
                url:"/bms/saveStudyTimeJs",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "upId":nowId,
                    "upTxt":nowTxt
                },
                success:function(msg){
                    if (msg.result === "true"){
                        showAlert("{{ __('strings.fn_save_complete') }}");
                    }else{
                        showAlert("{{ __('strings.err_fail_to_save') }}");
                    }
                },
                error:function(e1,e2,e3){
                    showAlert(e2);
                }
            })
        });

        // sort times
        $(document).on("click","#btnSaveTimeSort",function (){
            event.preventDefault();

            let sortData = [];

            $.each($("input[name='stime_ids[]'"),function (i,obj){
                sortData.push($(this).val());
            });

            $("#fn_spinner_time").removeClass("d-none");

            $.ajax({
                type:"POST",
                dataType:"json",
                url:"/bms/saveSortStudyTimesJs",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "sortData":sortData.toString()
                },
                success:function(msg){
                    if (msg.result === "true"){
                        showAlert("{{ __('strings.fn_save_complete') }}");
                        $("#fn_spinner_time").addClass("d-none");
                    }else{
                        showAlert("{{ __('strings.err_fail_to_save') }}");
                        $("#fn_spinner_time").addClass("d-none");
                    }
                }
            })
        });

        // days
        let _studyDays = [@foreach($bmsStudyDays as $bmsStudyDay)
        {"id":{{ $bmsStudyDay->id }},"title":"{{ $bmsStudyDay->days_title }}","index":{{$bmsStudyDay->days_index}} },
        @endforeach];

        $(document).on('click','#btnAddDay',function (){
            event.preventDefault();

            if ($("#up_study_day").val() === "") return;

            $("#fn_spinner_day").removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/bms/addStudyDayJs",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "up_day":$("#up_study_day").val()
                },
                success:function (msg){
                    if (msg.result === "true"){
                        showAlert("{{ __('strings.fn_save_complete') }}");
                        _studyDays.push({"id":msg.data.id,"title":msg.data.days_title,"index":msg.data.days_index});
                        reloadPrint("stDays");
                        $("#up_study_day").val("");
                        $("#fn_spinner_day").addClass("d-none");
                        return;
                    }else{
                        showAlert("{{ __('strings.err_fail_to_save') }}");
                        $("#fn_spinner_day").addClass("d-none");
                        return;
                    }
                }
            })
        });

        // days sort save
        $(document).on("click","#btnSaveDaySort",function (){
            event.preventDefault();

            $("#fn_spinner_day").removeClass("d-none");

            let sortData = [];
            $.each($("input[name='sday_ids[]'"),function (i,obj){
                sortData.push($(obj).val());
            })

            $.ajax({
                type:"POST",
                url:"/bms/saveSortStudyDaysJs",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "sortData":sortData.toString()
                },
                success:function(msg){
                    if (msg.result === "true"){
                        showAlert("{{__('strings.fn_save_complete')}}");
                        $("#fn_spinner_day").addClass("d-none");
                    }else{
                        showAlert("{{ __('strings.err_fail_to_save') }}");
                        $("#fn_spinner_day").addClass("d-none");
                    }
                }
            })
        });

        // modify
        $(document).on("click",".fn_day_modify",function (){
            event.preventDefault();

            let idVal = $(this).parent().find("input[name='sday_ids[]'").val();
            let txtVal = $(this).parent().find("input[name='up_day_title[]'").val();

            $.ajax({
                type:"POST",
                url:"/bms/saveStudyDayJs",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "upId":idVal,
                    "upTxt":txtVal
                },
                success:function (msg){
                    if (msg.result === "true"){
                        showAlert("{{ __('strings.fn_save_complete') }}");
                    }else{
                        showAlert("{{ __('strings.err_fail_to_save') }}");
                    }
                }
            })
        });

        let _studyTypes = [@foreach($bmsStudyTypes as $bmsStudyType)
        {"title":"{{ $bmsStudyType->study_title }}","index":{{$bmsStudyType->study_type_index}},"id":{{$bmsStudyType->id}}},
        @endforeach];

        $(document).on("click","#btnAddSt",function (){
            event.preventDefault();
            if ($("#up_study_type").val() === "") return;

            $("#fn_spinner_type").removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/bms/addStudyTypesJs",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "up_name":$("#up_study_type").val()
                },
                success:function(msg){
                    if (msg.result === "true"){
                        _studyTypes.push({"title":$("#up_study_type").val(),"index":msg.data.study_title_index,"id":msg.data.id});
                        reloadPrint("stType");
                        $("#fn_spinner_type").addClass("d-none");
                    }else{
                        showAlert("{{ __('strings.err_fail_to_save') }}");
                        $("#fn_spinner_type").addClass("d-none");
                        return;
                    }
                    $("#up_study_type").val("");
                },
                error:function (e1,e2,e3){
                    showAlert(e2);
                    $("#fn_spinner_type").addClass("d-none");
                }
            })
        });

        // for edit
        $(document).on("click","#ls_studies > div > .fn_modify",function (){
            event.preventDefault();
            let value = $(this).parent().find("input[name='up_stype_title[]'").val();
            let nowId = $(this).attr("fn_id");

            $.ajax({
                type:"POST",
                url:"/bms/saveStudyTypeJs",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "upId":nowId,
                    "upVal":value
                },
                success:function (msg){
                    if (msg.result === "true"){
                        showAlert("{{ __('strings.fn_save_complete') }}");
                    }else{
                        showAlert("{{ __('strings.err_fail_to_save') }}");
                    }
                },
                error:function(e1,e2,e3){
                    showAlert(e2);
                }
            });
        });

        // sort save
        $(document).on("click","#btnSaveStSort",function (){
            let sortData = [];
            $.each($("input[name='stype_ids[]'"),function (i,obj){
                sortData.push($(obj).val());
            });
            $.ajax({
                type:"POST",
                url:"/bms/saveSortStudyTypes",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "sortData":sortData.toString()
                },
                success:function (msg){
                    if (msg.result === "true"){
                        showAlert("{{ __('strings.lb_save_sort') }}");
                        return;
                    }else{
                        showAlert("{{ __('strings.err_fail_to_save') }}");
                        return;
                    }
                },
                error:function (e1,e2,e3){
                    showAlert(e2);
                    return;
                }
            })
        });

        function reloadPrint(fn){
            if (fn === "stType"){
                //
                $("#ls_studies").empty();
                $.each(_studyTypes,function (i,obj){
                    $("<div class='list-group-item d-flex justify-content-between'>" +
                        "<input type='hidden' name='stype_ids[]' value='" + obj.id + "'/>" +
                        "<input type='text' name='up_stype_title[]' value='" + obj.title + "' class='form-control'/>" +
                        "<button class='btn btn-sm btn-outline-info fn_modify text-nowrap' fn_id='" + obj.id + "'>" +
                        "<i class='fa fa-edit'></i> {{ __('strings.fn_modify')}}" +
                        "</button></div>").appendTo($("#ls_studies"));
                });
            }else if (fn === "stDays"){
                $("#ls_days").empty();
                $.each(_studyDays,function (i,obj){
                    $("<div class='list-group-item d-flex justify-content-between'>" +
                        "<input type='hidden' name='sday_ids[]' value='" + obj.id + "'/>" +
                        "<input type='text' name='up_day_title[]' value='" + obj.title + "' class='form-control'/>" +
                        "<button class='btn btn-sm btn-outline-info fn_day_modify text-nowrap' fn_id='" + obj.id + "'>" +
                        "<i class='fa fa-edit'></i> {{ __('strings.fn_modify')}}" +
                        "</button></div>").appendTo($("#ls_days"));
                });
            }else if (fn === "sTime"){
                $("#ls_times").empty();
                $.each(_studyTimes, function(i,obj){
                    $("<div class='list-group-item d-flex justify-content-between'>" +
                        "<input type='hidden' name='stime_ids[]' value='" + obj.id + "'/>" +
                        "<input type='text' name='up_time_title[]' class='form-control' value='" + obj.title + "'/>" +
                        "<button class='btn btn-sm btn-outline-info text-nowrap fn_time_modify ml-2' fn_id='" + obj.id + "'>" +
                            "<i class='fa fa-edit'></i> {{ __('strings.fn_modify') }}" +
                        "</button>" +
                    "</div>").appendTo($("#ls_times"));
                });
            }
        }

        $(document).ready(function (){
            $("#ls_studies,#ls_days,#ls_times").sortable();
        });

        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
