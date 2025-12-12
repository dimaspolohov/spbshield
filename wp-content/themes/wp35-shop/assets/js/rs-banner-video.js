(function () {
	var supportsVideo = !!document.createElement('video').canPlayType;
	console.log(isMobile.any())
	if (supportsVideo) {
		var videoContainer = document.querySelector('.js-bgvideo-container');
		var video = document.querySelector('.js-bgvideo');
		var videoControls = document.querySelector('.js-bgvideo-controls');

		// Hide the default controls
		if (video) {
			video.controls = false;
		}

		// Display the own defined video controls
		if (videoControls) {
			videoControls.style.display = 'flex';
		}

		var playpause = document.getElementById('bgvideoPlaypause');
		var stop = document.getElementById('bgvideoStop');
		var mute = document.getElementById('bgvideoMute');
		var playIcon = document.querySelector('.bgvideo__control .video-icon--play');
		var pauseIcon = document.querySelector('.bgvideo__control .video-icon--pause');
		var speakerIcon = document.querySelector('.bgvideo__control .video-icon--speaker');
		var muteIcon = document.querySelector('.bgvideo__control .video-icon--mute');

		if (playpause) {
			playpause.addEventListener('click', function (e) {
				playIcon.classList.toggle('is-active');
				pauseIcon.classList.toggle('is-active');
				if (video.paused || video.ended) video.play();
				else video.pause();
			});
		}

		if (stop) {
			stop.addEventListener('click', function (e) {
				if (pauseIcon.classList.contains('is-active')) {
					pauseIcon.classList.remove('is-active');
					playIcon.classList.add('is-active');
				}
				video.pause();
				video.currentTime = 0;
				progress.value = 0;
			});
		}

		if (stop) {
			mute.addEventListener('click', function (e) {
				speakerIcon.classList.toggle('is-active');
				muteIcon.classList.toggle('is-active');
				video.muted = !video.muted;
			});
		}


	}
})();