<script>
import LoanItem from './LoanItem.vue'

export default { 
    components: {
        LoanItem
    },
    props: {
        loans: []
    },
    emit: ['delete-loan']
}
</script>

<template>
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
            @delete-loan="$emit('delete-loan', loan.id)">
            </LoanItem>
        </tbody>
        <tbody v-else>
            <p>Loan is empty.</p>
        </tbody>
    </table>    
</template>