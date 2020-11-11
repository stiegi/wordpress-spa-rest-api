import App from './App.svelte';

const app = new App({
	target: document.getElementById('spa-svelte-menu'),
	props: {
		name: 'world'
	}
});

export default app;
