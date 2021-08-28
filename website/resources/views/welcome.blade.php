@extends('layouts.landing_page')

@section('page-title')
    Home
@endsection

@section('content')
        {{-- welcome page descriptor text - START --}}
        <section>
            <div class="row py-5 my-5">
                <div class="col-md-6 col-12 align-self-center">
                    <h1 class="fs_64px-s mb-5">The Smarter Way To learn <span class="fg_parrot_green-s"><u>Anything</u></span> </h1>
                    <span class="fg_light_grey-s">In publishing and graphic design, Lorem ipsum is a placeholder text commonly used to demonstrate visual form of a document or a typeface without relying on meaningful content.</span>
                </div>
                <div class="col-6 d-none d-xl-block d-lg-block d-md-block">
                    <p>&nbsp;</p>
                </div>
            </div>
        </section>
        {{-- welcome page descriptor text - END --}}

        {{-- Brag about ourself - START --}}
        <section>
            <div class="row justify-content-center py-5">
                <div class="col-lg-8 col-md-10 col-12 text-center">
                    <h1 class="fs_30px_on_small_Screen-s">Welcome To <span class="fg_parrot_green-s">Room A To Z</span> </h1>
                </div>
                <div class="col-lg-5 col-md-7 col-12 text-center">
                    <span class="fg_light_grey-s">In publishing and graphic design, Lorem ipsum is a placeholder text commonly used to demonstrate visual form</span>
                </div>
            </div>
            <div class="row mt-2">
                <!--awesome Teacher Card-->
                <div class="col-xl-3 col-lg-6 col-md-6 col-12 pt-4">
                    <div class="card shadow border-0 br_10px-s">
                        <div class="card-body">
                            <!--card image-->
                            <div>
                                <img src="{{ asset('assets/images/graduation_cap_icon.svg') }}" alt="graduation-cap-icon">
                            </div>
                            <!--card title-->
                            <div class="card-title mt-3">
                                <h6><strong>Awesome Teachers</strong></h6>
                            </div>
                            <!--card text-->
                            <div class="card-text">
                                <p class="fg_light_grey-s">In publishing and graphic design, Lorem ipsum</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--awesome teacher card end-->

                <!--Global certificate card -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-12 pt-4">
                    <div class="card shadow border-0 br_10px-s">
                        <div class="card-body">
                            <!--card image-->
                            <div>
                                <img src="{{ asset('assets/images/global_education_icon.svg') }}" alt="global education icon">
                            </div>
                            <!--card title-->
                            <div class="card-title mt-3">
                                <h6><strong>Global Certificate</strong></h6>
                            </div>
                            <!--card text-->
                            <div class="card-text">
                                <p class="fg_light_grey-s">In publishing and graphic design, Lorem ipsum</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--GLobal certificate card End-->

                <!--BEst program card -->
                <div class="col-xl-3 col-lg-6 col-md-6 col-12 pt-4">
                    <div class="card shadow border-0 br_10px-s">
                        <div class="card-body">
                            <!--card image-->
                            <div>
                                <img src="{{ asset('assets/images/atom_icon.svg') }}" alt="star programs" />
                            </div>
                            <!--card title-->
                            <div class="card-title mt-3">
                                <h6><strong>Best Program</strong></h6>
                            </div>
                            <!--card text-->
                            <div class="card-text">
                                <p class="fg_light_grey-s">In publishing and graphic design, Lorem ipsum</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--BEst program card End-->

                <!--Student Support Service card-->
                <div class="col-xl-3 col-lg-6 col-md-6 col-12 pt-4">
                    <div class="card shadow border-0 br_10px-s">
                        <div class="card-body">
                            <!--card image-->
                            <div>
                                <img src="{{ asset('assets/images/student_support_icon.svg') }}" alt="student support icon">
                            </div>
                            <!--card title-->
                            <div class="card-title mt-3">
                                <h6><strong>Student Support Service</strong></h6>
                            </div>
                            <!--card text-->
                            <div class="card-text">
                                <p class="fg_light_grey-s">In publishing and graphic design, Lorem ipsum</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Student Support Service card End-->
            </div>
        </section>
        {{-- Brag about ourself - END --}}

        <!-- Show course Section - STRAT -->
        <section class="background_circle_image-s py-5">
            <div class="row mt-5">
                <div class="col-12">
                    <!--Title-->
                    <h1 class="ml-3 fs_30px_on_small_Screen-s">Our Courses</h1>
                    <!--intro-->
                    <p class="fg_light_grey-s text-wrap text-break">In publishing and graphic design, Lorem ipsum is a placeholder text commonly used to demonstrate</p>
                </div>
            </div>

            <div class="row">
                <!--show courses carousal - START -->
                @php
                    $courses = getAllApprovedCourses();
                @endphp
                @include('partials/courses_listing', ['courses' => $courses])
                <!--show courses carousal - END -->
            </div>

            <!--Course button-->
            {{-- <div class="row justify-content-center mt-4">
                <div class="col-md-3 col-5 ">
                    <a href="{{ route('ourCourses') }}" class="btn text-white bg_green_gradient-s rounded-pill border-0 w-100 py-2">Courses</a>
                </div>
            </div> --}}
        </section>
        <!-- Show course Section - END -->

        {{-- Asesome teachers - START --}}
        <section class="py-5">
            <div class="row justify-content-center">
                <div class="col-5 text-center">
                    <h1  class="fs_30px_on_small_Screen-s">Our Awesome Teachers</h1>
                </div>
                <div class="col-8 text-center">
                    <p class="fg_light_grey-s">In publishing and graphic design, Lorem ipsum is a placeholder text commonly used to demonstrate</p>
                </div>
            </div>

            @php
                // dd(getAllApprovedTeachers());
            @endphp
            <div class="row mt-4">
                @include('partials.teachers', [])
            </div>
        </section>
        {{-- Asesome teachers - END --}}

        {{-- News Section - START --}}
        <section style='display:none;'>
            <div class="row justify-content-center">
                <div class="col-5 text-center">
                    <h1  class="fs_30px_on_small_Screen-s">Recent News</h1>
                </div>
                <div class="col-8 text-center">
                    <p class="fg_light_grey-s">In publishing and graphic design, Lorem ipsum is a placeholder text commonly used to demonstrate</p>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card border-0" >
                        <div class="position-relative">
                            <img class="card-img-top br_19px-s img_h_240px-s w-100" src="assets/landing_page/teacher1.jpg" alt="Card image cap">
                            <div class="text-center text-white rounded-pill px-2 position-absolute top_95_left_5-s px-4 bg_parrot_green-s">English</div>
                        </div>
                        <div class="card-body px-3 pt-4">
                            <h6 class="fg_stone_color-s ">17/01/2021</h6>
                            <h5 class="card-title mb-1"><strong>Campus CLean WorkShop</strong></h5>
                            <small class="fg_light_grey-s">In publishing and graphic design, Lorem ipsum is a placeholder text commonly </small>
                        </div>
                      </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card border-0" >
                        <div class="position-relative">
                            <img class="card-img-top br_19px-s img_h_240px-s w-100" src="assets/landing_page/teacher1.jpg" alt="Card image cap">
                            <div class="text-center text-white rounded-pill px-2 position-absolute top_95_left_5-s px-4 bg_parrot_green-s">English</div>
                        </div>
                        <div class="card-body px-3 pt-4">
                            <h6 class="fg_stone_color-s ">17/01/2021</h6>
                            <h5 class="card-title mb-1"><strong>Campus CLean WorkShop</strong></h5>
                            <small class="fg_light_grey-s">In publishing and graphic design, Lorem ipsum is a placeholder text commonly </small>
                        </div>
                      </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card border-0" >
                        <div class="position-relative">
                            <img class="card-img-top br_19px-s img_h_240px-s w-100" src="assets/landing_page/teacher1.jpg" alt="Card image cap">
                            <div class="text-center text-white rounded-pill px-2 position-absolute top_95_left_5-s px-4 bg_parrot_green-s">English</div>
                        </div>
                        <div class="card-body px-3 pt-4">
                            <h6 class="fg_stone_color-s ">17/01/2021</h6>
                            <h5 class="card-title mb-1"><strong>Campus CLean WorkShop</strong></h5>
                            <small class="fg_light_grey-s">In publishing and graphic design, Lorem ipsum is a placeholder text commonly </small>
                        </div>
                      </div>
                </div>
            </div>
        </section>
        {{-- News Section - END --}}
@endsection
