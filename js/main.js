/**
 * 初期処理
 * @return { void }
 * @param { void }
 */
const init = function () {
	screenLoading();
	onEventsFunc();
}

/**
 * イベントの関数
 * @return { void }
 * @param { void }
 */
const onEventsFunc = function () {
	document.getElementById("serch_schedule").addEventListener("input", searchSchedule);
}

/**
 * スケジュールを検索
 * @return { void }
 * @param { void }
 */
const searchSchedule = function () {
	const keyword = this.value;
	const $section = document.querySelectorAll("#display_schedule_area > li.section");
	const $schedule = document.querySelectorAll("#display_schedule_area > li.schedule");
	if (keyword) {
		$section.forEach(function (e) { e.style.display = "none"; });
		$schedule.forEach(function (e) {
			e.style.display = "none";
			let title = e.getAttribute("data-title");
			if (title.indexOf(keyword.toLowerCase()) !== -1 ||
				title.indexOf(keyword.toUpperCase()) !== -1) {
				e.style.display = "block";
			}
		});
	} else {
		$schedule.forEach(function (e) { e.style.display = "block"; });
		$section.forEach(function (e) { e.style.display = "block"; });
	}
}

/**
* オープニング画面表示
* @return { void }
* @param { void }
*/
const screenLoading = function () {
	if (sessionStorage.getItem("accessed")) {
		document.getElementById("loading_screen").style.display = "none";
	} else {
		sessionStorage.setItem("accessed", "true");
		gsap.to(document.getElementById("loading_screen"), {
			display: "none",
			scale: 1.2,
			opacity: 0,
			ease: "power2.in",
			duration: 0.3,
			delay: 3,
		});
	}
}

init();