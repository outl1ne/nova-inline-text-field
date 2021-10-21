<template>
  <panel-item :field="field">
    <template slot="value">
      <inline-text-editable-value :field="field" :resourceName="resourceName" />
    </template>
  </panel-item>
</template>

<script>
import InlineTextEditableValue from './InlineTextEditableValue';

export default {
  components: { InlineTextEditableValue },
  props: ['resource', 'resourceName', 'resourceId', 'field'],
};
</script>
