<template>
  <div class="p-6">
    <h2 class="text-2xl font-bold mb-4 text-gray-800">Posted Quotes</h2>

    <div class="overflow-x-auto">
      <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border-b">DOB</th>
            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border-b">State</th>
            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border-b">Smoker?</th>
            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border-b">Gender</th>
            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border-b">Term</th>
            <th
              class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border-b"
            >Coverage Amt</th>
            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border-b">Created On</th>
            <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700 border-b">Actions</th>
          </tr>
        </thead>
        <transition-group name="fade" tag="tbody">
          <tr
            v-for="quote in allQuotes"
            :key="quote.id"
            class="even:bg-gray-100 hover:bg-gray-200 transition-colors duration-150"
          >
            <td class="px-4 py-2 border-b">{{ quote.dob }}</td>
            <td class="px-4 py-2 border-b">{{ quote.state }}</td>
            <td class="px-4 py-2 border-b">{{ quote.smoker ? "🚬" : "🚭" }}</td>
            <td class="px-4 py-2 border-b">{{ quote.gender }}</td>
            <td class="px-4 py-2 border-b">{{ quote.term }}</td>
            <td class="px-4 py-2 border-b">${{ quote.coverage_amount.toLocaleString() }}</td>
            <td class="px-4 py-2 border-b">{{ new Date(quote.created_at).toLocaleDateString() }}</td>
            <td class="px-4 py-2 border-b">
              <button
                @click="deleteQuote(quote.id)"
                class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition"
              >Delete</button>
            </td>
          </tr>
        </transition-group>
      </table>
    </div>
    <div>
      <router-link to="/">
        <button class="text-blue-400 mx-3 my-3 font-large">Back to Quote Form</button>
      </router-link>
    </div>
  </div>
</template>


<style lang="scss" scoped>
.fade-enter-active,
.fade-leave-active {
  transition: all 0.5s ease;
}
.fade-leave-form {
  opacity: 1;
  transform: scale(1);
}
.fade-leave-to {
  opacity: 0;
  transform: scale(0.95);
}
</style>

<script>
import axios from "axios";

export default {
  name: "PostedQuotes",
  data() {
    return {
      allQuotes: []
    };
  },
  async mounted() {
    try {
      let response = await axios.get("/api/posted-quotes");
      this.allQuotes = response.data;
      console.log("fetched data = ", this.allQuotes);
    } catch (err) {
      console.log("uh oh!🆘 error = ", err);
    }
  },
  methods: {
    async deleteQuote(id) {
      try {
        let response = await axios.delete(`/api/quotes/${id}`);
        console.log("you just deleted quote ID ", id);
        this.allQuotes = this.allQuotes.filter(quotes => quotes.id !== id);
      } catch (err) {
        console.error("🚨 uh oh!");
      }
    }
  }
};
</script>
