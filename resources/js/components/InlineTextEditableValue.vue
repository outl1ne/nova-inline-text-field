<template>
  <div
    :class="`nova-inline-text-field-index text-${field.textAlign}${editing ? ' -editing' : ''} w-full`"
    @click.stop="e => !e.target.classList.contains('inline-icon')"
    @dblclick.stop.capture="startEditing"
  >
    <template v-if="!editing">
      <EditIcon @click.stop.capture="startEditing" />

      <div :style="contentStyle" v-if="!hasValue"><p>&mdash;</p></div>
      <div :style="contentStyle" v-else-if="field.asHtml" v-html="value"></div>
      <span :style="contentStyle" v-else class="whitespace-no-wrap">{{ value }}</span>
    </template>

    <template v-else>
      <input
        ref="input"
        v-model="fieldValue"
        @keypress="onInputKeyPress"
        type="text"
        :disabled="loading"
        class="form-control form-input form-input-bordered o1-w-full"
        @click.stop.capture="true"
      />

      <ConfirmIcon @click.stop.capture="!loading ? updateFieldValue() : void 0" />
      <CancelIcon @click.stop.capture="cancelEditing" />
    </template>
  </div>
</template>

<script>
import EditIcon from '../icons/EditIcon';
import CancelIcon from '../icons/CancelIcon';
import ConfirmIcon from '../icons/ConfirmIcon';
import InteractsWithResourceInformation from 'nova/mixins/InteractsWithResourceInformation';

export default {
  props: ['resourceName', 'field'],
  mixins: [InteractsWithResourceInformation],
  components: { EditIcon, CancelIcon, ConfirmIcon },

  data: () => ({
    editing: false,
    loading: false,
    fieldValue: '',
  }),

  mounted() {
    this.fieldValue = this.value;
  },

  methods: {
    onInputKeyPress(e) {
      if (e.which === 13) this.updateFieldValue();
    },

    startEditing() {
      if (this.editing) return;
      this.fieldValue = typeof this.value === 'number' ? this.value || '' : (this.value || '').trim();
      this.editing = true;

      this.$nextTick(() => this.$refs.input && this.$refs.input.focus());
    },

    cancelEditing() {
      if (this.loading) return;
      this.editing = false;
    },

    async updateFieldValue() {
      this.loading = true;
      try {
        const matchedLensPath = window.location.pathname.match(`/resources/${this.resourceName}/lens/([^/]+)`);
        await Nova.request().post(`/nova-vendor/nova-inline-text-field/update/${this.resourceName}`, {
          _lensUri: matchedLensPath ? matchedLensPath[1] : null,
          _inlineResourceId: this.field.resourceId,
          _inlineAttribute: this.field.attribute,
          [this.field.attribute]: this.fieldValue,
          extraData: this.field.extraData,
        });
        this.editing = false;
        this.field.value = this.fieldValue;

        Nova.success(
          this.__('The :resource was updated!', {
            resource: this.resourceInformation.singularLabel.toLowerCase(),
          })
        );
      } catch (e) {
        console.error(e);

        let error = e.response?.data?.error;

        Nova.error(error ? error : this.__('There was a problem submitting the form.'));
      }
      this.loading = false;
    },
  },

  computed: {
    hasValue() {
      return this.value !== null;
    },

    value() {
      return this.field.value || this.field.displayedAs;
    },

    contentStyle() {
      return {
        maxWidth: this.field.maxWidth ? `${this.field.maxWidth}px` : void 0,
      };
    },
  },
};
</script>

<style lang="scss">
.nova-inline-text-field-index {
  position: relative;
  display: flex;
  align-items: center;

  > *:not(input) {
    overflow: hidden;
    text-overflow: ellipsis;
  }

  > .edit-icon {
    height: 24px;
    width: 24px;
    margin-right: 2px;
    margin-bottom: 1px;
    flex-shrink: 0;
    min-width: 24px;
    cursor: pointer;
    padding: 4px;

    &:hover {
      fill: rgba(var(--colors-primary-500));
    }
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
    fill: #f87171;
  }

  > .confirm-icon {
    fill: #4ade80;
  }

  > .form-input {
    margin-right: 8px;
    max-width: 50vw;

    height: 1.75rem;
    padding-left: 0.5rem;
    padding-right: 0.5rem;
    font-size: 14px;
    max-height: calc(100% - 2px);
  }
}
</style>
