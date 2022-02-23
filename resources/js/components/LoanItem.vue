<script>
    export default {
        props: {
            loan: Object
        },
        emits: ['delete-loan']
    }
</script>

<template>
    <tr>
    <td>{{ loan.id }}</td>
    <td>{{ Number(parseFloat(loan.loan_amount).toFixed(2)).toLocaleString() }}</td>
    <td>{{ parseInt(loan.loan_term ) / 12 }} Years</td>
    <td>{{ Number(parseFloat(loan.interest_rate).toFixed(2)) * 100 }}%</td>
    <td>{{ loan.created_at }}</td>
    <td>
        <div>
            <router-link :to="{name: 'view', params: { id: loan.id }}" class="btn btn-info">View</router-link>
            <router-link :to="{name: 'edit', params: { id: loan.id }}" class="btn btn-success">Edit</router-link>
            <button class="btn btn-danger" @click="$emit('delete-loan')">Delete</button>
        </div>
    </td>
    </tr>
</template>
