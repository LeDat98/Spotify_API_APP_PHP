let selectedSongUri;
        let spotifyPlayer; // Spotify Player
        let deviceId; // Device ID
        let currentVolume = 0.5; // Giả sử âm lượng ban đầu là 50%
        let songSuggestions = []; // Mảng để lưu trữ các gợi ý bài hát

        // Hàm lấy gợi ý bài hát
        async function getSongSuggestions() {
            let songName = document.getElementById('search_box').value;
            if (songName.length < 3) { // Chỉ gợi ý khi có ít nhất 3 ký tự
                document.getElementById('suggestions').innerHTML = '';
                return;
            }
            let assectoken = await getAccessToken();
            let response = await fetch(`https://api.spotify.com/v1/search?q=${songName}&type=track&limit=10`, {
                headers: {
                    'Authorization': `Bearer ${assectoken}`
                }
            });
            if (response.ok) {
                let data = await response.json();
                songSuggestions = data.tracks.items; // Lưu trữ gợi ý
                displaySuggestions();
            } else {
                console.log('Error:', response);
            }
        }
        // Hàm hiển thị gợi ý
        function displaySuggestions() {
            let suggestionsElement = document.getElementById('suggestions');
            suggestionsElement.innerHTML = '';
            songSuggestions.forEach((track, index) => {
                let li = document.createElement('li');
                
                // Tạo container cho text và ảnh
                let textContainer = document.createElement('div');
                textContainer.classList.add('suggestion-text');

                // Thêm ảnh đại diện
                let img = document.createElement('img');
                img.src = track.album.images[0].url; // Lấy ảnh từ album
                img.classList.add('suggestion-img');
                textContainer.appendChild(img);

                // Thêm tên bài hát
                let songTitle = document.createElement('div');
                songTitle.textContent = track.name;
                textContainer.appendChild(songTitle);

                // Thêm tên nghệ sĩ
                let artistInfo = document.createElement('div');
                artistInfo.textContent = track.artists.map(artist => artist.name).join(", ");
                artistInfo.classList.add('suggestion-artist');
                textContainer.appendChild(artistInfo);

                li.appendChild(textContainer);
                li.onclick = () => selectSong(index);
                suggestionsElement.appendChild(li);
            });
        }
        // Hàm chọn bài hát từ gợi ý
        function selectSong(index) {
            let track = songSuggestions[index];
            selectedSongUri = track.uri;
            let trackUri = track.uri;
            playSong(trackUri);
            document.getElementById('suggestions').innerHTML = ''; // Xóa danh sách gợi ý
            document.getElementById('search_box').value = songSuggestions[index].name; // Cập nhật tên bài hát
            // Cập nhật hình ảnh, tên bài hát và tên nghệ sĩ
            document.getElementById('SongImage').src = track.album.images[0].url;
            document.getElementById('MusicName').innerText = track.name;
            document.getElementById('SingerName').innerText = track.artists.map(artist => artist.name).join(", ");
        }
        function addToPlaylist(user_id) {
            // Lấy thông tin bài hát
            var songName = document.getElementById('MusicName').innerText;
            var artistName = document.getElementById('SingerName').innerText;
            var imageUrl = document.getElementById('SongImage').src;
            var user_id = user_id;
            
            // Tạo đối tượng để gửi
            var data = {
                songUri: selectedSongUri,
                user_id : user_id,
                songName: songName,
                artistName: artistName,
                imageUrl: imageUrl
            };

            // Gửi dữ liệu đến server bằng AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "update.php", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Xử lý phản hồi từ server ở đây
                    console.log(xhr.responseText);
                }
            };
            var jsonData = JSON.stringify(data);
            xhr.send(jsonData);
        }

        async function getAccessToken() {
            // Gọi API trên server PHP của bạn để lấy Access Token
            const response = await fetch('/MusicApp/get-token.php');
            if (response.ok) {
                const data = await response.json();
                console.log(data.access_token)
                return data.access_token;
            } else {
                throw new Error('Unable to fetch access token');
            }
        }

        // Sử dụng token này trong các yêu cầu API khác
        window.onSpotifyWebPlaybackSDKReady = async () => {
            const token = await getAccessToken();
            spotifyPlayer = new Spotify.Player({
                name: 'Web Playback SDK',
                getOAuthToken: cb => { cb(token); },
                volume: currentVolume
            });

            // Kết nối với Player
            spotifyPlayer.connect();

            // Lấy Device ID
            spotifyPlayer.addListener('ready', ({ device_id }) => {
                console.log('Ready with Device ID', device_id);
                deviceId = device_id;
            });
        };

        // Hàm tìm và phát bài hát
        async function searchAndPlaySong() {
            let songName = document.getElementById('search_box').value;
            let assectoken = await getAccessToken();
            let response = await fetch(`https://api.spotify.com/v1/search?q=${songName}&type=track`, {
                headers: {
                    'Authorization': `Bearer ${assectoken}`
                }
            });

            if (response.ok) {
                let data = await response.json();
                let trackUri = data.tracks.items[0].uri; // Lấy URI của bài hát đầu tiên
                playSong(trackUri);
            } else {
                console.log('Error:', response);
            }
        }

        // Hàm phát bài hát
        async function playSong(trackUri) {
            let assectoken = await getAccessToken();
            fetch(`https://api.spotify.com/v1/me/player/play?device_id=${deviceId}`, {
                method: 'PUT',
                body: JSON.stringify({ uris: [trackUri] }),
                headers: {
                    'Authorization': `Bearer ${assectoken}`,
                    'Content-Type': 'application/json'
                }
            });
        }

        // Hàm tăng âm lượng
        function increaseVolume() {
            currentVolume = Math.min(currentVolume + 0.1, 1); // Tăng 10% nhưng không quá 100%
            spotifyPlayer.setVolume(currentVolume);
        }

        // Hàm giảm âm lượng
        function decreaseVolume() {
            currentVolume = Math.max(currentVolume - 0.1, 0); // Giảm 10% nhưng không dưới 0%
            spotifyPlayer.setVolume(currentVolume);
        }
        // Hàm chuyển đổi giữa chơi và tạm dừng
        async function togglePlayPause() {
            let assectoken = await getAccessToken();
            let isPlaying = await checkIfPlaying();

            let endpoint = isPlaying 
                ? `https://api.spotify.com/v1/me/player/pause?device_id=${deviceId}`
                : `https://api.spotify.com/v1/me/player/play?device_id=${deviceId}`;

            fetch(endpoint, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${assectoken}`,
                    'Content-Type': 'application/json'
                }
            }).catch(error => console.error('Error in togglePlayPause:', error));
        }
        // Kiểm tra xem Spotify Player có đang phát nhạc không
        async function checkIfPlaying() {
            let assectoken = await getAccessToken();
            try {
                let response = await fetch(`https://api.spotify.com/v1/me/player`, {
                    headers: {
                        'Authorization': `Bearer ${assectoken}`
                    }
                });
                if (!response.ok) {
                    throw new Error(`API Request failed: ${response.status}`);
                }
                let data = await response.json();
                return data.is_playing;
            } catch (error) {
                console.error('Error in checkIfPlaying:', error);
                return false;
            }
        }
        let isSeeking = false;
        document.getElementById('seekSlider').addEventListener('mousedown', () => {
            isSeeking = true;
        });
        document.getElementById('seekSlider').addEventListener('mouseup', () => {
            isSeeking = false;
            // Điều chỉnh bài hát dựa trên vị trí mới của thanh trượt
            seekSong();
        });
        async function seekSong() {
            let seekValueSec = document.getElementById('seekSlider').value;
            let positionMs = seekValueSec * 1000; // Chuyển đổi sang mili giây
            let assectoken = await getAccessToken();
            fetch(`https://api.spotify.com/v1/me/player/seek?position_ms=${positionMs}`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${assectoken}`
                }
            }).catch(error => console.error('Error in seekSong:', error));
        }
        function updateProgress() {
            if (spotifyPlayer && !isSeeking) {
                spotifyPlayer.getCurrentState().then(state => {
                    if (!state) {
                        console.error('Cannot get current state or Spotify Player is not connected');
                        return;
                    }
                    let positionMs = state.position; // Thời gian hiện tại (ms)
                    let durationMs = state.duration; // Thời lượng tổng cộng (ms)

                    let durationSec = Math.floor(durationMs / 1000); // Chuyển đổi sang giây
                    document.getElementById('seekSlider').max = durationSec;

                    let currentPosition = Math.floor(positionMs / 1000); // Chuyển đổi sang giây
                    document.getElementById('seekSlider').value = currentPosition;

                    document.getElementById('currentTime').innerText = formatTime(positionMs);
                    document.getElementById('totalDuration').innerText = formatTime(durationMs);
                }).catch(error => console.error('Error getting current state:', error));
            }
        }
        function formatTime(milliseconds) {
            let seconds = Math.floor(milliseconds / 1000);
            let minutes = Math.floor(seconds / 60);
            seconds = seconds % 60;

            // Thêm số 0 ở đầu nếu cần
            seconds = seconds < 10 ? '0' + seconds : seconds;
            return minutes + ':' + seconds;
        }
        setInterval(updateProgress, 1000);
        function changeVolume(value) {
            // Chuyển đổi giá trị từ 0-100 thành tỷ lệ phần trăm 0-1
            let volume = value / 100;
            // Đặt âm lượng cho Spotify Player
            spotifyPlayer.setVolume(volume).catch(error => console.error('Error setting volume:', error));
        }
        // Hàm này sẽ được gọi khi người dùng click vào một hàng trong danh sách
        function playSelectedSong(songUri, songName, artistName, imageUrl) {
            playSong(songUri); // Hàm này sẽ gửi URI đến Spotify để phát
            // Cập nhật UI với thông tin bài hát được chọn
            document.getElementById('SongImage').src = imageUrl;
            document.getElementById('MusicName').innerText = songName;
            document.getElementById('SingerName').innerText = artistName;
        }
        // Fit font size text 
        function adjustFontSizeToFit(containerId) {
            var container = document.getElementById(containerId);
            var desiredWidth = container.clientWidth; // Lấy chiều rộng của div
            var desiredHeight = container.clientHeight; // Lấy chiều cao của div
            var currentSize = parseInt(window.getComputedStyle(container, null).getPropertyValue('font-size'), 10);

            while (container.scrollHeight > desiredHeight || container.scrollWidth > desiredWidth) {
                currentSize--;
                container.style.fontSize = currentSize + "px";
            }
        }
        // Gọi hàm này với ID của div
        adjustFontSizeToFit('MusicName');
        //Hàm gửi thông tin uri của bài hát về server để xoá bài hát 
        function deleteSong(uri) {
            if (!confirm('Bạn có chắc chắn muốn xóa bài hát này không?')) {
                return;
            }

            var xhr = new XMLHttpRequest();
            xhr.open("GET", "delete.php?uri=" + encodeURIComponent(uri), true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Tải lại trang sau khi xóa thành công
                    location.reload();
                }
            };
            xhr.send();
        }