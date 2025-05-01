<template>
    <div class="quote-container">
        <div class="quote-data">
            <div class="quote-side quote-left">
                <h2>Face Amount: {{ format$(quoteData.faceAmount) }}</h2>
                <p class="prodcode">{{ quoteData.productCode }}</p>
            </div>
            <div class="quote-side quote-right">
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
        </div>
    </div>
</template>

<style lang="scss" scoped>
.quote-container {
    border: 2px solid lightseagreen;
    border-radius: 14px;
    padding: 12px;
    .quote-data {
        display: flex;
        h2 {
            font-weight: 800;
        }
        .quote-side {
            flex: 1;
        }
    }
    .quote-left {
        .prodcode {
            color: #5c5c5c;
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
