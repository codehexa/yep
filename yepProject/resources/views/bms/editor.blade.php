@extends('layouts.app_bms')

@section('content')
<div class="container-fluid">
    <h5><i class="fa fa-wind"></i> {{ __('strings.lb_bms_title') }} &gt; {{ __('strings.lb_editor') }}</h5>

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
            <div class="col-3 bg-secondary overflow-auto p-2">
                <div class="bg-white rounded list-group">
                    <div class="list-group-item">
                        <h6>기본 정보 셋팅</h6>
                        <div class="form-group">
                            <label>Semester</label>
                            <select name="ed_semester" id="ed_semester" class="form-control form-control-sm">
                                <option value="">{{ __('strings.fn_select_item') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <h6>{{ __('strings.lb_bms_sheets_list') }} <button class="btn btn-sm btn-primary" title="{{ __('strings.fn_load') }}"><i class="fa fa-cloud-download-alt"></i> </button> </h6>
                        <div class="d-flex justify-content-between">
                            <select name="up_bs_sheets" id="up_bs_sheets" class="form-control form-control-sm">
                                <option value="">{{ __('strings.fn_select_item') }}</option>
                            </select>
                            <div class="btn-group btn-group-sm ml-1">
                                <button class="btn btn-sm btn-outline-primary"><i class="fa fa-plus"></i> </button>
                                <button class="btn btn-sm btn-outline-secondary"><i class="fa fa-edit"></i> </button>
                                <button class="btn btn-sm btn-outline-danger"><i class="fa fa-trash"></i> </button>
                            </div>
                        </div>

                    </div>
                    <div class="list-group-item">
                        <label>{{ __('strings.lb_semester') }}</label>
                        <select name="up_semester" id="up_semester" class="form-control form-control-sm">
                            <option value="">{{ __('strings.fn_select_item') }}</option>
                        </select>
                    </div>
                    <div class="list-group-item">
                        <label>{{ __('strings.lb_academy_name') }}</label>
                        <select name="up_academy" id="up_academy" class="form-control form-control-sm">
                            <option value="">{{ __('strings.fn_select_item') }}</option>
                        </select>
                    </div>
                    <div class="list-group-item">
                        <button class="btn btn-outline-primary btn-sm"><i class="fa fa-plus"></i> New Line</button>
                        <button class="btn btn-outline-primary btn-sm"><i class="fa fa-plus"></i> String</button>
                    </div>
                </div>

            </div>
            <!-- right panel -->
            <div class="col-9 overflow-auto">
                <div class="list-group" id="fn_panel">
                    <div class="list-group-item">Preview</div>
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
    <script type="text/javascript">

        let _bs_Sheets = [];    // bs sheets list

        // drag & drop

        $(document).ready(function (){
            let $gallery = $("#fn_draggable");
            //let $
            $("button","#fn_draggable > button").draggable({
                cancel: "a.ui-icon",
                revert: "invalid",
                containment: "document",
                helper: "clone",
                cursor: "move"
            });

            /*$("#fn_draggable").droppable({
                accept: "#fn_draggable > button",
                classes: {
                    "ui-droppable-active" : "ui-state-highlight"
                },
                drop: function(event, ui){
                    recycleImage( ui.draggable);
                }
            });*/
            $("#fn_panel").droppable({
                accept: "#fn_draggable > button",
                classes: {
                    "ui-droppable-active":"ui-state-highlight"
                },
                drop: function(event, ui){
                    deleteImage(ui.draggable);
                }
            });

            function deleteImage($item){
                $item.fadeOut(function(){
                    //var $list = $("")
                })
            }

            function recycleImage($item){
                $item.fadeOut(function(){
                    $item
                    .find("a.ui-icon-refresh")
                    .remove()
                    .end()

                })
            }
        });


        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }

    </script>
@endsection
