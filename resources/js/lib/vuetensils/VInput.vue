<template>
  <div :class="rootClass">
    <label
      v-if="label"
      :for="name"
      :class="labelClass"
    >
      {{ label }}
    </label>
    <input
      :id="name"
      ref="input"
      v-bind="inputAttrs"
      v-model="innerValue"
      :name="name"
      :type="type"
      :class="inputClass"
    >
    <!-- Optional description slot (used in ClippingTool) -->
    <div
      v-if="$slots.description"
      :class="descriptionClass"
    >
      <slot
        name="description"
        :error="hasError"
        :invalid="invalidState"
      />
    </div>
  </div>
</template>
<script>
export default {
  name: 'VInput',
  inheritAttrs: false, // we decide where $attrs go
  props: {
    modelValue: {
      type: [String, Number],
      default: '',
    },
    name: {
      type: String,
      default: '',
    },
    type: {
      type: String,
      default: 'text',
    },
    label: {
      type: String,
      default: '',
    },
    classes: {
      type: Object,
      default: () => ({}),
    },
  },
  emits: ['update:modelValue'],
  computed: {
    innerValue: {
      get() {
        return this.modelValue;
      },
      set(val) {
        this.$emit('update:modelValue', val);
      },
    },
    rootClass() {
      return this.classes.root || null;
    },
    labelClass() {
      return this.classes.label || null;
    },
    inputClass() {
      return this.classes.input || null;
    },
    descriptionClass() {
      return this.classes.description || null;
    },
    inputAttrs() {
      // Send all non-prop attributes (placeholder, aria-*, @keydown, etc.)
      // straight to the <input>, plus basic props like name/type.
      const {
        modelValue, classes, label, ...propRest
      } = this.$props;
      return {
        ...this.$attrs, // placeholder, aria-label, autocomplete, @keydown, etc.
        ...propRest,
      };
    },
    invalidState() {
      const el = this.$refs.input;
      if (!el || !el.validity) {
        return { pattern: false };
      }
      return {
        pattern: el.validity.patternMismatch,
      };
    },
    hasError() {
      return Object.values(this.invalidState).some(Boolean);
    },
  },
};
</script>
