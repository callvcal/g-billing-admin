<!DOCTYPE html>
<html>

<head>


    <base href="public/web/">

    <meta charset="UTF-8">
    <meta content="IE=Edge" http-equiv="X-UA-Compatible">
    <meta name="description" content="A new Flutter project.">

    <!-- iOS meta tags & icons -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="callvcal">
    <link rel="apple-touch-icon" href="icons/Icon-192.png">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="favicon.png" />

    <title>Eatplan8</title>
    <link rel="manifest" href="manifest.json">
    <meta name="google-signin-client_id"
        content="638384799613-0piuq3mvvs07e9fc4g55iphmma1jk00q.apps.googleusercontent.com">

    <script>
        // The value below is injected by flutter build, do not touch.
        const serviceWorkerVersion = "1744646706";
    </script>
    <!-- This script adds the flutter initialization JS code -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDOIuUFLJ_FsT6J-bwI85N8mISLTVSHbxo&libraries=drawing">
    </script>

    <script src="flutter.js" defer></script>

    <style>
        .loader {
            width: 48px;
            height: 48px;
            border: 16px solid #f3f3f3;
            border-top: 16px solid #3498db;
            border-radius: 50%;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>

    <center>
        <div class="loader">
        </div>
    </center>

    <script type="module">
        // Import the functions you need from the SDKs you need
        import {
            initializeApp
        } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-app.js";
        import {
            getAnalytics
        } from "https://www.gstatic.com/firebasejs/10.11.0/firebase-analytics.js";
        // TODO: Add SDKs for Firebase products that you want to use
        // https://firebase.google.com/docs/web/setup#available-libraries

        // Your web app's Firebase configuration
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional
        const firebaseConfig = {
            apiKey: "AIzaSyBfHx6erm21k7ogUsgPhuqsIAY1eLyvs2k",
            authDomain: "eatplan8-270b4.firebaseapp.com",
            databaseURL: "https://eatplan8-270b4-default-rtdb.asia-southeast1.firebasedatabase.app",
            projectId: "eatplan8-270b4",
            storageBucket: "eatplan8-270b4.appspot.com",
            messagingSenderId: "638384799613",
            appId: "1:638384799613:web:a3df737a3bdaaa349ad614",
            measurementId: "G-GDLVXNPF39"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const analytics = getAnalytics(app);
        self.FIREBASE_APPCHECK_DEBUG_TOKEN = "B0D3984F-57FB-4BBF-AB22-5152AA1C071D";




        let useHtml = true;



        window.addEventListener('load', function(ev) {
            // Download main.dart.js
            _flutter.loader.loadEntrypoint({
                serviceWorker: {
                    serviceWorkerVersion: serviceWorkerVersion,
                },
                onEntrypointLoaded: function(engineInitializer) {
                    document.body.classList.add('loaded');
                    let config = {
                        renderer: useHtml ? "html" : "canvaskit",
                    };
                    engineInitializer.initializeEngine(config).then(function(appRunner) {
                        appRunner.runApp();
                    });
                }
            });
        });
    </script>
</body>

</html>
