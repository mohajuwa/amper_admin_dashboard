@extends('layout.app')

@section('style')
<style>
    .btn-xs {
        padding: 2px 5px;
        font-size: 10px;
        line-height: 1.5;
        border-radius: 3px;
        width: 50px !important;
        /* Adjust the width as needed */
    }
</style>
@endsection

@section('content')
<main class="main">
    <div class="page-header text-center">
        <div class="container">
            <h1 class="page-title">Notifications</h1>
        </div>
    </div>
    <div class="page-content">
        <div class="dashboard">
            <div class="container">
                <br />
                <div class="row">
                    @include('user._sidebar')
                    <div class="col-md-8 col-lg-9">
                        <div class="tab-content">

                            <table class="table table-striped">

                                <tbody>
                                    @foreach ($getRecord as $value)
                                    <tr>

                                        <td style="padding: 10px;"><a
                                                style="color:#000; {{ empty($value->is_read) ? 'font-weight:bold': ''}}"
                                                href="{{url($value->url)}}?noti_id={{$value->id}}">{{$value->message}}</a>

                                            <div>
                                                <small>
                                                    {{date('d-m-Y h:i A',strtotime($value->created_at))}}
                                                </small>
                                            </div>
                                        </td>







                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div style="padding: 10px; float: right;">
                                {!! $getRecord->appends(Illuminate\Support\Facades\Request::except('page'))->links()
                                !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('script')
<script src="{{ url('assets/js/jquery.elevateZoom.min.js') }}"></script>
<script src="{{ url('assets/js/bootstrap-input-spinner.js') }}"></script>
<script src="{{ url('assets/js/jquery.magnific-popup.min.js') }}"></script>
<script type="text/javascript"></script>
@endsection