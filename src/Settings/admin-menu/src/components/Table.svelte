<script>
    export let group;
    import Fields from "../fields/allFields";
    import Tablerow from "./Tablerow.svelte";
    $: Section = Fields[group];
    $: allKeys = Object.keys(Fields[group]);
</script>

<!--<h3>{group}</h3>-->
<table class="form-table">
    <tbody>
        {#each allKeys as key (key)}
            <Tablerow
                    {...Section[key]}
                    {key}
                    {group}
            />
        {/each}
    </tbody>
</table>
