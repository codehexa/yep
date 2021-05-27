@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h5>{{ __('strings.lb_settings') }} &gt; {{ __('strings.lb_options_manage') }} &gt; {{ __('strings.lb_option_log') }}</h5>
    <div class="mt-3">
        <a href="/settings" class="btn btn-outline-secondary btn-sm"><i class="fa fa-arrow-alt-circle-left"></i> {{ __("strings.fn_init") }}</a>
        <a href="/logs" class="btn btn-outline-primary btn-sm"><i class="fa fa-filter"></i> {{ __("strings.lb_logs_manage") }}</a>
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
                    <th scope="col">{{ __('strings.lb_user_name') }}</th>
                    <th scope="col">{{ __('strings.lb_mode') }}</th>
                    <th scope="col">{{ __('strings.lb_target') }}</th>
                    <th scope="col">{{ __('strings.lb_desc') }}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $datum)
                <tr class="text-center">
                    <th scope="row">{{ $datum->id }}</th>
                    <td>{{ $datum->updated_at }}</td>
                    <td>{{ $datum->writer->name }}</td>
                    <td
                        @if ($datum->log_mode == "delete")
                            class="text-danger"
                        @elseif ($datum->log_mode == "modify")
                            class="text-primary"
                        @endif
                    >{{ strtoupper($datum->log_mode) }}</td>
                    <td>@if (!is_null($datum->academy))
                            {{ $datum->academy->ac_name }}
                        @else
                            <span class="text-danger">{{ __('strings.lb_deleted_academy') }}</span>
                        @endif
                    </td>
                    <td>{{ $datum->log_desc }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if (sizeof($data) <= 0)
            <div class="text-secondary">{{ __('strings.str_there_is_no_data')}}</div>
        @endif
    </div>
    <div class="mt-3">
        {{ $data->links() }}
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
