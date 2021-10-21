<template>
  <div
    :class="`nova-inline-text-field-index text-${field.textAlign}${editing ? ' -editing' : ''}`"
    @click.capture="editing = true"
  >
    <template v-if="hasValue">
      <template v-if="!editing">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="inline-icon edit-icon fill-current text-70">
          <path
            d="m6.3 12.3 10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H7a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM8 16h2.59l9-9L17 4.41l-9 9V16zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2h6a1 1 0 0 1 0 2H4v14h14v-6z"
          />
        </svg>
        <div v-if="field.asHtml" v-html="field.value"></div>
        <span v-else class="whitespace-no-wrap">{{ field.value }}</span>
      </template>
      <template v-else>
        <input
          :ref="input"
          v-model="fieldValue"
          @keypress="onKeyPress"
          type="text"
          class="form-control form-input form-input-bordered"
          autofocus
        />

        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
          class="inline-icon confirm-icon"
          @click.capture="updateFieldValue"
        >
          <path
            d="M12 22a10 10 0 1 1 0-20 10 10 0 0 1 0 20zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-2.3-8.7 1.3 1.29 3.3-3.3a1 1 0 0 1 1.4 1.42l-4 4a1 1 0 0 1-1.4 0l-2-2a1 1 0 0 1 1.4-1.42z"
          />
        </svg>

        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
          width="24"
          height="24"
          class="inline-icon cancel-icon"
          @click.capture="editing = false"
        >
          <path
            d="M4.93 19.07A10 10 0 1 1 19.07 4.93 10 10 0 0 1 4.93 19.07zm1.41-1.41A8 8 0 1 0 17.66 6.34 8 8 0 0 0 6.34 17.66zM13.41 12l1.42 1.41a1 1 0 1 1-1.42 1.42L12 13.4l-1.41 1.42a1 1 0 1 1-1.42-1.42L10.6 12l-1.42-1.41a1 1 0 1 1 1.42-1.42L12 10.6l1.41-1.42a1 1 0 1 1 1.42 1.42L13.4 12z"
          />
        </svg>
      </template>
    </template>
    <p v-else>&mdash;</p>
  </div>
</template>

<script>
export default {
  props: ['resourceName', 'field'],

  data: () => ({
    editing: false,
    loading: false,
    fieldValue: '',
  }),

  mounted() {
    this.fieldValue = this.field.value;
  },

  methods: {
    onKeyPress(e) {
      if (e.which === 13) this.updateFieldValue();
    },

    async updateFieldValue() {
      this.loading = true;
      try {
        await Nova.request().post(`/nova-vendor/nova-inline-text-field/update/${this.resourceName}`, {
          _inlineResourceId: this.field.resourceId,
          _inlineAttribute: this.field.attribute,
          [this.field.attribute]: this.fieldValue,
        });
        this.editing = false;
        this.field.value = this.fieldValue;
        Nova.success('Success');
      } catch (e) {
        console.error(e);
        Nova.error('error updating');
      }
      this.loading = false;
    },
  },

  computed: {
    /**
     * Determine if the field has a value other than null.
     */
    hasValue() {
      return this.field.value !== null;
    },
  },
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
