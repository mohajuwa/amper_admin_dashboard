@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fuild">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1> SMTP Setting </h1>
            </div>

        </div>
    </div>
</section>
@include('admin.layouts._message')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card   card-info">
                    <div class="card-header">
                        <h3 class="card-title"> SMTP Setting</h3>
                    </div>



                    <form action="" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <div class="form-group">
                                <label>Name <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" value="{{ old('name', $getRecord->name) }}"
                                    name="name" placeholder="Enter Name">
                                <div style="color:red">{{ $errors->first('name') }}</div>
                            </div>

                            <div class="form-group">
                                <label>Mail Mailer <span style="color:red;">*</span></label>
                                <input type="text" class="form-control"
                                    value="{{ old('mail_mailer', $getRecord->mail_mailer) }}" name="mail_mailer"
                                    placeholder="Enter Mail Mailer">
                                <div style="color:red">{{ $errors->first('mail_mailer') }}</div>
                            </div>

                            <div class="form-group">
                                <label>Mail Host <span style="color:red;">*</span></label>
                                <input type="text" class="form-control"
                                    value="{{ old('mail_host', $getRecord->mail_host) }}" name="mail_host"
                                    placeholder="Enter Mail Host">
                                <div style="color:red">{{ $errors->first('mail_host') }}</div>
                            </div>

                            <div class="form-group">
                                <label>Mail Port <span style="color:red;">*</span></label>
                                <input type="text" class="form-control"
                                    value="{{ old('mail_port', $getRecord->mail_port) }}" name="mail_port"
                                    placeholder="Enter Mail Port">
                                <div style="color:red">{{ $errors->first('mail_port') }}</div>
                            </div>

                            <div class="form-group">
                                <label>Mail Username <span style="color:red;">*</span></label>
                                <input type="text" class="form-control"
                                    value="{{ old('mail_username', $getRecord->mail_username) }}" name="mail_username"
                                    placeholder="Enter Mail Username">
                                <div style="color:red">{{ $errors->first('mail_username') }}</div>
                            </div>

                            <div class="form-group">
                                <label>Mail Password <span style="color:red;">*</span></label>
                                <input type="text" class="form-control"
                                    value="{{ old('mail_password', $getRecord->mail_password) }}" name="mail_password"
                                    placeholder="Enter Mail Password">
                                <div style="color:red">{{ $errors->first('mail_password') }}</div>
                            </div>

                            <div class="form-group">
                                <label>Mail Encryption <span style="color:red;">*</span></label>
                                <input type="text" class="form-control"
                                    value="{{ old('mail_encryption', $getRecord->mail_encryption) }}"
                                    name="mail_encryption" placeholder="Enter Mail Encryption">
                                <div style="color:red">{{ $errors->first('mail_encryption') }}</div>
                            </div>

                            <div class="form-group">
                                <label>Mail From Address <span style="color:red;">*</span></label>
                                <input type="email" class="form-control"
                                    value="{{ old('mail_from_address', $getRecord->mail_from_address) }}"
                                    name="mail_from_address" placeholder="Enter Mail From Address">
                                <div style="color:red">{{ $errors->first('mail_from_address') }}</div>
                            </div>
                        </div>


                        <div class="card-footer">
                            <button type="submit" class="btn btn-info">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
@endsection