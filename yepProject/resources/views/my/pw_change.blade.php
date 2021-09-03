@extends('layouts.app')

@section('content')
<div class="container-fluid">
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

    <div class="mt-3 row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-key"></i> {{ __('strings.lb_password_reset') }}
                </div>
                <div class="card-body">
                    <form name="usFrm" id="usFrm" method="post" action="myPwEdit">
                        @csrf
                        <div class="list-group">
                            <div class="list-group-item">
                                <label class="text-nowrap mt-2 mr-2">{{ __('strings.lb_current_password') }}</label>
                                <input type="password" name="up_passwd1" id="up_passwd1" class="form-control" value=""/>
                            </div>
                            <div class="list-group-item">
                                <label class="text-nowrap mt-2 mr-2">{{ __('strings.lb_new_password') }}</label>
                                <input type="password" name="up_passwd2" id="up_passwd2" class="form-control"  value=""/>
                            </div>
                            <div class="list-group-item">
                                <label class="text-nowrap mt-2 mr-2">{{ __('strings.str_insert_password_2') }}</label>
                                <input type="password" name="up_passwd3" id="up_passwd3" class="form-control" value=""/>
                            </div>
                        </div>
                    </form>
                    <div class="mt-2">
                        <button id="btnSave" class="btn btn-primary"><i class="fa fa-save"></i> {{ __('strings.fn_save') }}</button>
                    </div>
                </div>
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

@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).on("click","#btnSave",function (){
            event.preventDefault();

            if ($("#up_passwd1").val() === "" || $("#up_passwd2").val() === "" || $("#up_passwd3").val() === ""){
                showAlert("{{ __('strings.str_insert_password') }}");
                return;
            }

            $("#usFrm").submit();
        })

        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
