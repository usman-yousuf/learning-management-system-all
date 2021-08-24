@extends('layouts.landing_page')

@section('page-title')
    Contact Us
@endsection

@section('content')
    <section id="contact" class="contact section-bg">
        <div class="container aos-init aos-animate" data-aos="fade-up">

            <div class="row my-3">

                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="info-box mb-2">
                                <i class="fa fa-map fa-2x mb-3 mt-2"></i>
                                <h3>Our Address</h3>
                                <p>Franklin St, Greenpoint Ave</p>
                                <a style="margin-top: 10px;margin-left: 0px;" target="blank"
                                    href="https://www.google.com/maps/dir//Franklin+St+%26+Greenpoint+Ave,+Brooklyn,+NY+11222,+USA/@40.7299428,-73.9926096,13z/data=!4m8!4m7!1m0!1m5!1m1!1s0x89c259404f5892ab:0x1774f052d3491a8c!2m2!1d-73.9575901!2d40.729883"
                                    class="get-started-btn scrollto">Get Direction</a>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class="col-md-6">
                            <div class="info-box my-3">
                                <i class="fa fa-envelope fa-2x mb-2 mt-2"></i>
                                <h3>Email Us</h3>
                                <p>
                                    <a href='mailto:roomatoz@gmail.com' class='no_link-s'>roomatoz@gmail.com</a>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box my-3">
                                <i class="fa fa-phone fa-2x mb-2 mt-2"></i>
                                <h3>Call Us</h3>
                                <p>
                                    <a href='tel:+2 342 4456 45' class='no_link-s'>+2 342 4456 45</a>
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-lg-6 mt-5 mt-md-0">
                    <form id="frm_contact_us-d" action="{{ route('contactUs') }}" method="post" role="form" class="php-email-form">
                        @csrf
                        <div class="form-row">
                            <div class="col-md-6 form-group">
                                <input type="text" name="name" class="form-control" placeholder="Your Name" data-msg="Please enter at least 4 chars"  value='{{ \Auth::user()->profile->full_name ?? '' }}' />
                            </div>
                            <div class="col-md-6 form-group">
                                <input type="email" class="form-control" name="email" placeholder="Your Email" value='{{ \Auth::user()->email ?? '' }}' />
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="subject" placeholder="Subject" />
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="message" rows="5" placeholder="Message"></textarea>
                        </div>
                        <div class="mb-3">
                            <h4 class="sent-notification text-success"></h4>
                        </div>
                        <div class="text-center py-3">
                            <button type="submit" class="bg_success-s br_24-s py-2 px-5 text-white  border border-white ">
                                {{-- onclick="sendEmail()" --}}
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </section>
@endsection
