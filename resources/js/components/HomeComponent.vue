<template>
  <div class="container container--full">
    <NavigationBar
      :items="videos"
      :active-item="currentSectionInView"
      :classes="['topic-menu']"
    />
    <div
      id="start-of-content"
      class="page-wrapper page-wrapper--full"
    >
      <p class="strapline">
        {{ copy.strapline }}
      </p>
      <Loader
        v-if="!featured"
        class="carousel--full-width"
      />
      <Carousel
        v-else
        id="featured"
        title="Featured programs"
        :controls="true"
        :classes="['carousel--featured']"
        :options="featuredCarouselOptions"
        :show-heading="false"
        :full-width="true"
      >
        <template #heading>
          <span
            tabindex="0"
            role="heading"
            aria-level="2"
          >Featured videos</span>
        </template>
        <FeaturedCarouselSlide
          v-for="video in featured"
          :key="video.id"
          :item="video"
        />
      </Carousel>

      <div class="carousels">
        <template v-for="({id, label, count, hits}, idx) in videos">
          <div
            v-if="idx === 3"
            :key="`${id}-search`"
            class="inline-block--search"
          >
            <div class="background--grate">
              <SearchBar />
            </div>
          </div>
          <div
            :key="id"
            v-view="viewHandler"
            :data-section-id="id"
          >
            <Carousel
              :id="id"
              :controls="true"
              :title="label"
              :options="{ groupCells, contain: true }"
            >
              <template #heading>
                <RouterLink
                  :aria-label="`A selection of videos from on topic: ${label}`"
                  :to="{name: 'search', query: {topics: label}}"
                >
                  {{ label }}
                </RouterLink>
              </template>
              <CarouselSlide
                v-for="video in hits"
                :key="video.id"
                :item="video"
              />
              <div class="carousel__slide see-more">
                <router-link
                  class="ui-card"
                  :to="{name: 'search', query: {topics: label}}"
                >
                  <div class="ui-card__thumbnail">
                    <div class="ui-card__thumbnail-image">
                      <span class="see-more__link">
                        {{ seeAllLinkText(count, label) }}
                      </span>
                    </div>
                  </div>
                </router-link>
              </div>
            </Carousel>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import vueWindowSizeMixin from 'vue-window-size';
import Carousel from './Carousel.vue';
import CarouselSlide from './CarouselSlide.vue';
import FeaturedCarouselSlide from './FeaturedCarouselSlide.vue';
import Loader from './Loader.vue';
import mixin from '../mixins/getRouteData';
import { store } from '../store';

export default {
  name: 'Home',
  components: {
    Carousel,
    CarouselSlide,
    FeaturedCarouselSlide,
    Loader,
  },
  filters: {
    filterId(value) {
      return value.replace(/[\s&]/gi, '').toLowerCase();
    },
  },
  mixins: [mixin, vueWindowSizeMixin],
  data() {
    return {
      currentSectionInView: null,
      featured: false,
      featuredCarouselOptions: { wrapAround: true, pageDots: true },
      groupCells: null,
      videos: null,
    };
  },
  computed: {
    copy() {
      return store.copy;
    },
  },
  mounted() {
    this.getFeatured();
    document.body.classList.add('front');
    // this.groupCells = this.windowWidth < 840 ? 1 : 2;
    const pageTitle = 'Video Archive | Hammer Museum';
    document.title = pageTitle;

    this.$gtm.trackEvent({
      event: 'virtualPageView',
      virtualPageURL: this.$route.fullPath,
      virtualPageTitle: document.title,
    });
  },
  destroyed() {
    document.body.classList.remove('front');
  },
  methods: {
    getFeatured() {
      // axios
      //   .get(`${process.env.MIX_DATASTORE_URL}playlists/Featured`)
      //   .then((response) => {
      //     this.featured = response.data.data.videos;
      //   }).catch((err) => {
      //     console.error(err);
      //   });

      const response =  {"success":true,"data":{"name":"Featured","videos":[{"title":"Saidiya Hartman & Arthur Jafa in conversation","thumbnailId":"22137bb7f924094d","asset_id":601,"title_slug":"saidiya-hartman-arthur-jafa-in-conversation","description":"<p>Saidiya Hartman joins filmmaker, artist, and award-winning cinematographer Arthur Jafa in discussion of her book Wayward Lives, Beautiful Experiments: Intimate Histories of Social Upheaval. The book delves into the lives of young black women in the early 20th century, exploring the forms of kinship and intimacy that created a revolution of black intimate life. In addition to creating acclaimed works such as the video essays The White Album and Love Is The Message, The Message Is Death, Jafa has worked with collaborators ranging from Spike Lee and Julie Dash to Kanye West and Solange.<\/p>","quote":"","topics":["Social Justice","Society & Culture"],"tags":["Artist Talk","black women","Writers","Sociology","U.S. History","migration","Wayward Lives","Beautiful Experiments: Intimate Histories of Social Upheaval","archives","black ghettos","20th century","radicalism","queer relations","Free love","carceral state","cultural movement"],"people":["Arthur Jafa","Saidiya Hartman"],"duration":"1:38:02","position":0,"links":{"self":{"href":"https:\/\/stage.datastore.hammer.cogapp.com\/api\/videos\/601"}}},{"title":"Chadwick Boseman, Lupita Nyong\u2019o & Angela Bassett on \"Black Panther\"","thumbnailId":"22137bb7c36b086b","asset_id":555,"title_slug":"chadwick-boseman-lupita-nyongo-angela-bassett-on-black-panther","description":"<p>A discussion with actors Lupita Nyong&rsquo;o, Angela Bassett, and Chadwick Boseman is moderated by Raj Roy, the Celeste Bartos Chief Curator of Film at The Museum of Modern Art. Following the tragic death of his father, crown prince T&rsquo;Challa (Chadwick Boseman) returns to the kingdom of Wakanda to take the throne. T&rsquo;Challa&rsquo;s plans to continue his father&rsquo;s legacy are disrupted by the arrival of a challenger, Erik Killmonger, who intends to reveal Wakanda&rsquo;s concealed technological utopia to the world and disrupt long-held peace. Fearlessly directed by Ryan Coogler (Fruitvale Station, Creed), Black Panther is the first Marvel film to feature a predominantly black cast, pointing to a new age of superhero films both behind and in front of the camera. The conversation was part of MoMA Contenders, The Museum of Modern Art&rsquo;s renowned series of influential, innovative films from the past 12 months. Whether bound for awards glory or cult classic status, each of these films is a contender for lasting historical significance.<\/p>","quote":"","topics":["Film & TV"],"tags":["actor discussion","africa","marvel","MoMA Contenders"],"people":["Angela Bassett","Chadwick Boseman","Lupita Nyong\u2019o","Raj Roy"],"duration":"0:39:36","position":1,"links":{"self":{"href":"https:\/\/stage.datastore.hammer.cogapp.com\/api\/videos\/555"}}},{"title":"Naomi Klein: the case for a Green New Deal","thumbnailId":"22137bb7f16b086b","asset_id":623,"title_slug":"naomi-klein-the-case-for-a-green-new-deal","description":"<p>Naomi Klein champions a sweeping environmental agenda with justice at its center. One of the foremost chroniclers of the economic war waged on both people and planet, Klein's book On Fire: The (Burning) Case for a Green New Deal pairs over a decade of Klein&rsquo;s impassioned writing with new material on our immediate political and economic choices. Klein argues that we will rise to the existential challenge of climate change only if we are willing to transform the systems that produced the crisis. She is joined in conversation by Aquilina Soriano Versoza, executive director of the Pilipino Worker&rsquo;s Center of Southern California.<\/p>","quote":"","topics":["Politics","Environment"],"tags":["US politics","Environment","Climate change","greenhouse gases","domestic workers","Intersectional","Green New Deal","Alexandria Ocasio-Cortez","a message from the future","Trump","Bolsonaro","carbon emissions"],"people":["Naomi Klein","Aquilina Soriano Versoza"],"duration":"1:37:09","position":2,"links":{"self":{"href":"https:\/\/stage.datastore.hammer.cogapp.com\/api\/videos\/623"}}},{"title":"Art history lecture: Van Gogh in Paris","thumbnailId":"22137bb7f9230b6b","asset_id":619,"title_slug":"art-history-lecture-van-gogh-in-paris","description":"<p>Van Gogh&rsquo;s career got a late, slow start in the Netherlands. But he soon became skilled enough at drawing and painting, and ambitious enough, to move to Paris. He arrived in the mid-1880s, when Impressionism was yielding to newer currents, and began painting in a distinctively colorful, forceful way. In this series of lectures at the Hammer, art historian, curator, and museum director John Walsh examines paintings by Vincent van Gogh on display in museums throughout Los Angeles. Considering them in artistic and historical context, Walsh illuminates how the works exemplify the artist&rsquo;s struggles and achievements.<\/p>","quote":"","topics":["Art"],"tags":["art history","drawings","paintings","portraits","Paris","self portraits","color","Anton Mauve"],"people":["John Walsh"],"duration":"1:06:03","position":3,"links":{"self":{"href":"https:\/\/stage.datastore.hammer.cogapp.com\/api\/videos\/619"}}}],"links":{"self":{"href":"https:\/\/stage.datastore.hammer.cogapp.com\/api\/playlists\/Featured"}}}}
      this.featured = response.data.videos
    },
    getPageData() {
      axios
        .get('/api')
        .then((response) => {
          this.content = response.data.videos;
        }).catch((err) => {
          console.error(err);
        });
    },
    seeAllLinkText(count, name) {
      const videos = count > 1 ? 'videos' : 'video';
      return `See all ${count} ${videos} tagged ${name}`;
    },
    viewHandler(e) {
      if (e.percentInView === 1 && e.percentTop < 0.9) {
        this.currentSectionInView = e.target.element.dataset.sectionId;
        this.$emit('update-current-section', this.currentSectionInView);
      }
    },
  },
};
</script>

<style>
.inline-block--search {
  background: #fff;
  margin-left: -8px;
}

.inline-block--search .search-bar {
  margin: 48px 0;
}
</style>
