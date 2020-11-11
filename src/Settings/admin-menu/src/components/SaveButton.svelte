<script>
    import {configuration, validated} from '../store/configuration';

    const saveSettings = () => {
        postData(window._spa.api.url, $configuration).then((r)=> {
        	console.log('saved', r);
		});
    }

	async function postData(url = '', data = {}) {
		// Default options are marked with *
		const response = await fetch(url, {
			method: 'POST', // *GET, POST, PUT, DELETE, etc.
			mode: 'cors', // no-cors, *cors, same-origin
			cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
			credentials: 'include', // include, *same-origin, omit
			headers: {
				'Content-Type': 'application/json',
				'X-WP-Nonce': window._spa.api.nonce
				// 'Content-Type': 'application/x-www-form-urlencoded',
			},
			redirect: 'follow', // manual, *follow, error
			referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
			body: JSON.stringify(data) // body data type must match "Content-Type" header
		});
		return response.json(); // parses JSON response into native JavaScript objects
	}
</script>

<button disabled={$validated !== true} class="button-primary" on:click|preventDefault={saveSettings}>Save Changes</button>
{#if $validated !== true}
  <p>Please enter a valid value for: {$validated}</p>
{/if}

