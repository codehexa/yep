@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h5>{{ __('strings.lb_settings') }} &gt; {{ __('strings.lb_sms_manage') }}</h5>
    <div class="mt-3">
        <a href="/settings" class="btn btn-outline-secondary btn-sm"><i class="fa fa-arrow-alt-circle-left"></i> {{ __("strings.fn_backward") }}</a>
    </div>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            @switch($error)
                @case ("FAIL_TO_UPDATE")
                <h4 class="text-center text-danger"> {{ __('strings.err_fail_to_save') }}</h4>
                @break
            @endswitch
        @endforeach
    @endif

    <div class="mt-3">
        <form name="frmSmsPage" id="frmSmsPage" method="post" action="/smsPageSetSave">
            @csrf
            <div class="list-group">
                <div class="list-group-item ">
                    <div class="d-flex justify-content-between">
                        <label for="up_greetings">{{ __('strings.lb_greetings') }}</label>
                        <div class="form-check">
                            <input type="checkbox" name="up_use_top" id="up_use_top" value="Y" class="form-check-input"
                                   @if ($data->use_top == "Y")
                                       checked
                                   @endif
                            />
                            <label for="up_use_top" class="form-check-label">{{ __('strings.lb_sms_page_use_check') }}</label>
                        </div>
                    </div>

                    <input type="text" name="up_greetings" id="up_greetings" class="form-control" placeholder="{{ __('strings.lb_input_greetings') }}" value="{{ $data->greetings }}"/>
                </div>
                <div class="list-group-item">
                    <label for="up_blog_guide">{{ __('strings.lb_blog_guide') }}</label>
                    <div class="input-group">
                        <input type="text" name="up_blog_guide"  id="up_blog_guide" class="form-control" placeholder="{{ __('strings.lb_input_blog_guide') }}" value="{{ $data->blog_guide }}"/>
                    </div>
                </div>
                <div class="list-group-item">
                    <label for="up_blog_url">{{ __('strings.lb_blog_url') }}</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basis_http2">http://</span>
                        <input type="text" name="up_blog_url" aria-describedby="basis_http2" id="up_blog_url" class="form-control" placeholder="{{ __('strings.lb_input_blog_url') }}" value="{{ $data->blog_link_url }}"/>
                    </div>
                </div>
                <div class="list-group-item">
                    <label for="up_teacher_say">{{ __('strings.lb_teacher_say') }}</label>
                    <input type="text" name="up_teacher_say" id="up_teacher_say" class="form-control" placeholder="{{ __('strings.lb_input_teacher_say') }}" value="{{ $data->teacher_title }}"/>
                </div>
                <div class="list-group-item">
                    <label for="up_teacher_say">{{ __('strings.lb_sms_text_title') }}</label>
                    <input type="text" name="up_sps_opt_1" id="up_sps_opt_1" class="form-control" placeholder="{{ __('strings.lb_input_sms_text_title') }}" value="{{ $data->sps_opt_1 }}"/>
                    <small>{{ __('strings.lb_sms_define_name') }}</small>
                </div>
                <div class="list-group-item">
                    <div class="d-flex justify-content-between">
                        <label for="up_teacher_say">{{ __('strings.lb_sms_page_bottom') }}</label>
                        <div class="form-check">
                            <input type="checkbox" name="up_use_bottom" id="up_use_bottom" class="form-check-input" value="Y"
                                   @if ($data->use_bottom == "Y")
                                       checked
                                   @endif
                            />
                            <label for="up_use_bottom" class="form-check-label">{{ __('strings.lb_sms_page_use_check') }}</label>
                        </div>
                    </div>

                    <input type="text" name="up_sps_opt_2" id="up_sps_opt_2" class="form-control" placeholder="{{ __('strings.lb_input_sms_bottom') }}" value="{{ $data->sps_opt_2 }}"/>
                </div>
            </div>
        </form>
        <div class="mt-3">
            <button  class="btn btn-primary" id="btnSave"><i class="fa fa-save"></i> {{ __('strings.fn_save') }}</button>
        </div>
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
        $(document).on("click","#btnSave",function (){
            event.preventDefault();
            $("#frmSmsPage").submit();
        });

        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
