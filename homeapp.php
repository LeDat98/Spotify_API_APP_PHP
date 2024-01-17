<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="static/style.css">
</head>
<body>
<?php
        // Bắt đầu session
        session_start();

        // Kiểm tra xem đã đăng nhập hay chưa
        if (!isset($_SESSION['UserID'])) {
            // Nếu chưa đăng nhập, có thể điều hướng về trang đăng nhập hoặc thực hiện các hành động khác
            header("Location: login.html");
            
            exit();
        }
        // Lấy UserID từ session
        $userID = $_SESSION['UserID'];
        ?>

<iframe src='https://my.spline.design/abstractgradientbackground-07b978148a41ccbd60596e51392b7a87/' frameborder='0' ></iframe>
    <div id="ControlContainer">
      <div id="Logoname_container">
        <img id="logo_images" src="images/SVLogo.png"></img>
        <img id="Tone1" src="images/Tone.png"></img>
        <div id="SoundVoyage" src="images/tone.png">Sound Voyage</div>
      </div>
      <div id="MusicPlayInface" style="width: 100%; height: 461px; position: relative; flex-direction: column; justify-content: flex-start; align-items: flex-start; display: flex">
        <img id="SongImage" style="width:100%; height: 250px; border-radius: 16px;margin-bottom: 10px " src="images/interfaceimageanimestyle.png" />
        <div id="MusicName" style="width: 182px; height: 60px; color: rgba(110.87, 0, 255, 0.66); font-size: 22px; font-family: Inika; font-weight: 700; word-wrap: break-word">Music Name</div>
        <div id="SingerName" style="width: 164px; height: 26px; color: rgba(110.87, 0, 255, 0.66); font-size: 19px; font-family: Inika; font-weight: 700; word-wrap: break-word">Singer</div>
        <div id="ControlBars" style="width: 100%; height: 55px; position: relative; top: 5%; left: 0%">
            <img id="PlayButton" style="width: 40px; height: 40px; left: 35%; top: 0px; position: absolute" src="images/play-button.png" onclick="togglePlayPause()" />
            <img id="Shuffle" style="width: 30px; height: 30px; left: 0px; top: 8px; position: absolute" src="images/shuffle.png" onclick="toggleShuffle()" />
            <img id="Repeat" style="width: 30px; height: 30px; left: 75%; top: 9px; position: absolute" src="images/repeat.png" onclick="toggleRepeat()" />
            <img id="Nextbutton" style="width: 28px; height: 28px; left: 58%; top: 9px; position: absolute" src="images/next.png" onclick="playNextSong()" />
            <img id="Backbutton" style="width: 28px; height: 28px; left: 17%; top: 9px; position: absolute" src="images/back.png" onclick="playPreviousSong()" />
            <img id="PlayList" style="width: 28px; height: 28px; left: 91%; top: 9px; position: absolute" src="images/heart.png" onclick="addToPlaylist('<?= $userID ?>')" />
        </div>
        <div id="seekSlider_container">
            <span id ="currentTime" style="color: #6F00FF;">0:00</span>
            <input type="range" id="seekSlider" min="0" max="100" value="0" step="1" onchange="seekSong()">
            <span id = "totalDuration" style="color: #6F00FF;">0:00</span>
            <div id="volumeControl" class="volume-control">
                <img id="volumeIcon" src="images/volume.png" class="volume-icon">
                <input type="range" id="volumeSlider" class="volume-slider" min="0" max="100" value="50" orient="vertical" onchange="changeVolume(this.value)">
            </div>
        </div>
        <div id="Connect_Spotify" onclick="window.location.href='loginspotify.php'">Connect to Spotify</div>
      </div>
    </div>
    <div id="Taskbar" style="width: 29%; height: 36px; left: 407px; top: 42px; position: absolute">
      <div id="Line1" style="width: 87%; height: 0px; left: 0px; top: 31px; position: absolute; border: 2px white solid"></div>
      <div id="Home" >HOME</div>
      <div id="PrivateSpace" >PRIVATE SPACE</div>
      <a href="logout.php"><div id="Logout" >LOGOUT</div></a>
    </div>
    <div id="Searchbar" style="width:40%; height: 40px; right: 5%; top: 32px; position: absolute">
      <input id="search_box" style="width: 100%; height: 40px; left: 0px; top: 0px; position: absolute; background: rgba(110.87, 0, 255, 0); border-radius: 15px; border: 3px white solid" oninput="getSongSuggestions()" ></input>
      <ul id="suggestions"></ul>
      <img id="search_icon" style="width: 27px; height: 27px; right: 7px; top: 7px; position: absolute;" src="images/search_icon.png" onclick="searchAndPlaySong()"/>
    </div>
    <div id="Kindofmusictask" style="width: 60%; height: 34px; left: 22%; top: 455px; position: absolute">
      <div id="Line2" style="width: 100%; height: 0px; left: 87px; top: 34px; position: absolute; border: 2px white solid"></div>
      <div id="KindOfMusic" style="color: rgb(110 0 253 / 73%); text-shadow: 0px 4px 4px rgb(0 0 0 / 26%);width: 138px; height: 34px; left: 0px; top: -10px; position: absolute; font-size: 40px; font-family: Inika; font-weight: 700; word-wrap: break-word">Play List</div>
    </div>

    <div id="KindofmusicContainer" >
    
    <?php
		$pdo = new PDO('mysql:host=localhost;dbname=musicapp;charset=utf8', 'root', '');
        // Sử dụng prepared statement để tránh SQL injection
        $query = $pdo->prepare('SELECT * FROM musicapp WHERE user_id = :userID');
        $query->bindParam(':userID', $userID, PDO::PARAM_STR);
        $query->execute();
		$cnt = 0;
		foreach ($query as $row) {
			$songName = $row['songName'];
			$artistName = $row['artistName'];
			$imageUrl = $row['imageUrl'];
            $uri = $row['uri'];
            $cnt = $cnt + 1;
		?>
        <div class ='row' onclick="playSelectedSong('<?= $row['uri'] ?>', '<?= $songName ?>', '<?= $artistName ?>', '<?= $imageUrl ?>')">
            <div class="td1"><?=$cnt?></div>
            <img class="td2" src='<?=$imageUrl?>'>
            <div class="td3"><?=$songName?></div>
            <div class="td4"><?=$artistName?></div>
            <div class="td5">
                <img class='delete_icon' src='images/delete2.png' onclick='deleteSong("<?= $uri ?>")'>
            </div>
        </div>
        <?php
        }
    ?>
    </div>
    <script src="https://sdk.scdn.co/spotify-player.js"></script>
    <script src="static/app.js"></script>

</body>
</html>