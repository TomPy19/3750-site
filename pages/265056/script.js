$(document).ready(function () {
	const audioFileData = [
		{ title: "Beginning", time: "0:00" },
		{ title: "Song 1", time: "0:23" },
		{ title: "WQUT Readout 1", time: "0:50" },
		{ title: "Song 2", time: "1:02" },
		{ title: "Community Announcements", time: "1:13" },
		{ title: "Song 3", time: "1:51" },
	];

	function generateUniqueId() {
		return Date.now();
	}

	// Function to populate the playlist
	function populatePlaylist() {
		const playlist = $("#playlist");
		playlist.empty();

		$.each(audioFileData, function (index, item) {
			const uniqueId = generateUniqueId(); // Generate a unique ID
			const titleButton = $("<li id='item'>").attr("data-id", uniqueId);
			const titleButtonText = $("<p>").text(`${item.title} - ${item.time}`);
			titleButton.append(titleButtonText);

			$(titleButton).on("click", function () {
				// Play the audio from the specified time
				const audioPlayer = $("#audio-player")[0];
				const [minutes, seconds] = item.time.split(":");
				audioPlayer.currentTime = parseInt(minutes) * 60 + parseInt(seconds);
				if (!audioPlayer.paused) {
					playPauseButton.click();
				}
				playPauseButton.click();
			});

			const removeTitleButton = $("<i class='fas fa-times-circle' id='remove-title-button'>");
			$(removeTitleButton).on("click", function () {
				const audioPlayer = $("#audio-player")[0];
				const currentTime = audioPlayer.currentTime;
				const $clickedItem = $(titleButton);
				const clickedItemId = $clickedItem.attr("data-id");

				// Remove the item from the playlist
				$clickedItem.remove();

				// Remove the corresponding data entry
				audioFileData = audioFileData.filter(item => {
					return item.title !== $clickedItem.text().trim();
				});
			});

			titleButton.append(removeTitleButton);

			playlist.append(titleButton);
		});
	}

	// Function to update the current time display
	function updateCurrentTime() {
		const audioPlayer = $("#audio-player")[0];
		const currentTime = $("#current-time");
		const minutes = Math.floor(audioPlayer.currentTime / 60);
		const seconds = Math.floor(audioPlayer.currentTime % 60);
		currentTime.text(`${minutes}:${seconds < 10 ? '0' : ''}${seconds}`);
	}

	// Initial setup
	const playPauseButton = $("#play-pause-button");
	$(playPauseButton).on("click", () => {
		const audioPlayer = $("#audio-player")[0];
		if (audioPlayer.paused) {
			audioPlayer.play();
			playPauseButton.attr("class", "fas fa-pause");
		} else {
			audioPlayer.pause();
			playPauseButton.attr("class", "fas fa-play");
		}
	});

	const rewindButton = $("#rewind-button");
	$(rewindButton).on("click", () => {
		const audioPlayer = $("#audio-player")[0];
		audioPlayer.currentTime -= 5;
	});

	const forwardButton = $("#forward-button");
	$(forwardButton).on("click", () => {
		const audioPlayer = $("#audio-player")[0];
		audioPlayer.currentTime += 5;
	});

	function addTitleToPlaylist(title, time) {
		const uniqueId = generateUniqueId();
		const titleButton = $("<li id='item'>").attr("data-id", uniqueId);
		const titleButtonText = $("<p>").text(title);
		titleButton.append(titleButtonText);

		$(titleButton).on("click", function () {
			// Play the audio from the specified time
			const audioPlayer = $("#audio-player")[0];
			const [minutes, seconds] = time.split(":");
			audioPlayer.currentTime = parseInt(minutes) * 60 + parseInt(seconds);
			if (!audioPlayer.paused) {
				playPauseButton.click();
			}
			playPauseButton.click();
		});

		const removeTitleButton = $("<i class='fas fa-times-circle' id='remove-title-button'>");
		$(removeTitleButton).on("click", function () {
			const audioPlayer = $("#audio-player")[0];
			const currentTime = audioPlayer.currentTime;
			const $clickedItem = $(titleButton);
			const clickedItemId = $clickedItem.attr("data-id");

			// Remove the item from the playlist
			$clickedItem.remove();

			// Remove the corresponding data entry
			audioFileData = audioFileData.filter(item => {
				return item.title !== $clickedItem.text().trim();
			});
		});

		titleButton.append(removeTitleButton);

		$("#playlist").append(titleButton);
	}

	function addTitle() {
		const audioPlayer = $("#audio-player")[0];
		const currentTime = audioPlayer.currentTime;

		const titleInput = $("#item-text-input");
		const title = titleInput.val().trim();

		if (title) {
			const minutes = Math.floor(currentTime / 60);
			const seconds = Math.floor(currentTime % 60);
			const formattedTime = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
			audioFileData.push({ title: `${title}`, time: formattedTime });
			addTitleToPlaylist(`${title} - ${formattedTime}`, formattedTime);
			titleInput.val('');
		}
	}

	const addTitleButton = $("#add-title-button");
	$(addTitleButton).on("click", addTitle);

	const enterEvent = $("#item-text-input");
	$(enterEvent).on("keydown", (e) => {
		if (e.which === 13) {
			addTitle();
		}
	})

	const audioPlayer = $("#audio-player")[0];
	$(audioPlayer).on("timeupdate", updateCurrentTime);

	// Populate the playlist
	populatePlaylist();
});
