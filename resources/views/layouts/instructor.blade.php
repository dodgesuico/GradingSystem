<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield("title", "Grading System")</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>

    @if (!Auth::check())
    <script>
        window.location.href = "{{ route('login') }}";
    </script>
    @else
    @if (Auth::user()->role !== 'instructor')
    <script>
        window.location.href = "{{ route('index') }}";
    </script>
    @endif
    @endif

    <div class="container">
        <div class="nav-bar">
            <div class="teacher-nav-contents">
                <div class="nav-header">
                    <img src="system_images/icon.png" alt="">
                    <label class="gradient-text">CKCM Grading <em>v.1</em></label>
                </div>

                <!-- Profile Section with Logout Dropdown -->
                <div class="nav-profile">
                    <img src="system_images/profile.png" alt="">
                    <div class="profile">
                        <label for="">{{ Auth::user()->name ?? 'Instructor Name' }}</label>
                        <p>ID#: {{ Auth::user()->id ?? '0000' }}</p>
                    </div>
                    <i class="fa-solid fa-chevron-down"></i>

                    <!-- Hidden Logout Dropdown -->
                    <div class="logout-container">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="logout-btn"><i class="fa-solid fa-arrow-right-from-bracket fa-flip-horizontal"></i> Logout</button>
                        </form>
                    </div>
                </div>

                <div class="nav-links">
                    <label for="" style="margin-top:20px;">DASHBOARD</label>

                    <a href="{{ route('instructor') }}" class="{{ Request::is('instructor_dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-house"></i> Home
                    </a>

                    <label for="" style="margin-top:20px;">OPERATION</label>

                    <a href="{{ route('classes') }}" class="{{ Request::is('instructor_classes') ? 'active' : '' }}">
                        <i class="fa-solid fa-clipboard"></i> Classes
                    </a>

                </div>



            </div>

            <div class="teacher-nav-footer">
                <h4>POWERED BY CKCM TECH</h4>
                <p>&copy; {{ date('Y') }} CKCM Technologies, LLC</p>
                <p>All Rights Reserved</p>
            </div>
        </div>

        <div class="main-content">
            <div class="content-header">
                <i id="fullscreen-icon" class="fa-solid fa-expand" title="Expand"></i>
            </div>

            <div class="content">
                @yield("content")
            </div>
        </div>
    </div>
















    <!-- scripts -->
    <!-- for expand -->
    <script>
        const icon = document.getElementById("fullscreen-icon");

        icon.addEventListener("click", () => {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen(); // Enter fullscreen
                icon.classList.replace("fa-expand", "fa-compress"); // Change icon
                icon.setAttribute("title", "Compress"); // Change tooltip text
            } else {
                document.exitFullscreen(); // Exit fullscreen
                icon.classList.replace("fa-compress", "fa-expand"); // Change icon back
                icon.setAttribute("title", "Expand"); // Change tooltip text back
            }
        });
    </script>


    <!-- for logout -->

    <script>
        $(document).ready(function() {
            $(".nav-profile").click(function(event) {
                event.stopPropagation();
                $(".logout-container").toggleClass("show");
            });

            $(document).click(function(event) {
                if (!$(event.target).closest(".nav-profile").length) {
                    $(".logout-container").removeClass("show");
                }
            });
        });
    </script>







    <style>
        /* Navigation profile styles */



        /* Logout dropdown */
        .logout-container {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: var(--ckcm-color2);
            width: 90%;
            text-align: center;
            padding: 10px;
            opacity: 0;
            visibility: hidden;
            transform-origin: top center;
            transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
            border-bottom: 1px solid var(--color7);
        }

        .logout-container.show {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }

        .logout-btn {
            background: var(--ckcm-color1);
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background: var(--color9);
        }
    </style>
















    <style>
        .container {
            width: 100%;
            height: 100%;
            background-color: var(--ckcm-color1);
            display: flex;
            flex-direction: row;
        }

        .nav-bar {
            width: 280px;
            height: 100%;
            background-color: var(--ckcm-color2);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-right: 1px solid var(--color7);
        }

        .teacher-nav-footer {
            text-align: left;
            padding: 10px;
            color: var(--color1);
            line-height: 1.2;
            border-top: var(--color6) 1px solid;
        }

        .teacher-nav-footer h4 {
            margin-bottom: 2px;
            font-size: 1.1rem;
        }

        .teacher-nav-footer p {
            color: var(--color5);
        }

        .teacher-nav-contents {
            display: flex;
            flex-direction: column;

        }

        .nav-header {
            display: flex;
            flex-direction: row;
            padding: 20px;
            gap: 10px;
            justify-content: center;
            align-items: center;
        }

        .nav-header img {
            width: 30px;

        }

        .gradient-text {
            font-weight: bold;
            /* Makes the text bold */
            font-size: 1.6rem;
            /* Adjust size as needed */
            background: linear-gradient(90deg, var(--ckcm-color4), var(--ckcm-color3), var(--ckcm-color3));
            /* Red → Green → Blue */
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        .gradient-text em {
            -webkit-text-fill-color: var(--color4);
            font-weight: 100;
            font-size: 1.1rem;
        }




        .nav-profile {
            cursor: pointer;
            position: relative;
            gap: 10px;
            padding: 10px;
            transition: background 0.3s ease-in-out;
            display: flex;
            flex-direction: row;
            padding: 20px;
            gap: 10px;
            justify-content: left;
            align-items: center;
            background-color: var(--ckcm-color1);
            border-left: 0;
            border-right: 0;
        }

        .nav-profile:hover {
            background: var(--color9);
        }

        .nav-profile img {
            width: 30px;
        }



        .nav-profile i {
            color: var(--color1);
            font-size: 1.2rem;
        }

        .profile label {
            color: var(--color1);
            font-size: 1.2rem;
            font-weight: bold;
            text-transform: uppercase;
        }

        .profile p {
            color: var(--color6);
            font-size: 1.2rem;

        }

        .nav-links label {
            color: var(--color6);
            font-size: 1.1rem;
            font-weight: bold;
            padding: 10px;
        }

        .nav-links {
            display: flex;
            flex-direction: column;

            padding: 10px;

        }

        .nav-links a {
            font-size: 1.3rem;
            border-radius: 5px;
            color: var(--color4);
            text-decoration: none;
            padding: 10px;
        }

        .nav-links a:hover {
            color: var(--color1);
            background-color: var(--ckcm-color1);
        }

        .nav-links a.active {
            color: var(--color1);
            background-color: var(--ckcm-color1);
        }

        .nav-links i {
            margin-right: 5px;

        }




        /* main content */

        .main-content {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .content-header {
            display: flex;
            padding: 15px;
            background-color: var(--ckcm-color1);
            border-bottom: 1px solid var(--color8);
            justify-content: right;
        }

        .content-header i {
            font-size: 1.3rem;
            color: var(--color6);
            cursor: pointer;
            transition: transform 0.2s ease, color 0.2s ease;

        }



        .content-header i:hover {
            transform: scale(1.1);
            color: #3498db;
            /* Change color on hover */
        }







        /* dynamic content */
        .content {

            overflow-y: scroll;


        }
    </style>


</body>

</html>