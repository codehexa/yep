@extends('layouts.app_bms')

@section('content')
<div class="container-fluid">
    <h5><i class="fa fa-wind"></i> {{ __('strings.lb_bms_title') }} &gt; {{ __('strings.lb_bms_hworks_manage') }}</h5>

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
                <span>검색</span>
                <select name="up_key_type" id="up_key_type" class="form-control form-control-sm ml-2">
                    <option value="">{{ __('strings.fn_select_item') }}</option>
                </select>
                <input type="text" name="up_key" id="up_key" class="form-control form-control-sm ml-2"/>
                <button class="btn btn-primary btn-sm ml-2"><i class="fa fa-search"></i> {{ __('strings.fn_search') }}</button>

                <div class="form-inline btn-group btn-group-sm ml-2">
                    <button class="btn btn-outline-primary" id="btnAdd"><i class="fa fa-plus"></i> Add</button>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">{{ __('strings.lb_bms_hworks_type') }}</th>
                    <th scope="col">{{ __('strings.lb_bms_high_school') }}</th>
                    <th scope="col">{{ __('strings.lb_bms_class') }}</th>
                    <th scope="col">{{ __('strings.lb_bms_dt') }}</th>
                    <th scope="col">{{ __('strings.lb_bms_books') }}</th>
                    <th scope="col">{{ __('strings.lb_bms_output_first') }}</th>
                    <th scope="col">{{ __('strings.lb_bms_output_second') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data as $datum)
                @endforeach
                </tbody>
            </table>

            <div class="mt-2">
                {{ $data->links('pagination::bootstrap-4') }}
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
                <form name="cmFrm" id="cmFrm" method="post" action="/setComments">
                    @csrf
                    <input type="hidden" name="cm_id" id="cm_id" value=""/>

                    <div class="form-group">
                        <label for="up_type">{{ __('strings.lb_bms_hworks_type') }}</label>
                        <select name="up_type" id="up_type" class="form-control">
                            <option value="">{{ __('strings.fn_select_item') }}</option>
                            @for ($i=0; $i < sizeof($hwTypes); $i++ )
                                <option value="{{ $hwTypes[$i]['code'] }}">{{ $hwTypes[$i]['title'] }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="up_high_school">{{ __('strings.lb_bms_high_school') }}</label>
                        <input type="text" name="up_high_school" id="up_high_school" class="form-control" placeholder="{{ __('strings.lb_insert_high_school') }}"/>
                    </div>
                    <div class="form-group">
                        <label for="up_class">{{ __('strings.lb_bms_class') }}</label>
                        <input type="text" name="up_class" id="up_class" class="form-control" placeholder="{{ __('strings.lb_insert_class') }}"/>
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

        let dataSet = [];
        let _jsEnter = "{{ \App\Models\Configurations::$JS_ENTER }}";
        let _jsString = "{{ \App\Models\Configurations::$JS_STRING }}";
        let _jsSelect = "{{ \App\Models\Configurations::$JS_SELECT }}";

        $(document).on("click",'#btnAdd',function (){
            event.preventDefault();

            $("#infoModalCenter").modal("show");
        });

        function printPage(){
            if (dataSet.length > 0){
                $.each(dataSet,function (i,obj){

                });
            }
        }

        function showAlert(str){
            $("#alertModalCenter").modal("show");
            $("#fn_body").html(str);
            return;
        }
    </script>
@endsection
