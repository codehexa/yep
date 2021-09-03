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
        <div class="row">
            <div class="col-2">
                <h6>{{ __('strings.lb_options') }}</h6>
                <div class="list-group">
                    <a href="#" class="btn btn-link btn-sm list-group-item fn_item" data-pid="pg1">{{ __('strings.lb_study') }}</a>
                    <a href="#" class="btn btn-link btn-sm list-group-item fn_item" data-pid="pg2">{{ __('strings.lb_study_days') }}</a>
                    <a href="#" class="btn btn-link btn-sm list-group-item fn_item" data-pid="pg3">{{ __('strings.lb_study_times') }}</a>
                    <a href="#" class="btn btn-link btn-sm list-group-item fn_item" data-pid="pg4">{{ __('strings.lb_curriculums') }}</a>
                    <a href="#" class="btn btn-link btn-sm list-group-item fn_item" data-pid="pg5">{{ __('strings.lb_workbook') }}</a>
                </div>
            </div>
            <div class="col">
                <div id="pg1" class="fn_items">
                    <h6>{{ __('strings.lb_study') }}</h6>
                    <form name="stypeFrm" id="stypeFrm" method="post">
                        @csrf
                        <div class="list-group fn_list">
                            @foreach ($bmsStudyTypes as $bmsStudyType)
                                <div class="list-group-item d-flex justify-content-between">
                                    <input type="hidden" name="cbox_stype_ids[]" value="{{ $bmsStudyType->id }}"/>
                                    <div class="form-check">
                                        <input type="checkbox" name="up_stype_id[]" class="form-check-input" value="{{ $bmsStudyType->id }}"/>
                                    </div>
                                    <input type="text" name="up_stype_title[]" class="form-control form-control-sm mr-2" value="{{ $bmsStudyType->study_title }}"/>
                                    <select name="up_stype_zoom[]" class="form-control form-control-sm fn_stype_zooom" style="width: 4rem;">
                                        <option value="">{{ __('strings.fn_select_item') }}</option>
                                        <option value="Y" {{ $bmsStudyType->show_zoom == "Y"? "selected":"" }}>Y</option>
                                        <option value="N" {{ $bmsStudyType->show_zoom == "N"? "selected":"" }}>N</option>
                                    </select>
                                    <button class="btn btn-sm btn-outline-info text-nowrap fn_stype_modify ml-2" fn_id="{{ $bmsStudyType->id }}">
                                        <i class="fa fa-save"></i> {{ __('strings.fn_save') }}
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </form>

                    <div class="mt-2 btn-group">
                        <button class="btn btn-sm btn-primary" id="btnStypeNew"><i class="fa fa-plus"></i> {{ __('strings.fn_add') }}</button>
                        <button class="btn btn-sm btn-info" id="btnStypeSort"><i class="fa fa-save"></i> {{ __('strings.fn_sort_save') }}</button>
                        <button class="btn btn-sm btn-danger" id="btnStypeDelete"><i class="fa fa-trash"></i> {{ __('strings.fn_delete') }}</button>
                        <i class="fa fa-spin fa-spinner d-none ml-1 mt-2" id="loadDelStype" ></i>
                    </div>

                    <div class="mt-3 d-none" id="panelStypeInfo">
                        <form id="stypeNewFrm" name="stypeNewFrm" method="post">
                            @csrf
                            <h6>{{ __('strings.lb_study') }} {{ __('strings.fn_add') }}</h6>
                            <div class="list-group">
                                <div class="list-group-item">
                                    <h6 class="mt-2">{{ __('strings.lb_study_title') }}</h6>
                                    <input type="text" name="up_study_type_title" id="up_study_type_title" class="form-control form-control-sm"/>
                                    <div class="form-group d-flex justify-content-between mt-2">
                                        <label class="text-nowrap mr-2 mt-2">{{ __('strings.lb_zoom_check') }}</label>
                                        <select name="up_study_type_zoom" id="up_study_type_zoom" class="form-control form-control-sm mt-1">
                                            <option value="">{{ __('strings.fn_select_item') }}</option>
                                            <option value="Y">Y</option>
                                            <option value="N">N</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary mt-2" id="btnStypeSave"><i class="fa fa-save"></i> {{ __('strings.fn_okay') }}</button>
                            <i class="fa fa-spinner fa-spin d-none" id="loadStype"></i>
                            <button class="btn btn-secondary mt-2" id="btnStypeCancel"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
                        </form>
                    </div>
                </div>

                <!-- pg1 end -->

                <div id="pg2" class="d-none fn_items">
                    <h6>{{ __('strings.lb_study_days') }}</h6>
                    <div class="list-group fn_list">
                        @foreach ($bmsStudyDays as $bmsStudyDay)
                            <div class="list-group-item d-flex justify-content-between">
                                <input type="checkbox" name="sday_ids[]" class="mr-2 mt-2" value="{{ $bmsStudyDay->id }}"/>
                                <input type="text" name="up_day_title[]" class="form-control mr-2" value="{{ $bmsStudyDay->days_title }}"/>
                                <input type="text" name="up_day_count[]" class="form-control fn_day_count" style="width:4rem;" value="{{ $bmsStudyDay->days_count }}"/>
                                <button class="btn btn-sm btn-outline-info text-nowrap fn_day_modify ml-2" fn_id="{{ $bmsStudyDay->id }}">
                                    <i class="fa fa-edit"></i> {{ __('strings.fn_save') }}
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-2 btn-group">
                        <button class="btn btn-sm btn-primary" id="btnDayNew"><i class="fa fa-plus"></i> {{ __('strings.fn_add') }}</button>
                        <button class="btn btn-sm btn-info" id="btnDaySort"><i class="fa fa-save"></i> {{ __('strings.fn_sort_save') }}</button>
                        <button class="btn btn-sm btn-danger" id="btnDayDelete"><i class="fa fa-trash"></i> {{ __('strings.fn_delete') }}</button>
                        <i class="fa fa-spin fa-spinner d-none ml-1 mt-2" id="loadDelDay" ></i>
                    </div>

                    <div class="mt-3 d-none" id="panelDaysInfo">
                        <form id="dayNewFrm" name="dayNewFrm" method="post">
                            @csrf
                            <h6>{{ __('strings.lb_study_days') }} {{ __('strings.fn_add') }}</h6>
                            <div class="list-group">
                                <div class="list-group-item">
                                    <h6 class="mt-2">{{ __('strings.lb_study_days') }}</h6>
                                    <input type="text" name="up_days_title" id="up_days_title" class="form-control form-control-sm"/>
                                    <div class="form-group d-flex justify-content-between mt-2">
                                        <label class="text-nowrap mr-2 mt-2">{{ __('strings.lb_days_count') }}</label>
                                        <input type="number" name="up_days_count" id="up_days_count" class="form-control"/>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary mt-2" id="btnDaysSave"><i class="fa fa-save"></i> {{ __('strings.fn_okay') }}</button>
                            <i class="fa fa-spinner fa-spin d-none" id="loadDays"></i>
                            <button class="btn btn-secondary mt-2" id="btnDaysCancel"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
                        </form>
                    </div>
                </div>


                <!-- pg3   section -->
                <div id="pg3" class="d-none fn_items">
                    <h6>{{ __('strings.lb_study_times') }}</h6>
                    <div class="list-group fn_list">
                        @foreach ($bmsStudyTimes as $bmsStudyTime)
                            <div class="list-group-item d-flex justify-content-between">
                                <input type="checkbox" name="stime_ids[]" class="mt-2 mr-2 fn_stime_chk" value="{{ $bmsStudyTime->id }}"/>
                                <input type="text" name="up_time_title[]" class="form-control" value="{{ $bmsStudyTime->time_title }}"/>
                                <button class="btn btn-sm btn-outline-info text-nowrap fn_time_modify ml-2" fn_id="{{ $bmsStudyTime->id }}">
                                    <i class="fa fa-edit"></i> {{ __('strings.fn_save') }}
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-2 btn-group">
                        <button class="btn btn-sm btn-primary" id="btnStimeNew"><i class="fa fa-plus"></i> {{ __('strings.fn_add') }}</button>
                        <button class="btn btn-sm btn-info" id="btnStimeSort"><i class="fa fa-save"></i> {{ __('strings.fn_sort_save') }}</button>
                        <button class="btn btn-sm btn-danger" id="btnStimeDelete"><i class="fa fa-trash"></i> {{ __('strings.fn_delete') }}</button>
                        <i class="fa fa-spin fa-spinner d-none ml-1 mt-2" id="loadDelStime" ></i>
                    </div>

                    <div class="mt-3 d-none" id="panelStimeInfo">
                        <form id="stimeNewFrm" name="stimeNewFrm" method="post">
                            @csrf
                            <h6>{{ __('strings.lb_study_times') }} {{ __('strings.fn_add') }}</h6>
                            <div class="list-group">
                                <div class="list-group-item">
                                    <h6 class="mt-2">{{ __('strings.lb_study_times') }}</h6>
                                    <input type="text" name="up_stime_title" id="up_stime_title" class="form-control form-control-sm"/>
                                </div>
                            </div>
                            <button class="btn btn-primary mt-2" id="btnStimeSave"><i class="fa fa-save"></i> {{ __('strings.fn_okay') }}</button>
                            <i class="fa fa-spinner fa-spin d-none" id="loadStime"></i>
                            <button class="btn btn-secondary mt-2" id="btnStimeCancel"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
                        </form>
                    </div>

                </div>

                <!-- pg3 end  section -->

                <!-- pg4 section -->
                <div id="pg4" class="d-none fn_items">
                    <h6>{{ __('strings.lb_curriculums') }}</h6>
                    <div class="list-group fn_list">
                        @foreach ($bmsCurriculums as $bmsCurriculum)
                            <div class="list-group-item d-flex justify-content-between">
                                <input type="checkbox" name="scurri_ids[]" class="mt-2 mr-2 fn_chk_curri" value="{{ $bmsCurriculum->id }}"/>
                                <input type="text" name="up_curri_title[]" class="form-control" value="{{ $bmsCurriculum->bcur_title }}"/>
                                <button class="btn btn-sm btn-outline-info text-nowrap fn_curri_modify ml-2" fn_id="{{ $bmsCurriculum->id }}">
                                    <i class="fa fa-edit"></i> {{ __('strings.fn_save') }}
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-2 btn-group">
                        <button class="btn btn-sm btn-primary" id="btnCurriNew"><i class="fa fa-plus"></i> {{ __('strings.fn_add') }}</button>
                        <button class="btn btn-sm btn-info" id="btnCurriSort"><i class="fa fa-save"></i> {{ __('strings.fn_sort_save') }}</button>
                        <button class="btn btn-sm btn-danger" id="btnCurriDelete"><i class="fa fa-trash"></i> {{ __('strings.fn_delete') }}</button>
                        <i class="fa fa-spin fa-spinner d-none ml-1 mt-2" id="loadDelCurri" ></i>
                    </div>

                    <div class="mt-3 d-none" id="panelCurriInfo">
                        <form id="curriNewFrm" name="curriNewFrm" method="post">
                            @csrf
                            <h6>{{ __('strings.lb_curriculums') }} {{ __('strings.fn_add') }}</h6>
                            <div class="list-group">
                                <div class="list-group-item">
                                    <h6 class="mt-2">{{ __('strings.lb_curriculums') }}</h6>
                                    <input type="text" name="up_curri_title" id="up_curri_title" class="form-control form-control-sm"/>
                                </div>
                            </div>
                            <button class="btn btn-primary mt-2" id="btnCurriSave"><i class="fa fa-save"></i> {{ __('strings.fn_okay') }}</button>
                            <i class="fa fa-spinner fa-spin d-none" id="loadCurri"></i>
                            <button class="btn btn-secondary mt-2" id="btnCurriCancel"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
                        </form>
                    </div>

                </div>
                <!-- pg4 section end -->


                <div id="pg5" class="d-none fn_items">
                    <h6>{{ __('strings.lb_workbook') }}</h6>
                    <div class="list-group fn_list">
                        @foreach ($bmsWorkbooks as $bmsWorkbook)
                            <div class="list-group-item d-flex justify-content-between">
                                <input type="checkbox" name="bw_ids[]" class="mt-2 mr-2" value="{{ $bmsWorkbook->id }}"/>
                                <input type="text" name="up_bw_title[]" class="form-control mr-2" value="{{ $bmsWorkbook->bw_title }}"/>
                                <input type="text" name="up_bw_text[]" class="form-control" value="{{ $bmsWorkbook->bw_text }}"/>
                                <button class="btn btn-sm btn-outline-info text-nowrap fn_bw_modify ml-2" fn_id="{{ $bmsWorkbook->id }}">
                                    <i class="fa fa-edit"></i> {{ __('strings.fn_save') }}
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-2 btn-group">
                        <button class="btn btn-sm btn-primary" id="btnBookNew"><i class="fa fa-plus"></i> {{ __('strings.fn_add') }}</button>
                        <button class="btn btn-sm btn-info" id="btnBookSort"><i class="fa fa-save"></i> {{ __('strings.fn_sort_save') }}</button>
                        <button class="btn btn-sm btn-danger" id="btnBookDelete"><i class="fa fa-trash"></i> {{ __('strings.fn_delete') }}</button>
                        <i class="fa fa-spin fa-spinner d-none ml-1 mt-2" id="loadDelBook" ></i>
                    </div>

                    <div class="mt-3 d-none" id="panelBookInfo">
                        <form id="BookNewFrm" name="BookNewFrm" method="post">
                            @csrf
                            <h6>{{ __('strings.lb_workbook') }} {{ __('strings.fn_add') }}</h6>
                            <div class="list-group">
                                <div class="list-group-item">
                                    <h6 class="mt-2">{{ __('strings.lb_workbook') }}</h6>
                                    <input type="text" name="up_book_title" id="up_book_title" class="form-control form-control-sm"/>
                                    <h6 class="mt-2">{{ __('strings.lb_workbook_text') }}</h6>
                                    <input type="text" name="up_book_text" id="up_book_text" class="form-control form-control-sm"/>
                                </div>
                            </div>
                            <button class="btn btn-primary mt-2" id="btnBookSave"><i class="fa fa-save"></i> {{ __('strings.fn_okay') }}</button>
                            <i class="fa fa-spinner fa-spin d-none" id="loadBook"></i>
                            <button class="btn btn-secondary mt-2" id="btnBookCancel"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
                        </form>
                    </div>
                </div>
            </div>
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
    <script id="stypeTmpl" type="text/x-jquery-tmpl">
        <div class="list-group-item d-flex justify-content-between">
            <input type="hidden" name="cbox_stype_ids[]" value="${id}"/>
            <div class="form-check">
                <input type="checkbox" name="up_stype_id[]" class="form-check-input" value="${id}"/>
            </div>
            <input type="text" name="up_stype_title[]" class="form-control form-control-sm mr-2" value="${study_title}"/>
            <select name="up_stype_zoom[]" class="form-control form-control-sm fn_stype_zooom" style="width: 4rem;">
                <option value="">{{ __('strings.fn_select_item') }}</option>
                <option value="Y" @{{if show_zoom == 'Y'  }} selected @{{/if}}>Y</option>
                <option value="N" @{{if show_zoom == 'N'  }} selected @{{/if}}>N</option>
            </select>
            <button class="btn btn-sm btn-outline-info text-nowrap fn_stype_modify ml-2" fn_id="${id}">
                <i class="fa fa-save"></i> {{ __('strings.fn_save') }}
            </button>
        </div>
    </script>

    <script id="daysTmpl" type="text/x-jquery-tmpl">
        <div class="list-group-item d-flex justify-content-between">
            <input type="checkbox" name="sday_ids[]" class="mr-2 mt-2" value="${id}"/>
            <input type="text" name="up_day_title[]" class="form-control mr-2" value="${days_title}"/>
            <input type="text" name="up_day_count[]" class="form-control fn_day_count" style="width:4rem;" value="${days_count}"/>
            <button class="btn btn-sm btn-outline-info text-nowrap fn_day_modify ml-2" fn_id="${id}">
                <i class="fa fa-edit"></i> {{ __('strings.fn_save') }}
            </button>
        </div>
    </script>

    <script id="studyTimesTmpl" type="text/x-jquery-tmpl">
        <div class="list-group-item d-flex justify-content-between">
            <input type="checkbox" name="stime_ids[]" class="mt-2 mr-2 fn_stime_chk" value="${id}"/>
            <input type="text" name="up_time_title[]" class="form-control" value="${time_title}"/>
            <button class="btn btn-sm btn-outline-info text-nowrap fn_time_modify ml-2" fn_id="${id}">
                <i class="fa fa-edit"></i> {{ __('strings.fn_save') }}
            </button>
        </div>
    </script>

    <script id="curriTmpl" type="text/x-jquery-tmpl">
        <div class="list-group-item d-flex justify-content-between">
            <input type="checkbox" name="scurri_ids[]" class="mt-2 mr-2 fn_chk_curri" value="${id}"/>
            <input type="text" name="up_curri_title[]" class="form-control" value="${bcur_title}"/>
            <button class="btn btn-sm btn-outline-info text-nowrap fn_curri_modify ml-2" fn_id="${id}">
                <i class="fa fa-edit"></i> {{ __('strings.fn_save') }}
            </button>
        </div>
    </script>

    <script id="bookTmpl" type="text/x-jquery-tmpl">
        <div class="list-group-item d-flex justify-content-between">
            <input type="checkbox" name="bw_ids[]" class="mt-2 mr-2" value="${id}"/>
            <input type="text" name="up_bw_title[]" class="form-control mr-2" value="${bw_title}"/>
            <input type="text" name="up_bw_text[]" class="form-control" value="${bw_text}"/>
            <button class="btn btn-sm btn-outline-info text-nowrap fn_bw_modify ml-2" fn_id="${id}">
                <i class="fa fa-edit"></i> {{ __('strings.fn_save') }}
            </button>
        </div>
    </script>

    <script type="text/javascript">
        $(document).on('click','.fn_item',function (){
            event.preventDefault();
            $(".fn_items").addClass("d-none");
            let _nowId = $(this).data("pid");
            $(".fn_item").removeClass("active");
            $(this).addClass("active");
            $("#" + _nowId).removeClass("d-none");
        });

        // stype new
        $(document).on("click","#btnStypeNew",function (){
            event.preventDefault();
            $("#panelStypeInfo").removeClass("d-none");
            $("#up_study_type_title").val("");
            $("#up_study_type_zoom").val("");
        });

        // stype new cancel
        $(document).on("click","#btnStypeCancel",function (){
            event.preventDefault();
            $("#panelStypeInfo").addClass("d-none");
        });

        // stype new save
        $(document).on("click","#btnStypeSave",function (){
            event.preventDefault();

            if ($("#up_study_type_title").val() === ""){
                showAlert("{{ __('strings.lb_input_page_name') }}");
                return;
            }

            if ($("#up_study_type_zoom").val() === ""){
                showAlert("{{ __('strings.lb_select_zoom_type') }}");
                return;
            }

            $("#loadStype").removeClass("d-none");

            $.ajax({
                type:"post",
                url:"/bms/addStudyTypesJs",
                dataType:"json",
                data:$("#stypeNewFrm").serialize(),
                success:function(msg){
                    $("#loadStype").addClass("d-none");
                    if (msg.result === "true"){
                        //
                        $("#stypeTmpl").tmpl(msg.data).appendTo($(".fn_list").eq(0));
                    }else{
                        //
                    }
                },
                error:function(e1,e2,e3){
                    showAlert(e2);
                    $("#loadStype").addClass("d-none");
                }
            })
        });

        // stype update
        $(document).on("click",".fn_stype_modify",function (){
            event.preventDefault();
            let _index = $(".fn_stype_modify").index($(this));
            let _nowId = $(this).attr("fn_id");
            let _title = $("input[name='up_stype_title[]']").eq(_index).val();
            let _zoom = $(".fn_stype_zooom").eq(_index).find("option:selected").val();
            console.log(_zoom);

            $.ajax({
                type:"POST",
                url:"/bms/storeStudyTypeJs",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "upId":_nowId,
                    "upVal":_title,
                    "upZoom":_zoom
                },
                success:function(msg){
                    if (msg.result === "true"){
                        showAlert("{{ __('strings.fn_save_complete') }}");
                    }else{
                        showAlert("{{ __('strings.fn_save_false') }}");
                    }
                }
            })
        });

        // stype delete
        $(document).on("click","#btnStypeDelete",function (){
            event.preventDefault();

            if ($("input[name='up_stype_id[]']:checked").length <= 0){
                showAlert("{{ __('strings.err_select_to_delete') }}");
                return;
            }

            let _delIds = [];
            $.each($("input[name='up_stype_id[]']:checked"),function (i,obj){
                _delIds.push($(obj).val());
            });

            $("#loadDelStype").removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/bms/deleteStudyTypeJs",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "_dels":_delIds.toString()
                },
                success:function(msg){
                    //
                    $("#loadDelStype").addClass("d-none");
                    if (msg.result === "true"){
                        $.each($("input[name='up_stype_id[]']"),function (i,obj){
                            if ($(obj).val().indexOf(_delIds) >= 0){
                                $(obj).parent().parent().remove();
                            }
                        });
                    }
                },
                error:function(e1,e2,e3){
                    $("#loadDelStype").addClass("d-none");
                    showAlert(e2);
                }
            })
        });

        // sort
        $(document).on("click","#btnStypeSort",function (){
            event.preventDefault();
            let _sortData = [];
            $.each($("input[name='up_stype_id[]']"),function (i,obj){
                $(obj).prop("checked",true);
                _sortData.push($(obj).val());
            });

            $("#loadDelStype").removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/bms/saveSortStudyTypes",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "_vals":_sortData.toString()
                },
                success:function (msg){
                    if (msg.result === "true"){
                        showAlert("{{ __('strings.fn_now_saved') }}");
                        $("#loadDelStype").addClass("d-none");
                        return;
                    }else{
                        showAlert("{{ __('strings.fn_save_false') }}");
                        $("#loadDelStype").addClass("d-none");
                    }
                }
            })
        });

        // 수업 요일 관련
        $(document).on("click","#btnDayNew",function (){
            event.preventDefault();
            $("#panelDaysInfo").removeClass("d-none");
            $("#up_days_title").val("");
            $("#up_days_count").val("0");
        });

        // save
        $(document).on("click","#btnDaysSave",function (){
            event.preventDefault();

            if ($("#up_days_title").val() === ""){
                showAlert("{{ __('strings.lb_input_page_name') }}");
                return;
            }

            if ($("#up_days_count").val() === ""){
                showAlert("{{ __('strings.str_input_days_count') }}");
                return;
            }

            $("#loadDays").removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/bms/addStudyDayJs",
                dataType:"json",
                data:$("#dayNewFrm").serialize(),
                success:function(msg){
                    //
                    if (msg.result === "true"){
                        $("#daysTmpl").tmpl(msg.data).appendTo($(".fn_list").eq(1));
                        $("#loadDays").addClass("d-none");
                    }else{
                        showAlert("{{ __('strings.fn_save_false') }}");
                        $("#loadDays").addClass("d-none");
                        return;
                    }
                },
                error:function(e1,e2,e3){
                    showAlert(e2);
                    $("#loadDays").addClass("d-none");
                    return;
                }
            })
        });

        // 수업 요일 업데이트
        $(document).on("click",".fn_day_modify",function (){
            event.preventDefault();
            event.stopImmediatePropagation();

            let _index = $(".fn_day_modify").index($(this));
            let _id = $(this).attr("fn_id");
            let _title = $("input[name='up_day_title[]']").eq(_index).val();
            let _cnt = $(".fn_day_count").eq(_index).val();

            $.ajax({
                url:"/bms/saveStudyDayJs",
                type:"POST",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "upId":_id,
                    "upTxt":_title,
                    "upCnt":_cnt
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

        // 수업 요일 삭제
        $(document).on("click","#btnDayDelete",function (){
            event.preventDefault();

            let _delIds = [];

            $.each($("input[name='sday_ids[]']:checked"),function (i,obj){
                _delIds.push($(obj).val());
            });

            if (_delIds.length <= 0){
                showAlert("{{ __('strings.err_select_to_delete') }}");
                return;
            }

            $("#loadDelDay").removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/bms/deleteStudyDayJs",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "dels":_delIds.toString(),
                },
                success:function (msg){
                    if (msg.result === "true"){
                        $("#loadDelDay").addClass("d-none");
                        $.each($("input[name='sday_ids[]']:checked"),function (i,obj){
                            $(obj).parent().remove();
                        });
                    }else{
                        showAlert("{{ __('strings.fn_save_false') }}");
                        $("#loadDelDay").addClass("d-none");
                        return;
                    }
                },
                error:function(e1,e2,e3){
                    showAlert(e2);
                    $("#loadDelDay").addClass("d-none");
                    return;
                }
            })
        });

        // 수업 요일 순서 저장.
        $(document).on("click","#btnDaySort",function(){
            event.preventDefault();

            let _ids = [];

            $.each($("input[name='sday_ids[]']"),function(i,obj){
                $(obj).prop("checked",true);
                _ids.push($(obj).val());
            });

            $("#loadDelDay").removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/bms/sortStudyDayJs",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "dels":_ids.toString(),
                },
                success:function(msg){
                    if (msg.result === "true"){
                        showAlert("{{ __('strings.fn_save_complete') }}");
                        $("#loadDelDay").addClass("d-none");
                    }else{
                        showAlert("{{ __('strings.fn_save_false') }}");
                        $("#loadDelDay").addClass("d-none");
                    }
                },
                error:function(e1,e2,e3){
                    showAlert(e2);
                }
            })
        });

        // study times set
        $(document).on("click","#btnStimeNew",function (){
            event.preventDefault();
            $("#panelStimeInfo").removeClass("d-none");
            $("#up_stime_title").val("");
        });

        // study times add
        $(document).on("click","#btnStimeSave",function(){
            event.preventDefault();
            if ($("#up_stime_title").val() === ""){
                showAlert("{{ __('strings.str_input_study_time') }}");
                return;
            }

            $("#loadStime").removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/bms/addStudyTimeJs",
                dataType:"json",
                data:$("#stimeNewFrm").serialize(),
                success:function(msg){
                    if (msg.result === "true"){
                        showAlert("{{ __('strings.fn_save_complete') }}");
                        $("#loadStime").addClass("d-none");
                        $("#studyTimesTmpl").tmpl(msg.data).appendTo($(".fn_list").eq(2));
                    }
                },
                error:function(e1,e2,e3){
                    showAlert(e2);
                    $("#loadStime").addClass("d-none");
                }
            })
        });

        // study times update
        $(document).on("click",".fn_time_modify",function (){
            event.preventDefault();

            let _index = $(".fn_time_modify").index($(this));
            let _id = $(this).attr("fn_id");

            if ($("input[name='up_time_title[]']").eq(_index).val() === ""){
                showAlert("{{ __('strings.str_input_study_time') }}");
                return;
            }

            $.ajax({
                type:"POST",
                url:"/bms/saveStudyTimeJs",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "_title":$("input[name='up_time_title[]']").eq(_index).val(),
                    "_id":_id
                },
                success:function(msg){
                    if (msg.result === "true"){
                        showAlert("{{ __('strings.fn_save_complete') }}");
                    }else{
                        showAlert("{{ __('strings.fn_save_false') }}");
                    }
                },
                error:function(e1,e2,e3){
                    showAlert(e2);
                }
            })
        });

        // 수업 시간 삭제
        $(document).on("click","#btnStimeDelete",function (){
            event.preventDefault();

            let _dels = [];
            $.each($(".fn_stime_chk:checked"),function (i,obj){
                _dels.push($(obj).val());
            });

            if (_dels.length <= 0){
                showAlert("{{ __('strings.str_select_times_to_delete') }}");
                return;
            }

            $("#loadDelStime").removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/bms/deleteStudyTimeJs",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "_dels":_dels.toString()
                },
                success:function(msg){
                    if (msg.result === "true"){
                        $.each($(".fn_stime_chk:checked"),function (i,obj){
                            $(obj).parent().remove();
                        });
                        $("#loadDelStime").addClass("d-none");
                    }else{
                        showAlert("{{ __('strings.fn_save_false') }}");
                        $("#loadDelStime").addClass("d-none");
                        return;
                    }
                }
            })
        });

        // 수업 시간 정렬 저장
        $(document).on("click","#btnStimeSort",function (){
            event.preventDefault();
            event.stopImmediatePropagation();

            let _ids = [];
            $(".fn_stime_chk").each(function(i,obj){
                $(obj).prop("checked",true);
                _ids.push($(obj).val());
            });

            $("#loadDelStime").removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/bms/saveSortStudyTimesJs",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "_ids":_ids.toString(),
                },
                success:function(msg){
                    if (msg.result === "true"){
                        showAlert("{{ __('strings.fn_save_complete') }}");
                        $("#loadDelStime").addClass("d-none");
                    }else{
                        showAlert("{{ __('strings.fn_save_false') }}");
                        $("#loadDelStime").addClass("d-none");
                    }
                },
                error:function(e1,e2,e3){
                    showAlert(e2);
                    $("#loadDelStime").addClass("d-none");
                }
            })
        });

        // curriculums
        $(document).on("click","#btnCurriNew",function (){
            event.preventDefault();

            $("#panelCurriInfo").removeClass("d-none");
            $("#up_curri_title").val("");
        });

        // add curri
        $(document).on("click","#btnCurriSave",function (){
            event.preventDefault();

            if ($("#up_curri_title").val() === ""){
                showAlert("{{ __('strings.str_input_curri_name') }}");
                return;
            }

            $("#loadCurri").removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/bms/addCurrculum",
                dataType:"json",
                data:$("#curriNewFrm").serialize(),
                success:function(msg){
                    //
                    $("#loadCurri").addClass("d-none");
                    if (msg.result === "true"){
                        showAlert("{{ __('strings.fn_save_complete') }}");
                        $("#curriTmpl").tmpl(msg.data).appendTo($(".fn_list").eq(3));
                    }else{
                        showAlert("{{ __('strings.fn_save_false') }}");
                    }
                },
                error:function(e1,e2,e3){
                    showAlert(e2);
                    $("#loadCurri").addClass("d-none");
                }
            })
        });

        // 과정 업데이트
        $(document).on("click",".fn_curri_modify",function(){
            event.preventDefault();

            let _id = $(this).attr("fn_id");
            let _index = $(".fn_curri_modify").index($(this));
            let _title = $("input[name='up_curri_title[]']").eq(_index).val();

            $.ajax({
                type:"POST",
                url:"/bms/saveCurriculumJs",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "_id":_id,
                    "_title":_title,
                },
                success:function(msg){
                    if (msg.result === "true"){
                        showAlert("{{ __('strings.fn_save_complete') }}");
                    }else{
                        showAlert("{{ __('strings.fn_save_false') }}");
                    }
                },
                error:function(e1,e2,e3){
                    showAlert(e2);
                }
            })
        });

        // 과정 삭제
        $(document).on("click","#btnCurriDelete",function (){
            event.preventDefault();

            let dels = [];
            $(".fn_chk_curri:checked").each(function(i,obj){
                dels.push($(obj).val());
            });

            if (dels.length <= 0){
                showAlert("{{ __('strings.err_select_to_delete') }}");
                return;
            }

            $("#loadDelCurri").removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/bms/deleteCurriculumJs",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "_dels":dels.toString()
                },
                success:function(msg){
                    $("#loadDelCurri").addClass("d-none");
                    if (msg.result === "true"){
                        $(".fn_chk_curri:checked").each(function(i,obj){
                            $(obj).parent().remove();
                        });
                        showAlert("{{ __('strings.fn_save_complete') }}");
                    }else{
                        showAlert("{{ __('strings.fn_save_false') }}");
                    }
                }
            })
        });

        // 순서 저장
        $(document).on("click","#btnCurriSort",function (){
            event.preventDefault();

            let ids = [];
            $(".fn_chk_curri").each(function(i,obj){
                $(obj).prop("checked",true);
                ids.push($(obj).val());
            });

            $("#loadDelCurri").removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/bms/saveSortCurriculumsJs",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "_ids":ids.toString()
                },
                success:function(msg){
                    if (msg.result === "true"){
                        showAlert("{{ __('strings.fn_save_complete') }}");
                    }else{
                        showAlert("{{ __('strings.fn_save_false') }}");
                    }
                    $("#loadDelCurri").addClass("d-none");
                },
                error:function(e1,e2,e3){
                    showAlert(e2);
                    $("#loadDelCurri").addClass("d-none");
                    return;
                }
            })
        });

        // workbook
        $(document).on("click","#btnBookNew",function (){
            event.preventDefault();
            $("#panelBookInfo").removeClass("d-none");
            $("#up_book_title").val("");
            $("#up_book_text").val("");
        });

        // update
        $(document).on("click",".fn_bw_modify",function (){
            event.preventDefault();

            let _index = $(".fn_bw_modify").index($(this));
            let _nowId = $(".fn_bw_modify").eq(_index).attr("fn_id");
            let _nowTitle = $("input[name='up_bw_title[]']").eq(_index).val();
            let _nowText = $("input[name='up_bw_text[]']").eq(_index).val();

            $.ajax({
                type:"POST",
                url:"/bms/storeWorkbook",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "_upId":_nowId,
                    "_upTitle":_nowTitle,
                    "_upText":_nowText
                },
                success:function(msg){
                    if (msg.result === "true"){
                        showAlert("{{ __('strings.fn_save_complete') }}");
                    }else{
                        showAlert("{{ __('strings.fn_save_false') }}");
                    }
                },
                error:function(e1,e2,e3){
                    showAlert(e2);
                }
            })
        });

        // insert new
        $(document).on("click","#btnBookSave",function (){
            event.preventDefault();

            if ($("#up_book_title").val() === ""){
                showAlert("{{ __('strings.lb_input_page_name') }}");
                return;
            }

            $("#loadBook").removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/bms/addWorkbook",
                dataType:"json",
                data:$("#BookNewFrm").serialize(),
                success:function(msg){
                    //
                    $("#loadBook").addClass("d-none");
                    if (msg.result === "true"){
                        showAlert("{{ __('strings.fn_save_complete') }}");
                        $("#bookTmpl").tmpl(msg.data).appendTo($(".fn_list").eq(4));
                    }else{
                        showAlert("{{ __('strings.fn_save_false') }}");
                    }
                },
                error:function(e1,e2,e3){
                    showAlert(e2);
                    $("#loadBook").addClass("d-none");
                }
            })
        });

        // delete
        $(document).on("click","#btnBookDelete",function (){
            event.preventDefault();
            let _ids = [];

            $.each($("input[name='bw_ids[]']:checked"),function(i,obj){
                _ids.push($(obj).val());
            });

            if (_ids.length <= 0){
                showAlert("{{ __('strings.err_select_to_delete') }}");
                return;
            }

            $("#loadDelBook").removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/bms/deleteWorkbook",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "_ids":_ids.toString()
                },
                success:function(msg){
                    if (msg.result === "true"){
                        showAlert("{{ __('strings.fn_delete_complete') }}");
                        $.each($("input[name='bw_ids[]']:checked"),function(i,obj){
                            $(obj).parent().remove();
                        });
                        $("#loadDelBook").addClass("d-none");
                    }else{
                        showAlert("{{ __('strings.fn_save_false') }}");
                        $("#loadDelBook").addClass("d-none");
                        return;
                    }
                }
            })
        });

        // sort save
        $(document).on("click","#btnBookSort",function (){
            event.preventDefault();

            let _ids = [];

            $("input[name='bw_ids[]']").each(function(i,obj){
                $(obj).prop("checked",true);
                _ids.push($(obj).val());
            });

            $("#loadDelBook").removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/bms/saveSortWorkbook",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "_dels":_ids.toString()
                },
                success:function (msg){
                    if (msg.result === "true"){
                        showAlert("{{ __('strings.fn_save_complete') }}");
                    }else{
                        showAlert("{{ __('strings.fn_save_false') }}");
                    }
                    $("#loadDelBook").addClass("d-none");
                },
                error:function(e1,e2,e3){
                    showAlert(e2);
                    $("#loadDelBook").addClass("d-none");
                }
            })
        })

        $(document).ready(function (){
            $(".fn_list").sortable();
        });

        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
