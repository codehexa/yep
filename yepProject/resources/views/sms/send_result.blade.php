@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h5>{{ __('strings.lb_sms_work_manage') }} </h5>
    <div class="mt-3 btn-group">
        <a href="/home" class="btn btn-outline-secondary btn-sm"><i class="fa fa-home"></i> {{ __("strings.fn_home") }}</a>
<!--        <button id="btn_add" name="btn_add" class="btn btn-sm btn-primary"><i class="fa fa-file-excel"></i> {{ __('strings.fn_add') }}</button>-->
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
                @case ("FAIL_CAUSE_SAVING")
                <h4 class="text-center text-danger"> {{ __('strings.err_cannot_saving') }}</h4>
                @break
                @case ("FAIL_CAUSE_SENT")
                <h4 class="text-center text-danger"> {{ __('strings.err_cannot_sent') }}</h4>
                @break
                @case ("FAIL_TO_ADD")
                <h4 class="text-center text-danger"> {{ __('strings.err_add_test_in_sms_paper') }}</h4>
                @break
            @endswitch
        @endforeach
    @endif

    <div class="mt-3">
        <h6>{{ __('strings.str_total_sent',['TOTAL'=>$total]) }}</h6>
    </div>

</div>

<div class="modal fade" id="infoModalCenter" tabindex="-1" role="dialog" aria-labelledby="infoModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLongTitle">{{ __('strings.lb_test_forms_match') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="opFrm" id="opFrm" method="post" action="/saveMatching">
                    @csrf
                    <input type="hidden" name="up_academy" id="up_academy"/>
                    <input type="hidden" name="up_grade" id="up_grade"/>
                    <input type="hidden" name="up_class" id="up_class"/>
                    <input type="hidden" name="up_year" id="up_year"/>
                    <input type="hidden" name="up_hakgi" id="up_hakgi"/>
                    <input type="hidden" name="up_week" id="up_week"/>
                    <div class="row pl-1 pr-1">
                        <div class="col border mr-1" style="height: 250px;">
                            <h6>{{ __('strings.lb_test_title') }}</h6>
                            <div class="list-group overflow-auto list-group-flush" id="ls_forms"></div>
                        </div>
                        <div class="col border ml-1" style="height: 250px;">
                            <h6>{{ __('strings.lb_subjects') }}</h6>
                            <div class="list-group overflow-auto list-group-flush" id="ls_subjects"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <i id="fn_loading" class="fa fa-spin fa-spinner mr-3 d-none"></i>
                <button type="button" class="btn btn-primary" id="btnSubmit" ><i class="fa fa-save"></i> {{ __('strings.fn_save') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
                <button type="button" class="btn btn-danger d-none" id="btnOpDelete"><i class="fa fa-trash"></i> {{ __('strings.fn_delete') }}</button>
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
                <form name="delFrm" id="delFrm" method="post" action="/delSmsFront">
                    @csrf
                    <input type="hidden" name="del_id" id="del_id"/>
                </form>
            </div>
            <div class="modal-footer">
                <span id="fn_confirm_loading" class="d-none"><i class="fa fa-spinner fa-spin"></i> </span>
                <button type="button" class="btn btn-primary" id="btnDeleteDo"><i class="fa fa-check-circle"></i> {{ __('strings.fn_okay') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="loaderModalCenter" tabindex="-1" role="dialog" aria-labelledby="loaderModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h1 id="fn_confirm_body"><i class="fa fa-spin fa-spinner"></i> {{ __('strings.lb_loading') }}</h1>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="paperModalCenter" tabindex="-1" role="dialog" aria-labelledby="paperModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLongTitle">{{ __('strings.lb_add_paper') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5><i class="fa fa-plus-circle"></i> {{ __('strings.lb_select_test_to_add') }}</h5>
                <form name="frmAdd" id="frmAdd" method="post">
                    @csrf
                    <input type="hidden" name="up_sp_id" id="up_sp_id"/>
                    <div class="form-group mt-3">
                        <label for="up_sel_test" >{{ __('strings.lb_title') }}</label>
                        <select name="up_sel_test" id="up_sel_test" class="form-select form-control"></select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <span id="fn_paper_loading" class="d-none"><i class="fa fa-spin fa-spinner"></i> </span>
                <button type="button" class="btn btn-primary" id="btnAddPaper"><i class="fa fa-check-circle"></i> {{ __('strings.fn_okay') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="sendConfirmModalCenter" tabindex="-1" role="dialog" aria-labelledby="sendConfirmModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendConfirmModalLongTitle">{{ __('strings.str_sms_send_confirm') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="sendFrm" id="sendFrm" method="post">
                    @csrf
                    <input type="hidden" name="up_sp_code" id="up_sp_code"/>
                    <input type="hidden" name="up_send_able" id="up_send_able"/>
                    <div class="mt-3" id="sendFrmText">{{ __('strings.lb_sms_send_now') }}</div>
                </form>
            </div>
            <div class="modal-footer">
                <span id="fn_paper_send_loading" class="d-none"><i class="fa fa-spin fa-spinner"></i> </span>
                <button type="button" class="btn btn-primary d-none" id="btnSendCancelPaper" data-dismiss="modal"><i class="fa fa-check"></i> {{ __('strings.fn_okay') }}</button>
                <button type="button" class="btn btn-primary" id="btnSendPaper"><i class="fa fa-paper-plane"></i> {{ __('strings.fn_okay') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

    <script type="text/javascript">
        //
        let hakgiData = [];
        let selInput;
        // 년도 변경 시
        $(document).on("change","#section_year",function (){
            event.stopImmediatePropagation();
            event.preventDefault();

            if ($(this).val() === ""){
                showAlert("{{ __('strings.str_select_year') }}");
                return;
            }

            if ($("#section_grade").val() === ""){
                showAlert("{{ __('strings.str_select_hakyon') }}");
                return;
            }

            $("#loaderModalCenter").modal("show");

            $.ajax({
                type:"POST",
                url:"/getHakgisAllJson",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "grade_id":$("#section_grade").val(),
                    "year":$("#section_year").val()
                },
                success:function(msg){
                    //
                    hakgiData = [];
                    $("#section_weeks").empty();
                    $("#section_hakgi").empty();

                    $("<option value=''>{{ __('strings.fn_select_item') }}</option>").appendTo($("#section_hakgi"));
                    $("<option value=''>{{ __('strings.fn_select_item') }}</option>").appendTo($("#section_weeks"));

                    $.each(msg.data,function (i,obj){
                        hakgiData.push({"id":obj.id,"weekCnt":obj.weeks});
                        $("<option value='" + obj.id + "'>" + obj.hakgi_name + "</option>").appendTo($("#section_hakgi"));
                    });

                    $("#loaderModalCenter").modal("hide");
                },
                error:function (e1,e2,e3){
                    showAlert(e2);
                    $("#loaderModalCenter").modal("hide");
                    return;
                }
            });
        });

        // delete
        $(document).on("click",".fn_del_item",function (){
            event.preventDefault();
            let nowCode = $(this).attr("fn_code");
            $("#del_id").val(nowCode);
            $("#confirmModalCenter").modal("show");
            console.log(nowCode);
        });

        // 시험추가
        $(document).on("click",".fn_add_item",function (){
            //
            event.preventDefault();
            $("#paperModalCenter").modal("show");
            let codeVal = $(this).attr("fn_code");
            $("#up_sp_id").val(codeVal);

            $.ajax({
                type:"POST",
                url:"/getTestPapersJson",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "sp_id":codeVal
                },
                dataType:"json",
                success:function(msg){
                    //
                    $("#up_sel_test").empty();
                    $("<option value=''>{{ __('strings.lb_select_test_to_add') }}</option>").appendTo($("#up_sel_test"));
                    $.each(msg.data,function (i,obj){
                        //
                        $("<option value='" + obj.id + "'>" + obj.form_title + "</option>").appendTo($("#up_sel_test"));
                    });
                },
                error:function (e1,e2,e3){
                    showAlert(e2);
                    return;
                }
            })
        });

        // 시험 추가 저장
        $(document).on("click","#btnAddPaper",function (){
            //
            if ($("up_sel_test").val() === ""){
                showAlert("{{ __('strings.lb_select_test_to_add') }}");
                return;
            }

            $("#fn_paper_loading").removeClass("d-none");
            $("#frmAdd").prop({"action":"/addSmsPapers"}).submit();
        });

        $(document).on("keyup",".fn_input",function (){
            //console.log("row Total (Y or N) : " + $(this).attr("fn_total") + " / group : " + $(this).attr("fn_group"));
            let grpId = $(this).attr("fn_group");
            let isTotal = $(this).attr("fn_total");
            let nowRow = $(this).attr("fn_row");
            let nowVal = $(this).val();
            let maxScore = $(this).attr("max");
            let minScore = $(this).attr("min");

            if ($(this).val() > $(this).attr("max")){
                $(this).val(maxScore);
                return;
            }

            if ($(this).val() < $(this).attr("min")){
                $(this).val(minScore);
                return;
            }

            selInput = $(this);
            let itemsArray = [];
            for (let i =0; i < $(".fn_input").length; i++){
                console.log("root group id :" + grpId + " , cur group : " + $(".fn_input").eq(i).attr("fn_group"));
                if ($(".fn_input").eq(i).attr("fn_group") === grpId && $(".fn_input").eq(i).attr("fn_row") === nowRow){
                    itemsArray.push($(".fn_input").eq(i));
                }
            }

            if (itemsArray.length > 1){
                let sumVal = 0;
                for (let j=0; j < itemsArray.length -1; j++){
                    sumVal += parseInt(itemsArray[j].val());
                }
                itemsArray[itemsArray.length -1].val(sumVal);
            }
            //console.log("items count ; " + itemsArray.length);
        });

        $(document).on("focus",".fn_input",function (){
            $(this).select();
        });

        // 의견 폼을 클릭할 때 이벤트
        let opinionRowId;
        $(document).on("click",".fn_opinion",function (){
            let curOpinion = $(this).val();
            let curId = $(this).attr("fn_op_id");
            opinionRowId = $(this).attr("id");
            $("#infoModalCenter").modal("show");
            $("#info_id").val(curId);
            $("#info_name").val(curOpinion).focus();
        });

        // 모달에서 저장하기를 클릭할 때 이벤트
        $(document).on("click","#btnOpSubmit",function (){
            $.ajax({
                type:"POST",
                url:"/saveOpinion",
                dataType:"json",
                data:$("#opFrm").serialize(),
                success:function (msg){
                    if (msg.result === "true"){
                        $("#" + opinionRowId).val($("#info_name").val());
                        $("#infoModalCenter").modal("hide");
                        $("#info_name").val("");
                    }else{
                        showAlert("{{ __('strings.err_fail_to_save') }}");
                        return;
                    }
                }
            })

        });

        $(document).on("click","#btnTest",function (){
            console.log($(".fn_input").length);
        });


        $(document).on("click","#btnAllSend",function (){
            event.preventDefault();
            $("#smsFrm").submit();
        });

        $(document).on("change","#section_academy,#section_grade",function (){
            event.preventDefault();
            if ($("#section_academy").val() === ""){
                showAlert("{{ __('strings.str_select_academy') }}");
                return;
            }

            $("#loaderModalCenter").modal("show");

            $.ajax({
                type:"POST",
                url:"/getClassesJson",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "section_academy":$("#section_academy").val(),
                    "section_grade":$("#section_grade").val(),
                    "section_year":$("#section_year").val(),
                },
                success:function (msg){
                    $("#section_class").empty();
                    $("<option value=''>{{ __('strings.fn_all') }}</option>").appendTo($("#section_class"));
                    $.each(msg.data,function (i,obj){
                        $("<option value='" + obj.id + "'>" + obj.class_name + "</option>").appendTo($("#section_class"));
                    });

                    $("#section_hakgi").empty();
                    hakgiData = [];
                    $("<option value=''>{{ __('strings.fn_all') }}</option>").appendTo($("#section_hakgi"));
                    $.each(msg.hakgi,function(i,obj){
                        $("<option value='" + obj.id + "'>" + obj.hakgi_name + "</option>").appendTo($("#section_hakgi"));
                        hakgiData.push({"id":obj.id,"weekCnt":obj.weeks});
                        //console.log("add week : " + obj.weeks);
                    });


                    $("#loaderModalCenter").modal("hide");
                },
                error:function (e1,e2,e3){
                    showAlert(e2);
                    $("#loaderModalCenter").modal("hide");
                    return;
                }
            })
        });

        // 학기를 선택할 때
        $(document).on("change","#section_hakgi",function (){
            if ($(this).val() === ""){
                return;
            }
            //$("#loaderModalCenter").modal("show");

            let nowSelHakgi = $(this).val();
            //console.log(typeof(nowSelHakgi));
            let maxWeek = 0;
            for(let i=0; i < hakgiData.length; i++){
                let nowHakgiDataId = hakgiData[i].id;
                console.log(typeof(nowHakgiDataId));
                if (parseInt(nowSelHakgi) === nowHakgiDataId){
                    maxWeek = hakgiData[i].weekCnt;
                    break;
                }
            }

            if (maxWeek > 0){
                $("#section_weeks").empty();
                $("<option value=''>{{ __('strings.fn_select_item') }}</option>").appendTo($("#section_weeks"));
                for (let i=1; i <= maxWeek; i++){
                    $("<option value='" + i + "'>" + i + " {{ __('strings.lb_weeks')}}</option>").appendTo($("#section_weeks"));
                }
            }
        });

        // 불러오기 버튼 클릭 시
        $(document).on("click","#btnLoad",function (){
            event.preventDefault();
            if ($("#section_academy").val() === ""){
                showAlert("{{ __('strings.str_select_academy') }}");
                return;
            }

            if ($("#section_grade").val() === ""){
                showAlert("{{ __('strings.str_select_grades') }}");
                return;
            }

            if ($("#section_class").val() === ""){
                showAlert("{{ __('strings.str_select_class') }}");
                return;
            }

            if ($("#section_year").val() === ""){
                showAlert("{{ __('strings.str_select_year') }}");
                return;
            }

            if ($("#section_hakgi").val() === ""){
                showAlert("{{ __('strings.str_select_hakgi') }}");
                return;
            }

            location.href = "/SmsFront/" + $("#section_academy").val() + "/" + $("#section_grade").val() + "/" + $("#section_class").val() +
                "/" + $("#section_year").val() + "/" + $("#section_hakgi").val() + "/" + $("#section_weeks").val();
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

            $("#up_ac_id").val($("#section_academy").val());
            $("#up_grade_id").val($("#section_grade").val());
            $("#up_cl_id").val($("#section_class").val());


            $("#tfFrm").submit();
        });

        // 폼 매칭하기 클릭
        $(document).on("click","#btnFormLoad",function (){
            if ($("#section_grade").val() === ""){
                showAlert("{{__('strings.str_select_grades')}}");
                return;
            }
            $("#infoModalCenter").modal("show");
            loadCurrentForms();
        });

        let testFormsData = [];

        function loadCurrentForms(){
            $("#fn_loading").removeClass("d-none");
            $.ajax({
                type:"POST",
                url:"/getTestFormsInSmsJson",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "up_grade_id":$("#section_grade").val()
                },
                success:function (msg){
                    $("#ls_forms").empty();
                    testFormsData = [];
                    let sObjs = [];
                    $.each(msg.data,function (i,obj){
                        let sArray = obj.subjects;
                        sObjs = [];
                        $.each(sArray,function (j,obj2){
                            sObjs.push({"title":obj2.sj_title});
                        });
                        testFormsData.push({"tfId":obj.tf_id,"tfName":obj.form_title,"tfSubjects":sObjs});
                        if (obj.exam === "Y"){
                            $("<div class='list-group-item ml-1 mr-1'><input type='checkbox' name='tf_ids[]' value='" + obj.id + "' class='form-check-input'/> <label class='form-check-label fn_in_tf'><i class='fa fa-star'></i> " + obj.form_title + " ({{ __('strings.lb_test_count') }} : " + obj.items_count + ")</label></div>").appendTo($("#ls_forms"));
                        }else{
                            $("<div class='list-group-item ml-1 mr-1'><input type='checkbox' name='tf_ids[]' value='" + obj.id + "' class='form-check-input'/> <label class='form-check-label fn_in_tf'>" + obj.form_title + " ({{ __('strings.lb_test_count') }} : " + obj.items_count + ")</label></div>").appendTo($("#ls_forms"));
                        }

                    });
                    $("#fn_loading").addClass("d-none");
                },
                error:function (e1,e2,e3){
                    showAlert(e2);
                    $("#fn_loading").addClass("d-none");
                    return;
                }
            });
        }

        // 시험 이름을 클릭할 때.
        $(document).on("click",".fn_in_tf",function (){
            event.preventDefault();
            let selIndex = $(".fn_in_tf").index($(this));
            let subjects = testFormsData[selIndex].tfSubjects;
            $("#ls_subjects").empty();

            $("#ls_forms > div").removeClass("active");
            $("#ls_forms > div").eq(selIndex).addClass("active");

            $.each(subjects,function (i,obj){
                $("<div class='list-group-item'>" + obj.title + "</div>").appendTo($("#ls_subjects"));
            });
        });

        // 관리 클릭 시...
        $(document).on("click",".fn_item",function (){
            //
            event.preventDefault();
            let spId = $(this).attr("fn_code");

            $("#del_id").val(spId);

            $.ajax({
                type:"POST",
                url:"/SmsCheckToSend",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "spId":spId
                },
                success:function (msg){
                    //
                    if (msg.result === "true"){
                        $("#sendConfirmModalCenter").modal("show");
                        $("#sendFrmText").html("{{ __('strings.lb_sms_send_now') }}");

                        $("#up_sp_code").val(spId);   // code
                        $("#up_send_able").val("true");
                        $("#btnSendPaper").removeClass("d-none");
                        $("#btnSendCancelPaper").addClass("d-none");
                    }else{
                        $("#sendConfirmModalCenter").modal("show");
                        $("#up_send_able").val("false");
                        $("#sendFrmText").html("{{ __('strings.str_sms_send_unable') }}");
                        $("#btnSendPaper").addClass("d-none");
                        $("#btnSendCancelPaper").removeClass("d-none");
                    }
                },
                error:function (e1,e2,e3){
                    showAlert(e2);
                    return;
                }
            });
        });

        // 신규 팝업에서 보내기 버튼 클릭 시
        $(document).on("click",'#btnSendPaper',function (){
            event.preventDefault();
            if ($("#up_send_able").val() === "false"){
                showAlert("{{ __('strings.str_sms_send_unable') }}");
                return;
            }else{
                $("#fn_paper_send_loading").removeClass("d-none");
                $("#sendFrm").prop({"action":"/sendSms"}).submit();
            }
        });

        $(document).on("click","#btnSubmit",function (){
            //
            event.preventDefault();
            if ($("#section_class").val() === ""){
                showAlert("{{ __('strings.str_select_class') }}");
                return;
            }

            if ($("#section_year").val() === ""){
                showAlert("{{ __('strings.str_select_year') }}");
                return;
            }

            if ($("#section_hakgi").val() === ""){
                showAlert("{{ __('strings.str_select_hakgi') }}");
                return;
            }

            if ($("#section_weeks").val() === ""){
                showAlert("{{ __('strings.str_select_week') }}");
                return;
            }

            $("#fn_loading").removeClass("d-none");
            $("#up_academy").val($("#section_academy").val());
            $("#up_grade").val($("#section_grade").val());
            $("#up_class").val($("#section_class").val());
            $("#up_year").val($("#section_year").val());
            $("#up_hakgi").val($("#section_hakgi").val());
            $("#up_week").val($("#section_weeks").val());

            $("#opFrm").submit();
        });

        $(document).on("click","#btnTfDelete",function (){
            event.preventDefault();

            $("#confirmModalCenter").modal("show");
        });

        $(document).on("click","#btnDeleteDo",function (){
            $("#delFrm").submit();
            $("#fn_confirm_loading").removeClass("d-none");
        });


        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
