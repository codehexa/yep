<html>
    <table>
        <tr>
            <td rowspan="2" colspan="5" style="text-align: center; vertical-align: auto">{{ __('strings.exc_student_info') }}</td>
            <td colspan="{{ $head_colspan }}" style="text-align: center; vertical-align: auto">{{ __('strings.exc_week_pre',['NO'=>$no]) }}</td>
        </tr>
        <tr>
            @foreach($items as $item)
                @if ($item->sj_depth == "0")
                    <td
                        @if ($item->sj_has_child == "Y")
                            colspan="{{ $item->child_size }}"
                        @else
                            rowspan="2"
                        @endif
                        style="text-align: center; vertical-align: auto">{{ $item->sj_title }}</td>
                @endif
            @endforeach
        </tr>
        <tr >
            <td style="text-align: center; vertical-align: auto">{{ __('strings.exc_student_name') }}</td>
            <td style="text-align: center; vertical-align: auto">{{ __('strings.exc_school') }}</td>
            <td style="text-align: center; vertical-align: auto">{{ __('strings.exc_grade') }}</td>
            <td style="text-align: center; vertical-align: auto">{{ __('strings.exc_class') }}</td>
            <td style="text-align: center; vertical-align: auto">{{ __('strings.exc_teacher') }}</td>
            @foreach($items as $item)
                @if ($item->sj_depth == "1")
                    <td style="text-align: center; vertical-align: auto">{{ $item->sj_title }}</td>
                @endif
            @endforeach
        </tr>

        @foreach ($data as $datum)
            <tr>
                <td style="text-align: center; background-color: #ffe495">{{ $datum->student_name }}</td>
                <td style="text-align: center; background-color: #ffe495">{{ $datum->school_name }}</td>
                <td style="text-align: center; background-color: #ffe495">{{ $datum->school_grade }}</td>
                <td style="text-align: center; background-color: #ffe495">{{ $datum->class_name }}</td>
                <td style="text-align: center; background-color: #ffe495">{{ $datum->teacher_name }}</td>
                @for ($i=0 ; $i < $head_colspan; $i++)
                    @php
                    $field = "score_".$i;
                    @endphp
                    <td style="text-align: center; background-color: #ffe495">{{ $datum->$field }}</td>
                @endfor
            </tr>
        @endforeach
    </table>
</html>
