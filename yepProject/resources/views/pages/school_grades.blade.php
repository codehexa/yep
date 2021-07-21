@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h5>{{ __('strings.lb_settings') }} &gt; {{ __('strings.lb_grade_manage') }}</h5>
    <div class="mt-3 btn-group">
        <a href="/settings" class="btn btn-outline-secondary btn-sm"><i class="fa fa-arrow-left"></i> {{ __("strings.fn_backward") }}</a>
        <a href="#" id="btn_new" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus-square"></i> {{ __("strings.fn_add") }}</a>
    </div>


    @if ($errors->any())
        @foreach ($errors->all() as $error)
            @switch($error)
                @case ("FAIL_TO_SAVE")
                <h4 class="text-center text-danger"> {{ __('strings.err_fail_to_save') }}</h4>
                @break
            @endswitch
        @endforeach
    @endif

    <div class="mt-3">
        <table class="mt-3 table table-striped">
            <thead>
                <tr class="text-center">
                    <th scope="col">#</th>
                    <th scope="col">{{ __('strings.lb_grade_name') }}</th>
                    <th scope="col">{{ __('strings.lb_index') }}</th>
                    <th scope="col">{{ __('strings.lb_function') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $datum)
                <tr class="text-center">
                    <th scope="row">{{ $datum->id }}</th>
                    <td>{{ $datum->scg_name }}</td>
                    <td>{{ $datum->scg_index }}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="#" class="btn btn-primary btn-sm fn_up" fnid="{{ $datum->id }}"><i class="fa fa-sort-up"></i> </a>
                            <a href="#" class="btn btn-primary btn-sm fn_down" fnid="{{ $datum->id }}"><i class="fa fa-sort-down"></i> </a>
                            <a href="#" class="btn btn-primary btn-sm fn_edit" fnid="{{ $datum->id }}"><i class="fa fa-edit"></i> </a>
                            <a href="#" class="btn btn-primary btn-sm fn_delete" fnid="{{ $datum->id }}"><i class="fa fa-trash"></i> </a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if (sizeof($data) <= 0)
            <div class="text-secondary">{{ __('strings.str_there_is_no_data')}}</div>
        @endif

    </div>
</div>


<div class="modal fade" id="functionModalCenter" tabindex="-1" role="dialog" aria-labelledby="functionModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="functionModalLongTitle">{{ __('strings.lb_information') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h4 id="functionModalTitle">{{ __('strings.lb_new_school_grade') }}</h4>
                <form name="newFrm" id="newFrm" method="post" action="/schoolGradePost">
                    <input type="hidden" name="ed_id" id="ed_id"/>
                    @csrf
                    <div class="form-group">
                        <label for="up_new_name">{{ __('strings.lb_grade_name') }}</label>
                        <input type="text" name="up_new_name" id="up_new_name" placeholder="{{ __('strings.lb_insert_grade') }}" class="form-control"/>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_new_submit"><i class="fa fa-save"></i> {{ __('strings.fn_okay') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-check-circle"></i> {{ __('strings.fn_okay') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmModalCenter" tabindex="-1" role="dialog" aria-labelledby="confirmModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLongTitle">{{ __('strings.fn_okay') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="del_id" id="del_id"/>
                <p id="fn_body">{{ __('strings.str_do_you_want_to_delete_cant_recover') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn_delete_do"><i class="fa fa-check-circle"></i> {{ __('strings.fn_okay') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
            </div>
        </div>
    </div>
</div>

<form name="indexFrm" id="indexFrm" method="post" action="/schoolGradeIndex">
    @csrf
    <input type="hidden" name="did" id="did"/>
    <input type="hidden" name="dmode" id="dmode"/>
</form>

@endsection

@section('scripts')
    <script type="text/javascript">
        // delete
        $(document).on("click",".fn_delete",function (){
            event.preventDefault();

            let dId = $(this).attr("fnid");

            $("#confirmModalCenter").modal("show");
            $("#del_id").val(dId);
        });

        $(document).on("click","#btn_delete_do",function (){
            $("#indexFrm").attr({"action":"/schoolGradeDelete"});
            $("#did").val($("#del_id").val());
            $("#indexFrm").submit();
        });
        // show
        $(document).on("click","#btn_new",function (){
            event.preventDefault();

            $("#functionModalCenter").modal("show");
            $("#up_new_name").val("");
            $("#functionModalTitle").html("{{ __('strings.lb_new_school_grade') }}");
            $("#newFrm").attr({"action":"/schoolGradePost"});
        });

        $(document).on("click","#btn_new_submit",function (){
            event.preventDefault();

            if ($("#up_new_name").val() === ""){
                showAlert("{{ __('strings.lb_insert_grade') }}");
                return;
            }

            $("#newFrm").submit();
        });

        $(document).on("click",".fn_up",function (){
            event.preventDefault();
            let did = $(this).attr("fnid");

            $("#did").val(did);
            $("#dmode").val("UP");
            $("#indexFrm").submit();
        });

        $(document).on("click",".fn_down",function (){
            event.preventDefault();
            let did = $(this).attr("fnid");

            $("#did").val(did);
            $("#dmode").val("DOWN");
            $("#indexFrm").submit();
        });

        $(document).on("click",".fn_edit",function (){
            event.preventDefault();
            let did = $(this).attr("fnid");

            $("#functionModalCenter").modal("show");
            $("#functionModalTitle").html("{{ __('strings.lb_edit_school_grade') }}");

            $.ajax({
                type:"POST",
                url:"/schoolGradeJson",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "did":did,
                },
                success:function(msg){
                    //
                    if (msg.result === "true"){
                        $("#ed_id").val(msg.data.id);
                        $("#up_new_name").val(msg.data.scg_name);
                        $("#newFrm").attr({"action":"/schoolGradeStore"});
                    }else{
                        showAlert("{{ __('strings.err_get_info') }}");
                    }

                },
                error:function (e1,e2,e3){
                    showAlert(e2);
                }
            });
        });

        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
