<script>
 import { configuration } from '../store/configuration';

 export let inputValue;
 export let key;
 export let label;
 export let help;
 export let group;
 export let condition;
 export let type;
 export let validation;
 export let errorMessage;

 let helpText = help ? help.en : ''; // TODO implement language
 let helpClass = 'description';
 $: if(validation) {
     const validationRegex = new RegExp(validation);
     if($configuration[group][key] && !validationRegex.test($configuration[group][key])) {
         helpText = (help ? help.en : '') + ' - Error: ' + errorMessage.en; // TODO implement language
         helpClass = 'error-message';
     }
     else {
         helpText = help ? help.en : ''; // TODO implement language
         helpClass = 'description';
     }
 }

 configuration.update((n) => {
     if (!n[group]) {
         n[group] = {};
     }
     return n;
 });
let shallBeVisible = true;
 $: if(condition) {
         const results = [];
         let operator;
          operator = condition._operator ? condition._operator : 'and';
          Object.keys(condition).
          filter((conditionKey) => conditionKey !== '_operator').
          forEach((conditionKey) => {
              if($configuration[group][conditionKey] === condition[conditionKey]) {
                  results.push(true);
              }
              else {
                  results.push(false);
              }
          });
         if (operator === 'and') {
             shallBeVisible = results.indexOf(false) === -1;
         }
         else if (operator === 'or') {
             shallBeVisible = results.includes(true);
         }
     }

let labelText, id;
 const getType = () => {
     if (typeof inputValue === 'string') {
         return 'text';
     }
     else if(key === 'parameter' || key === 'category' || key === 'goal') {
         return 'table';
     }
     else if (Array.isArray(inputValue)) {
         return 'radio'
     }
     else if (typeof inputValue === 'boolean') {
         return 'checkbox';
     }
     return 'select';
 };

 $: if(shallBeVisible) {
     if (!type) {
         type = getType();
     }

     labelText = (() => {
         if (typeof label === 'string') {
             return label;
         }
         else if (typeof label === 'undefined') {
             return key;
         }
         return label.en; // TODO implement language
     })();
     id = 'mapp_' + key;

 }


</script>
{#if shallBeVisible}
    <tr valign="top">
        {#if type === 'text'}
			<th scope="row">
				<label for={id}>{labelText}</label>
			</th>
			<td>
				<input id={id} type="text" bind:value={$configuration[group][key]} class="regular-text" aria-describedby={id+'_help'}/>
				{#if help}
					<p class={helpClass} id={id+'_help'}>
						{helpText}
					</p>
				{/if}
			</td>

        {:else if type === 'radio'}
            <th scope="row">
                {labelText}
            </th>
            <td>
                <fieldset>
                    <legend class="screen-reader-text"><span>{labelText}</span></legend>
                    {#each inputValue as option (option.value)}
                        <label>
                          <input id={`${group}_${key}_${option.value}`} type="radio" bind:group={$configuration[group][key]} value={option.value}>
    <!--                        TODO implement language-->
                            <span class="date-time-text format-i18n">{option.label.en}</span>
                        </label>
                        <br>
                    {/each}
                </fieldset>
                {#if help}
                    <p class={helpClass} id={id+'_help'}>{helpText}</p>
                {/if}
            </td>


        {:else if type === 'table'}
            <th scope="row">
                <label for={id}>{labelText}</label>
            </th>
            <td>
                <input type="text" aria-describedby={id+'_id'}/>
                <p class={helpClass} id={id+'_id'}>{group} {key} id</p>
            </td>
            <td>
                <select id={id} aria-describedby={id+'_help'}>
                    <optgroup label="Defaults">
                        <option value="123" selected="selected">Post title</option>
                    </optgroup>
                    <optgroup label="Taxonomies">
                        <option value="color" type="t">color</option>
                        <option value="size" type="t">size</option>
                    </optgroup>
                </select>
                <p class={helpClass} id={id+'_help'}>Map value</p>

            </td>
            <td>
<!--                TODO: reset button functionality-->
                <button class="button-secondary" on:click|preventDefault={()=>alert('reset')} id={id + 'reset'}>Reset</button>
                <p class={helpClass} id={id + 'reset'}>Delete mapping</p>
            </td>



        {:else if type === 'select'}
            <th scope="row">
                <label for={id}>{labelText}</label>
            </th>
            <td>
                <select id={id}>
                    <optgroup label="Defaults">
                        <option value="123" selected="selected">Post title</option>
                    </optgroup>
                    <optgroup label="Taxonomies">
                        <option value="color" type="t">color</option>
                        <option value="size" type="t">size</option>
                    </optgroup>
                </select>
                {#if help}
                    <p class={helpClass} id={id+'_help'}>{helpText}</p>
                {/if}
            </td>


        {:else if type === 'checkbox'}
            <th scope="row">
                <label for={id}>{labelText}</label>
            </th>
            <td>
                <fieldset>
                    <legend class="screen-reader-text"><span>{labelText}</span></legend>
                    <label for={id}>
                        <input type="checkbox" id={id} bind:checked={$configuration[group][key]}>{helpText}
                    </label>
                </fieldset>
            </td>

        {:else if type === 'dropdown'}
            <th scope="row">
                <label for={id}>{labelText}</label>
            </th>
            <td>
                <select id={id} bind:value={$configuration[group][key]}>
                        {#each inputValue as option (option.value)}
<!--                        TODO implement language-->
                            <option value="{option.value}">{option.label.en}</option>
                        {/each}
                </select>
                {#if help}
                    <p class={helpClass} id={id+'_help'}>{helpText}</p>
                {/if}
            </td>

        {:else if type === 'textarea'}
            <th scope="row">
                <label for={id}>{labelText}</label>
            </th>
            <td>
                    <textarea id={id} bind:value={$configuration[group][key]} class="regular-text" aria-describedby={id+'_help'}></textarea>
                {#if help}
                    <p class={helpClass} id={id+'_help'}>{helpText}</p>
                {/if}
            </td>
        {/if}
    </tr>
{/if}
