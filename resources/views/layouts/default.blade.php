<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Grading System')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('system_images/icon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Flex:opsz,wght@8..144,100..1000&display=swap"
        rel="stylesheet">

</head>

<body>



    <div class="container">
        <div class="nav-bar" id="navBar">
            <div class="main-nav-contents">
                <div class="nav-header">
                    <img src="{{ asset('system_images/icon.png') }}" alt="">
                    <label class="gradient-text">CKCM Grading <em>v.1</em></label>
                </div>

                <!-- Profile Section with Logout Dropdown -->
                <div class="nav-profile">

                    <div style="display: flex; gap:10px;">
                        <img src="{{ asset('system_images/user.png') }}" alt="">

                        <div class="profile">
                            <label for="">{{ Auth::user()->name ?? 'Registrar Name' }}</label>
                            <p>ID#: {{ Auth::user()->id ?? '0000' }}</p>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-down"></i>

                    <!-- Hidden Logout Dropdown -->
                    <div class="logout-container">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="logout-btn">
                                <i class="fa-solid fa-arrow-right-from-bracket fa-flip-horizontal"></i> Logout</button>
                        </form>
                    </div>
                </div>

                <div class="nav-links">
                    <label for="">DASHBOARD</label>
                    <a href="{{ route('index') }}" class="{{ Request::is('/') ? 'active' : '' }}">
                        <i class="fa-solid fa-house"></i><span>Home</span></a>
                    <a href=""><i class="fa-solid fa-star"></i>
                        <span>My Grades</span></a>


                    <label for="" style="margin-top:10px;">OPERATION</label>
                    <a href=""><i class="fa-regular fa-clipboard"></i>
                        <span>My Class</span></a>
                    <a href="{{ route('registrar_classes') }}"
                        class="{{ Request::is('registrar_classes') ? 'active' : '' }}">
                        <i class="fa-solid fa-clipboard"></i> <span>All Class</span>
                    </a>

                    <a href="{{ route('show.grades') }}" class="{{ Request::is('allgrades') ? 'active' : '' }}"><i
                            class="fa-solid fa-box-archive"></i>
                        <span>All Grades</span></a>

                    <a href="{{ route('user.show') }}" class="{{ Request::is('users') ? 'active' : '' }}"><i
                            class="fa-solid fa-users"></i>
                        <span>Users</span></a>


                    <label for="" style="margin-top:10px;">SETTINGS</label>

                    <a href=""><i class="fa-solid fa-key"></i>
                        <span>Admin</span></a>
                </div>
            </div>

            <div class="main-nav-footer">
                <h4 class="footer-title">POWERED BY CKCM TECH</h4>
                <p>&copy; {{ date('Y') }} CKCM Technologies, LLC</p>
                <p>All Rights Reserved</p>
            </div>
        </div>

        <div class="main-content">
            <div class="content-header">
                <i class="fa-solid fa-bars" id="menuToggle"></i>
                <i id="fullscreen-icon" class="fa-solid fa-expand" title="Expand"></i>
            </div>

            <div class="content">
                @yield('content')
            </div>
        </div>
    </div>










    <script>
        $(document).ready(function() {
            // Handle navbar state from localStorage
            if (localStorage.getItem('navMinimized') === 'true') {
                $('#navBar').addClass('minimized');
            }

            $('#menuToggle').click(function() {
                $('#navBar').toggleClass('minimized');
                localStorage.setItem('navMinimized', $('#navBar').hasClass('minimized'));
            });

            // Handle fullscreen mode with localStorage
            const icon = document.getElementById("fullscreen-icon");

            if (localStorage.getItem('fullscreen') === 'true') {
                document.documentElement.requestFullscreen();
                icon.classList.replace("fa-expand", "fa-compress");
                icon.setAttribute("title", "Compress");
            }

            icon.addEventListener("click", () => {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen();
                    icon.classList.replace("fa-expand", "fa-compress");
                    icon.setAttribute("title", "Compress");
                    localStorage.setItem('fullscreen', 'true');
                } else {
                    document.exitFullscreen();
                    icon.classList.replace("fa-compress", "fa-expand");
                    icon.setAttribute("title", "Expand");
                    localStorage.setItem('fullscreen', 'false');
                }
            });
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
            overflow-y: auto;
            /* Enables scrolling when content overflows */
            overflow-x: hidden;
            /* Hides horizontal scroll */
            transition: width 0.3s ease;

        }

        .main-nav-footer {
            text-align: left;
            padding: 10px;
            color: var(--color1);
            line-height: 1.2;
            border-top: var(--color6) 1px solid;
        }

        .main-nav-footer h4 {
            margin-bottom: 2px;
            font-size: 1.1rem;
            color: var(--color3);
        }

        .main-nav-footer p {
            color: var(--color5);
        }

        .main-nav-contents {
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

        .nav-profile {
            cursor: pointer;
            position: relative;
            gap: 10px;
            margin: 0 10px;
            transition: background 0.3s ease-in-out;
            display: flex;
            flex-direction: row;
            padding: 10px;
            gap: 10px;
            justify-content: space-between;
            align-items: center;
            background-color: var(--ckcm-color2);
            border-left: 0;
            border-right: 0;
            border-radius: 5px;
            /* border: 1px solid var(--color6); */
        }

        .nav-profile:hover {
            background-color: var(--hover-background-color);
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
            text-transform: capitalize;
        }

        .profile p {
            color: var(--color6);
            font-size: 1.2rem;

        }

        .nav-links label {
            color: var(--color6);
            font-size: 1.1rem;
            font-weight: bold;
            padding: 10px 5px;
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
            margin-bottom: 5px;
        }

        .nav-links a:hover {
            color: var(--color1);
            background-color: var(--hover-background-color);
        }

        .nav-links a.active {
            color: var(--color1);
            background-color: var(--ckcm-color1);
            /* border: 1px solid var(--color6); */
        }

        .nav-links i {
            margin-right: 5px;

        }


        /* main content */

        .main-content {
            display: flex;
            flex-direction: column;
            width: 100%;
            overflow-x: auto
        }

        .content-header {
            display: flex;
            padding: 12px;
            background-color: var(--ckcm-color1);
            border-bottom: 1px solid var(--color8);
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .content-header i {
            font-size: 1.5rem;
            color: var(--color6);
            cursor: pointer;
            transition: transform 0.2s ease, color 0.2s ease;

        }

        .content-header i:hover {
            color: #3498db;
            /* Change color on hover */
        }


        /* dynamic content */
        .content {
            overflow-y: scroll;
            overflow-x: auto;
        }
    </style>




    <style>
        /* Navigation profile styles */
        /* Logout dropdown */
        .logout-container {
            position: absolute;
            top: 110%;
            left: 50%;
            transform: translateX(-50%);
            background: var(--ckcm-color1);
            width: 100%;
            text-align: center;

            border-radius: 5px;
            opacity: 0;
            visibility: hidden;
            transform-origin: top center;
            transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
            border: 1px solid var(--color7);
        }

        .logout-container.show {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }

        .logout-btn {
            background: var(--ckcm-color1);
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background: var(--hover-background-color);
        }
    </style>






    {{-- minimized style --}}
    <style>
        .nav-bar.minimized {
            width: 60px;
            transition: ease-in-out 0.3s;
        }


        .nav-bar.minimized .nav-links a span,
        .nav-bar.minimized .nav-header label,
        .nav-bar.minimized .profile,
        .nav-bar.minimized .nav-profile i,
        .nav-bar.minimized .nav-links label,
        .nav-bar.minimized .main-nav-footer p {
            display: none;
        }



        .nav-bar.minimized .nav-links {
            gap: 10px;
        }

        .nav-bar.minimized .nav-links a {
            text-align: center;
            padding: 10px;
        }

        .nav-bar.minimized .nav-header {
            padding: 20px 10px;
        }

        .nav-bar.minimized .main-nav-footer h4 {
            font-size: .8rem;
        }

        .nav-bar.minimized img {
            width: 25px;
        }

        .nav-bar.minimized .logout-btn {
            font-size: 1rem;
            margin: 0;
            padding: 5px;
        }



        .nav-bar.minimized .nav-profile {
            margin: 0;
            justify-content: center;
        }
    </style>





    {{-- for mobile --}}
    <style>
        @media (max-width: 480px) {


            .content-header i:nth-child(2) {
                display: none;
            }


            .nav-bar.minimized {
                width: 65px;
                display: flex;
            }


            .nav-bar.minimized .nav-links a span,
            .nav-bar.minimized .nav-header label,
            .nav-bar.minimized .profile,
            .nav-bar.minimized .nav-profile i,
            .nav-bar.minimized .nav-links label,
            .nav-bar.minimized .main-nav-footer p {
                display: none;
            }



            .nav-bar.minimized .nav-links {
                gap: 10px;
            }

            .nav-bar.minimized .nav-links a {
                text-align: center;
                padding: 10px;
            }

            .nav-bar.minimized .nav-header {
                padding: 20px 10px;
            }

            .nav-bar.minimized .main-nav-footer h4 {
                font-size: .6rem;
            }

            .nav-bar.minimized img {
                width: 25px;
            }

            .nav-bar.minimized .logout-btn {
                font-size: 1rem;
                margin: 0;
                padding: 5px;
            }

            .nav-bar.minimized .nav-profile {
                margin: 0;
                justify-content: center;
            }
        }
    </style>

    {{-- tablet --}}
    <style>
        @media (max-width: 768px) {


            .nav-bar {

                display: none;
            }

            .content-header i:nth-child(2) {
                display: none;
            }


            .nav-bar.minimized {
                width: 65px;
                display: flex;
            }


            .nav-bar.minimized .nav-links a span,
            .nav-bar.minimized .nav-header label,
            .nav-bar.minimized .profile,
            .nav-bar.minimized .nav-profile i,
            .nav-bar.minimized .nav-links label,
            .nav-bar.minimized .main-nav-footer p {
                display: none;
            }



            .nav-bar.minimized .nav-links {
                gap: 10px;
            }

            .nav-bar.minimized .nav-links a {
                text-align: center;
                padding: 10px;
            }

            .nav-bar.minimized .nav-header {
                padding: 20px 10px;
            }

            .nav-bar.minimized .main-nav-footer h4 {
                font-size: .6rem;
            }

            .nav-bar.minimized img {
                width: 25px;
            }

            .nav-bar.minimized .logout-btn {
                font-size: 1rem;
                margin: 0;
                padding: 5px;
            }

            .nav-bar.minimized .nav-profile {
                margin: 0;
                justify-content: center;
            }
        }
    </style>




    {{-- laptop --}}
    <style>
        @media (max-width: 1024px) {
            .nav-bar {
                display: none;
            }

            .nav-bar.minimized {
                width: 65px;
                display: flex;
            }


            .nav-bar.minimized .nav-links a span,
            .nav-bar.minimized .nav-header label,
            .nav-bar.minimized .profile,
            .nav-bar.minimized .nav-profile i,
            .nav-bar.minimized .nav-links label,
            .nav-bar.minimized .main-nav-footer p {
                display: none;
            }



            .nav-bar.minimized .nav-links {
                gap: 10px;
            }

            .nav-bar.minimized .nav-links a {
                text-align: center;
                padding: 10px;
            }

            .nav-bar.minimized .nav-header {
                padding: 20px 10px;
            }

            .nav-bar.minimized .main-nav-footer h4 {
                font-size: .8rem;
            }

            .nav-bar.minimized img {
                width: 25px;
            }

            .nav-bar.minimized .logout-btn {
                font-size: 1rem;
                margin: 0;
                padding: 5px;
            }



            .nav-bar.minimized .nav-profile {
                margin: 0;
                justify-content: center;
            }
        }
    </style>


</body>

</html>
