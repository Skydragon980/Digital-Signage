<?php
include('bin/db.php'); // Include il file di connessione al database dalla nuova posizione

if (!isset($_GET['station_id'])) {
    echo "Station ID is required.";
    exit();
}

$station_id = $_GET['station_id'];

$stmt = $conn->prepare("
    SELECT files.filename, files.filepath
    FROM files
    JOIN playlists ON files.id = playlists.file_id
    JOIN stations ON playlists.id = stations.playlist_id
    WHERE stations.id = ? AND playlists.active = 1
    ORDER BY playlists.position ASC
");
$stmt->bind_param("i", $station_id);
$stmt->execute();
$result = $stmt->get_result();
$files = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Content</title>
    <style>
        body { margin: 0; overflow: hidden; cursor: none; }
        .fullscreen {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
    <div id="content" class="fullscreen"></div>
    <script>
        let files = <?php echo json_encode($files); ?>;
        let index = 0;

        function showNextFile() {
            if (files.length === 0) return;

            const file = files[index];
            const contentDiv = document.getElementById('content');

            if (file.filepath.endsWith('.mp4')) {
                const video = document.createElement('video');
                video.src = file.filepath;
                video.classList.add('fullscreen');
                video.autoplay = true;
                video.loop = false;
                video.onended = () => {
                    index = (index + 1) % files.length;
                    showNextFile();
                };
                contentDiv.innerHTML = '';
                contentDiv.appendChild(video);
            } else {
                const img = document.createElement('img');
                img.src = file.filepath;
                img.classList.add('fullscreen');
                contentDiv.innerHTML = '';
                contentDiv.appendChild(img);
                setTimeout(() => {
                    index = (index + 1) % files.length;
                    showNextFile();
                }, 5000);
            }
        }

        document.addEventListener('DOMContentLoaded', showNextFile);
        document.body.style.cursor = 'none';

        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey && event.key === 'x') {
                window.location.href = 'login.html';
            }
        });
    </script>
</body>
</html>
