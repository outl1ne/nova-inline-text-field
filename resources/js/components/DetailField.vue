<template>
  <PanelItem :field="field">
    <template #value>
      <InlineTextEditableValue :field="field" :resourceName="resourceName" />
    </template>
  </PanelItem>
</template>

<script>
import InlineTextEditableValue from './InlineTextEditableValue';

export default {
  components: { InlineTextEditableValue },
  props: ['resource', 'resourceName', 'resourceId', 'field'],
};
</script>
