<script>
	import SaveButton from "./components/SaveButton.svelte";
	import FileCacheButton from "./components/FileCacheButton.svelte";
	import Layout from './components/Layout.svelte';

	import { configuration } from "./store/configuration";
	$: debugConfig = JSON.stringify($configuration);
	let debug = false;
	if (document.cookie.indexOf('wt_debug') !== -1) {
		debug = true;
	}

</script>
<main>
	{#if debug}
		<textarea cols="70" bind:value={debugConfig}></textarea>
	{/if}
	<Layout />
	<p>
		<SaveButton />
		<FileCacheButton />
	</p>
</main>
