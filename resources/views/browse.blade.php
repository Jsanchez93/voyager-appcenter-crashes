@extends('voyager::master')

@section('page_title', 'Appcenter Crashes Report')

@section('page_header')
    <div class="page-title">
        <i class="voyager-file-text"></i>
        Appcenter Crashes Report
    </div>    
    @include('voyager::multilingual.language-selector')
@stop

@section('css')
<style type="text/css">.show-crash {background-color: #fff;box-shadow: 5px 5px 20px rgba(0,0,0,0.1);padding: 20px 15px;}.show-crash h2 {border-bottom: 1px solid rgba(51,51,51,0.5);margin-bottom: 25px;text-align: center;}.show-crash h2 span {background-color: #fff;font-size: 0.6em;padding: 0 5px;position: relative;top: 12px;left: 0px;}.show-crash h3 {font-size: 2em;margin: 0 0 20px 0;}.show-crash p span:nth-child(2) {color: #f00;}.show-crash p span:nth-child(1) {font-size: 1.1em;font-weight: 400;}</style>
@endsection

@section('content')
    <div class="page-content container-fluid" id="voyagerBreadEditAdd">
        <div class="row">
            
                
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="table-responsive">
                    <table id="dataTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>App version</th>
                                <th>Impacted users</th>
                                <th>Reason</th>
                                <th>Last occurrence</th>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>                            
                            @foreach ($data->crash_groups as $info)
                            <tr>
                                <td class="text-center">{{ $info->app_version }}</td>
                                <td class="text-center">{{ $info->impacted_users }}</td>
                                <td>{{ $info->crash_reason }}</td>
                                <td>{{ \Carbon\Carbon::parse($info->last_occurrence)->format('d/m/Y - h:i:sA') }}</td>
                                <td><a data-id='{{ $info->crash_group_id }}' href="#!" class="btn-sm btn-primary view_report">More</a></td>
                            </tr>
                            @endforeach  
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="show-crash hide">
                    <h3>General</h3>
                    <div class="general">
                        <p><span>App version:       </span><span class="appG"></span></p>
                        <p><span>Build:             </span><span class="buildG"></span></p>
                        <p><span>Count:             </span><span class="countG"></span></p>
                        <p><span>Impacted users:    </span><span class="impactedG"></span></p>                        
                        <p><span>First occurrence:  </span><span class="firstG"></span></p>
                        <p><span>Last occurrence:   </span><span class="lastG"></span></p>
                    </div>
                    <h2><span>Reasons (devs)</span></h2>
                    <div class="reasons">
                        <p><span class="fatalR"></span></p>
                        <p><span>Crash reason: </span><span class="crashR"></span></p>
                        <div class="reasons_frame"></div>
                    </div>
                    
                </div>
            </div>
        </div><!-- .row -->
    </div><!-- .page-content -->
    
@stop


@section('javascript')
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/themes/smoothness/jquery-ui.css">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script>

    <!-- DataTables -->        
    <script>
        $(document).ready(function () {            
            var table = $('#dataTable').DataTable({!! json_encode(
                array_merge([
                    "order" => [],
                    "language" => __('voyager.datatable'),
                ],
                config('voyager.dashboard.data_tables', []))
            , true) !!});

            var data = {};
            @foreach ($data->crash_groups as $info)
                data['{{ $info->crash_group_id }}'] = {!! json_encode($info, JSON_HEX_QUOT) !!};
            @endforeach            
            $(".view_report").on('click', function(event){
                event.preventDefault();
                var info = data[$(this).data("id")];
                $('.appG').text( info.app_version );
                $('.buildG').text( info.build );
                $('.countG').text( info.count );
                $('.impactedG').text( info.impacted_users );
                $('.firstG').text( info.first_occurrence );
                $('.lastG').text( info.last_occurrence );
                $('.fatalR').text( info.fatal?'Fatal Error':'Non-fatal error' );
                $('.crashR').text( info.crash_reason );
                
                $(".reasons_frame").empty();
                for(var k in info.reason_frame) {
                    if(k!='code_formatted'){
                        var p = $("<p></p>");
                        var span = $("<span></span>");
                        span.text(k+': ');
                        p.append(span);
                        span = $("<span></span>");
                        span.text(info.reason_frame[k]);
                        p.append(span);
                        
                        $(".reasons_frame").append(p);
                    }
                }

                $(".show-crash").removeClass('hide');


            });

            
            
        });

    </script>
@stop
