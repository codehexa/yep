@extends('layouts.app_bms')

@section('content')
<div class="container-fluid">
    <h5><i class="fa fa-wind"></i> {{ __('strings.lb_bms_title') }} &gt; {{ __('strings.lb_page_elements_set') }}</h5>

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
        <div class="list-group mt-3" id="page_list">
            @foreach($data as $datum)
                <div class="list-group-item">
                    <input type="hidden" name="up_sort_id[]" value="{{ $datum->id }}"/>
                    <div class="d-flex justify-content-between">
                        <label ><span class="fn_field_name_val">{{ $datum->field_name }}</span> | <span class="text-primary border border-1 p-1 fn_field_tag_val">{{ $datum->field_tag }}</span> </label>
                        <div class="btn btn-group btn-group-sm">
                            <button class="btn btn-sm btn-primary fn_edit_element"><i class="fa fa-edit"></i> {{ __('strings.fn_modify') }}</button>
                            <button class="btn btn-danger btn-sm fn_delete_element" data-id="{{ $datum->id }}"><i class="fa fa-trash"></i> {{ __('strings.fn_delete') }}</button>
                        </div>
                    </div>

                    <div class="mt-1 fn_rows">
                        {{ $datum->field_function }}
                    </div>
                    <div class="mt-1 form-inline">
                        <i class="fa fa-arrow-right"></i>
                        <div class="ml-2 fn_list_group_item"></div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-2">
            <div class="btn btn-group btn-sm">
                <button class="btn btn-primary btn-sm" id="btnSortSave"><i class="fa fa-save"></i> {{ __('strings.fn_sort_save') }}</button>
                <span id="sortLoader" class="d-none"><i class="fa fa-spin fa-spinner"></i> </span>
            </div>

        </div>

        <div class="mt-3">
            <h6>{{ __('strings.lb_page_input_form') }}</h6>
            <form name="frm" id="frm" method="post" action="/bms/pageAddSetting">
                <input type="hidden" name="up_pg_id" id="up_pg_id">
                @csrf
                <div class="form-group list-group border border-3 border-info">
                    <div class="list-group-item">
                        <div class="form-inline">
                            <label >{{ __('strings.lb_title') }}</label>
                            <input type="text" name="up_field_title" id="up_field_title" class="form-control form-control-sm ml-2"/>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="form-inline">
                            <label>{{ __('strings.lb_page_elements_tag') }}</label>
                            <select name="up_field_tags" id="up_field_tags" class="form-control form-control-sm ml-2">
                                <option value="">{{ __('strings.fn_select_item') }}</option>
                                @foreach($fieldsTitles as $title)
                                    <option value="{{ $title->code }}">{{ $title->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="list-group-item">
                        <div class="form-inline">
                            @foreach($tags as $keyField)
                                <button class="btn btn-outline-secondary btn-sm mr-2 mb-1 fn_tag_btn" fn_key="{{ $keyField->tag }}">{{ $keyField->title }}</button>
                            @endforeach
                        </div>
                    </div>

                    <div class="list-group-item">
                        <label class="text-nowrap mr-3">{{ __('strings.lb_input_strings') }}</label>
                        <textarea name="up_field_function" id="up_field_function" class="form-control form-text form-control-sm "></textarea>
                    </div>
                </div>
            </form>
            <div class="btn-group btn-group-sm mt-2">
                <button id="btnAdd" class="btn btn-outline-primary">
                    <i class="fa fa-plus"></i> {{ __('strings.fn_add') }}
                </button>
                <button id="btnSave" class="btn btn-outline-primary">
                    <i class="fa fa-save"></i> {{ __('strings.fn_save') }}
                </button>
                <span id="saveLoader" class="d-none ml-2"><i class="fa fa-spin fa-spinner"></i> {{ __('strings.fn_saving') }}</span>
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
                <form name="delFrm" id="delFrm" method="post" action="/bms/delPageSetting">
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

        let _keysFields = @json($tags);
        let _formListIndex = -1;    // 폼 정보 입력 선택 인덱스
        let _txtAreaPosition = -1;  // 메인 입력 폼의 커서 위치

        $(document).on("click","#btnSave",function (){
            event.preventDefault();
            if ($("#up_field_title").val() === ""){
                showAlert("{{ __('strings.lb_input_page_name') }}");
                return;
            }

            if ($("#up_field_tags").val() === ""){
                showAlert("{{ __('strings.err_select_tag') }}");
                return;
            }

            if ($("#up_field_function").val() === ""){
                showAlert("{{ __('strings.err_input_text') }}");
                return;
            }

            $("#saveLoader").removeClass("d-none");
            $("#frm").submit();
        });

        $(document).on("click","#btnAdd",function (){
            event.preventDefault();
            $("#up_field_title,#up_field_tags,#up_field_function").val("");
            $("#frm").prop({"action":"/bms/pageAddSetting"});
            $("#up_pg_id").val("");
        })

        function printText(){
            $.each($(".fn_rows"),function (i,obj){
                let _txt = codeChange($(obj).text());
                $(".fn_list_group_item").eq(i).html(_txt);
            });
        }

        // code 에 따라 스트링 변경하기
        function codeChange(txt){
            $.each(_keysFields,function (i,obj){
                let regEx = new RegExp(obj.tag,"gi");

                txt = txt.replace(regEx,"<span class='text-primary'>'" + obj.title + "'</span>");
            });
            return txt;
        }

        // text form cursor position
        $(document).on("click","#up_field_function",function (){
            _txtAreaPosition = $(this).prop('selectionStart');
        });

        $(document).on("keyup","#up_field_function",function(){
            _txtAreaPosition = $(this).prop('selectionStart');
        });

        // tag button click
        $(document).on('click','.fn_tag_btn',function (){
            event.preventDefault();
            console.log('btn : ' + $(this).attr("fn_key"));
            let _savedTxt = $("#up_field_function").val();
            let _preTxt = _savedTxt.substring(0,_txtAreaPosition);
            let _aftTxt = _savedTxt.substring(_txtAreaPosition,_savedTxt.length);
            let _concatTxt = _preTxt + $(this).attr("fn_key") + _aftTxt;

            $("#up_field_function").val(_concatTxt);

        });


        $(document).ready(function (){
            $("#page_list").sortable();
            printText();
        });

        $(document).on("click",".fn_edit_element",function (){
            let _idx = $(".fn_edit_element").index($(this));
            $("#up_pg_id").val($("input[name='up_sort_id[]']").eq(_idx).val());
            $("#up_field_title").val($(".fn_field_name_val").eq(_idx).text());
            $("#up_field_tags").val($(".fn_field_tag_val").eq(_idx).text());
            $("#up_field_function").val($.trim($(".fn_rows").eq(_idx).text()));
            $("#frm").prop({"action":"/bms/pageStoreSetting"});
        });

        $(document).on("click","#btnSortSave",function (){
            event.preventDefault();
            let _sortIds = "";
            let _sortArray = [];
            $.each($("input[name='up_sort_id[]'"),function (i,obj){
                _sortArray.push($(obj).val());
            });

            _sortIds = _sortArray.toString();

            $("#sortLoader").removeClass("d-none");

            $.ajax({
                type:"POST",
                url:"/bms/saveSort",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "sortIds":_sortIds
                },
                success:function(msg){
                    if (msg.result === "true"){
                        showAlert("{{ __('strings.fn_save_complete') }}");
                        $("#sortLoader").addClass("d-none");
                        return;
                    }else{
                        showAlert("{{ __('strings.fn_save_false') }}");
                        $("#sortLoader").addClass("d-none");
                        return;
                    }
                }
            })
        });

        $(document).on("click",".fn_delete_element",function (){
            event.preventDefault();
            $("#confirmModalCenter").modal("show");
            let _dataId = $(this).data("id");
            $("#del_id").val(_dataId);
        })

        $(document).on("click","#btnDeleteDo",function (){
            $("#confirm_spin").removeClass("d-none");
            $("#delFrm").submit();
        });

        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
