<script>
    import { fade } from 'svelte/transition';
    import NavigationEntry from './NavigationEntry.svelte';
    import Table from './Table.svelte'
    import {configuration, entries, activePage} from '../store/configuration';
</script>

<h2 class="nav-tab-wrapper">
    {#each $entries as entry (entry)}
        <NavigationEntry pageName={entry}/>
    {/each}
</h2>

<div>
    <Table group={$activePage}/>
</div>


