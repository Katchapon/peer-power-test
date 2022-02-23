<script>
    import LoanItem from './LoanItem.vue'

    export default {
        components: {
            LoanItem
        },
        data() {
            return {
                loans: []
            }
        },
        created() {
            this.axios
                .get(`http://localhost/api/loans/`)
                .then(response => {
                    console.log(response.data.data)
                    this.loans = response.data.data
                })
        },
        methods: {
            deleteLoan(id) {
                if (confirm(`Do you really want to delete Loan ID: ${id}?`)) {
                    this.axios
                        .delete(`http://localhost/api/loans/${id}`)
                        .then(response => {
                            let i = this.loans.map(data => data.id).indexOf(id);
                            this.loans.splice(i, 1);
                            alert(`Deleting Loan ID: ${id}`)
                        })
                        .catch(err => {
                            if (err.response.data.errors) {
                                var errMsg = ""
                                for (const [_, value] of Object.entries(err.response.data.errors)) {
                                    errMsg += value + "\n"
                                }
                                alert(errMsg)
                            } else {
                                alert("Can't delete item");
                            }
                        })
                        .finally(() => (this.loading = false));                        
                }
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
            <div class="card card-body">
                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.
            </div>
        </div>  
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Loan Amount</th>
                    <th>Loan Term</th>
                    <th>Interest Rate</th>
                    <th>Created at</th>
                    <th>Edit</th>
                </tr>
            </thead>
            <tbody v-if="loans.length">
                <LoanItem
                 v-for="loan in loans"
                 :loan="loan"
                 :key="loan.id"
                 @delete-loan="deleteLoan(loan.id)">
                </LoanItem>
            </tbody>
            <tbody v-else>
                <p>Loan is empty.</p>
            </tbody>
        </table>
    </div>
</template>
