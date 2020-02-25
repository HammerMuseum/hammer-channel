<template>
  <div class="drawer-wrapper">
    <div class="drawer">
      <span class="drawer__button" @click="openDrawer($event, 'topic')">Topics and tags</span>
      <div class="drawer__content topic-drawer">
        <p>Here is the drawer content.</p>
        <p>Here is the drawer content.</p>
        <p>Here is the drawer content.</p>
        <p>Here is the drawer content.</p>
        <p>Here is the drawer content.</p>
        <p>Here is the drawer content.</p>
        <p>Here is the drawer content.</p>
        <p>Here is the drawer content.</p>
        <p>Here is the drawer content.</p>
        <p>Here is the drawer content.</p>
        <p>Here is the drawer content.</p>
        <p>Here is the drawer content.</p>
        <p>Here is the drawer content.</p>
        <p>Here is the drawer content.</p>
        <p>Here is the drawer content.</p>
        <p>Here is the drawer content.</p>
      </div>
    </div>
    <div class="drawer">
      <span class="drawer__button" @click="openDrawer($event, 'related')">Related videos</span>
      <div class="drawer__content related-drawer">
        Here is the drawer content.
      </div>
    </div>
    <div class="drawer">
      <span class="drawer__button" @click="openDrawer($event, 'event')">Attend our next event</span>
      <div class="drawer__content event-drawer">
        Here is the drawer content.
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        activeDrawer: null,
      }
    },
    mounted() {
      console.log('Component mounted.');
    },
    methods: {
      openDrawer(event, name) {
        const clickedElem = event.target;
        if (document.querySelector('.active-drawer')) {
          document.querySelector('.active-drawer').classList.remove('active-drawer');
        } else {
          clickedElem.parentNode.classList.add('active-drawer');
        }
      }
    }
  };
</script>
