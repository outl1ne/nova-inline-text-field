<template>
  <inline-text-editable-value :field="field" :resourceName="resourceName" />
</template>

<script>
import InlineTextEditableValue from './InlineTextEditableValue';

export default {
  components: { InlineTextEditableValue },
  props: ['resourceName', 'field'],
};
</script>

<style lang="scss" scoped>
.nova-inline-text-field-index {
  position: relative;
  display: flex;
  align-items: center;

  &:not(.-editing) {
    cursor: pointer;
  }

  > .edit-icon {
    height: 16px;
    width: 16px;
    margin-right: 6px;
    margin-bottom: 1px;
  }

  > .cancel-icon,
  > .confirm-icon {
    height: 24px;
    width: 24px;
    cursor: pointer;
    margin-left: 6px;
    opacity: 0.6;

    &:hover {
      opacity: 1;
    }
  }

  > .cancel-icon {
    fill: var(--danger);
  }

  > .confirm-icon {
    fill: var(--success);
  }

  > .form-input {
    margin-right: 8px;
  }

  &:hover {
    > .edit-icon {
      fill: var(--primary);
      color: var(--primary);
    }
  }
}
</style>
