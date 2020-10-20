<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap.min.css') }}">

        <style type="text/css">
            .ml4 {
              position: relative;
              font-weight: 900;
              font-size: 4.5em;
            }
            .ml4 .letters {
              position: absolute;
              margin: auto;
              left: 0;
              top: 0.3em;
              right: 0;
              opacity: 0; 
            }

            #box-1{
                width: 150%;
                height: 400px;
                background: blue;
                position: absolute;
                transform: rotate(45deg);
            }

              #box-2{
                width: 150%;
                height: 400px;
                background: red;
                position: absolute;
                bottom: 0px;
                left:-10%;
                transform: rotate(-5deg);
            }
            body {
                width: 100vw;
                height: 100vh;
                overflow: hidden;
            }

            .flip-scale-up-hor {
                -webkit-animation: shadow-pop-tr 0.3s cubic-bezier(0.470, 0.000, 0.745, 0.715) both;
                        animation: shadow-pop-tr 0.3s cubic-bezier(0.470, 0.000, 0.745, 0.715) both;
            }


            .flip-scale-up-hor-2 {
                -webkit-animation: shadow-pop-tr-2 0.3s cubic-bezier(0.470, 0.000, 0.745, 0.715) both;
                        animation: shadow-pop-tr-2 0.3s cubic-bezier(0.470, 0.000, 0.745, 0.715) both;
            }
                        @-webkit-keyframes shadow-pop-tr {
              0% {
                -webkit-box-shadow: 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e;
                        box-shadow: 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e;
                -webkit-transform: translateX(0) translateY(0) rotate(-5deg);
                        transform: translateX(0) translateY(0) rotate(-5deg);
              }
              100% {
                -webkit-box-shadow: 1px -1px #3e3e3e, 2px -2px #3e3e3e, 3px -3px #3e3e3e, 4px -4px #3e3e3e, 5px -5px #3e3e3e, 6px -6px #3e3e3e, 7px -7px #3e3e3e, 8px -8px #3e3e3e;
                        box-shadow: 1px -1px #3e3e3e, 2px -2px #3e3e3e, 3px -3px #3e3e3e, 4px -4px #3e3e3e, 5px -5px #3e3e3e, 6px -6px #3e3e3e, 7px -7px #3e3e3e, 8px -8px #3e3e3e;
                -webkit-transform: translateX(-8px) translateY(8px) rotate(-5deg);
                        transform: translateX(-8px) translateY(8px) rotate(-5deg);
              }
            }
            @keyframes shadow-pop-tr {
              0% {
                -webkit-box-shadow: 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e;
                        box-shadow: 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e;
                -webkit-transform: translateX(0) translateY(0) rotate(-5deg);
                        transform: translateX(0) translateY(0) rotate(-5deg);
              }
              100% {
                -webkit-box-shadow: 1px -1px #3e3e3e, 2px -2px #3e3e3e, 3px -3px #3e3e3e, 4px -4px #3e3e3e, 5px -5px #3e3e3e, 6px -6px #3e3e3e, 7px -7px #3e3e3e, 8px -8px #3e3e3e;
                        box-shadow: 1px -1px #3e3e3e, 2px -2px #3e3e3e, 3px -3px #3e3e3e, 4px -4px #3e3e3e, 5px -5px #3e3e3e, 6px -6px #3e3e3e, 7px -7px #3e3e3e, 8px -8px #3e3e3e;
                -webkit-transform: translateX(-8px) translateY(8px) rotate(-5deg);
                        transform: translateX(-8px) translateY(8px) rotate(-5deg);
              }
            }


             @-webkit-keyframes shadow-pop-tr-2 {
              0% {
                -webkit-box-shadow: 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e;
                        box-shadow: 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e;
                -webkit-transform: translateX(0) translateY(0) rotate(45deg);
                        transform: translateX(0) translateY(0) rotate(45deg);
              }
              100% {
                -webkit-box-shadow: 1px -1px #3e3e3e, 2px -2px #3e3e3e, 3px -3px #3e3e3e, 4px -4px #3e3e3e, 5px 45px #3e3e3e, 6px -6px #3e3e3e, 7px -7px #3e3e3e, 8px -8px #3e3e3e;
                        box-shadow: 1px -1px #3e3e3e, 2px -2px #3e3e3e, 3px -3px #3e3e3e, 4px -4px #3e3e3e, 5px 45px #3e3e3e, 6px -6px #3e3e3e, 7px -7px #3e3e3e, 8px -8px #3e3e3e;
                -webkit-transform: translateX(-8px) translateY(8px) rotate(45deg);
                        transform: translateX(-8px) translateY(8px) rotate(45deg);
              }
            }
            @keyframes shadow-pop-tr-2 {
              0% {
                -webkit-box-shadow: 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e;
                        box-shadow: 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e, 0 0 #3e3e3e;
                -webkit-transform: translateX(0) translateY(0) rotate(45deg);
                        transform: translateX(0) translateY(0) rotate(45deg);
              }
              100% {
                -webkit-box-shadow: 1px -1px #3e3e3e, 2px -2px #3e3e3e, 3px -3px #3e3e3e, 4px -4px #3e3e3e, 5px 45px #3e3e3e, 6px -6px #3e3e3e, 7px -7px #3e3e3e, 8px -8px #3e3e3e;
                        box-shadow: 1px -1px #3e3e3e, 2px -2px #3e3e3e, 3px -3px #3e3e3e, 4px -4px #3e3e3e, 5px 45px #3e3e3e, 6px -6px #3e3e3e, 7px -7px #3e3e3e, 8px -8px #3e3e3e;
                -webkit-transform: translateX(-8px) translateY(8px) rotate(45deg);
                        transform: translateX(-8px) translateY(8px) rotate(45deg);
              }
            }


        </style>
       
    </head>
    <body>
    <div id="box-1" class="flip-scale-up-hor-2"></div>
    <div id="box-2" class="flip-scale-up-hor"></div>

       <div class="container">
            <div class="flex-center position-ref full-height">
            <h1 class="text-capitalize"><span><img src="{{asset('robot.png')}}" style="width: 50px;" ></span><b> APLIKSI SUPPORT DATA</b></h1>
            

           <h1 class="ml4">
              <span class="letters letters-1">Ready</span>
              <span class="letters letters-2">Set</span>
              <span class="letters letters-3">Go!</span>
            </h1>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/2.0.2/anime.min.js"></script>
        </div>
       </div>
    </body>

    <script type="text/javascript">
        var ml4 = {};
            ml4.opacityIn = [0,1];
            ml4.scaleIn = [0.2, 1];
            ml4.scaleOut = 3;
            ml4.durationIn = 800;
            ml4.durationOut = 600;
            ml4.delay = 500;

            anime.timeline({loop: true})
              .add({
                targets: '.ml4 .letters-1',
                opacity: ml4.opacityIn,
                scale: ml4.scaleIn,
                duration: ml4.durationIn
              }).add({
                targets: '.ml4 .letters-1',
                opacity: 0,
                scale: ml4.scaleOut,
                duration: ml4.durationOut,
                easing: "easeInExpo",
                delay: ml4.delay
              }).add({
                targets: '.ml4 .letters-2',
                opacity: ml4.opacityIn,
                scale: ml4.scaleIn,
                duration: ml4.durationIn
              }).add({
                targets: '.ml4 .letters-2',
                opacity: 0,
                scale: ml4.scaleOut,
                duration: ml4.durationOut,
                easing: "easeInExpo",
                delay: ml4.delay
              }).add({
                targets: '.ml4 .letters-3',
                opacity: ml4.opacityIn,
                scale: ml4.scaleIn,
                duration: ml4.durationIn
              }).add({
                targets: '.ml4 .letters-3',
                opacity: 0,
                scale: ml4.scaleOut,
                duration: ml4.durationOut,
                easing: "easeInExpo",
                delay: ml4.delay
              }).add({
                targets: '.ml4',
                opacity: 0,
                duration: 500,
                delay: 500
              });

              // setTimeout(function(){
              //   window.location.href='{{route('login')}}';
              // },5000);
    </script>
</html>
