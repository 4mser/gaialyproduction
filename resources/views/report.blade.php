<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('Findings report') }}</title>
    <style>
        * {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            line-height: 1.5;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
            margin: 0px;
            padding: 0px;
        }

        h1 {
            font-size: 25px;
        }

        h2 {
            font-size: 18px;
        }

        .title {
            text-align: center;
            font-weight: normal;
            text-transform: uppercase;
            margin-bottom: 80px;
        }

        .table-operation {
            width: 100%;
        }

        .table-operation th {
            vertical-align: top;
            text-align: left;
            width: 25%;
            padding-bottom: 10px;
        }

        .table-operation td {
            text-align: justify;
            padding-bottom: 10px;
        }

        .table-finding-wrapper {
            width: 100%;
        }

        .table-finding-wrapper .td-data,
        .table-finding-wrapper .td-map {
            width: 50%;
        }

        .table-finding-wrapper td,
        .table-finding-wrapper th {
            vertical-align: top;
            text-align: left;
            padding-bottom: 10px;
        }

        .table-finding-wrapper th {
            padding-right: 20px;
        }


        .nowrap {
            white-space: nowrap;
        }

        img.map {
            width: 100%;
        }

        .table-finding-items {
            width: 100%;
            border-spacing: 0;
            border-collapse: collapse;
        }

        .table-finding-items th,
        .table-finding-items td {
            font-size: 14px;
            text-align: left;
            padding: 2px 4px;
            margin: 0px;
            border: 1px solid #999;
        }

        .page-break {
            page-break-before: always;
        }

        .text-center {
            text-align: center;
        }

        footer {
            position: fixed;
            bottom: -40px;
            left: 0px;
            right: 0px;
            height: 50px;

            /** Extra personal styles **/
            /* background-color: #03a9f4; */
            /* color: white; */
            text-align: center;
            line-height: 35px;
        }
    </style>
</head>

<body>
    <div style="text-align:center; margin-top: 280px; margin-bottom:30px;">
        @if (empty(auth()->user()->parentUser()->first()->company_photo_path
            ))
            <img style="width:200px;" src="{{ public_path('img/logo.png') }}" />
        @else
            <img style="width:150px;" src="{{ uploads_path(auth()->user()->parentUser()->first()->company_photo_path) }}" />
        @endif
        <div style="font-weight:bold; font-size:32px; margin-top:10px;">{{ auth()->user()->parentUser()->first()->company->name }}</div>
    </div>
    <h1 class="title">Findings Report</h1>
    <h2>Operation Info</h2>
    <table class="table-operation">
        <tbody>
            <tr>
                <th>Name</th>
                <td>{{ $operation->name }}</td>
            </tr>
            <tr>
                <th>Description</th>
                <td>{{ $operation->description }}</td>
            </tr>
            <tr>
                <th>Type</th>
                <td>{{ $operation->operationType->name }}</td>
            </tr>

            <tr>
                <th>Company</th>
                <td>{{ $operation->company->name }}</td>
            </tr>
        </tbody>
    </table>
    <div class="page-break"></div>
    <h2>Findings</h2>
    @php
        $layerCounter = 0;
    @endphp
    @foreach ($layers as $layer)
        @php
            $data = json_decode($layer->data);
        @endphp

        <table class="table-finding-wrapper">
            <tbody>
                <tr>
                    <td class="td-data">
                        <table>
                            <tbody>
                                <tr>
                                    <th>Name</th>
                                    <td>{{ $layer->name }}</td>
                                </tr>
                                <tr>
                                    <th>Position</th>
                                    <td>
                                        @php
                                            $geom = json_decode($layer->geom);
                                        @endphp
                                        @if (isset($geom->coordinates) && is_array($geom->coordinates) && count($geom->coordinates) == 2)
                                            <a target="_blank" href="https://www.google.com/maps/search/?api=1&query={{ $geom->coordinates[1] }}%2C{{ $geom->coordinates[0] }}">{{ $geom->coordinates[0] }},{{ $geom->coordinates[1] }}</a>
                                        @else
                                            <div>No coordinates</div>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="nowrap">File name</th>
                                    <td>{{ $layer->file_name }}</td>
                                </tr>
                                <tr>
                                    <th>Findings</th>
                                    <td>{{ is_countable($data) ? count($data) : 0 }}</td>
                                </tr>
                                @if ($layer->layer_type_id == App\Models\LayerType::THERMO && !empty($layer->thermal_data['min_temp']) && !empty($layer->thermal_data['max_temp']) && !empty($layer->thermal_data['avg_temp']))
                                    <tr>
                                        <th>Temp min</th>
                                        <td>{{ $layer->thermal_data['min_temp'] }} °C</td>
                                    </tr>
                                    <tr>
                                        <th>Temp max</th>
                                        <td>{{ $layer->thermal_data['max_temp'] }} °C</td>
                                    </tr>
                                    <tr>
                                        <th>Temp avg</th>
                                        <td>{{ $layer->thermal_data['min_temp'] }} °C</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th class="nowrap">Created at</th>
                                    <td>{{ $layer->created_at->format('m-d-Y H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td class="td-map">
                        @php
                            $map = uploads_path('layers/map_' . $layer->id . '.jpg');
                        @endphp

                        @if (isset($geom->coordinates) && is_array($geom->coordinates) && count($geom->coordinates) == 2)
                            <img class="map" src="https://maps.googleapis.com/maps/api/staticmap?markers=size:mid|color:red|{{ $geom->coordinates[1] }},{{ $geom->coordinates[0] }}&zoom=14&size=400x400&key={{ env('GOOGLE_API_KEY') }}" />
                        @else
                            <div style="text-align:center;">No coordinates</div>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
        <br>
        <table class="table-finding-items">
            <thead>
                <tr>
                    <th>N</th>
                    <th>Label</th>
                    <th>Confidence</th>
                    <th>Severity</th>
                    <th>Solved</th>
                    <th>Remedy action</th>
                    <th>Repair cost</th>
                    <th>Category</th>
                    @if ($layer->layer_type_id == App\Models\LayerType::THERMO)
                        <th>Temp</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if ($data)
                    @foreach ($data as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ !empty($item->label) ? $item->label : '' }}</td>
                            <td>{{ !empty($item->confidence) ? $item->confidence . '%' : '' }}</td>
                            <td>{{ !empty($item->severity) ? $item->severity : '' }}</td>
                            <td>{{ !empty($item->solved) ? 'Yes' : 'No' }}</td>
                            <td>{{ !empty($item->remedy) ? $item->remedy : '' }}</td>
                            <td class="nowrap">
                                {{ !empty($item->currency) ? $item->currency : '' }}
                                {{ !empty($item->cost) ? $item->cost : '' }}
                            </td>
                            <td>
                                @if (!empty($item->category))
                                    @if ($item->category == 1)
                                        Power Lines
                                    @elseif ($item->category == 2)
                                        LiDAR Vegetation
                                    @elseif ($item->category == 3)
                                        Topography
                                    @endif
                                @endif
                            </td>
                            @if ($layer->layer_type_id == App\Models\LayerType::THERMO)
                                <td>
                                    Min: {{ $item->temp_min }} °C<br>
                                    Max: {{ $item->temp_max }} °C<br>
                                    Avg: {{ $item->temp_avg }} °C
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="{{ $layer->layer_type_id == App\Models\LayerType::THERMO ? '9' : '8' }}" style="text-align:center;">No findings</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <br>
        @if ($data)
            <img width="50%" src="{{ uploads_path('layers/preview_' . $layer->id . '.jpg') }}">
        @else
            <img width="100%" src="{{ uploads_path('layers/' . $layer->id . '.jpg') }}">
        @endif
        @php $layerCounter++ @endphp
        @if ($layerCounter < count($layers))
            <div class="page-break"></div>
        @endif
    @endforeach

    <div class="text-center">
        @if (auth()->user()->signature_photo_path)
            <img style="max-height:120px;" src="{{ uploads_path(auth()->user()->signature_photo_path) }}">
        @endif
        <p>{{ auth()->user()->name . ' ' . auth()->user()->last_name }}</p>
        <p>{{ auth()->user()->title }}</p>
    </div>

    <footer>
        This report has been created automatically by GaiaLy
    </footer>
</body>

</html>
