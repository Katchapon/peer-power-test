<script>
    import LoanItem from './LoanItem.vue'
    import SearchForm from './SearchForm.vue'
    import LoanTable from './LoanTable.vue'

    export default {
        components: {
            LoanItem,
            SearchForm,
            LoanTable
        },
        data() {
            return {
                loans: []
            }
        },
        created() {
            this.getLoans()
        },
        mounted() {
            // this.getLoans()
        },
        methods: {
            async getLoans() {
                try {
                    const res = await this.axios.get(`http://localhost/api/loans/`)

                    this.loans = res.data.data
                } catch (err) {
                    alert('Error! Could not reach the API.')
                }
            },
            async deleteLoan(id) {
                if (confirm(`Do you really want to delete Loan ID: ${id}?`)) {
                    try {
                        const res = await this.axios
                                              .delete(`http://localhost/api/loans/${id}`)

                        let i = this.loans.map(data => data.id).indexOf(id)
                        this.loans.splice(i, 1)
                        alert(`Deleting Loan ID: ${id}`)
                    } catch (err) {
                            if (err.response.data.errors) {
                                var errMsg = ""
                                for (const [_, value] of Object.entries(err.response.data.errors)) {
                                    errMsg += value + "\n"
                                }
                                alert(errMsg)
                            } else {
                                alert("Can't delete item")
                            }                        
                    }                      
                }
            },
            async searchLoan(searchForm) {
                try {
                    const res = await this.axios
                                            .get(`http://localhost/api/loans/`, {
                                                params: {
                                                    min_loan_amount: searchForm.loanAmount.min,
                                                    max_loan_amount: searchForm.loanAmount.max,
                                                    min_loan_term: searchForm.loanTerm.min,
                                                    max_loan_term: searchForm.loanTerm.max,
                                                    min_interest_rate: searchForm.interestRate.min,
                                                    max_interest_rate: searchForm.interestRate.max
                                                }
                                            })

                    this.loans = res.data.data
                } catch (err) {
                    alert("Can't search loans")
                }
                this.axios
                .get(`http://localhost/api/loans/`, {
                    params: {
                        min_loan_amount: searchForm.loanAmount.min,
                        max_loan_amount: searchForm.loanAmount.max,
                        min_loan_term: searchForm.loanTerm.min,
                        max_loan_term: searchForm.loanTerm.max,
                        min_interest_rate: searchForm.interestRate.min,
                        max_interest_rate: searchForm.interestRate.max
                    }
                })
                .then(response => {
                    this.loans = response.data.data
                })
            }
        }
    }
</script>

<template>
    <div>
        <h1>All Loan</h1>
        <div class="justify-content-between d-flex mb-3" role="toolbar" aria-label="Toolbar with button groups">
            <div class="btn-group" role="group" aria-label="First group">
                <router-link :to="{name: 'create'}" type="button" class="btn btn-primary">Add New Loan</router-link>
            </div>
            <div class="btn-group" role="group" aria-label="Second group">
                <button type="button" class="btn btn-secondary" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Advanced Search</button>
            </div>     
        </div>   
        <div class="collapse" id="collapseExample">
            <SearchForm @searchSubmit="searchLoan"></SearchForm>
        </div>
        <LoanTable
            :loans="loans"
            @delete-loan="deleteLoan"
        >
        </LoanTable>
    </div>
</template>
