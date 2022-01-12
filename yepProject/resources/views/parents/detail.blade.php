<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}a{background-color:transparent}[hidden]{display:none}html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5}*,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}a{color:inherit;text-decoration:inherit}svg,video{display:block;vertical-align:middle}video{max-width:100%;height:auto}.bg-white{--bg-opacity:1;background-color:#fff;background-color:rgba(255,255,255,var(--bg-opacity))}.bg-gray-100{--bg-opacity:1;background-color:#f7fafc;background-color:rgba(247,250,252,var(--bg-opacity))}.border-gray-200{--border-opacity:1;border-color:#edf2f7;border-color:rgba(237,242,247,var(--border-opacity))}.border-t{border-top-width:1px}.flex{display:flex}.grid{display:grid}.hidden{display:none}.items-center{align-items:center}.justify-center{justify-content:center}.font-semibold{font-weight:600}.h-5{height:1.25rem}.h-8{height:2rem}.h-16{height:4rem}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.leading-7{line-height:1.75rem}.mx-auto{margin-left:auto;margin-right:auto}.ml-1{margin-left:.25rem}.mt-2{margin-top:.5rem}.mr-2{margin-right:.5rem}.ml-2{margin-left:.5rem}.mt-4{margin-top:1rem}.ml-4{margin-left:1rem}.mt-8{margin-top:2rem}.ml-12{margin-left:3rem}.-mt-px{margin-top:-1px}.max-w-6xl{max-width:72rem}.min-h-screen{min-height:100vh}.overflow-hidden{overflow:hidden}.p-6{padding:1.5rem}.py-4{padding-top:1rem;padding-bottom:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.pt-8{padding-top:2rem}.fixed{position:fixed}.relative{position:relative}.top-0{top:0}.right-0{right:0}.shadow{box-shadow:0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06)}.text-center{text-align:center}.text-gray-200{--text-opacity:1;color:#edf2f7;color:rgba(237,242,247,var(--text-opacity))}.text-gray-300{--text-opacity:1;color:#e2e8f0;color:rgba(226,232,240,var(--text-opacity))}.text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.text-gray-500{--text-opacity:1;color:#a0aec0;color:rgba(160,174,192,var(--text-opacity))}.text-gray-600{--text-opacity:1;color:#718096;color:rgba(113,128,150,var(--text-opacity))}.text-gray-700{--text-opacity:1;color:#4a5568;color:rgba(74,85,104,var(--text-opacity))}.text-gray-900{--text-opacity:1;color:#1a202c;color:rgba(26,32,44,var(--text-opacity))}.underline{text-decoration:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.w-5{width:1.25rem}.w-8{width:2rem}.w-auto{width:auto}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}@media (min-width:640px){.sm\:rounded-lg{border-radius:.5rem}.sm\:block{display:block}.sm\:items-center{align-items:center}.sm\:justify-start{justify-content:flex-start}.sm\:justify-between{justify-content:space-between}.sm\:h-20{height:5rem}.sm\:ml-0{margin-left:0}.sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}.sm\:pt-0{padding-top:0}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width:768px){.md\:border-t-0{border-top-width:0}.md\:border-l{border-left-width:1px}.md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}}@media (prefers-color-scheme:dark){.dark\:bg-gray-800{--bg-opacity:1;background-color:#2d3748;background-color:rgba(45,55,72,var(--bg-opacity))}.dark\:bg-gray-900{--bg-opacity:1;background-color:#1a202c;background-color:rgba(26,32,44,var(--bg-opacity))}.dark\:border-gray-700{--border-opacity:1;border-color:#4a5568;border-color:rgba(74,85,104,var(--border-opacity))}.dark\:text-white{--text-opacity:1;color:#fff;color:rgba(255,255,255,var(--text-opacity))}.dark\:text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}}
        </style>

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }

            .cls-1 {
                font-size: 120px;
                font-family: Krungthep;
            }
        </style>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <script src="{{ asset('js/jquery.js') }}"></script>

        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>
    </head>
    <body class="antialiased" >
        <div id="app" class="relative items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-100 sm:pt-0">

            <div class="p-1 bg-white d-flex justify-content-center shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="160" height="30" viewBox="0 0 1213 230">
                    <image x="10" y="6" width="1191" height="216" xlink:href="data:img/png;base64,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"/>
                </svg>
            </div>

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    @switch($error)
                        @case ("NO_MATCH_STUDENT")
                        <h4 class="text-center text-danger"> {{ __('strings.err_no_student') }}</h4>
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
                        @case ("CALL_TO_DEV")
                        <h4 class="text-center text-danger"> {{ __('strings.err_call_to_dev',["CODE"=>"FILE_EXCEL_FAIL"]) }}</h4>
                        @break
                        @case ("CANT_SET_DONE")
                        <h4 class="text-center text-danger"> {{ __('strings.err_set_sms_paper_done') }}</h4>
                        @break
                    @endswitch
                @endforeach
            @endif

            <div class="mt-3 ml-3 mr-3">
                @if ($settings->use_top == "Y")
                <h4 class="text-center">{{ $settings->greetings }}</h4>
                @endif
                <div class="mt-3">
                    <div class="mt-3">
                        <div class="list-group">
                            <div class="list-group-item">
                                <label for="">{{ __('strings.lb_title') }}</label>
                                <span class="text-primary">{{ $student->student_name }}</span>
                            </div>
                            <div class="list-group-item">
                                <label for="">{{ __('strings.lb_test_paper_title') }}</label>
                                <span class="text-primary">
                                    {{ $smsPaper->year }} {{ __('strings.lb_year') }}
                                    {{ $smsPaper->Hakgi->hakgi_name }}
                                    {{ $smsPaper->week }} {{ __('strings.lb_week_st') }}
                                    {{ __('strings.lb_weekly_test_paper') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- graph -->
<!--                <div id="chartContainer" style="height: 370px; width:100%"></div>-->

                <div class="mt-3">
                    @for($i=0; $i < sizeof($dataSet); $i++)
                        <div class="mt-2">
                            <h5>
                                @if($dataSet[$i]["exam"] == "Y")
                                    [{{ __("strings.lb_practice_exam") }}]
                                @endif
                                    {{ $dataSet[$i]['testTitle'] }}
                            </h5>
                            <div class="d-flex justify-content-center">
                                <canvas id="chart_{{ $i }}" style="width: 80vw;min-height: 30vh; height:{{ $canvas_height }}vh; max-height: 140vh;" role="img"></canvas>
                            </div>

                        </div>
                    @endfor
                    <div class="mt-1 text-sm text-center"><small>{{ __('strings.lb_zero_point_guide') }}</small></div>
                    <div class="mt-1 text-center">{{ __('strings.guide_all_dt_65') }}</div>
                    <div class="mt-1 text-sm text-center"><small>{{ __('strings.guide_max_score_100') }}</small></div>
                </div>
                <!-- 성적표 내용 표시하기 -->

                <h5 class="mt-3">{{ __('strings.lb_test_analysis') }}</h5>

                <div class="list-group">
                @for($i=0; $i < sizeof($scoreAnalysis); $i++)
                    <div class="list-group-item ">
                        <div class="w-100">
                            <div class="font-weight-bold">[{{ $scoreAnalysis[$i]['bTitle'] }}]</div>
                            @for($j=0; $j < sizeof($scoreAnalysis[$i]['child']); $j++)
                                <div class="text-sm">{{ $scoreAnalysis[$i]['child'][$j]['sTitle'] == "" ? "":$scoreAnalysis[$i]['child'][$j]['sTitle']." :" }}  {{$scoreAnalysis[$i]['child'][$j]['txt']}}</div>
                            @endfor
                        </div>
                    </div>


                @endfor
                </div>


                    <!-- 선생님 내용 표시하기 -->
                <h5 class="mt-3">{{ $settings->teacher_title }} </h5>
                <div class="list-group">
                    @foreach($teacherSays as $teacherSay)
                        <div class="list-group-item"> {{ $teacherSay }}</div>
                    @endforeach
                </div>
                <!-- wordian -->
                @if (sizeof($wordians) > 0)
                    <h5 class="mt-3">{{ __('strings.lb_wordian_title') }} </h5>
                    <div class="list-group">
                        @foreach($wordians as $wordian)
                            <div class="list-group-item"> {{ $wordian }}</div>
                        @endforeach
                    </div>
                @endif
                <!-- blog -->
                @if ($settings->blog_link_url != "")
                    <div class="mt-2 pb-2">
                        {{ $settings->blog_guide }} <a href="http://{{ $settings->blog_link_url }}">http://{{ $settings->blog_link_url }}</a>
                    </div>
                @endif
            </div>

            @if ($settings->use_bottom == "Y")
            <div class="mt-5 bg-dark container-fluid p-4">
                <h6 class="text-white text-center">{{ $settings->sps_opt_2 }}</h6>
            </div>
            @endif
        </div>

        <script type="text/javascript">
            $(document).ready(function (){
                printChart();
            });



            function printChart(){
                @for($i=0; $i < sizeof($jsData); $i++){
                    let ctx = document.getElementById('chart_{{ $i }}');

                    let myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: [
                                '이전 주','이번 주','DB'
                            ],
                            datasets: [
                                @for($j = 0; $j < sizeof($jsData[$i]); $j++)
                                {
                                    label: '{!! $jsData[$i][$j]['labels'] !!}',
                                    data: [{{ $jsData[$i][$j]['scores'] }}],
                                    backgroundColor: getRandomColor({{ $jsData[$i][$j]['stack'] }},'{!! $jsData[$i][$j]['max'] !!}'),
                                    borderColor: 'rgba(0, 0, 0, 0.2)',
                                    borderWidth: 1,
                                    stack: 'Stack {{ $jsData[$i][$j]['stack'] }}',
                                    dataMaxs: '{!! $jsData[$i][$j]['max'] !!}',
                                },
                                @endfor
                            ]
                        },
                        options: {
                            responsive: false,
                            scales: {
                                x: {
                                    stacked: true,
                                },
                                y: {
                                    beginAtZero: true,
                                    stacked: true
                                }
                            },
                            legend: {
                                display: true,
                                labels: {
                                    fontColor: 'rgb(31,30,30)'
                                }
                            },
                            tooltips: {
                                callbacks: {
                                    label: function(tooltipItem, data) {
                                        var label = data.datasets[tooltipItem.datasetIndex].label || '';

                                        if (label) {
                                            label += ': ';
                                        }
                                        label += tooltipItem.yLabel;
                                        label += " / " + data.datasets[tooltipItem.datasetIndex].dataMaxs;
                                        return label;
                                    }
                                }
                            },
                            plugins: {
                                datalabels: {
                                    //color: 'white',
                                    labels: {
                                        title: {
                                            font: {
                                                weight: 'bold',
                                            }
                                        },
                                        value: {
                                            color: 'green'
                                        }
                                    }
                                },

                            }
                        }
                    });
                }
                @endfor

            }

            function getRandomColor(index,mScore){
                let colors = [
                    'rgb(255, 99, 132, 0.2)',
                    'rgb(255, 159, 64, 0.2)',
                    'rgb(255, 205, 86, 0.2)',
                    'rgb(75, 192, 192, 0.2)',
                    'rgb(54, 162, 235, 0.2)',
                    'rgb(153, 102, 255, 0.2)',
                    'rgb(201, 203, 207, 0.2)',
                    'rgba(0,255,0,0.2)',
                    'rgba(231,178,144,0.2)',
                    'rgba(82,239,192,0.2)',
                    'rgba(56,138,232,0.2)',
                    'rgba(54,79,243,0.2)',
                    'rgba(246,117,245,0.2)'
                    ];
                if (mScore === '100'){
                    return 'rgba(252,3,3,0.5)';
                }else{
                    return colors[index];
                }

            }
        </script>
    </body>
</html>
