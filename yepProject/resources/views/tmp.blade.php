<?php
<div class="form-group">
                    <label>{{ __('strings.lb_first_class') }} </label>
<select class="form-control form-control-sm fn_up_class_first_subject">
    <option value="">{{ __('strings.fn_select_item') }}</option>
    @foreach($subjects as $subject)
        <option value="{{ $subject->id }}" @{{if bms_sii_first_class == {!! $subject->id !!}  }} selected @{{/if}}>{{ $subject->subject_title }}</option>
    @endforeach
</select>
<select class="form-control form-control-sm fn_up_class_first_teacher">
    <option value="">{{ __('strings.fn_select_item') }}</option>
    @foreach($teachers as $teacher)
        <option value="{{ $teacher->id }}" @{{if bms_sii_first_teacher == {!! $teacher->id !!} }} selected @{{/if}}>{{ $teacher->name }}</option>
    @endforeach
</select>
</div>
<div class="form-group ml-2">
    <label>{{ __('strings.lb_second_class') }}</label>
    <select class="form-control form-control-sm fn_up_class_second_subject">
        <option value="">{{ __('strings.fn_select_item') }}</option>
        @foreach($subjects as $subject)
            <option value="{{ $subject->id }}" @{{if bms_sii_second_class == {!! $subject->id !!} }} selected @{{/if}}>{{ $subject->subject_title }}</option>
        @endforeach
    </select>
    <select class="form-control form-control-sm fn_up_class_second_teacher">
        <option value="">{{ __('strings.fn_select_item') }}</option>
        @foreach($teachers as $teacher)
            <option value="{{ $teacher->id }}" @{{if bms_sii_second_teacher == {!! $teacher->id !!} }} selected @{{/if}}>{{ $teacher->name }}</option>
        @endforeach
    </select>
</div>
<div class="form-group ml-2">
    <label>{{ __('strings.lb_dt_title') }}</label>
    <select class="form-control form-control-sm fn_up_class_dt">
        <option value="" >{{ __('strings.fn_select_item') }}</option>
        <option value="Y" @{{if bms_sii_dt == 'Y' }} selected @{{/if}}>{{ __('strings.fn_been') }}</option>
        <option value="N" @{{if bms_sii_dt == 'N' }} selected @{{/if}}>{{ __('strings.fn_not_been') }}</option>
    </select>
</div>
