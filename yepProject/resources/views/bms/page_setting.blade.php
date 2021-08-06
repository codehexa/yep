@extends('layouts.app_bms')

@section('content')
<div class="container-fluid">
    <h5><i class="fa fa-wind"></i> {{ __('strings.lb_bms_title') }} &gt; {{ __('strings.lb_page_setting') }}</h5>

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
        <div class="list-group" id="page_list">
            @foreach($data as $datum)
                <div class="list-group-item">
                    <label>{{ $datum->field_index }}. {{ $datum->field_name }}
                        @if ($datum->field_type == "ARRAY_START")
                            <span class="font-weight-bold">{{ __('strings.lb_array_type_start') }}</span>
                        @elseif ($datum->field_type == "ARRAY_END")
                            <span class="font-weight-bold">{{ __('strings.lb_array_type_end') }}</span>
                        @endif
                    </label>
                    <div class="mt-1">
                        <div class="fn_rows" fn_index="{{ $datum->field_index }}">{{ $datum->field_function }}</div>
                        <textarea name="field_vals[]" class="bg-light fn_textarea form-control" fn_id="{{ $datum->id }}">{{ $datum->field_function }}</textarea>
                        <div class="my-1">
                            <button class="btn btn-sm btn-outline-primary"><i class="fa fa-upload"></i> {{ __('strings.fn_modify') }}</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-3">
            <h6>{{ __('strings.lb_page_input_form') }}</h6>
            <form name="frm" id="frm" method="post" action="/bms/pageAddSetting">
                @csrf
                <div class="form-group list-group border border-3 border-info">
                    <div class="list-group-item">
                        <label >{{ __('strings.lb_title') }}</label>
                        <input type="text" name="up_field_title" id="up_field_title" class="form-control form-control-sm"/>
                    </div>
                    <div class="list-group-item">
                        <div class="list-inline">
                            @foreach($tags as $keyField)
                                @if ($keyField->type == "STRING")
                                    <button fn_key="{{ $keyField->tag }}" class="list-inline-item mb-1 btn btn-sm btn-outline-secondary text-nowrap fn_tag_btn">{{ $keyField->title }}</button>
                                @else
                                    <button fn_key="{{ $keyField->tag }}" class="list-inline-item mb-1 btn btn-sm btn-outline-info text-nowrap fn_tag_btn"><i class="fa fa-filter"></i> {{ $keyField->title }}</button>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="list-group-horizontal list-group">
                            <label class="text-nowrap mr-3">{{ __('strings.lb_input_strings') }}</label>
                            <select name="up_field_type" id="up_field_type" class="form-control form-control-sm">
                                <option value="TXT">{{ __('strings.lb_input_string') }}</option>
                                <option value="ARRAY_START">{{ __('strings.lb_array_type_start') }}</option>
                                <option value="ARRAY_END">{{ __('strings.lb_array_type_end') }}</option>
                                <option value="FUNCTION">{{ __('strings.lb_input_function') }}</option>
                            </select>
                        </div>

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

            if ($("#up_field_function").val() === ""){
                showAlert("{{ __('strings.lb_input_page_function') }}");
                return;
            }

            $("#frm").submit();
        });

        $(document).on("click","#btnAdd",function (){
            event.preventDefault();
            $("#up_field_title,#up_field_function").val("");
        })

        function printText(){
            $.each($(".fn_rows"),function (i,obj){
                let _txt = codeChange($(obj).text());
                $(".fn_rows").eq(i).html(_txt);
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

        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
