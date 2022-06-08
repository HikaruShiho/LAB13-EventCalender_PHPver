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
	const $schedules = document.querySelectorAll("#display_schedule_area > li");
	const $no_schedule = document.querySelector(".no_schedule");
	$no_schedule.style.display = "none";
	if (keyword) {
		$schedules.forEach(e => e.style.display = "none");
		const filter = Array.from($schedules).filter(e => {
			const schedule_title = e.dataset.title;
			const schedule_author = e.dataset.author;
			if (schedule_title.indexOf(keyword.toLowerCase()) !== -1 ||
				schedule_title.indexOf(keyword.toUpperCase()) !== -1 ||
				schedule_author.indexOf(keyword.toLowerCase()) !== -1 ||
				schedule_author.indexOf(keyword.toUpperCase()) !== -1) {
				return e;
			}
		});
		filter.length === 0 ? $no_schedule.style.display = "block" : filter.forEach(e => e.style.display = "block");
	} else {
		$schedules.forEach(e => e.style.display = "block");
		$no_schedule.style.display = "none";
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