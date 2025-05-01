<template>
    <div class="quote-container">
        <div class="quote-data flex justify-between">
            <div class="basis-2/5 quote-left">
                <h2>Face Amount: {{ format$(quoteData.faceAmount) }}</h2>
                <p class="prodcode">{{ quoteData.productCode }}</p>
            </div>
            <div class="basis-2/5">
                <h2 class="underline">Premiums</h2>
                <div class="premium">
                    Monthly: {{ format$(quoteData.monthlyPremium) }}
                </div>
                <div class="premium">
                    Quarterly: {{ format$(quoteData.quarterlyPremium) }}
                </div>
                <div class="premium">
                    Semi Annual: {{ format$(quoteData.semiannuallyPremium) }}
                </div>
                <div class="premium">
                    Annual: {{ format$(quoteData.annuallyPremium) }}
                </div>
            </div>
            <div class="basis-1/5">
                <button
                    @click="showModal = true"
                    class="px-7 py-2 bg-green-200 hover:bg-green-300 rounded-lg"
                >
                    View
                </button>
            </div>
        </div>
        <div
            v-if="showModal"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        >
            <div class="bg-white p-8 rounded-lg max-w-md w-full relative">
                <h2 class="text-xl font-bold mb-4">Quote Details</h2>

                <ul class="text-sm text-gray-700">
                    <li>
                        <strong>Face Amount:</strong>
                        {{ format$(quoteData.faceAmount) }}
                    </li>
                    <li>
                        <strong>Product Code:</strong>
                        {{ quoteData.productCode }}
                    </li>
                    <li>
                        <strong>Monthly Premium:</strong>
                        {{ format$(quoteData.monthlyPremium) }}
                    </li>
                    <li>
                        <strong>Quarterly Premium:</strong>
                        {{ format$(quoteData.quarterlyPremium) }}
                    </li>
                    <li>
                        <strong>Semiannual Premium:</strong>
                        {{ format$(quoteData.semiannuallyPremium) }}
                    </li>
                    <li>
                        <strong>Annual Premium:</strong>
                        {{ format$(quoteData.annuallyPremium) }}
                    </li>
                </ul>

                <button
                    @click="showModal = false"
                    class="mt-6 w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg"
                >
                    Close
                </button>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.quote-container {
    border: 2px solid lightseagreen;
    border-radius: 14px;
    padding: 12px;
    .quote-data {
        h2 {
            font-weight: 800;
        }
        .quote-side {
            flex: 1;
        }
    }
    .quote-left {
        .prodcode {
            color: #949494;
        }
    }
}
</style>

<script>
export default {
    name: "Quote",
    props: {
        quoteData: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            showModal: false,
        };
    },
    methods: {
        format$(val) {
            return new Intl.NumberFormat("en-US", {
                style: "currency",
                currency: "USD",
            }).format(val);
        },
    },
};
</script>
