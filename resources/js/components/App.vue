<template>
  <div tabindex="-1">
    <vue-progress-bar />
    <vue-announcer />
    <the-header />
    <transition name="fade">
      <main class="main">
        <router-view ref="routerView" />
      </main>
    </transition>
  </div>
</template>
