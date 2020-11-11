<script>
    import { activePage } from '../store/configuration';
    export let pageName;
    const handleClick = () => {
        activePage.set(pageName);
    }
</script>

<a href="javascript:void(0)" class={$activePage === pageName ? 'nav-tab nav-tab-active' : 'nav-tab'} on:click={handleClick}>{pageName}</a>