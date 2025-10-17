<html>

<head>
    <title>BotMan Widget</title>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
    <link href="{{ asset('packages/fontawesome/css/font-awesome.css') }}" type="text/css" rel="stylesheet" media="screen">
    <link href="{{ asset('css/application.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/assets/css/chat.min.css">
    <!-- <link rel="stylesheet" type="text/css" href="static/css/chat.min.css"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


    {{-- <link rel="stylesheet" type="text/css" href="static/css/styles_attachment.css"> --}}
    {{-- styles_attachment.css --}}
    <style>
        /* .view-attachment-left {
            z-index: 999999999999999999;
            padding-left: 20px;
            cursor: pointer;
        }

        .view-attachment-right {
            z-index: 999999999999999999;
            padding-right: 10px;
            display: inline-block;
            cursor: pointer;
            position: absolute;
            right: 0px;
        } */

        .div-attachments-container {
            width: 100%;
            display: inline-block;
            position: fixed;
            bottom: 70px;
        }

        .div-attachments {
            margin-top: 10px;
            position: relative;
        }

        div.btn {
            white-space: normal !important;
            word-break: break-word !important;
            overflow-wrap: break-word !important;
        }

        .circle-button {
            width: 40px;
            height: 40px;
            background-color: #3498db;
            border-radius: 50% !important;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            position: relative;
            z-index: 1;
            opacity: 1;
            /* Updated to set initial opacity to 1 */
            transition: opacity 0.2s;
            /* Added transition for opacity */
        }

        .circle-button-icon {
            color: #ffffff;
            font-size: 24px;
            transition: transform 0.2s;
        }

        .circle-button:hover .circle-button-icon {
            transform: scale(1.2);
        }

        .options {
            position: absolute;
            bottom: calc(100% + 10px);
            left: 50%;
            transform: translateX(-50%);
            background-color: #ffffff;
            color: #000000;
            padding: 8px;
            border-radius: 4px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
        }

        .active .options {
            opacity: 1;
            pointer-events: auto;
        }
    </style>
</head>

<body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"
        integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>

    <script id="botmanWidget" src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/chat.js'></script>

    {{-- <script src="static/js/jquery-1.10.2.min.js"></script>
	<script src="static/bootstrap-4.1.3/js/bootstrap.min.js"></script> --}}

    <div id="fileApp"></div>
    <div class="div-attachments-container">
        <div class="div-attachments">
            <div class="pull-right">
                <div style="padding-right: 10px;">
                    <div class="circle-button" id="circle-button">
                        <div class="circle-button-icon" id="circle-button-icon">
                            <i class="fa fa-paperclip"></i>
                        </div>
                        <div class="options" id="open-folder">
                            Tambah Lampiran
                        </div>
                    </div>
                    <span id="view-file-name" style="display:none"></span>
                </div>
            </div>


        </div>
    </div>

    {{-- <script src='static/js/bot_attachment.js?v=1'></script> --}}
    {{-- bot_attachment.js  --}}
    <script>
        $(document).ready(function() {
            $("#circle-button").on("click", function() {
                $(this).toggleClass("active");
                var icon = $(this).find(".circle-button-icon i");
                icon.toggleClass("fa-paperclip fa-times");
            });


            document.getElementById('fileApp').innerHTML =
                '<div> <input style="display:none" type="file" id="fileInput" /> </div> ';

            const fileInput = document.querySelector("#fileInput");
            var file_type;
            var files;

            $("#open-folder").on("click", function(e) {
                file_type = "image";
                fileInput.click();
            });

            // $("#view-audio").on("click", function(e){
            //     file_type = "audio";
            //     fileInput.click();
            // });

            // $("#send").on("click", function(e){
            //     if(($("#view-file-name").text() == "") || (files == null)) return;
            //     sendFile(files[0], file_type);
            // });

            $("#fileInput").on("change", function(e) {
                console.log("File here");
                files = e.target.files;
                console.log(files);
                if (files.length > 0) {
                    $("#view-file-name").text(files[0]["name"]);
                    sendFile(files[0], file_type)
                }

            });

            function sendFile(file, filetype) {
                var form = new FormData();
                form.append("driver", "web");
                form.append("attachment", filetype);
                form.append("interactive", 0);
                form.append("file", file);
                form.append("userId", '{{ $chat_id ?? 'EMpty Id' }}');

                var settings = {
                    "url": "{{ url('botman') }}",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    "method": "POST",
                    "timeout": 0,
                    "processData": false,
                    "mimeType": "multipart/form-data",
                    "contentType": false,
                    "data": form
                };

                $.ajax(settings).done(function(response) {
                    files = null;
                    $("#fileInput").val(null);
                    $("#view-file-name").text("");

                    response = JSON.parse(response);

                    window.parent.postMessage(response, '*');
                });
            }
        });
    </script>


    {{-- <script src="static/js/chat_changes.js?v=1"></script> --}}
    {{-- chat_changes.js --}}
    <script>
        window.addEventListener('load', function() {
            var messageArea = document.getElementById("messageArea");
            var userText = document.getElementById("userText");
            var chatOl = document.getElementsByClassName("chat")[0];
            var messageAreaHeight = messageArea.clientHeight;
            chatHeight = chatOl.clientHeight;
            // messageArea.style.height = (messageAreaHeight - 20) + "px";
            // chatOl.style.height = (chatHeight - 20) + "px";
            userText.setAttribute("autocomplete", "off");
            userText.style.width = "100%";
            messageArea.style.overflow = "auto";
            // userText.style.position = "absolute";
            // userText.style.bottom = "40px";
        });
    </script>
</body>

</html>

<!doctype html>
<html>

<head>
    <title>BotMan Widget</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/assets/css/chat.min.css">
</head>

<body>

</body>

</html>
