<template>
    <transition name="slide-fade">
        <div class="video__info">
            <div class="video-info__card" scroll>
                <div class="date">
                    {{ new Date(date) | dateFormat('dddd, DD MMMM, YYYY') }}
                </div>
                <div class="description">
                    {{ description }}
                </div>
                <div class="keywords">
                    <ul>
                        <li
                                v-for="item in keywords"
                                :key="item.id"
                        >
                            {{ item }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
  export default {
    name: 'About',
    props: {
      date: String,
      description: String,
      keywords: Array
    },
    mounted() {
      console.log('Component mounted.');
      console.log(this.date);
    },
  };
</script>
