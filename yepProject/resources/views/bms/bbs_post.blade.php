@extends('layouts.app_bms')

@section('content')
<div class="container-fluid">
    <h5><i class="fa fa-wind"></i> {{ __('strings.lb_bms_bbs') }} </h5>
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
        <a href="/bms/bbs" class="btn btn-primary btn-sm"><i class="fa fa-list"></i> {{ __('strings.lb_bbs_list') }}</a>
    </div>

    <div class="mt-3">
        <form name="postFrm" id="postFrm" method="post" action="/bms/addBbs">
            @csrf
            <div class="list-group">
                <div class="list-group-item d-flex justify-content-between">
                    <label for="up_academy" class="text-nowrap form-check-label mt-2 mr-2">{{ __('strings.lb_academy_name') }}</label>
                    <select name="up_academy" id="up_academy" class="form-control">
                        @if (\Illuminate\Support\Facades\Auth::user()->power == \App\Models\Configurations::$USER_POWER_TEACHER)
                            @foreach ($academies as $academy)
                                <option value="{{ $academy->id }}">{{ $academy->ac_name }}</option>
                            @endforeach
                        @else
                            <option value="0">{{ __('strings.fn_all') }}</option>
                            @foreach ($academies as $academy)
                                <option value="{{ $academy->id }}">{{ $academy->ac_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                @if (\Illuminate\Support\Facades\Auth::user()->power != \App\Models\Configurations::$USER_POWER_TEACHER)
                    <div class="list-group-item form-check">
                        <label for="up_type_all" class="form-check-label mr-4">{{ __('strings.lb_bbs_type_all') }}</label>
                        <input type="checkbox" name="up_type_all" id="up_type_all" value="{{ \App\Models\Configurations::$BBS_TYPE_ALL }}" class="form-check-input"/>
                    </div>
                @endif
                <div class="list-group-item">
                    <input type="text" name="up_title" id="up_title" class="form-control " placeholder="{{ __('strings.guide_input_title') }}"/>
                </div>
                <div class="list-group-item">
                    <textarea name="up_comment" id="up_comment" class="form-control" style="height: 40rem"></textarea>
                </div>
            </div>
        </form>
        <button id="btnSubmit" class="btn btn-primary btn-sm mt-3"><i class="fa fa-save"></i> {{ __('strings.fn_save') }}</button>
        <i class="fa fa-spinner fa-spin d-none mt-2 ml-2" id="loadPost"></i>
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
        //post
        $(document).on("click","#btnSubmit",function (){
            event.preventDefault();

            if ($("#up_type_all") !== undefined && !$("#up_type_all").is(":checked") && $("#up_academy").val() === "0"){
                showAlert("{{ __('strings.err_bbs_if_check_all') }}");
                return;
            }

            if ($("#up_title").val() === ""){
                showAlert("{{ __('strings.err_input_title') }}");
                return;
            }

            $("#loadPost").removeClass("d-none");

            $("#postFrm").submit();
        });

        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
