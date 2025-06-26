@extends('admin.layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fuild">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>إعدادات النظام</h1>
            </div>
        </div>
    </div>
</section>

@include('admin.layouts._message')

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">إعدادات النظام</h3>
                    </div>

                    {{-- Use correct route and method --}}
                    <form action="{{ url('admin/settings/system') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="card-body">
                            {{-- Website Name --}}
                            <div class="form-group">
                                <label>اسم الموقع <span style="color:red;">*</span></label>
                                <input type="text" class="form-control"
                                    value="{{ old('website_name', $getRecord->website_name) }}" name="website_name"
                                    placeholder="أدخل اسم الموقع">
                                <div style="color:red">{{ $errors->first('website_name') }}</div>
                            </div>

                            {{-- Logo --}}
                            <div class="form-group">
                                <label>الشعار <span style="color:red;">*</span></label>
                                <input type="file" class="form-control" name="logo">
                                <div style="color:red">{{ $errors->first('logo') }}</div>
                                @if (!empty($getRecord->getLogo()))
                                    <img src="{{ $getRecord->getLogo() }}" style="width:200px;">
                                @endif
                            </div>

                            {{-- Fevicon --}}
                            <div class="form-group">
                                <label>أيقونة التبويب (Fevicon) <span style="color:red;">*</span></label>
                                <input type="file" class="form-control" name="fevicon">
                                <div style="color:red">{{ $errors->first('fevicon') }}</div>
                                @if (!empty($getRecord->getFevIcon()))
                                    <img src="{{ $getRecord->getFevIcon() }}" style="width:50px;">
                                @endif
                            </div>

                            {{-- Footer Payment Icon --}}
                            <div class="form-group">
                                <label>أيقونة الدفع في التذييل <span style="color:red;">*</span></label>
                                <input type="file" class="form-control" name="footer_payment_icon">
                                <div style="color:red">{{ $errors->first('footer_payment_icon') }}</div>
                                @if (!empty($getRecord->getFooterPaymenIcon()))
                                    <img src="{{ $getRecord->getFooterPaymenIcon() }}" style="width:200px;">
                                @endif
                            </div>

                            {{-- Address --}}
                            <div class="form-group">
                                <label>العنوان <span style="color:red;">*</span></label>
                                <textarea class="form-control" name="address">{{ old('address', $getRecord->address) }}</textarea>
                                <div style="color:red">{{ $errors->first('address') }}</div>
                            </div>

                            {{-- Phone --}}
                            <div class="form-group">
                                <label>الهاتف <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" value="{{ old('phone', $getRecord->phone) }}" name="phone" placeholder="أدخل الهاتف">
                                <div style="color:red">{{ $errors->first('phone') }}</div>
                            </div>

                            {{-- Phone 2 --}}
                            <div class="form-group">
                                <label>الهاتف 2 <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" value="{{ old('phone_two', $getRecord->phone_two) }}" name="phone_two" placeholder="أدخل الهاتف الثاني">
                                <div style="color:red">{{ $errors->first('phone_two') }}</div>
                            </div>

                            {{-- Submit Email --}}
                            <div class="form-group">
                                <label>بريد التواصل <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" value="{{ old('submit_email', $getRecord->submit_email) }}" name="submit_email" placeholder="أدخل بريد التواصل">
                                <div style="color:red">{{ $errors->first('submit_email') }}</div>
                            </div>

                            {{-- Email --}}
                            <div class="form-group">
                                <label>البريد الإلكتروني <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" value="{{ old('email', $getRecord->email) }}" name="email" placeholder="أدخل البريد الإلكتروني">
                                <div style="color:red">{{ $errors->first('email') }}</div>
                            </div>

                            {{-- Email 2 --}}
                            <div class="form-group">
                                <label>البريد الإلكتروني 2 <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" value="{{ old('email_two', $getRecord->email_two) }}" name="email_two" placeholder="أدخل البريد الثاني">
                                <div style="color:red">{{ $errors->first('email_two') }}</div>
                            </div>

                            {{-- Working Hours --}}
                            <div class="form-group">
                                <label>ساعات العمل <span style="color:red;">*</span></label>
                                <textarea class="form-control" name="working_hours">{{ old('working_hours', $getRecord->working_hours) }}</textarea>
                                <div style="color:red">{{ $errors->first('working_hours') }}</div>
                            </div>

                            <hr>

                            {{-- Social Media Links --}}
                            <div class="form-group">
                                <label>رابط الفيسبوك <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" value="{{ old('facebook_link', $getRecord->facebook_link) }}" name="facebook_link" placeholder="أدخل رابط الفيسبوك">
                                <div style="color:red">{{ $errors->first('facebook_link') }}</div>
                            </div>
                            <div class="form-group">
                                <label>رابط تويتر(X) <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" value="{{ old('twitter_link', $getRecord->twitter_link) }}" name="twitter_link" placeholder="أدخل رابط تويتر">
                                <div style="color:red">{{ $errors->first('twitter_link') }}</div>
                            </div>
                            <div class="form-group">
                                <label>رابط إنستغرام <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" value="{{ old('instagram_link', $getRecord->instagram_link) }}" name="instagram_link" placeholder="أدخل رابط إنستغرام">
                                <div style="color:red">{{ $errors->first('instagram_link') }}</div>
                            </div>
                            <div class="form-group">
                                <label>رابط يوتيوب <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" value="{{ old('youtube_link', $getRecord->youtube_link) }}" name="youtube_link" placeholder="أدخل رابط يوتيوب">
                                <div style="color:red">{{ $errors->first('youtube_link') }}</div>
                            </div>
                            <div class="form-group">
                                <label>رابط Pinterest <span style="color:red;">*</span></label>
                                <input type="text" class="form-control" value="{{ old('paintrest_link', $getRecord->paintrest_link) }}" name="paintrest_link" placeholder="أدخل رابط Pinterest">
                                <div style="color:red">{{ $errors->first('paintrest_link') }}</div>
                            </div>

                            {{-- Footer Description --}}
                            <div class="form-group">
                                <label>وصف التذييل <span style="color:red;">*</span></label>
                                <textarea class="form-control editor" name="footer_description">{{ old('footer_description', $getRecord->footer_description) }}</textarea>
                                <div style="color:red">{{ $errors->first('footer_description') }}</div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-info">حفظ</button>
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
