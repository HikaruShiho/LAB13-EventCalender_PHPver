/**
 * 初期処理
 * @return { void }
 * @param { void }
 */
const init = function () {
	screenLoading();
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