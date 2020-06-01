<template>
  <Carousel
    v-if="items"
    id="related"
    :classes="['carousel--related-content']"
    :options="{}"
    title="Related content"
    :controls="true"
    :show-heading="false"
  >
    <CarouselSlide
      v-for="item in items"
      :key="item.id"
      :item="item"
      class="ui-card--dark-mode"
    />
  </Carousel>
</template>

<script>
import Carousel from './Carousel.vue';
import CarouselSlide from './CarouselSlide.vue';

export default {
  name: 'RelatedContent',
  components: {
    Carousel,
    CarouselSlide,
  },
  props: {
    items: {
      type: Array,
      required: true,
    },
  },
};
</script>

<style>
.carousel--related-content .carousel__slide {
  width: 75%;
}
</style>
