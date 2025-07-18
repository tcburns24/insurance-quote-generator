<template>
  <div>
    <div>
      <h2>Explore by continent</h2>
    </div>
    <div class="accordion europe">
      <CAccordion v-for="region in regions" :key="region">
        <CAccordionItem>
          <CAccordionHeader>{{ region.name }}</CAccordionHeader>
          <CAccordionBody>
            <ul>
              <li v-for="country in region.countries" :key="country">
                <router-link :to="`/country/${country.name.common}`">
                  <span>{{ country.flag }} {{ country.name.common }}</span>
                </router-link>
              </li>
            </ul>
          </CAccordionBody>
        </CAccordionItem>
      </CAccordion>
    </div>
  </div>
</template>

<style lang="scss" scoped>
</style>

<script>
import {
  CAccordion,
  CAccordionItem,
  CAccordionHeader,
  CAccordionBody
} from "@coreui/vue";
import axios from "axios";
export default {
  components: {
    CAccordion,
    CAccordionItem,
    CAccordionHeader,
    CAccordionBody
  },
  data() {
    return {
      regions: [
        { name: "europe", countries: [] },
        { name: "asia", countries: [] },
        { name: "americas", countries: [] },
        { name: "africa", countries: [] },
        { name: "oceania", countries: [] }
      ],
      europe: []
    };
  },
  mounted() {
    for (let i = 0; i < this.regions.length; i++) {
      axios
        .get(`https://restcountries.com/v3.1/region/${this.regions[i].name}`)
        .then(response => {
          this.regions[i].countries = response.data;
        })
        .catch(err => {
          (this.err = "Error with getAllCountries: "), err.message;
        });
    }
  }
};
</script>
