<template>
  <span>
    <slot name="toggle" />
    <transition
      :name="bgTransition || transition"
      appear
    >
      <template v-if="modelValue">
        <div
          :id="id"
          :class="['vts-drawer', classes.root, classes.bg, $attrs.class]"
          @click.self="$emit('update:modelValue', false)"
        >
          <transition
            :name="transition"
            appear
          >
            <component
              :is="tag"
              v-if="modelValue"
              ref="content"
              :class="['vts-drawer__content', contentClass]"
              role="dialog"
              aria-modal="true"
              tabindex="-1"
            >
              <slot />
            </component>
          </transition>
        </div>
      </template>
    </transition>
  </span>
</template>

<script>
export default {
  name: 'VDrawer',
  props: {
    modelValue: {
      type: Boolean,
      default: false,
    },
    id: {
      type: String,
      default: '',
    },
    transition: {
      type: String,
      default: '',
    },
    bgTransition: {
      type: String,
      default: '',
    },
    noScroll: {
      type: Boolean,
      default: false,
    },
    tag: {
      type: String,
      default: 'div',
    },
    classes: {
      type: Object,
      default: () => ({}),
    },
  },
  emits: ['update:modelValue'],
  data() {
    return {
      previouslyFocusedEl: null,
    };
  },
  computed: {
    contentClass() {
      return this.classes.content || null;
    },
  },
  watch: {
    modelValue(val) {
      if (this.noScroll) {
        // Lock/unlock page scroll while drawer is open
        document.body.style.overflow = val ? 'hidden' : '';
      }

      if (val) {
        this.open();
      } else {
        this.close();
      }
    },
  },
  methods: {
    open() {
      // Remember element that had focus before opening so we can restore it
      this.previouslyFocusedEl = document.activeElement || null;
      this.$nextTick(() => {
        const content = this.$refs.content;
        if (!content) return;
        const focusables = this.getFocusableElements(content);
        if (focusables.length) {
          focusables[0].focus();
        } else {
          // Fallback: make panel itself focusable
          content.setAttribute('tabindex', '-1');
          content.focus();
        }
        document.addEventListener('keydown', this.onKeydown);
      });
    },
    close() {
      document.removeEventListener('keydown', this.onKeydown);
      if (this.previouslyFocusedEl && typeof this.previouslyFocusedEl.focus === 'function') {
        this.previouslyFocusedEl.focus();
      }
      this.previouslyFocusedEl = null;
    },
    getFocusableElements(root) {
      const selectors = [
        'a[href]',
        'button:not([disabled])',
        'textarea:not([disabled])',
        'input:not([disabled])',
        'select:not([disabled])',
        '[tabindex]:not([tabindex="-1"])',
      ];
      return Array.from(root.querySelectorAll(selectors.join(',')))
        .filter((el) => !el.hasAttribute('disabled') && !el.getAttribute('aria-hidden'));
    },
    onKeydown(e) {
      if (!this.modelValue) return;
      if (e.key === 'Escape') {
        e.stopPropagation();
        this.$emit('update:modelValue', false);
        return;
      }
      if (e.key !== 'Tab') return;

      const content = this.$refs.content;
      if (!content) return;
      const focusables = this.getFocusableElements(content);
      if (!focusables.length) {
        e.preventDefault();
        return;
      }

      const first = focusables[0];
      const last = focusables[focusables.length - 1];
      const isShift = e.shiftKey;
      const current = document.activeElement;

      if (!isShift && current === last) {
        e.preventDefault();
        first.focus();
      } else if (isShift && current === first) {
        e.preventDefault();
        last.focus();
      }
    },
  },
};
</script>

<style>
.vts-drawer {
  position: fixed;
  z-index: 100;
  inset: 0;
}

.vts-drawer__content {
  overflow: auto;
  max-inline-size: 20rem;
  block-size: 100%;
}

.vts-drawer__content:focus {
  outline: 0;
}
</style>
