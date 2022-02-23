<script>
import LoanForm from './LoanForm.vue'
import moment from 'moment'

export default {
    components: {
        LoanForm
    },
    data() {
        return {
            loan: {},
            alreadyGetData: false
        }
    },
    mounted() {
            this.axios
                .get(`http://localhost/api/loans/${this.$route.params.id}`)
                .then(response => {
                    this.loan = response.data.data,
                    this.alreadyGetData = true
                })
    },
    methods: {
        editLoan(loanForm) {
            this.axios
                .put(`/api/loans/${this.loan.id}`, {
                    loan_amount: Number(loanForm.loan_amount),
                    loan_term: Number(loanForm.loan_term),
                    interest_rate: Number(loanForm.interest_rate),
                    start_at: moment(loanForm.start_month + " " + loanForm.start_year).format('')
                })
                .then(response => {
                    this.$router.push({ name: "view", params: { id: response.data.data.id } })
                })
                .catch(err => {
                    if (err.response.data.errors) {
                        var errMsg = ""
                        for (const [_, value] of Object.entries(err.response.data.errors)) {
                            errMsg += value + "\n"
                        }
                        alert(errMsg)
                    } else {
                        alert("Can't update item");
                    }
                })
                .finally(() => (this.loading = false));
        }
    }
}
</script>

<template>
    <div>
        <LoanForm
         v-if="alreadyGetData"
         title="Edit Loan"
         buttonTitle="Update"
         :loanAmount="loan.loan_amount"
         :loanTerm="loan.loan_term / 12"
         :interestRate="loan.interest_rate * 100"
         :startDate="loan.start_at"
         @on-submit="editLoan"
        >
        </LoanForm>
    </div>
</template>