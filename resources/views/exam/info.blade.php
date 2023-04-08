@extends('dashboard.layouts.master')
@section('css')
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
 <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
 <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.bootstrap4.min.css">
@endsection
@section('additonal_classes_for_main_div')
    overflow-hidden
@endsection
@section('content')
<header class="clrhdr">
    <div class="leftIcn">
        <div class="backicn" onclick="javascript: window.history.back();">BACK</div>
    </div>

    <div class="pgnme">
        {{ $exam->title }}
    </div>

    <div class="rgtIcn">

    </div>
</header>

<section class="pageBody" style="height: calc(100% - 50px) !important">
    <div style="max-width:800px !important; margin: auto !important">
    <aside class="whtBx1" style="border-radius: 0px !important">
        <div class="inSet">
            <h6 style="font-size: 1.5rem;"><span>Users</span></h6>
        </div>

        <div class="inSet ml-2">
            <h6 style="color: black"><span>Registered: {{ $registered_count }}</span></h6>
            <div class="progress ml-2" style="height: 5px;">
                <div class="progress-bar reg" role="progressbar" style="width: 0%; background-color: #511285;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
        <div class="inSet ml-2">
            <h6 style="color: black"><span>Guests: {{ $guests_count }}</span></h6>
            <div class="progress ml-2" style="height: 5px;">
                <div class="progress-bar guest" role="progressbar" style="width: 0%; background-color: #511285;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
        <div class="inSet ml-2">
            <h6 style="color: black"><span>Total: {{ $total_users_count }}</span></h6>
            <div class="progress ml-2" style="height: 5px;">
                <div class="progress-bar total" role="progressbar" style="width: 0%; background-color: #511285;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </aside>
    <aside class="whtBx1" style="border-radius: 0px !important">
        <div class="inSet">
            <h6 style="font-size: 1.5rem;"><span>Results</span></h6>
        </div>
        <div class="inSet ml-2">
            <h6 style="color: black"><span>Passed: {{ $passed_count }}</span></h6>
            <div class="progress ml-2" style="height: 5px;">
                <div class="progress-bar passed" role="progressbar" style="width: 0%; background-color: #66b8d9;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
        <div class="inSet ml-2">
            <h6 style="color: black"><span>Failed: {{ $failed_count }}</span></h6>
            <div class="progress ml-2" style="height: 5px;">
                <div class="progress-bar failed" role="progressbar" style="width: 0%; background-color: #66b8d9;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
        <div class="inSet ml-2">
            <h6 style="color: black"><span>Total: {{ $total_attempts_count }}</span></h6>
            <div class="progress ml-2" style="height: 5px;">
                <div class="progress-bar total" role="progressbar" style="width: 0%; background-color: #66b8d9;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </aside>

    <div class="inSet">
        <h6 style="font-size: 1.5rem;"><span>Shared in Groups</span></h6>
    </div>
    <aside class="qsLst flwngList">
        <ul class="gqList userSelectGrp">
            @foreach ($exam->groups as $group)
                <li class="xmrm_{{ $group->id }}">
                    <input type="checkbox" class="xm_list deletable" id="existing_xmlst_{{ $group->id }}" name="groups[]" value="{{ $group->id }}">
                    <label for="grplst_{{ $group->id }}" style="margin-bottom: 0% !important">
                        <div class="lblIn">
                            <div class="gqlImg">
                                <img src="{{ isset($group->icon) ? Storage::url($group->icon) : url('images/placeholder.jpeg') }}"/>
                            </div>
                            <div class="gqlT1">CODE: <b>{{ $group->id + 1000 }}</b> Owner: <b>{{ $group->owner->name }}</b></div>
                            <div class="gqlT2">{{ $group->title ?? 'untitled' }}</div>
                        </div>
                    </label>
                    <span class="gqlDlt" onclick="RmvFrmGrp('{{ $group->id }}')">X</span>
                </li>
            @endforeach
        </ul>
    </aside>
    <div class="inSet">
        <h6 style="font-size: 1.5rem;"><span>Report</span></h6>
        {!!  $dataTable->table() !!}
    </div>
    </div>
</section>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset("js/group_funcs.js") }}"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.bootstrap4.min.js"></script>
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    {!! $dataTable->scripts() !!}
@endsection
