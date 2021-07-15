@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h5>{{ __('strings.lb_hakgi_manage') }} </h5>
    <div class="mt-3 btn-group">
        <a href="/settings" class="btn btn-outline-secondary btn-sm"><i class="fa fa-cog"></i> {{ __("strings.lb_settings") }}</a>
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
                    <th scope="col">{{ __('strings.lb_year_name') }}</th>
                    <th scope="col">{{ __('strings.lb_interdisciplinary') }}</th>
                    <th scope="col">{{ __('strings.lb_hakgi_name') }}</th>
                    <th scope="col">{{ __('strings.lb_week') }}</th>
                    <th scope="col">{{ __('strings.lb_show') }}</th>
                    <th scope="col">{{ __('strings.lb_function') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $datum)
                <tr class="text-center">
                    <th scope="row">{{ $datum->id }}</th>
                    <td>{{ $datum->year }}</td>
                    <td>{{ $datum->SchoolGrades->scg_name }}</td>
                    <td>{{ $datum->hakgi_name }}</td>
                    <td>{{ $datum->weeks }}</td>
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
                <h5 class="modal-title" id="infoModalLongTitle">{{ __('strings.lb_information') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="hakgiFrm" id="hakgiFrm" method="post" action="/addHakgi">
                    @csrf
                    <input type="hidden" name="up_id" id="up_id"/>
                    <div class="form-group">
                        <label for="up_year">{{ __('strings.lb_year_name') }}</label>
                        <select name="up_year" id="up_year" class="form-control">
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
                        <label for="up_school_grade">{{ __('strings.lb_interdisciplinary') }}</label>
                        <select name="up_school_grade" id="up_school_grade" class="form-control">
                            @foreach ($grades as $grade)
                                <option value="{{ $grade->id }}">{{ $grade->scg_name }}</option>
                            @endforeach
                        </select>
                    </div>
<!--
                    <div class="form-check">
                        <input type="checkbox" name="up_common" id="up_common" class="form-check-input" value="Y" />
                        <label for="up_common" class="form-check-label">{{ __('strings.lb_common_insert') }}</label>

                    </div>-->

                    <div class="form-group">
                        <label for="up_name">{{ __('strings.lb_name') }}</label>
                        <input type="text" name="up_name" id="up_name" class="form-control" placeholder="{{ __('strings.str_insert_class_name') }}"/>
                    </div>

                    <div class="form-group">
                        <label for="up_weeks">{{ __('strings.lb_week') }}</label>
                        <input type="text" name="up_weeks" id="up_weeks" class="form-control" placeholder="{{ __('strings.str_insert_class_name') }}"/>
                    </div>

                    <div class="form-group">
                        <label for="up_show">{{ __('strings.lb_show') }}</label>
                        <select name="up_show" id="up_show" class="form-control">
                            <option value="N">N</option>
                            <option value="Y">Y</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <i id="fn_loading" class="fa fa-spin fa-spinner mr-3 d-none"></i>
                <button type="button" class="btn btn-primary" id="btnHakgiSubmit" ><i class="fa fa-save"></i> {{ __('strings.fn_okay') }}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> {{ __('strings.fn_cancel') }}</button>
                <button type="button" class="btn btn-danger d-none" id="btnHakgiDelete"><i class="fa fa-trash"></i> {{ __('strings.fn_delete') }}</button>
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
                <form name="delFrm" id="delFrm" method="post" action="/delHakgi">
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

        $(document).on("click","#btn_add",function (){
            event.preventDefault();
            $("#infoModalCenter").modal("show");
            $("#hakgiFrm").attr({"action":"/addHakgi"});
            $("#btnHakgiDelete").addClass("d-none");
        });

        $(document).on("click","#btnHakgiDelete",function (){
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
            $("#btnHakgiDelete").removeClass("d-none");
            $("#fn_loading").removeClass("d-none");
            $("#hakgiFrm").attr({"action":"/storeHakgi"});

            let clId = $(this).attr("fn_id");
            $("#del_id").val(clId);

            $.ajax({
                type:"POST",
                url:"/hakgiInfoJson",
                dataType:"json",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "cid":clId
                },
                success:function (msg){
                    if (msg.result === "true"){
                        $("#up_id").val(clId);
                        $("#up_year").val(msg.data.year);
                        $("#up_school_grade").val(msg.data.school_grade);
                        $("#up_name").val(msg.data.hakgi_name);
                        $("#up_show").val(msg.data.show);
                        $("#up_weeks").val(msg.data.weeks);
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


        $(document).on("click","#btnHakgiSubmit",function (){
            event.preventDefault();
            if ($("#up_name").val() === ""){
                showAlert("{{ __('strings.str_insert_hakgi_name') }}");
                return;
            }

            $("#fn_loading").removeClass("d-none");

            $("#hakgiFrm").submit();
        });



        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
