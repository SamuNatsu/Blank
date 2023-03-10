/* Blank Style Srcipt | v0.1.1 */

// Ease functions
const easeInCubic = (x)=>(x * x * x)
const easeOutCubic = (x)=>(1 - Math.pow(1 - x, 3))
const easeInOutCubic = (x)=>(x < 0.5 ? 4 * x * x * x : 1 - Math.pow(-2 * x + 2, 3) / 2)

// Animation functions
const executeAnimate = (context, time, transformFunction, easeFunction, callback)=>{
	if (context.handle !== null) {
		cancelAnimationFrame(context.handle)
		context.start = null
		context.done = false
		context.handle = null
	}

	const step = (timestamp)=>{
		if (context.start === null) { context.start = timestamp }
		if (context.last !== timestamp) {
			let tmp = easeFunction((timestamp - context.start) / time)
			if (tmp > 1) {
				transformFunction(1)
				context.done = true
			}
			else { transformFunction(tmp) }
		}
		if (timestamp - context.start < time) {
			context.last = timestamp
			if (!context.done) { context.handle = requestAnimationFrame(step) }
		}
		else if (callback !== undefined) { callback() }
	}
	context.handle = requestAnimationFrame(step)
}

// Log functions
const log = (msg)=>{ console.log("[Blank] [" + window.location.pathname + "] " + msg) }
const warn = (msg)=>{ console.warn("[Blank] [" + window.location.pathname + "] " + msg) }

// Ajax functions
const ajaxGet = (url, data)=>{
	return new Promise((resolve, reject)=>{
		let s = ''
		if (data instanceof Object) {
			Object.keys(data).forEach((key)=>{
				if (s.length !== 0) { s += "&" }
				s += encodeURI(key) + "=" + encodeURI(data[key])
			})
		}
		var xhr = new XMLHttpRequest();
		xhr.open('GET', url + "?" + s, true)
		xhr.onload = ()=>{
			if (xhr.status === 200) { resolve(xhr.responseText) }
			else { reject(xhr.statusText) }
		}
		xhr.onerror = ()=>{ reject(xhr.statusText) }
		xhr.send()
	})
}

// Progress bar system
const $progressContext = {
	handle: null,
	start: null,
	last: null,
	done: false,
	bar: null
}
const initProgress = ()=>{
	log("Initializing progress bar...")

	let el = document.createElement("style")
	el.innerText = ".progress-bar{background:\
linear-gradient(90deg,#f00 0%,#ff9a00 10%,#d0de21 20%,#4fdc4a 30%,\
#3fdad8 40%,#2fc9e2 50%,#1c7fee 60%,#5f15f2 70%,#ba0cf8 80%,#fb07d9 90%,\
#f00 100%);box-shadow:0 1px 5px #888;height:5px;left:0;position:fixed;\
top:-10px;width:100%;z-index:10000}"
	document.head.append(el)

	$progressContext.bar = document.createElement("div")
	$progressContext.bar.classList.add("progress-bar")
	document.body.prepend($progressContext.bar)
}
const progressBegin = ()=>{
	$progressContext.bar.style.top = "0"
	executeAnimate(
		$progressContext, 
		5000, 
		(x)=>{ $progressContext.bar.style.width = (x * 80) + "%" },
		easeOutCubic
	)
}
const progressEnd = ()=>{
	let start = parseFloat(/(.*)%/.exec($progressContext.bar.style.width)[1])
	let delta = 100 - start

	$progressContext.bar.style.top = "0"
	executeAnimate(
		$progressContext, 
		500,
		(x)=>{ $progressContext.bar.style.width = (x * delta + start) + "%" },
		easeOutCubic,
		()=>{
			executeAnimate(
				$progressContext, 
				200, 
				(x)=>{ $progressContext.bar.style.top = (x * -10) + "px" },
				easeInOutCubic
			)
		}
	)
}

// Element scroll system
const $scrollContext = {
	handle: null,
	start: null,
	last: null,
	done: false
}
const elementScroll = (el)=>{
	let start = (document.documentElement.scrollTop || document.body.scrollTop)
	let end = (el === undefined ? 0 : el.offsetTop)
	let delta = end - start

	executeAnimate(
		$scrollContext, 
		1000, 
		(x)=>{ window.scrollTo(0, start + x * delta) },
		easeInOutCubic
	)
}

// Pjax system
let pjax = null
const initPjax = ()=>{
	log("Initializing pjax...")

	pjax = new Pjax({
		elements: 'a[href^="' + window.location.origin + '"]:not([target=_blank]):not([no-pjax]):not([onclick])',
		selectors: ["title", "nav", "#center"],
		cacheBust: false
	})

	document.addEventListener("pjax:send", progressBegin)
	document.addEventListener("pjax:complete", progressEnd)
	document.addEventListener("pjax:success", ()=>{
		initSearch(false)
		initSideBtn(false)

		let el = document.querySelector("#post-content")
		if (el !== null) {
			highlight()
			renderMath(el)
			initEmotion()
			let els = document.querySelectorAll("#post-content a:not([data-pjax-state]):not([target])")
			els.forEach((v)=>v.setAttribute("target", "_blank"))
		}
	})
	document.addEventListener("pjax:error", (e)=>{
		warn("Pjax failed to load remote page: " + e.request.responseURL)
		window.open(e.request.responseURL)
	})
}

// Welcome log
const welcome = ()=>{
	console.log(
" ________  ___       ________  ________   ___  __       \n\
|\\   __  \\|\\  \\     |\\   __  \\|\\   ___  \\|\\  \\|\\  \\     \n\
\\ \\  \\|\\ /\\ \\  \\    \\ \\  \\|\\  \\ \\  \\\\ \\  \\ \\  \\/  /|_        A Simple and Pure Theme for Typecho\n\
 \\ \\   __  \\ \\  \\    \\ \\   __  \\ \\  \\\\ \\  \\ \\   ___  \\       Made By SNRainiar(https://rainiar.top)\n\
  \\ \\  \\|\\  \\ \\  \\____\\ \\  \\ \\  \\ \\  \\\\ \\  \\ \\  \\\\ \\  \\      v0.1.0\n\
   \\ \\_______\\ \\_______\\ \\__\\ \\__\\ \\__\\\\ \\__\\ \\__\\\\ \\__\\\n\
    \\|_______|\\|_______|\\|__|\\|__|\\|__| \\|__|\\|__| \\|__|\n\
\n\
      Theme Github: https://github.com/SamuNatsu/Blank\n\n"
	)
}

// Code highlight
const highlight = ()=>{
	log("Highlighting...")

	hljs.highlightAll()
	hljs.initLineNumbersOnLoad()
}

// Katex render
const renderMath = (el)=>{
	log("Rendering math...")

	renderMathInElement(
		el,
		{
			delimiters: [
				{ left: '$$', right: '$$', display: true },
				{ left: '$', right: '$', display: false }
			]
		}
	)
}

// OwO
let emotion = null
const initEmotion = ()=>{
	if (document.querySelector('#OwO-area') === null) { return }

	log("Initializing OwO emotion...")

	emotion = new OwO({
		logo: 'OwO',
		container: document.querySelectorAll('.OwO')[0],
		target: document.querySelector('#OwO-area'),
		api: blankThemeUrl + 'emotion.json',
		width: '75%'
	})
}

// Search
const initSearch = (b)=>{
	if (b) {
		document.querySelector(".search-bg").onclick = ()=>{
			document.querySelector(".search-layer").style.display = "none"
		}
	}
	document.querySelector("#search-btn").onclick = ()=>{
		document.querySelector(".search-layer").style.display = "flex"
	}
}

// Side button
const initSideBtn = (b)=>{
	if (b) {
		let el = document.querySelector(".side-btn-top")
		el.onclick = ()=>elementScroll()
		window.onscroll = ()=>{
			let el = document.querySelector(".side-btn-top")
			let cur = document.documentElement.scrollTop || document.body.scrollTop
			if (cur > 1000) { el.style.display = "block" }
			else { el.style.display = "none" }
		}

		el = document.querySelector(".mobile-btn-nav")
		el.onclick = ()=>{
			let el = document.querySelector("nav")
			el.style.right = "0rem"
		}

		el = document.querySelector(".mobile-btn-nav-close")
		el.onclick = ()=>{
			let el = document.querySelector("nav")
			el.style.right = "-10rem"
		}

		el = document.querySelector(".mobile-btn-aside")
		el.onclick = ()=>{
			let el = document.querySelector("aside")
			el.style.left = "0rem"
		}

		el = document.querySelector(".mobile-btn-aside-close")
		el.onclick = ()=>{
			let el = document.querySelector("aside")
			el.style.left = "-50rem"
		}
	}

	let el = document.querySelector(".side-btn-comment")
	if (document.querySelector("#comments") !== null) {
		el.onclick = ()=>elementScroll(document.querySelector("#comments"))
		el.style.display = "block"
	}
	else { el.style.display = "none" }
}

// Share
const genShareText = ()=>{
	
}

// On DOM loaded
window.addEventListener('DOMContentLoaded', ()=>{
	welcome()
	initProgress()
	initPjax()
	initSearch(true)
	initSideBtn(true)

	let el = document.querySelector("#post-content")
	if (el !== null) {
		highlight()
		renderMath(el)
		initEmotion()
		let els = document.querySelectorAll("#post-content a:not([data-pjax-state]):not([target])")
		els.forEach((v)=>v.setAttribute("target", "_blank"))
	}
})

// On all loaded
window.onload = ()=>{
	const ctx = {
		handle: null,
		start: null,
		last: null,
		done: false,
		frame: document.querySelector(".loading-frame")
	}
	executeAnimate(
		ctx, 
		500,
		(x)=>{ ctx.frame.style.opacity = (1 - x) + "" },
		easeInCubic,
		()=>{ ctx.frame.style.display = "none"}
	)
}