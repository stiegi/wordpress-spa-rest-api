<script>

	const buildFileCache = () => {
		postData(window._spa.api.buildFileCache).then((r)=> {
			console.log('Cache files generated', r);
		});
	}

	async function postData(url) {
		// Default options are marked with *
		const response = await fetch(url, {
			method: 'GET', // *GET, POST, PUT, DELETE, etc.
			mode: 'cors', // no-cors, *cors, same-origin
			cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
			credentials: 'include', // include, *same-origin, omit
			headers: {
				'Content-Type': 'application/json',
				'X-WP-Nonce': window._spa.api.nonce
				// 'Content-Type': 'application/x-www-form-urlencoded',
			},
			redirect: 'follow', // manual, *follow, error
			referrerPolicy: 'no-referrer' // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
		});
		return response.json(); // parses JSON response into native JavaScript objects
	}
</script>

<button class="button-primary" on:click|preventDefault={buildFileCache}>Generate File Cache</button>
