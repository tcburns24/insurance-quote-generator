<template>
    <div>
        <h2>Posted Quotes</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>DOB</th>
                        <th>State</th>
                        <th>Smoker?</th>
                        <th>Gender</th>
                        <th>Term</th>
                        <th>Coverage Amt</th>
                        <th>Created On</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="quote in allQuotes" :key="quote.id">
                        <td>{{ quote.dob }}</td>
                        <td>{{ quote.state }}</td>
                        <td>{{ quote.smoker ? "🚬" : "🚭" }}</td>
                        <td>{{ quote.gender }}</td>
                        <td>{{ quote.term }}</td>
                        <td>{{ quote.coverage_amount }}</td>
                        <td>
                            {{ new Date(quote.created_at) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style lang="scss" scoped></style>

<script>
import axios from "axios";

export default {
    name: "PostedQuotes",
    data() {
        return {
            allQuotes: [],
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
};
</script>
