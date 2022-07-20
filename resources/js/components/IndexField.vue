<template>
  <InlineTextEditableValue :field="field" :resourceName="resourceName" />
</template>

<script>
import InlineTextEditableValue from './InlineTextEditableValue';

export default {
  components: { InlineTextEditableValue },
  props: ['resourceName', 'field'],
};
</script>
