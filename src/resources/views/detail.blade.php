@php use phongtran\Logger\app\Services\Definitions\LoggerDef; @endphp
@extends('logger.layout')
@section('content')
    <style>
        .icon-back {
            width: 30px;
            padding-bottom: 10px;
        }
        .back-box {
            width: fit-content;
        }
        .back-box:hover {
            cursor: pointer;
        }
    </style>
    <div class="content-wrapper">
        <div class="row">
            <div class="back-box" onclick="window.location.href='{{ !$log->activity_id ? route('log.index') : url()->previous() }}'">
                <svg  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-back">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </div>
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Log Detail</h4>
                        <ul>
                            <li>Activity ID: {{$log->id}}</li>
                            <li>Channel: {{$log->channel}}</li>
                            <li>Level: {{$log->level}}</li>
                            <li>Body: {{$log->message}}</li>
                            <li>Created at: {{$log->created_at}}</li>
                        </ul>
                        <h4 class="card-title">Response</h4>
                        @if($log->response)
                            @include('logger.components.json-viewer', ['json' => $log->response])
                        @endif
                    </div>
                    @if(isset($log->activity_id))
                        <div class="card-body">
                            <h4 class="card-title">Log activity</h4>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>Channel</th>
                                        <th>Content</th>
                                        <th>Level</th>
                                        <th>Log date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($log->log as $item)
                                        <tr onclick="window.location.href='{{ route('log.detail', ['id' => $item->id]) }}'" style="cursor: pointer;">
                                            <td>{{$item->channel}}</td>
                                            <td>{{$item->message}}</td>
                                            <td>
                                                <label class="badge badge-{{LoggerDef::getLogBadgeLevel($item->level)}}">
                                                    {{$item->level}}
                                                </label>
                                            </td>
                                            <td>{{$item->created_at}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
