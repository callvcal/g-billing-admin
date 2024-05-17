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
                            <h2 class="card-title">Account Deletion Request</h2>
                            <p class="card-text">
                                If you would like to request the deletion of your account, please contact us using the
                                information provided below. We will be happy to assist you. Also please contact with same
                                email/mobile used for authentication.
                            </p>
                            <div class="contact-details">
                                <p><strong>Eatplan8 Pvt. Ltd.</strong></p>
                                <p><strong>Email:</strong> <a href="mailto:tech@eatplan8.com">tech@callvcal.com</a></p>
                                <p><strong>Mobile Number:</strong> <a href="tel:+917033879015">+917033879015</a></p>
                            </div>
                        </div>
                    </div>
                </div>


            </div> <!-- container-fluid -->
        </div>
        

    </div>
@endsection
