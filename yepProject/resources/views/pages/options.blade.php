@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h5>{{ __('strings.lb_settings') }} &gt; {{ __('strings.lb_options_manage') }}</h5>
    <div class="mt-3">
        <a href="/settings" class="btn btn-outline-secondary btn-sm"><i class="fa fa-arrow-alt-circle-left"></i> {{ __("strings.fn_backward") }}</a>
        <a href="/optionsLog" class="btn btn-outline-primary btn-sm"><i class="fa fa-list"></i> {{ __("strings.lb_option_log") }}</a>
    </div>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            @switch($error)
                @case ("NO_HAS_POWER_ADMIN")
                <h4 class="text-center text-danger"> {{ __('strings.err_need_admin_power') }}</h4>
                @break
            @endswitch
        @endforeach
    @endif

    <div class="mt-3">
        <table class="mt-3 table table-striped">
            <thead>
                <tr class="text-center">
                    <th scope="col">#</th>
                    <th scope="col">{{ __('strings.lb_date') }}</th>
                    <th scope="col">{{ __('strings.lb_code') }}</th>
                    <th scope="col">{{ __('strings.lb_name') }}</th>
                    <th scope="col">{{ __('strings.lb_value') }}</th>
                    <th scope="col">{{ __('strings.lb_desc') }}</th>
                    <th scope="col">{{ __('strings.lb_function') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $datum)
                <tr class="text-center">
                    <th scope="row">{{ $datum->id }}</th>
                    <td>{{ $datum->updated_at }}</td>
                    <td>{{ $datum->set_code }}</td>
                    <td>{{ $datum->set_name }}</td>
                    <td>{{ $datum->set_value }}</td>
                    <td>{{ $datum->set_short_desc }}</td>
                    <td>
                        <a href="#" class="btn btn-primary btn-sm fn_option" fn_id="{{ $datum->id }}">{{ __('strings.lb_btn_manage') }}</a>
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
                <h5 class="modal-title" id="functionModalLongTitle">{{ __('strings.fn_information') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form name="optFrm" id="optFrm" method="post" action="/optionUpdate">
                    @csrf
                    <input type="hidden" name="opt_id" id="opt_id" />
                    <div class="form-group">
                        <label for="opt_value" >{{ __('strings.lb_value') }}</label>
                        <input type="text" name="opt_value" id="opt_value" class="form-control"/>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <span id="opt_spin" class="d-none"><i class="fa fa-spinner fa-spin"></i> </span>
                <button type="button" class="btn btn-primary" id="btn_opt_submit"><i class="fa fa-save"></i> {{ __('strings.fn_okay') }}</button>
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

@endsection

@section('scripts')
    <script type="text/javascript">
        // show
        $(document).on("click",".fn_option",function (){
            event.preventDefault();
            let optId = $(this).attr("fn_id");

            $("#functionModalCenter").modal("show");
            $("#opt_spin").toggleClass();
            $.ajax({
                type:"POST",
                url:"/optionGetJson",
                data:{
                    "_token":$("input[name='_token']").val(),
                    "optId":optId
                },
                dataType:"json",
                success:function (msg){
                    if (msg.result === "true"){
                        $("#opt_value").val(msg.data.set_value);
                        $("#opt_id").val(optId);
                        $("#opt_spin").toggleClass();
                    }else{
                        showAlert("{{ __('strings.err_get_info') }}");
                        $("#opt_spin").toggleClass();
                        return;
                    }
                }
            })
        });

        $(document).on("click","#btn_opt_submit",function (){
            event.preventDefault();
            if ($("#opt_value").val() === ""){
                showAlert("{{ __('strings.err_insert_value') }}");
                return;
            }
            $("#optFrm").submit();
        });

        $(document).on("click",".fn_user",function (){
            event.preventDefault();
            $(this).parent().children("div").toggleClass("d-none");
        });

        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
