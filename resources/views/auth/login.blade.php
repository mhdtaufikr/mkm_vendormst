<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Supplier & Customer Master</title>
    <link href="{{asset('assets/css/styles.css')}}" rel="stylesheet" />
    <link rel="icon" href="{{ asset('assets/img/mms.png') }}">
    <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.28.0/feather.min.js" crossorigin="anonymous"></script>

    <style>
        body {
            background-image: url("{{ asset('assets/img/vendorMaster.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Animation */
        @keyframes cardAnimation {
            0% {
                transform: translateY(-20px);
                opacity: 0;
            }

            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .card-animation {
            animation: cardAnimation 0.5s ease forwards;
        }

        /* Custom styles for the card */
        .custom-card {
            height: 650px;
            width: 100%; /* Full width inside the column */
            max-width: 400px; /* Maximum width for responsiveness */
            margin: auto; /* Center the card horizontally */
        }
    </style>
</head>

<body class="bg-dark">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main >
                <div class="container-xl px-4">
                    <div class="row justify-content-end">
                        <!-- Adjust column size and offset -->
                        <div class="col-lg-4 offset-lg-6 col-md-6 offset-md-3 d-flex justify-content-center">
                            <!-- Basic login form-->
                            <div class="card shadow-lg border-0 rounded-lg mt-5 card-animation custom-card">
                                <div class="card-body">
                                    <!--alert success -->
                                    @if (session('statusLogin'))
                                    <div class="alert alert-warning" role="alert">
                                        <strong>{{ session('statusLogin') }}</strong>
                                    </div>
                                    @elseif(session('statusLogout'))
                                    <div class="alert alert-success" role="alert">
                                        <strong>{{ session('statusLogout') }}</strong>
                                    </div>
                                    @endif

                                    <!--alert success -->
                                    <div class="text-center mb-4">
                                        <img class="img-fluid mb-4" src="{{ asset('assets/img/mms.png') }}" alt="" style="width: 50px; height: auto;">
                                        <h1 class="text-center font-weight-bold" > <strong>Supplier & Customer Master</strong></h1>
                                        <small  class="text-center mb-4">Digital Supplier & Customer Master</small>
                                    </div>



                                    <!-- Login form-->
                                    <form action="{{ url('auth/login') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <!-- Form Group (email address)-->
                                        <div class="mb-3 mt-2">
                                            <label class="small mb-1" for="inputEmailAddress">Username</label>
                                            <input class="form-control" id="inputEmailAddress" type="text" placeholder="Enter email address" name="email" />
                                        </div>
                                        <!-- Form Group (password)-->
                                        <div class="mb-3">
                                            <label class="small mb-1" for="inputPassword">Password</label>
                                            <input class="form-control" id="inputPassword" type="password" placeholder="Enter password" name="password" />
                                        </div>
                                        <!-- Form Group (login box)-->
                                        <div class="text-center mb-3">
                                            <button type="submit" class="btn btn-dark">Login</button>
                                        </div>
                                    </form>
                                    <hr>
                                    <div class="text-center mb-3">
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#requestAccessModal">Request Access</button>
                                    </div>
                                </div>
                                <div class="card-footer text-center justify-content-center">
                                    <div class="col-12 small">Copyright PT Mitsubishi Krama Yudha Motors and Manufacturing&copy; 2023</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <!-- Modal -->
    <div class="modal fade" id="requestAccessModal" tabindex="-1" aria-labelledby="requestAccessModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestAccessModalLabel">Request Access</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="requestAccessForm" action="{{ url('request/access') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="inputName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="inputName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="inputEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="inputEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="inputPurpose" class="form-label">Purpose</label>
                            <textarea class="form-control" id="inputPurpose" name="purpose" rows="3" required></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="{{asset('assets/js/scripts.js')}}"></script>
</body>

</html>
