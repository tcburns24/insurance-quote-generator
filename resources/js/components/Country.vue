<template>
  <div class="country-container" v-if="country && country.name">
    <h2>{{ country.name.common || 'banana' }}</h2>
    <p>population: {{ country.population.toLocaleString() }}</p>
    <img :src="country.flags.svg" :alt="country.name.common" width="250" />
    <div class="spots-by-city">
      <h3>Spots</h3>
      <div class="spot" v-for="spot in spots" :key="spot.id">{{ spot.name }} | {{ spot.city.name }}</div>
    </div>
    <button @click="toggleModal" class="btn">Add New Spot</button>
    <div class="spot-modal">
      <div v-if="showModal" class="modal-overlay">
        <div class="modal-content">
          <h2>Add New Spot</h2>

          <form @submit.prevent="submitSpot">
            <label>
              Name:
              <input v-model="form.name" required />
            </label>

            <label>
              Description:
              <textarea v-model="form.description" required></textarea>
            </label>

            <label>
              Address:
              <input v-model="form.address" required />
            </label>

            <label>
              City:
              <input v-model="form.city" required />
            </label>

            <label>
              Category:
              <select v-model="form.category" required @change="updateSubcategories">
                <option disabled value>Select Category</option>
                <option value="food & drink">Food & Drink</option>
                <option value="shopping">Shopping</option>
                <option value="educational">Educational</option>
                <option value="entertainment">Entertainment</option>
                <option value="other">Other</option>
              </select>
            </label>

            <label v-if="form.category">
              Sub-category:
              <select v-model="form.sub_category" required>
                <option
                  v-for="option in subCategories[form.category]"
                  :key="option"
                  :value="option"
                >{{ option }}</option>
              </select>
            </label>

            <label>
              Date Visited:
              <input type="date" v-model="form.date_visited" required />
            </label>

            <button type="submit">Submit</button>
            <button type="button" @click="showModal = false">Cancel</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  padding: 2rem;
  border-radius: 10px;
  width: 400px;
  max-width: 90%;
}

label {
  display: block;
  margin-bottom: 1rem;
}

input,
textarea,
select {
  width: 100%;
  margin-top: 0.3rem;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 5px;
}
</style>


<script>
import axios from "axios";
export default {
  name: "Country",
  props: {
    name: { type: String, required: true }
  },
  data() {
    return {
      loading: true,
      country: null,
      showModal: false,
      citiesWithSpots: [],
      spots: [],
      form: {
        name: "",
        description: "",
        address: "",
        city: "",
        category: "",
        sub_category: "",
        date_visited: ""
      },
      subCategories: {
        "food & drink": ["bar", "restaurant", "street food"],
        shopping: ["indoor mall", "outdoor mall", "store", "street fair"],
        educational: ["museum", "gallery", "course"],
        entertainment: ["theater", "movie", "acrobatics", "magic", "other"],
        other: ["other"]
      }
    };
  },
  async mounted() {
    const endpoints = [
      `https://restcountries.com/v3.1/name/${this.name}?fullText=true`,
      `/api/spots/by-country?country=${encodeURIComponent(this.name)}`
    ];

    try {
      const responses = await axios.all(
        endpoints.map(endpoint => axios.get(endpoint))
      );

      this.country = responses[0].data[0];
      this.spots = responses[1].data;
    } catch (err) {
      this.err = "Error fetching data: " + err.message;
    } finally {
      this.loading = false;
    }
  },
  methods: {
    toggleModal() {
      this.showModal = !this.showModal;
    },
    async addNewSpot(data) {
      try {
        await axios.post("/api/spots", this.form);
        this.showModal = false;
      } catch (err) {
        console.error("ah crap 💩 ", err);
      }
    }
  },
  computed: {}
};
</script>
