<template>
    <div class="container mx-auto p-8 border-solid border-red-400 rounded-md">
        <h1 class="text-2xl font-bold mb-6">Quote Generator</h1>

        <form
            @submit.prevent="submitForm"
            class="border-solid border-red-400 rounded-md"
        >
            <div class="form-row grid gap-6 mb-6 md:grid-cols-2">
                <div class="form-item">
                    <label
                        for="dob"
                        class="block mb-2 text-sm font-medium text-gray-700"
                        >Date of Birth</label
                    >
                    <input
                        type="date"
                        id="dob"
                        v-model="formData.dob"
                        required
                    />
                </div>
                <div class="form-item">
                    <label
                        for="state"
                        class="block mb-2 text-sm font-medium text-gray-700"
                        >State</label
                    >
                    <select id="state" v-model="formData.state" required>
                        <option value disabled selected>
                            Select your state
                        </option>
                        <option
                            v-for="state in states"
                            :key="state.code"
                            :value="state.code"
                        >
                            {{ state.name }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="form-row grid gap-6 mb-6 md:grid-cols-2">
                <div class="form-item">
                    <label
                        for="gender"
                        class="block mb-2 text-sm font-medium text-gray-700"
                    >
                        Gender
                    </label>
                    <select id="gender" v-model="formData.gender" required>
                        <option value disabled selected>Select gender</option>
                        <option value="M">Male</option>
                        <option value="F">Female</option>
                    </select>
                </div>
                <div class="form-item">
                    <label class="block mb-2 text-sm font-medium text-gray-700"
                        >Smoker</label
                    >
                    <div class="flex gap-4">
                        <div class="flex items-center">
                            <input
                                type="radio"
                                id="smoker-yes"
                                name="smoker"
                                :value="true"
                                v-model="formData.smoker"
                            />
                            <label
                                for="smoker-yes"
                                class="ml-2 text-sm font-medium text-gray-900"
                                >Yes</label
                            >
                        </div>
                        <div class="flex items-center">
                            <input
                                type="radio"
                                id="smoker-no"
                                name="smoker"
                                :value="false"
                                v-model="formData.smoker"
                            />
                            <label
                                for="smoker-no"
                                class="ml-2 text-sm font-medium text-gray-900"
                                >No</label
                            >
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row grid gap-6 mb-6 md:grid-cols-2">
                <div class="form-item">
                    <label
                        for="term"
                        class="block mb-2 text-sm font-medium text-gray-700"
                        >Term Length</label
                    >
                    <select id="term" v-model="formData.term" required>
                        <option value disabled selected>
                            --Select term length--
                        </option>
                        <option value="10">10 years</option>
                        <option value="15">15 years</option>
                        <option value="20">20 years</option>
                        <option value="30">30 years</option>
                    </select>
                </div>
                <div class="form-item">
                    <label
                        for="coverage"
                        class="block mb-2 text-sm font-medium text-gray-700"
                        >Amount</label
                    >
                    <select
                        id="coverage"
                        v-model="formData.coverageAmount"
                        required
                    >
                        <option value disabled selected>
                            Select coverage amount
                        </option>
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
                        >
                            ${{ amount }}
                        </option>
                    </select>
                </div>
            </div>
            <div>
                <button
                    type="submit"
                    class="px-8 py-2 bg-blue-500 text-white rounded-lg"
                >
                    Generate Quote
                </button>
            </div>
            <div v-if="errors.length" class="errors-container">
                <p>Errors found:</p>
                <ul>
                    <li v-for="(err, index) in errors" :key="index">
                        {{ err }}
                    </li>
                </ul>
            </div>
        </form>

        <div v-if="loading">Loading...</div>

        <div v-else-if="quoteResult">
            <h2>Results:</h2>
            <div class="grid gap-4">
                <div v-for="(val, key) in quoteResult" :key="key">
                    <span>{{ key }}: </span>
                    <span>{{ val }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.form-item {
    padding: 8px;
}
.form-row {
    border: 1px solid grey;
    border-radius: 8px;
}
</style>

<script>
import { ref, reactive } from "vue";
import axios from "axios";

export default {
    data() {
        return {
            formData: {
                dob: "1987-09-03",
                state: "CT",
                smoker: "false",
                gender: "M",
                term: 15,
                coverageAmount: "250000",
                productId: 1,
                riskClass: 1,
            },
            loading: false,
            errors: [],
            quoteResult: null,
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
                { code: "WV", name: "West Virginia" },
                { code: "WI", name: "Wisconsin" },
                { code: "WY", name: "Wyoming" },
                { code: "DC", name: "Washington D.C." },
            ],
        };
    },
    methods: {
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
        async submitForm() {
            if (!this.validateForm()) {
                return;
            }
            this.loading = true;

            try {
                let queryParams = { ...this.formData };
                const response = await axios.get(
                    "http://localhost:8000/api/quotes",
                    {
                        params: queryParams,
                    }
                );
                this.quoteResult = response.data;
            } catch (err) {
                console.error("Error: ", err);
                this.errors.push("An error occurred, plaese try again.");
            } finally {
                this.loading = false;
            }
        },
        resetForm() {
            this.quoteResult = null;
            this.errors = [];
        },
    },
};
</script>
