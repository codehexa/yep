@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h5>{{ __('strings.lb_settings') }} &gt; {{ __('strings.lb_academy_manage') }}</h5>
    <div class="mt-3"><a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm"><i class="fa fa-arrow-alt-circle-left"></i> {{ __("strings.fn_backward") }}</a></div>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            @switch($error)
                @case ("FAIL_TO_SAVE")
                <h4 class="text-center text-danger"> {{ __('strings.err_need_admin_power') }}</h4>
                @break
                @case ("ALREADY_HAS")
                <h4 class="text-center text-danger"> {{ __('strings.err_has_already') }}</h4>
                @break
                @case ("FAIL_TO_MODIFY")
                <h4 class="text-center text-danger"> {{ __('strings.err_fail_to_modify') }}</h4>
                @break
            @endswitch
        @endforeach
    @endif

    <div class="mt-3">
        <form name="searchFrm" id="searchFrm" method="post" action="">
            @csrf
            <div class="form-inline">
                <div class="form-group">
                    <label for="up_key">{{ __('strings.lb_search_key') }}</label>
                    <input type="text" name="up_key" id="up_key" class="form-control form-control-sm ml-3" value="{{ $name }}" placeholder="{{ __('strings.lb_insert_academy_name') }}">
                </div>
                <button id="btn_search" name="btn_search" class="btn btn-sm btn-primary ml-3"><i class="fa fa-search"></i> {{ __('strings.fn_search') }}</button>
                <button id="btn_add" name="btn_add" class="btn btn-sm btn-primary ml-3"><i class="fa fa-plus-circle"></i> {{ __('strings.fn_add') }}</button>
            </div>
        </form>

        <table class="mt-3 table table-striped">
            <thead>
                <tr class="text-center">
                    <th scope="col">#</th>
                    <th scope="col">{{ __('strings.lb_academy_name') }}</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $datum)
                <tr class="text-center">
                    <th scope="row">{{ $datum->id }}</th>
                    <td>{{ $datum->ac_name }}</td>
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
                <h5 class="modal-title" id="infoModalLongTitle">{{ __('strings.lb_information') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="acFrm" id="acFrm" method="post" action="{{ route('addAcademy') }}">
                    @csrf
                    <input type="hidden" name="up_id" id="up_id"/>
                    <div class="form-group">
                        <label for="up_name">{{ __('strings.lb_academy_name') }}</label>
                        <input type="text" name="up_name" id="up_name" class="form-control" placeholder="{{ __('strings.lb_insert_academy_name') }}"/>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <i id="fn_loading" class="d-none fa fa-spin fa-spinner mr-3"></i>
                <button type="button" class="btn btn-primary" id="btnSubmit" ><i class="fa fa-save"></i> {{ __('strings.fn_okay') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
                <button type="button" class="btn btn-danger" id="btnDelete"><i class="fa fa-trash"></i> {{ __('strings.fn_delete') }}</button>
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.lb_okay') }}</button>
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
                <form name="delFrm" id="delFrm" method="post" action="{{ route('deleteCategory') }}">
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
        // 검색
        $(document).on("click","#btn_search",function (){
            event.preventDefault();

            if ($("#up_key").val() === ""){
                location.href = "/academyManage";
            }else{
                location.href = "/academyManage/" + encodeURIComponent($("#up_key").val());
            }
        });


        $(document).on("click",".fn_item",function (){
            event.preventDefault();
            let acId = $(this).attr("fn_id");

            $("#infoModalCenter").modal("show");

            $("#fn_loading").removeClass("d-none");

            $.ajax({
                url:"{{ route('getAcademyInfoJson') }}",
                dataType:"JSON",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "up_id":acId,
                },
                method:"POST",
                success:function (msg){
                    //
                    if (msg.result === "true"){
                        $("#up_id").val(acId);
                        $("#up_name").val(msg.data.ac_name);
                        $("#acFrm").attr({"action":"{{ route('storeAcademy') }}"});
                        $("#fn_loading").addClass("d-none");
                        $("#del_id").val(acId);
                    }else{
                        showAlert("{{ __('strings.err_get_info') }}");
                        $("#fn_loading").addClass("d-none");
                    }
                },
                error:function (e1,e2,e3){
                    showAlert(e2);
                }
            });
        });

        $(document).on("click","#btnDelete",function (){
            event.preventDefault();
            $("#confirmModalCenter").modal("show");
        });

        $(document).on("click","#btnDeleteDo",function (){
            $("#delFrm").submit();
        });

        $(document).on("click","#btn_add",function (){
            event.preventDefault();
            $("#infoModalCenter").modal("show");
            $("#acFrm").attr({"action":"{{ route('addAcademy') }}"});
            $("#up_name").val("");
        });

        $(document).on("click","#btnSubmit",function (){
            event.preventDefault();
            if ($("#up_name").val() === ""){
                showAlert("{{ __('strings.lb_insert_academy_name') }}");
                return;
            }

            $("#fn_loading").removeClass("d-none");

            $("#acFrm").submit();
        });

        $(document).on("click",".fn_user",function (){
            event.preventDefault();
            $("#alertModalCenter").modal("show");
        });

        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
