<template>
  <div class="container mx-auto p-8">
    <h1 class="text-2xl font-bold mb-6">Insurance Quote Calculator</h1>

    <div v-if="loading" class="text-center">
      <p class="text-lg">Loading your quote...</p>
    </div>

    <div v-else-if="quoteResult" class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
      <h2 class="text-xl font-semibold mb-4">Your Quote Results</h2>
      <div class="grid gap-4">
        <div v-for="(value, key) in quoteResult" :key="key" class="flex">
          <span class="font-medium w-1/3">{{ formatLabel(key) }}:</span>
          <span>{{ formatValue(key, value) }}</span>
        </div>
      </div>
      <button
        @click="resetForm"
        class="mt-6 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded"
      >Get Another Quote</button>
    </div>

    <form
      v-else
      @submit.prevent="submitForm"
      class="bg-gray-50 border border-gray-200 rounded-lg p-6"
    >
      <div class="grid gap-6 mb-6 md:grid-cols-2">
        <div>
          <label for="dob" class="block mb-2 text-sm font-medium text-gray-700">Date of Birth</label>
          <input
            type="date"
            id="dob"
            v-model="formData.date_of_birth"
            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
            required
          />
        </div>

        <div>
          <label for="state" class="block mb-2 text-sm font-medium text-gray-700">State</label>
          <select
            id="state"
            v-model="formData.state"
            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
            required
          >
            <option value disabled selected>Select your state</option>
            <option v-for="state in states" :key="state.code" :value="state.code">{{ state.name }}</option>
          </select>
        </div>

        <div>
          <label for="gender" class="block mb-2 text-sm font-medium text-gray-700">Gender</label>
          <select
            id="gender"
            v-model="formData.gender"
            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
            required
          >
            <option value disabled selected>Select your gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
          </select>
        </div>

        <div>
          <label class="block mb-2 text-sm font-medium text-gray-700">Smoker</label>
          <div class="flex gap-4">
            <div class="flex items-center">
              <input
                type="radio"
                id="smoker-yes"
                name="smoker"
                :value="true"
                v-model="formData.smoker"
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500"
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
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500"
              />
              <label for="smoker-no" class="ml-2 text-sm font-medium text-gray-900">No</label>
            </div>
          </div>
        </div>

        <div>
          <label for="term" class="block mb-2 text-sm font-medium text-gray-700">Term (years)</label>
          <select
            id="term"
            v-model="formData.term"
            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
            required
          >
            <option value disabled selected>Select term length</option>
            <option value="10">10 years</option>
            <option value="15">15 years</option>
            <option value="20">20 years</option>
            <option value="30">30 years</option>
          </select>
        </div>

        <div>
          <label
            for="coverage"
            class="block mb-2 text-sm font-medium text-gray-700"
          >Coverage Amount ($)</label>
          <select
            id="coverage"
            v-model="formData.coverage_amount"
            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
            required
          >
            <option value disabled selected>Select coverage amount</option>
            <option
              v-for="amount in coverageAmounts"
              :key="amount"
              :value="amount"
            >{{ formatCurrency(amount) }}</option>
          </select>
        </div>
      </div>

      <div class="flex items-center justify-center">
        <button
          type="submit"
          class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-6 rounded-lg text-lg"
        >Get Quote</button>
      </div>

      <div
        v-if="errors.length"
        class="mt-6 bg-red-50 border border-red-200 text-red-800 rounded-lg p-4"
      >
        <p class="font-semibold mb-2">Please fix the following errors:</p>
        <ul class="list-disc pl-5">
          <li v-for="(error, index) in errors" :key="index">{{ error }}</li>
        </ul>
      </div>
    </form>
  </div>
</template>

<script>
import { ref, reactive } from "vue";
import axios from "axios";

export default {
  setup() {
    const formData = reactive({
      date_of_birth: "",
      state: "",
      smoker: null,
      gender: "",
      term: "",
      coverage_amount: ""
    });

    const quoteResult = ref(null);
    const loading = ref(false);
    const errors = ref([]);

    const states = [
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
      { code: "WV", name: "West Virginia" },
      { code: "WI", name: "Wisconsin" },
      { code: "WY", name: "Wyoming" },
      { code: "DC", name: "District of Columbia" }
    ];

    const coverageAmounts = [100000, 250000, 500000, 750000, 1000000];

    const validateForm = () => {
      errors.value = [];

      if (!formData.date_of_birth) {
        errors.value.push("Date of birth is required");
      }

      if (!formData.state) {
        errors.value.push("State is required");
      }

      if (formData.smoker === null) {
        errors.value.push("Please indicate whether you are a smoker");
      }

      if (!formData.gender) {
        errors.value.push("Gender is required");
      }

      if (!formData.term) {
        errors.value.push("Term length is required");
      }

      if (!formData.coverage_amount) {
        errors.value.push("Coverage amount is required");
      }

      return errors.value.length === 0;
    };

    const submitForm = async () => {
      if (!validateForm()) {
        return;
      }

      loading.value = true;

      try {
        const response = await axios.post("/api/quotes", formData);
        quoteResult.value = response.data;
      } catch (error) {
        console.error("Error fetching quote:", error);
        errors.value = [
          "An error occurred while fetching your quote. Please try again."
        ];
      } finally {
        loading.value = false;
      }
    };

    const resetForm = () => {
      quoteResult.value = null;
      errors.value = [];
    };

    const formatCurrency = amount => {
      return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "USD",
        maximumFractionDigits: 0
      }).format(amount);
    };

    const formatValue = (key, value) => {
      if (
        key === "monthly_premium" ||
        key === "annual_premium" ||
        key === "coverage_amount"
      ) {
        return formatCurrency(value);
      } else if (key === "smoker") {
        return value ? "Yes" : "No";
      } else {
        return value;
      }
    };

    const formatLabel = key => {
      return key
        .split("_")
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(" ");
    };

    return {
      formData,
      quoteResult,
      loading,
      errors,
      states,
      coverageAmounts,
      submitForm,
      resetForm,
      formatCurrency,
      formatValue,
      formatLabel
    };
  }
};
</script>
