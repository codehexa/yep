@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h5>{{ __('strings.lb_testweek_manage') }} </h5>
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
        <div class="form-group">
            <label for="up_year" class="form-label mr-2">{{ __('strings.lb_year_name') }}</label>
            <select name="up_year" id="up_year" class="form-select mr-2">
                @for ($i=date('Y') + 1; $i > (date('Y') -4); $i--)
                    <option value="{{ $i }}"
                            @if ($i == date('Y') || $i == $ryear)
                            selected
                        @endif
                    >{{ $i }} {{ __('strings.lb_year_name') }}</option>
                @endfor
            </select>
            <label for="up_school_grade" class="form-label mr-2">{{ __('strings.lb_haknyon') }}</label>
            <select name="up_school_grade" id="up_school_grade" class="form-select mr-2">
                <option value="">{{ __('strings.fn_all') }}</option>
                @foreach($sgrades as $sgrade)
                    <option value="{{ $sgrade->id }}"
                            @if (!is_null($rgrade) && $sgrade->id == $rgrade)
                            selected
                        @endif
                    >{{ $sgrade->gname }}</option>
                @endforeach
            </select>
            <span id="in_spin" class="d-none"><i class="fa fa-spinner fa-spin"></i> </span>
            <label for="up_hakgi" class="form-label mr-2">{{ __('strings.lb_hakgi') }}</label>
            <select name="up_hakgi" id="up_hakgi" class="form-select">
                <option value="">{{ __('strings.fn_all') }}</option>
                @if ($tmpHakgies)
                    @foreach($tmpHakgies as $tmpHakgi)
                        <option value="{{ $tmpHakgi->id }}"
                        @if ($tmpHakgi->id == $rhakgi)
                            selected
                        @endif
                        >{{ $tmpHakgi->hakgi_name }}</option>
                    @endforeach
                @endif

            </select>

            <button id="btn_search" class="btn btn-sm btn-primary ml-3"><i class="fa fa-search"></i> {{ __('strings.fn_search') }} </button>
        </div>
    </div>

    <div class="mt-3">
        <table class="mt-3 table table-striped">
            <thead>
                <tr class="text-center">
                    <th scope="col">#</th>
                    <th scope="col">{{ __('strings.lb_year_name') }}</th>
                    <th scope="col">{{ __('strings.lb_week_st') }}</th>
                    <th scope="col">{{ __('strings.lb_context') }}</th>
                    <th scope="col">{{ __('strings.lb_show') }}</th>
                    <th scope="col">{{ __('strings.lb_function') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $datum)
                <tr class="text-center">
                    <th scope="row">{{ $datum->id }}</th>
                    <td>{{ $datum->year }}</td>
                    <td>{{ $datum->weeks }}</td>
                    <td>{{ $datum->context }}</td>
                    <td>{{ $datum->show }}</td>
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
                <h5 class="modal-title" id="infoModalLongTitle">{{ __('strings.lb_new_context') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="twFrm" id="twFrm" method="post" action="/addTestWeek">
                    @csrf
                    <input type="hidden" name="info_id" id="info_id"/>
                    <div class="form-group">
                        <label for="info_year">{{ __('strings.lb_year_name') }}</label>
                        <select name="info_year" id="info_year" class="form-control">
                            @for ($i=date('Y') + 1; $i > (date('Y') -4); $i--)
                                <option value="{{ $i }}"
                                        @if ($i == date('Y'))
                                            selected
                                        @endif
                                >{{ $i }} {{ __('strings.lb_year_name') }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="info_week">{{ __('strings.lb_week_st') }}</label>
                        <select name="info_week" id="info_week" class="form-control">
                            @for($w = 1; $w <= 52; $w++)
                                <option value="{{ $w }}"
                                @if ($w == date("w"))
                                    selected
                                @endif
                                >{{ $w }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="info_grade">{{ __('strings.lb_haknyon') }}</label>
                        <select name="info_grade" id="info_grade" class="form-control">
                            <option value="">{{ __('strings.fn_all') }}</option>
                            @foreach($sgrades as $sgrade)
                                <option value="{{ $sgrade->id }}">{{ $sgrade->gname }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="info_hakgi">{{ __('strings.lb_hakgi') }}</label>
                        <select name="info_hakgi" id="info_hakgi" class="form-control">
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="info_show">{{ __('strings.lb_show') }}</label>
                        <select name="info_show" id="info_show" class="form-control">
                            <option value="N">N</option>
                            <option value="Y">Y</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="info_context">{{ __('strings.lb_context') }}</label>
                        <textarea name="info_context" id="info_context" class="form-control" placeholder="{{ __('strings.lb_insert_context') }}"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <i id="fn_loading" class="fa fa-spin fa-spinner mr-3 d-none"></i>
                <button type="button" class="btn btn-primary" id="btnTwSubmit" ><i class="fa fa-save"></i> {{ __('strings.fn_okay') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
                <button type="button" class="btn btn-danger d-none" id="btnTwDelete"><i class="fa fa-trash"></i> {{ __('strings.fn_delete') }}</button>
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
                <form name="delFrm" id="delFrm" method="post" action="/delTestWeek">
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
        let rhakgi = "{{ $rhakgi }}";

        $(document).on("change","#up_school_grade",function (){
            event.preventDefault();
            if ($("#up_school_grade").val() === ""){
                showAlert("{{ __('strings.str_select_hakyon') }}");
                return;
            }

            $("#info_grade").val($("#up_school_grade").val());
            $("#in_spin").removeClass("d-none");
            $.ajax({
                type:"POST",
                url:"/getHakgiListJson",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "sHaknyon":$("#up_school_grade").val(),
                    "sYear":$("#up_year").val()
                },
                success:function(msg){
                    //
                    $("#up_hakgi").empty();
                    $("#info_hakgi").empty();
                    $("<option value=''>{{ __('strings.fn_all') }}</option>").appendTo($("#up_hakgi"));
                    $("<option value=''>{{ __('strings.fn_all') }}</option>").appendTo($("#info_hakgi"));
                    $.each(msg.data,function(i,obj){
                        $("<option value='" + obj.id + "'>" + obj.hakgi_name + "</option>").appendTo($("#up_hakgi"));
                        $("<option value='" + obj.id + "'>" + obj.hakgi_name + "</option>").appendTo($("#info_hakgi"));
                    });
                    $("#in_spin").addClass("d-none");
                },
                error:function (e1,e2,e3){
                    showAlert(e2);
                }
            });
        });

        $(document).on("change","#info_grade",function (){
            event.preventDefault();
            $("#in_spin").removeClass("d-none");
            $.ajax({
                type:"POST",
                url:"/getHakgiListJson",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "sHaknyon":$("#info_grade").val(),
                    "sYear":$("#info_year").val()
                },
                success:function(msg){
                    //
                    $("#info_hakgi").empty();
                    $("<option value=''>{{ __('strings.fn_all') }}</option>").appendTo($("#info_hakgi"));
                    $.each(msg.data,function(i,obj){
                        $("<option value='" + obj.id + "'>" + obj.hakgi_name + "</option>").appendTo($("#info_hakgi"));
                    });
                    $("#in_spin").addClass("d-none");
                },
                error:function (e1,e2,e3){
                    showAlert(e2);
                }
            });
        });

        $(document).on("click","#btn_add",function (){
            event.preventDefault();

            if ($("#up_school_grade").val() === ""){
                showAlert("{{ __('strings.str_select_hakyon') }}");
                return;
            }

            if ($("#up_hakgi").val() === ""){
                showAlert("{{ __('strings.str_select_hakgi') }}");
                return;
            }

            $("#infoModalCenter").modal("show");
            $("#hakgiFrm").attr({"action":"/addTestWeek"});
            $("#btnTwDelete").addClass("d-none");

            $("#info_hakgi").val($("#up_hakgi").val());
        });

        // 검색.
        $(document).on("click","#btn_search",function (){
            let syear = $("#up_year").val();
            let sgrade = $("#up_school_grade").val();
            let shakgi = $("#up_hakgi").val();

            if (sgrade != ""){
                location.href = "/testWeeks/" + syear + "/" + sgrade;
            } else if (sgrade != "" && shakgi != ""){
                location.href = "/testWeeks/" + syear + "/" + sgrade + "/" + shakgi;
            } else {
                location.href = "/testWeeks/" + syear;
            }
        });

        $(document).on("click","#btnTwDelete",function (){
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
            $("#btnTwDelete").removeClass("d-none");
            $("#fn_loading").removeClass("d-none");
            $("#twFrm").attr({"action":"/storeTestWeek"});

            let clId = $(this).attr("fn_id");
            $("#del_id").val(clId);
            $("#info_id").val(clId);

            // 여기까지 작업 중.

            $.ajax({
                type:"POST",
                url:"/testWeekJson",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "cid":clId
                },
                success:function (msg){
                    if (msg.result === "true"){
                        $("#info_hakgi").empty();
                        $.each(msg.hakgiData,function(i,obj){
                            $("<option value='" + obj.id + "'>" + obj.hakgi_name + "</option>").appendTo($("#info_hakgi"));
                        });

                        $("#info_id").val(clId);
                        $("#info_year").val(msg.data.year);
                        $("#info_week").val(msg.data.weeks);
                        $("#info_grade").val(msg.data.school_grade);
                        $("#info_hakgi").val(msg.data.hakgi);
                        $("#info_show").val(msg.data.show);
                        $("#info_context").val(msg.data.context);
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


        $(document).on("click","#btnTwSubmit",function (){
            event.preventDefault();
            if ($("#info_hakgi").val() === ""){
                showAlert("{{ __('strings.str_select_hakgi') }}");
                return;
            }

            if ($("#info_context").val() === ""){
                showAlert("{{ __('strings.lb_insert_context') }}");
                return;
            }

            $("#twFrm").submit();
        });



        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
