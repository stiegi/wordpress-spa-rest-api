import { writable, derived } from 'svelte/store';
import Fields from '../fields/allFields';
export const activePage = writable('General');

export const configuration = writable(JSON.parse(window._spa.settings.settings));

export const entries = derived(
    configuration,
    ($configuration)=>  Object.keys(Fields)
);

// TODO validation for all and not just General
// TODO bake in condition
export const validated = derived(
  configuration,
  ($configuration) => {
    const generalConfig = $configuration.General;
    const generalFields = Fields.General
    const generalConfigKeys = Object.keys(generalFields);
    for(let i = 0; i < generalConfigKeys.length; i++) {
      const currentConfigValue = generalConfig[generalConfigKeys[i]];
      if(generalFields[generalConfigKeys[i]].validation && currentConfigValue) {
        const r = new RegExp(generalFields[generalConfigKeys[i]].validation);
        if(!r.test(currentConfigValue)) {
          return generalFields[generalConfigKeys[i]].label;
        }
      }
    }
    return true;
  }
)
