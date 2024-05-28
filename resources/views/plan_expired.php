@extends('layouts.app')

@section('content')
    <div id="layout-wrapper">



        <div class="main-content">
            @yield('main')
        </div>






        <div class="page-content">
            <div class="container-fluid">

                <div class="container mt-5">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title">Sorry! You don not have to access Admin Panel</h2>
                            <p class="card-text">
                                Only Premium Users can access Admin Panel. <a href="/"> See Our Plans</a>
                            </p>
                            <div class="contact-details">
                                <p><strong>Callvcal Technology Pvt. Ltd.</strong></p>
                                <p><strong>Email:</strong> <a href="mailto:tech@callvcal.com">tech@callvcal.com</a></p>
                                <p><strong>Mobile Number:</strong> <a href="tel:+917033879015">+917033879015</a></p>
                            </div>
                        </div>
                    </div>
                </div>


            </div> <!-- container-fluid -->
        </div>
        

    </div>
@endsection
