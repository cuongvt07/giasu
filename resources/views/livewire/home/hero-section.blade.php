<div class="relative bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
            <svg class="hidden lg:block absolute right-0 inset-y-0 h-full w-48 text-white transform translate-x-1/2" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                <polygon points="50,0 100,0 50,100 0,100" />
            </svg>

            <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 lg:mt-16 lg:px-8 xl:mt-20">
                <div class="sm:text-center lg:text-left">
                    <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                        <span class="block xl:inline">Kết nối gia sư</span>
                        <span class="block text-indigo-600 xl:inline">Uy tín và hiệu quả</span>
                    </h1>
                    <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                        Với sự hỗ trợ của AI, chúng tôi sẽ giúp bạn tìm được gia sư phù hợp nhất với nhu cầu học tập của bạn.
                    </p>
                    <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                        <div class="rounded-md shadow">
                            <a href="{{ route('ai-advisor') }}" class="w-full flex items-center justify-center px-4 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-4">
                                Chat cùng AI
                            </a>
                        </div>
                        <div class="mt-3 sm:mt-0 sm:ml-3">
                            <a href="{{ route('tutors.index') }}" class="w-full flex items-center justify-center px-4 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 md:py-4 md:text-lg md:px-4">
                                Kết nối gia sư nhanh
                            </a>
                        </div>
                        <div class="mt-3 sm:mt-0 sm:ml-3">
                            <a href="#"
                                @auth @if(auth()->user()->isTutor()) onclick="return false;" @else @click.prevent="openPost = true" @endif @endauth
                                @guest onclick="return false;" @endguest
                                class="w-full flex items-center justify-center px-4 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 md:py-4 md:text-lg md:px-4 @guest opacity-50 cursor-not-allowed @endguest">
                                Đăng tin tuyển gia sư
                            </a>
                        </div>
                    </div>
                    @guest
                    <div class="mt-3 text-center lg:text-left">
                        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-indigo-600">
                            Đã có tài khoản? Đăng nhập
                        </a>
                    </div>
                    @endguest
                </div>
            </main>
        </div>
    </div>
    <div id="button-contact-vr" class="">
        <div id="gom-all-in-one"><!-- v3 -->
            <!-- zalo -->
            <div id="zalo-vr" class="button-contact">
                <div class="phone-vr">
                    <div class="phone-vr-circle-fill"></div>
                    <div class="phone-vr-img-circle">
                        <a target="_blank" href="https://zalo.me/0823384410">
                            <img alt="Zalo" src="https://thaibinhweb.net/wp-content/plugins/button-contact-vr/legacy/img/zalo.png">
                        </a>
                    </div>
                </div>
            </div>
            <!-- end zalo -->


            <!-- Phone -->
            <div id="phone-vr" class="button-contact">
                <div class="phone-vr">
                    <div class="phone-vr-circle-fill"></div>
                    <div class="phone-vr-img-circle">
                        <a href="tel:0823384410">
                            <img alt="Phone" src="https://thaibinhweb.net/wp-content/plugins/button-contact-vr/legacy/img/phone.png">
                        </a>
                    </div>
                </div>
            </div>
            <div class="phone-bar phone-bar-n">
                <a href="tel:0823384410">
                    <span class="text-phone">0823384410 </span>
                </a>
            </div>
            <!-- end phone -->

        </div><!-- end v3 class gom-all-in-one -->


    </div>
    <style>
        #button-contact-vr {
            position: fixed;
            bottom: 0;
            z-index: 99999
        }

        #button-contact-vr .button-contact {
            position: relative;
            margin-top: -5px
        }

        #button-contact-vr .button-contact .phone-vr {
            position: relative;
            visibility: visible;
            background-color: transparent;
            width: 90px;
            height: 90px;
            cursor: pointer;
            z-index: 11;
            -webkit-backface-visibility: hidden;
            -webkit-transform: translateZ(0);
            transition: visibility .5s;
            left: 0;
            bottom: 0;
            display: block
        }

        .phone-vr-circle-fill {
            width: 65px;
            height: 65px;
            top: 12px;
            left: 12px;
            position: absolute;
            box-shadow: 0 0 0 0 #c31d1d;
            background-color: rgba(230, 8, 8, 0.7);
            border-radius: 50%;
            border: 2px solid transparent;
            -webkit-animation: phone-vr-circle-fill 2.3s infinite ease-in-out;
            animation: phone-vr-circle-fill 2.3s infinite ease-in-out;
            transition: all .5s;
            -webkit-transform-origin: 50% 50%;
            -ms-transform-origin: 50% 50%;
            transform-origin: 50% 50%;
            -webkit-animuiion: zoom 1.3s infinite;
            animation: zoom 1.3s infinite
        }

        .phone-vr-img-circle {
            background-color: #e60808;
            width: 40px;
            height: 40px;
            line-height: 40px;
            top: 25px;
            left: 25px;
            position: absolute;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            justify-content: center;
            -webkit-animation: phonering-alo-circle-img-anim 1s infinite ease-in-out;
            animation: phone-vr-circle-fill 1s infinite ease-in-out
        }

        .phone-vr-img-circle a {
            display: block;
            line-height: 37px
        }

        .phone-vr-img-circle img {
            max-height: 25px;
            max-width: 27px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            -moz-transform: translate(-50%, -50%);
            -webkit-transform: translate(-50%, -50%);
            -o-transform: translate(-50%, -50%)
        }

        #instagram-vr .phone-vr-circle-fill {
            background: rgb(17, 143, 253);
            background: linear-gradient(160deg, rgba(17, 143, 253, 1) 20%, rgba(188, 60, 218, 1) 50%, rgba(253, 223, 5, 1) 80%);
            background-size: contain;
            box-shadow: 0 0 0 0 #c840c9;
            background-color: rgb(79 103 254);
            border: 0
        }

        #instagram-vr .phone-vr-img-circle {
            background: transparent
        }

        #telegram-vr .phone-vr-circle-fill {
            box-shadow: 0 0 0 0 #2c9fd8;
            background-color: rgb(44 159 216 / 74%)
        }

        #telegram-vr .phone-vr-img-circle {
            background: #2c9fd8
        }

        @-webkit-keyframes phone-vr-circle-fill {
            0% {
                -webkit-transform: rotate(0) scale(1) skew(1deg)
            }

            10% {
                -webkit-transform: rotate(-25deg) scale(1) skew(1deg)
            }

            20% {
                -webkit-transform: rotate(25deg) scale(1) skew(1deg)
            }

            30% {
                -webkit-transform: rotate(-25deg) scale(1) skew(1deg)
            }

            40% {
                -webkit-transform: rotate(25deg) scale(1) skew(1deg)
            }

            50% {
                -webkit-transform: rotate(0) scale(1) skew(1deg)
            }

            100% {
                -webkit-transform: rotate(0) scale(1) skew(1deg)
            }
        }

        @-webkit-keyframes zoom {
            0% {
                transform: scale(.9)
            }

            70% {
                transform: scale(1);
                box-shadow: 0 0 0 15px transparent
            }

            100% {
                transform: scale(.9);
                box-shadow: 0 0 0 0 transparent
            }
        }

        @keyframes zoom {
            0% {
                transform: scale(.9)
            }

            70% {
                transform: scale(1);
                box-shadow: 0 0 0 15px transparent
            }

            100% {
                transform: scale(.9);
                box-shadow: 0 0 0 0 transparent
            }
        }

        .phone-bar a {
            position: absolute;
            margin-top: -65px;
            left: 30px;
            z-index: -1;
            color: #fff;
            font-size: 16px;
            padding: 7px 15px 7px 50px;
            border-radius: 100px;
            white-space: nowrap
        }

        .phone-bar a:hover {
            opacity: 0.8;
            color: #fff
        }

        @media(max-width: 736px) {
            .phone-bar {
                display: none
            }
        }

        #zalo-vr .phone-vr-circle-fill {
            box-shadow: 0 0 0 0 #2196F3;
            background-color: rgba(33, 150, 243, 0.7)
        }

        #zalo-vr .phone-vr-img-circle {
            background-color: #2196F3
        }

        #viber-vr .phone-vr-circle-fill {
            box-shadow: 0 0 0 0 #714497;
            background-color: rgba(113, 68, 151, 0.8)
        }

        #viber-vr .phone-vr-img-circle {
            background-color: #714497
        }

        #contact-vr .phone-vr-circle-fill {
            box-shadow: 0 0 0 0 #2196F3;
            background-color: rgba(33, 150, 243, 0.7)
        }

        #contact-vr .phone-vr-img-circle {
            background-color: #2196F3
        }
        div#whatsapp-vr .phone-vr .phone-vr-circle-fill {
    box-shadow: 0 0 0 0 #1fd744;
    background-color: rgb(35 217 72 / 70%)
}

div#whatsapp-vr .phone-vr .phone-vr-img-circle {
    background: #1cd741
}

div#whatsapp-vr .phone-vr .phone-vr-img-circle img {
    max-width: 100%;
    max-height: 100%;
    border-radius: 50%
}

#fanpage-vr img {
    max-width: 35px;
    max-height: 35px
}

#fanpage-vr .phone-vr-img-circle {
    background-color: #1877f2
}

#fanpage-vr .phone-vr-circle-fill {
    box-shadow: 0 0 0 0 rgb(24 119 242 / 65%);
    background-color: rgb(24 119 242 / 70%)
}

#gom-all-in-one .button-contact {
    transition: 1.6s all;
    -moz-transition: 1.6s all;
    -webkit-transition: 1.6s all
}

#button-contact-vr.active #gom-all-in-one .button-contact {
    margin-left: -100%
}
    </style>
    <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
        <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full" src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1471&q=80" alt="Students studying">
    </div>
</div>