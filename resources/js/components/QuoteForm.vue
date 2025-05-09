<template>
  <div class="container mx-auto p-8">
    <h1 class="text-2xl font-bold mb-6">Quote Generator</h1>
    <Loading :active="loading" :is-full-page="false" loader="bars" color="#32a852" />
    <form @submit.prevent="submitForm" class="border-solid border-red-400 rounded-md">
      <div class="form-row grid gap-6 mb-6 md:grid-cols-2">
        <div class="form-item">
          <label for="dob" class="block mb-2 text-sm font-medium text-gray-700">Date of Birth</label>
          <input type="date" id="dob" v-model="formData.dob" required />
        </div>
        <div class="form-item">
          <label for="state" class="block mb-2 text-sm font-medium text-gray-700">State</label>
          <select id="state" v-model="formData.state" required>
            <option value disabled selected>Select your state</option>
            <option v-for="state in states" :key="state.code" :value="state.code">{{ state.name }}</option>
          </select>
        </div>
      </div>
      <div class="form-row grid gap-6 mb-6 md:grid-cols-2">
        <div class="form-item">
          <label for="gender" class="block mb-2 text-sm font-medium text-gray-700">Gender</label>
          <select id="gender" v-model="formData.gender" required>
            <option value disabled selected>Select gender</option>
            <option value="M">Male</option>
            <option value="F">Female</option>
          </select>
        </div>
        <div class="form-item">
          <label class="block mb-2 text-sm font-medium text-gray-700">Smoker</label>
          <div class="flex gap-4">
            <div class="flex items-center">
              <input
                type="radio"
                id="smoker-yes"
                name="smoker"
                :value="true"
                v-model="formData.smoker"
              />
              <label for="smoker-yes" class="ml-2 text-sm font-medium text-gray-900">Yes</label>
            </div>
            <div class="flex items-center">
              <input
                type="radio"
                id="smoker-no"
                name="smoker"
                :value="false"
                v-model="formData.smoker"
              />
              <label for="smoker-no" class="ml-2 text-sm font-medium text-gray-900">I don't smoke</label>
            </div>
          </div>
        </div>
      </div>
      <div class="form-row grid gap-6 mb-6 md:grid-cols-2">
        <div class="form-item">
          <label for="term" class="block mb-2 text-sm font-medium text-gray-700">Term Length</label>
          <select id="term" v-model="formData.term" required>
            <option value disabled selected>--Select term length--</option>
            <option value="10">10 years</option>
            <option value="15">15 years</option>
            <option value="20">20 years</option>
            <option value="30">30 years</option>
          </select>
        </div>
        <div class="form-item">
          <label for="coverage" class="block mb-2 text-sm font-medium text-gray-700">Amount</label>
          <select id="coverage" v-model="formData.coverageAmount" required>
            <option value disabled selected>Select coverage amount</option>
            <option
              v-for="amount in [
                                100000,
                                250000,
                                500000,
                                750000,
                                1000000,
                            ]"
              :value="amount"
              :key="amount"
            >{{ format$(amount) }}</option>
          </select>
        </div>
      </div>
      <div class="flex justify-end">
        <button
          @click.prevent="resetForm"
          class="px-8 py-2 mr-2 bg-gray-600 hover:bg-gray-500 text-white rounded-lg"
        >Clear Form</button>
        <button
          type="submit"
          class="px-8 py-2 mr-2 bg-blue-500 hover:bg-blue-700 text-white rounded-lg"
        >{{ loading ? "Loading..." : "Generate Quotes" }}</button>
        <button
          @click.prevent="postQuote"
          class="px-8 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg"
        >Post Quote</button>
      </div>
      <div v-if="errors.length" class="errors-container">
        <p>Errors found:</p>
        <ul>
          <li v-for="(err, index) in errors" :key="index">{{ err }}</li>
        </ul>
      </div>
    </form>

    <div v-if="quoteResult">
      <div class="flex justify-between">
        <div class="text-lg font-bold py-4">
          {{
          !showAllQuotes
          ? "Your Result:"
          : allQuotes.length + " Results:"
          }}
        </div>
        <div class="toggle-wrap flex py-4">
          <span class="px-3 font-bold">
            Show
            {{
            showAllQuotes ? "Your Result" : "All Results"
            }}
          </span>
          <div
            class="toggle-container"
            :style="
                            showAllQuotes
                                ? 'background-color: #c3f7d1'
                                : 'background-color: #ccc'
                        "
            @click="toggle"
          >
            <div class="toggle-track">
              <div class="toggle-thumb" :class="{ 'toggle-on': showAllQuotes }"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="grid gap-4">
        <Quote
          v-for="(q, index) in showAllQuotes
                        ? displayedItems
                        : quoteResult"
          :quoteData="q"
          :key="index"
        />
      </div>
      <div class="flex justify-center mt-4 space-x-4" v-show="showAllQuotes">
        <button
          @click="prevPage"
          :disabled="currentPage === 1"
          class="px-4 py-2 bg-green-500 text-white rounded disabled:opacity-50"
        >Previous</button>

        <span class="self-center">Page {{ currentPage }} of {{ totalPages }}</span>

        <button
          @click="nextPage"
          :disabled="currentPage === totalPages"
          class="px-4 py-2 bg-green-500 text-white rounded disabled:opacity-50"
        >Next</button>
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.container {
  margin-top: 20px;
  border: 3px solid black;
  border-radius: 8px;
}
.form-item {
  padding: 8px;
  select {
    border: 1px solid #bdbdbd;
    border-radius: 10px;
    padding: 2px;
  }
}
.form-row {
  border: 1px solid grey;
  border-radius: 8px;
}
.toggle-container {
  width: 60px;
  height: 30px;
  background-color: #ccc;
  border-radius: 15px;
  cursor: pointer;
  position: relative;
}

.toggle-track {
  width: 100%;
  height: 100%;
  border-radius: 15px;
  position: absolute;
}

.toggle-thumb {
  width: 26px;
  height: 26px;
  background-color: #fff;
  border-radius: 50%;
  position: absolute;
  top: 2px;
  left: 2px;
  transition: transform 0.2s ease-in-out;
}

.toggle-on {
  transform: translateX(30px);
  background-color: #4caf50;
}
</style>

<script>
import { ref, reactive } from "vue";
import axios from "axios";
import Loading from "vue3-loading-overlay";
import "vue3-loading-overlay/dist/vue3-loading-overlay.css";
import Quote from "./Quote.vue";

export default {
  components: {
    Quote: Quote,
    Loading: Loading
  },
  data() {
    return {
      formData: {
        dob: "1987-09-03",
        state: "CT",
        smoker: "false",
        gender: "M",
        term: 20,
        coverageAmount: "500000",
        productId: 1,
        riskClass: 1
      },
      itemsPerPage: 10,
      currentPage: 1,
      loading: false,
      errors: [],
      quoteResult: null,
      allQuotes: null,
      showAllQuotes: false,
      states: [
        { code: "AL", name: "Alabama" },
        { code: "AK", name: "Alaska" },
        { code: "AZ", name: "Arizona" },
        { code: "AR", name: "Arkansas" },
        { code: "CA", name: "California" },
        { code: "CO", name: "Colorado" },
        { code: "CT", name: "Connecticut" },
        { code: "DE", name: "Delaware" },
        { code: "FL", name: "Florida" },
        { code: "GA", name: "Georgia" },
        { code: "HI", name: "Hawaii" },
        { code: "ID", name: "Idaho" },
        { code: "IL", name: "Illinois" },
        { code: "IN", name: "Indiana" },
        { code: "IA", name: "Iowa" },
        { code: "KS", name: "Kansas" },
        { code: "KY", name: "Kentucky" },
        { code: "LA", name: "Louisiana" },
        { code: "ME", name: "Maine" },
        { code: "MD", name: "Maryland" },
        { code: "MA", name: "Massachusetts" },
        { code: "MI", name: "Michigan" },
        { code: "MN", name: "Minnesota" },
        { code: "MS", name: "Mississippi" },
        { code: "MO", name: "Missouri" },
        { code: "MT", name: "Montana" },
        { code: "NE", name: "Nebraska" },
        { code: "NV", name: "Nevada" },
        { code: "NH", name: "New Hampshire" },
        { code: "NJ", name: "New Jersey" },
        { code: "NM", name: "New Mexico" },
        { code: "NY", name: "New York" },
        { code: "NC", name: "North Carolina" },
        { code: "ND", name: "North Dakota" },
        { code: "OH", name: "Ohio" },
        { code: "OK", name: "Oklahoma" },
        { code: "OR", name: "Oregon" },
        { code: "PA", name: "Pennsylvania" },
        { code: "RI", name: "Rhode Island" },
        { code: "SC", name: "South Carolina" },
        { code: "SD", name: "South Dakota" },
        { code: "TN", name: "Tennessee" },
        { code: "TX", name: "Texas" },
        { code: "UT", name: "Utah" },
        { code: "VT", name: "Vermont" },
        { code: "VA", name: "Virginia" },
        { code: "WA", name: "Washington" },
        { code: "DC", name: "Washington D.C." },
        { code: "WV", name: "West Virginia" },
        { code: "WI", name: "Wisconsin" },
        { code: "WY", name: "Wyoming" }
      ]
    };
  },
  computed: {
    totalPages() {
      return Math.ceil(this.allQuotes.length / this.itemsPerPage);
    },
    displayedItems() {
      let startingIndex = (this.currentPage - 1) * this.itemsPerPage;
      let endingIndex = startingIndex + this.itemsPerPage;
      return this.allQuotes.slice(startingIndex, endingIndex);
    }
  },
  methods: {
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage -= 1;
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage += 1;
      }
    },
    toggle() {
      this.showAllQuotes = !this.showAllQuotes;
    },
    validateForm() {
      let errorsArr = [];

      if (!this.formData.dob) {
        errorsArr.push("Date of birth is reuqired");
      }

      if (!this.formData.state) {
        errorsArr.push("State is required");
      }

      if (this.formData.smoker === null) {
        errorsArr.push("Choose smoker or non-smoker");
      }

      if (!this.formData.gender) {
        errorsArr.push("Gender is required");
      }

      if (!this.formData.term) {
        errorsArr.push("Term length is required");
      }

      if (!this.formData.coverageAmount) {
        errorsArr.push("Coverage amount is required");
      }

      return errorsArr.length === 0;
    },
    format$(val) {
      return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "USD",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
      }).format(val);
    },
    async submitForm() {
      if (!this.validateForm()) {
        return;
      }
      this.loading = true;

      try {
        let queryParams = { ...this.formData };
        const response = await axios.get("http://localhost:8000/api/quotes", {
          params: queryParams
        });
        this.allQuotes = response.data.quotes[this.formData.term];
        this.quoteResult = this.allQuotes.filter(
          quote => quote.faceAmount == this.formData.coverageAmount
        );
        this.errors = [];
      } catch (err) {
        console.error("Error: ", err);
        this.errors.push("An error occurred, plaese try again.");
      } finally {
        this.loading = false;
      }
    },
    async postQuote() {
      console.log("🗒️ postQuote()");
      if (!this.validateForm()) {
        return;
      }
      this.loading = true;

      try {
        const respose = await axios.post("http://localhost:8000/api/quote", {
          dob: this.formData.dob,
          state: this.formData.state,
          smoker:
            this.formData.smoker === "true" || this.formData.smoker === true,
          gender: this.formData.gender,
          term: Number(this.formData.term),
          coverage_amount: Number(this.formData.coverageAmount)
        });

        console.log("POST successful:", response.data);
        this.successMessage = "Quote posted successfully!";
      } catch (err) {
        console.error("POST Error: ", err);
        this.errors.push("Uh oh! POST busted!");
      } finally {
        this.loading = false;
      }
    },
    resetForm() {
      this.quoteResult = null;
      this.errors = [];
      this.formData = {
        dob: "",
        state: "",
        smoker: null,
        gender: "",
        term: null,
        coverageAmount: "",
        productId: 1,
        riskClass: 1
      };
    }
  }
};
</script>
