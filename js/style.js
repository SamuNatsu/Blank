/* Blank Style Srcipt | v0.0.1 */

// Welcome log
const welcome = ()=>{
	console.log(
" ________  ___       ________  ________   ___  __       \n\
|\\   __  \\|\\  \\     |\\   __  \\|\\   ___  \\|\\  \\|\\  \\     \n\
\\ \\  \\|\\ /\\ \\  \\    \\ \\  \\|\\  \\ \\  \\\\ \\  \\ \\  \\/  /|_        A Simple and Pure Theme for Typecho\n\
 \\ \\   __  \\ \\  \\    \\ \\   __  \\ \\  \\\\ \\  \\ \\   ___  \\       Made By SNRainiar(https://rainiar.top)\n\
  \\ \\  \\|\\  \\ \\  \\____\\ \\  \\ \\  \\ \\  \\\\ \\  \\ \\  \\\\ \\  \\      v0.0.1\n\
   \\ \\_______\\ \\_______\\ \\__\\ \\__\\ \\__\\\\ \\__\\ \\__\\\\ \\__\\\n\
    \\|_______|\\|_______|\\|__|\\|__|\\|__| \\|__|\\|__| \\|__|\n\
\n\
      Theme Github: https://github.com/SamuNatsu/Blank\n\n"
	)
}

// Highlight all
const highlight = ()=>{
	console.log("[Blank] Highlighting...")
	hljs.highlightAll()
	hljs.initLineNumbersOnLoad()
}

// Katex render
const renderMath = (el)=>{
	console.log("[Blank] Rendering math...")
	renderMathInElement(
		el,
		{ delimiters: [{left: '$$', right: '$$', display: true}, {left: '$', right: '$', display: false}] }
	)
}

// OwO
let emotion = null
const initEmotion = ()=>{
	console.log("[Blank] Initializing OwO emotion...")
	emotion = new OwO({
		logo: 'OwO',
		container: document.querySelectorAll('.OwO')[0],
		target: document.querySelector('#OwO-area'),
		api: blankThemeUrl + 'emotion.json',
		width: '75%'
	})
}

// Progress bar system
let progressAnimate = null, progressStartTime = null, progressPreTime, progressDone = false;
const initProgress = ()=>{
	console.log("[Blank] Initializing progress bar...")
	let el = document.createElement("style")
	el.innerText = ".progress-bar{background:linear-gradient(90deg,rgba(255,0,0,1) 0%,rgba(255,154,0,1) 10%,rgba(208,222,33,1) 20%,rgba(79,220,74,1) 30%,rgba(63,218,216,1) 40%,rgba(47,201,226,1) 50%,rgba(28,127,238,1) 60%,rgba(95,21,242,1) 70%,rgba(186,12,248,1) 80%,rgba(251,7,217,1) 90%,rgba(255,0,0,1) 100%);box-shadow:0 1px 5px #888;height:5px;left:0;position:fixed;top:-10px;width:100%;z-index:10000}"
	document.head.append(el)
	el = document.createElement("div")
	el.classList.add("progress-bar")
	document.body.prepend(el)
}
const progressBegin = ()=>{
	if (progressAnimate !== null) {
		cancelAnimationFrame(progressAnimate)
		progressStartTime = null
		progressDone = false
		progressAnimate = null
	}
	let el = document.querySelector(".progress-bar")
	el.style.top = "0"
	let step = (timestamp)=>{
		if (progressStartTime === null) {
			progressStartTime = timestamp
		}
		if (progressPreTime !== timestamp) {
			let tmp = (1 - Math.pow(1 - (timestamp - progressStartTime) / 5000, 3)) * 80
			if (tmp > 80) {
				el.style.width = "80%"
				progressDone = true
			}
			else {
				el.style.width = tmp + "%"
			}
		}
		if (timestamp - progressStartTime < 5000) {
			progressPreTime = timestamp
			if (!progressDone) {
				progressAnimate = requestAnimationFrame(step)
			}
		}
	}
	progressAnimate = requestAnimationFrame(step)
}
const progressEnd = ()=>{
	if (progressAnimate !== null) {
		cancelAnimationFrame(progressAnimate)
		progressStartTime = null
		progressDone = false
		progressAnimate = null
	}
	let el = document.querySelector(".progress-bar")
	el.style.top = "0"
	let step2 = (timestamp)=>{
		if (progressStartTime === null) {
			progressStartTime = timestamp
		}
		if (progressPreTime !== timestamp) {
			let tmp = (1 - Math.pow(1 - (timestamp - progressStartTime) / 200, 3)) * -10
			if (tmp < -10) {
				el.style.top = "-10px"
				progressDone = true
			}
			else {
				el.style.top = tmp + "px"
			}
		}
		if (timestamp - progressStartTime < 200) {
			progressPreTime = timestamp
			if (!progressDone) {
				progressAnimate = requestAnimationFrame(step2)
			}
		}
	}
	let step1 = (timestamp)=>{
		if (progressStartTime === null) {
			progressStartTime = timestamp
		}
		if (progressPreTime !== timestamp) {
			let tmp = (1 - Math.pow(1 - (timestamp - progressStartTime) / 500, 3)) * 20 + 80
			if (tmp > 100) {
				el.style.width = "100%"
				progressDone = true
			}
			else {
				el.style.width = tmp + "%"
			}
		}
		if (timestamp - progressStartTime < 500) {
			progressPreTime = timestamp
			if (!progressDone) {
				progressAnimate = requestAnimationFrame(step1)
			}
		}
		else {
			progressStartTime = null
			progressDone = false
			progressAnimate = requestAnimationFrame(step2)
		}
	}
	progressAnimate = requestAnimationFrame(step1)
}

// Pjax system
let pjax = null
const initPjax = ()=>{
	console.log("[Blank] Initializing pjax...")
	pjax = new Pjax({
		elements: 'a[href^="' + window.location.origin + '"]:not([target=_blank]):not([no-pjax]):not([onclick])',
		selectors: ["title", "nav", "#center"],
		cacheBust: false
	})
	document.addEventListener("pjax:send", progressBegin)
	document.addEventListener("pjax:complete", progressEnd)
	document.addEventListener("pjax:success", ()=>{
		initSearch(false)
		let el = document.querySelector("#post-content")
		if (el !== null) {
			highlight()
			renderMath(el)
			initEmotion()
		}
	})
	document.addEventListener("pjax:error", (e)=>{
		console.warn("[Blank] Pjax failed to load remote page: " + e.request.responseURL)
		window.open(e.request.responseURL)
	})
}

// Search
const initSearch = (b)=>{
	if (b) {
		document.querySelector("#search-bg").onclick = ()=>{
			document.querySelector("#search-layer").style.display = "none"
		}
	}
	document.querySelector("#search-btn").onclick = ()=>{
		document.querySelector("#search-layer").style.display = "flex"
	}
}

// Main entrance
window.addEventListener('DOMContentLoaded', ()=>{
	welcome()
	initProgress()
	initPjax()
	initSearch(true)

	let el = document.querySelector("#post-content")
	if (el !== null) {
		highlight()
		renderMath(el)
		initEmotion()
	}
})
