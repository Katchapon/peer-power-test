<script>
    import moment from 'moment'

    export default {
        data() {
            return {
                loan: {}
            }
        },
        created() {
            this.axios
                .get(`http://localhost/api/loans/${this.$route.params.id}`)
                .then(response => {
                    this.loan = response.data.data
                })
        },

        methods: {
            formateDecimalNumber(num) {
                return Number(parseFloat(num).toFixed(2)).toLocaleString(undefined, {minimumFractionDigits: 2})
            },
            formateLoanTerm(num) {
                return parseInt(num) / 12
            },
            formateInterestRate(num) {
                return (Number(parseFloat(num).toFixed(2)) * 100).toLocaleString(undefined, {minimumFractionDigits: 2})
            },
            formatePaidDate(date) {
                return moment(date).format('MMM YYYY')
            }
        }
    }
</script>

<template>
    <div>
        <h1>Loan Details</h1>
        <table class="table">
            <tr>
            <th>ID:</th>
            <td>{{ this.loan.id }}</td>
            </tr>
            <tr>
            <th>Loan Amount:</th>
            <td>{{ formateDecimalNumber(this.loan.loan_amount) }}</td>
            </tr>
            <tr>
            <th>Loan Term:</th>
            <td>{{ formateLoanTerm(this.loan.loan_term) }} Years</td>
            </tr>
            <tr>
            <th>Interest Rate:</th>
            <td>{{ formateInterestRate(this.loan.interest_rate) }}%</td>
            </tr>
            <tr>
            <th>Created at:</th>
            <td>{{ this.loan.created_at }}</td>
            </tr>
        </table>

        <router-link :to="{name: 'home'}" type="button" class="btn btn-light">Back</router-link>

         <h1>Repayment Schedule</h1>
        <table class="table table-striped">
        <thead>
            <tr>
            <th scope="col">Payment No.</th>
            <th scope="col">Date</th>
            <th scope="col">Payment Amount</th>
            <th scope="col">Principal</th>
            <th scope="col">Interest</th>
            <th scope="col">Balance</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="repay in loan.repayment_schedules" :key="repay.id">
            <th scope="row">{{ repay.payment_no }}</th>
            <td>{{ formatePaidDate(repay.date) }}</td>
            <td>{{ formateDecimalNumber(repay.payment_amount) }}</td>
            <td>{{ formateDecimalNumber(repay.principal) }}</td>
            <td>{{ formateDecimalNumber(repay.interest) }}</td>
            <td>{{ formateDecimalNumber(repay.balance) }}</td>
            </tr>
        </tbody>
        </table>
    </div>
</template>