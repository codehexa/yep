@extends('layouts.app_bms')

@section('content')
<div class="container-fluid">
    <h5><i class="fa fa-wind"></i> {{ __('strings.lb_bms_title') }} &gt; {{ __('strings.lb_bms_hworks_manage') }} &gt; {{ __('strings.lb_hwork_input_title') }}</h5>

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
        <div class="list-inline">
            <div class="form-group form-inline">
                <div class="form-inline btn-group btn-group-sm ml-2">
                    <a href="{{ url()->previous() }}" class="btn btn-outline-primary" ><i class="fa fa-list"></i> {{ __('strings.fn_list') }}</a>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <form name="frm" id="frm" method="post" action="
            @if (isset($data->id))
                /bms/hworkStore
            @else
                /bms/hworkSave
            @endif">
                @csrf
                @if (isset($data->id))
                    <input type="hidden" name="saved_hw_id" id="saved_hw_id" value="{{ $data->id }}"/>
                @endif
                <div class="list-group">
                    <div class="list-group-item">
                        <label>{{ __('strings.lb_bms_hworks_type') }}</label>
                        <select name="up_sgid" id="up_sgid" class="form-control form-control-sm">
                            <option value="">{{ __('strings.fn_select_item') }}</option>
                            @foreach($sgrades as $sgrade)
                                <option value="{{ $sgrade->id }}"
                                        @if (isset($data->hwork_sgid) && $data->hwork_sgid == $sgrade->id)
                                            selected
                                        @endif
                                >{{ $sgrade->scg_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="list-group-item">
                        <label>{{ __('strings.lb_bms_school_class') }}</label>
                        <select name="up_class" id="up_class" class="form-control form-control-sm">
                            <option value="">{{ __('strings.fn_select_item') }}</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}"
                                        @if (isset($data->hwork_class) && $data->hwork_class == $subject->id)
                                            selected
                                        @endif
                                >{{ $subject->subject_title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="list-group-item">
                        <div class="row">
                            <div class="col-4">
                                <div class="list-inline">
                                    @foreach($functions as $function)
                                        <button class="btn btn-outline-primary btn-sm mb-1 fn_function" fn_code="{{ $function->tag }}">{{ $function->title }}</button>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="list-group" id="ls_fields">
                                    <div class="list-group-item">
                                        <label>{{ __('strings.lb_bms_class') }}</label>
                                        <input type="text" name="up_content" id="up_content" class="fn_item form-control form-control-sm"
                                               @if (isset($data->hwork_content))
                                                   value="{{ $data->hwork_content }}"
                                               @endif
                                        />
                                    </div>
                                    <div class="list-group-item">
                                        <label>{{ __('strings.lb_bms_dt') }}</label>
                                        <input type="text" name="up_dt" id="up_dt" class="fn_item form-control form-control-sm"
                                               @if (isset($data->hwork_dt))
                                                   value="{{ $data->hwork_dt }}"
                                               @endif
                                        />
                                    </div>
                                    <div class="list-group-item">
                                        <label>{{ __('strings.lb_bms_books') }}</label>
                                        <input type="text" name="up_book" id="up_book" class="fn_item form-control form-control-sm"
                                               @if (isset($data->hwork_book))
                                                   value="{{ $data->hwork_book }}"
                                                @endif
                                        />
                                    </div>
                                    <div class="list-group-item">
                                        <label>{{ __('strings.lb_bms_output_first') }}</label>
                                        <input type="text" name="up_output_first" id="up_output_first" class="fn_item form-control form-control-sm"
                                               @if (isset($data->hwork_output_first))
                                                   value="{{ $data->hwork_output_first }}"
                                                @endif
                                        />
                                    </div>
                                    <div class="list-group-item">
                                        <label>{{ __('strings.lb_bms_output_second') }}</label>
                                        <input type="text" name="up_output_second" id="up_output_second" class="fn_item form-control form-control-sm"
                                               @if (isset($data->hwork_output_second))
                                                   value="{{ $data->hwork_output_second }}"
                                                @endif
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="mt-3">
                <button id="btn_save" class="btn btn-primary"><i class="fa fa-save"></i> {{ __('strings.fn_save') }}</button>
                <button id="btn_reset" class="btn btn-primary"><i class="fa fa-redo"></i> {{ __('strings.fn_reset') }}</button>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="infoModalCenter" tabindex="-1" role="dialog" aria-labelledby="infoModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLongTitle">{{ __('strings.lb_bms_hworks_information') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="cmFrm" id="cmFrm" method="post" action="/bms/addHworks">
                    @csrf
                    <input type="hidden" name="cm_id" id="cm_id" value=""/>

                    <div class="form-group">
                        <label for="up_school_grade">{{ __('strings.lb_school_grade') }}</label>
                        <select name="up_school_grade" id="up_school_grade" class="form-control">
                            <option value="">{{ __('strings.fn_select_item') }}</option>

                        </select>
                    </div>

                    <div class="form-group">
                        <label for="up_class">{{ __('strings.lb_bms_school_class') }}</label>
                        <input type="text" name="up_class" id="up_class" class="form-control" placeholder="{{ __('strings.lb_insert_class') }}"/>
                    </div>
                    <div class="form-group">
                        <label for="up_content">{{ __('strings.lb_bms_class') }}</label>
                        <input type="text" name="up_content" id="up_content" class="form-control" placeholder="{{ __('strings.lb_insert_content') }}"/>
                    </div>
                    <div class="form-group">
                        <label for="up_dt">{{ __('strings.lb_bms_dt') }}</label>
                        <input type="text" name="up_dt" id="up_dt" class="form-control" placeholder="{{ __('strings.lb_insert_dt') }}"/>
                    </div>
                    <div class="form-group">
                        <label for="up_books">{{ __('strings.lb_bms_books') }}</label>
                        <input type="text" name="up_books" id="up_books" class="form-control" placeholder="{{ __('strings.lb_insert_books') }}"/>
                    </div>
                    <div class="form-group">
                        <label for="up_class">{{ __('strings.lb_bms_output_first') }}</label>
                        <input type="text" name="up_output_first" id="up_output_first" class="form-control" placeholder="{{ __('strings.lb_insert_output_first') }}"/>
                    </div>
                    <div class="form-group">
                        <label for="up_class">{{ __('strings.lb_bms_output_second') }}</label>
                        <input type="text" name="up_output_second" id="up_output_second" class="form-control" placeholder="{{ __('strings.lb_insert_output_second') }}"/>
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


        let _listIndex = -1;    // 현재 포커스 된 위치
        $(document).on("click",".fn_item",function (){
            _listIndex = $(".fn_item").index($(this));
            console.log(_listIndex);
            $(".fn_item").parent().removeClass("active");
            $(".fn_item").eq(_listIndex).parent().addClass("active");
        });

        // buttons click event
        $(document).on("click",".fn_function",function (){
            //
            event.preventDefault();
            let _curTag = $(this).attr("fn_code");
            let _str = $(".fn_item").eq(_listIndex).val();
            let _preStr = _str.substring(0,$(".fn_item").eq(_listIndex).prop('selectionStart'));
            let _aftStr = _str.substring($(".fn_item").eq(_listIndex).prop('selectionStart'),_str.length);

            $(".fn_item").eq(_listIndex).val(_preStr + _curTag + _aftStr);
        });

        // save
        $(document).on("click","#btn_save",function (){
            event.preventDefault();
            if ($("#up_sgid").val() === ""){
                showAlert("{{ __('strings.str_select_sgrade') }}");
                return;
            }

            if ($("#up_class").val() === ""){
                showAlert("{{ __('strings.str_select_class') }}");
                return;
            }

            if ($("#up_content").val() === ""){
                showAlert("{{ __('strings.str_input_content') }}");
                return;
            }

            if ($("#up_dt").val() === ""){
                showAlert("{{ __('strings.str_input_dt') }}");
                return;
            }

            if ($("#up_book").val() === ""){
                showAlert("{{ __('strings.str_input_book') }}");
                return;
            }

            if ($("#up_output_first").val() === ""){
                showAlert("{{ __('strings.str_input_output_first') }}");
                return;
            }

            if ($("#up_output_second").val() === ""){
                showAlert("{{ __('strings.str_input_output_second') }}");
                return;
            }

            $("#frm").submit();
        });

        $(document).on("click","#btn_reset",function (){
            location.href="/bms/hworkInput";
        });


        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
        }
    </script>
@endsection
