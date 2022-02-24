<script>
import LoanForm from './LoanForm.vue'
import moment from 'moment'

export default {
    components: {
        LoanForm
    },
    methods: {
        async createLoan(loanForm) {
            try {
                const res = await this.axios
                                    .post(`/api/loans`, {
                                        loan_amount: Number(loanForm.loan_amount),
                                        loan_term: Number(loanForm.loan_term),
                                        interest_rate: Number(loanForm.interest_rate),
                                        start_at: moment(loanForm.start_month + " " + loanForm.start_year).format('')
                                    })

                this.$router.push({ name: "view", params: { id: res.data.data.id } })
            } catch (err) {
                if (err.response.data.errors) {
                        var errMsg = ""
                        for (const [_, value] of Object.entries(err.response.data.errors)) {
                            errMsg += value + "\n"
                        }
                        alert(errMsg)
                    } else {
                        alert("Can't create item");
                    }
            }
        }
    }
}

</script>

<template>
    <div>
        <LoanForm title="Create Loan" buttonTitle="Create" @on-submit="createLoan"></LoanForm>
    </div>
</template>