@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h5>{{ __('strings.lb_settings') }} &gt; {{ __('strings.lb_user_manage') }}</h5>
    <div class="mt-3"><a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm"><i class="fa fa-arrow-alt-circle-left"></i> {{ __("strings.fn_backward") }}</a></div>
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
        <form name="searchFrm" id="searchFrm" method="post" action="">
            @csrf
            <div class="form-inline">
                <div class="form-group">
                    <label for="up_key">{{ __('strings.lb_search_key') }}</label>
                    <input type="text" name="up_key" id="up_key" class="form-control form-control-sm ml-3" placeholder="{{ __('strings.lb_insert_user_name') }}">
                </div>
                <button id="btn_search" name="btn_search" class="btn btn-sm btn-primary ml-3">{{ __('strings.fn_search') }}</button>
                <button id="btn_add" name="btn_add" class="btn btn-sm btn-primary ml-3">{{ __('strings.fn_add') }}</button>
            </div>
        </form>

        <table class="mt-3 table table-striped">
            <thead>
                <tr class="text-center">
                    <th scope="col">#</th>
                    <th scope="col">{{ __('strings.lb_power') }}</th>
                    <th scope="col">{{ __('strings.lb_academy_name') }}</th>
                    <th scope="col">{{ __('strings.lb_name') }}</th>
                    <th scope="col">{{ __('strings.lb_email') }}</th>
                    <th scope="col">{{ __('strings.lb_created_date') }}</th>
                    <th scope="col">{{ __('strings.lb_last_login') }}</th>
                    <th scope="col">{{ __('strings.lb_live') }}</th>
                    <th scope="col">{{ __('strings.lb_drop_date') }}</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
            @foreach($data as $datum)
                <tr class="text-center">
                    <th scope="row">{{ $datum->id }}</th>
                    <td>{{ $datum->power }}</td>
                    <td>
                        @if (is_null($datum->academy))
                            {{ __('strings.fn_not_set') }}
                        @else
                            {{ $datum->academy->ac_name }}
                        @endif
                    </td>
                    <td>{{ $datum->name }}</td>
                    <td>{{ $datum->email }}</td>
                    <td>{{ $datum->created_at }}</td>
                    <td>{{ $datum->last_login }}</td>
                    <td>
                        @if ($datum->live == "Y")
                            {{ __('strings.fn_live_y') }}
                        @else
                            {{ __('strings.fn_live_n') }}
                        @endif
                    </td>
                    <td>{{ $datum->drop_date }}</td>
                    <td><a href="#" class="btn btn-primary btn-sm fn_user" fn_id="{{ $datum->id }}">{{ __('strings.lb_btn_manage') }}</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if (sizeof($data) <= 0)
            <div class="text-secondary">{{ __('strings.str_there_is_no_data')}}</div>
        @endif
        <div class="mt-3">
            {{ $data->links() }}
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('strings.lb_okay') }}</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script type="text/javascript">
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
