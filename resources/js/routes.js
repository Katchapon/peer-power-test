import ExampleComponent from './components/ExampleComponent.vue';
import LoansMainComponent from './components/LoansMainComponent.vue';
import LoanDetailsComponent from './components/LoanDetailsComponent.vue';
import CreateLoanComponent from './components/CreateLoanComponent.vue';
import UpdateLoanComponent from './components/UpdateLoanComponent.vue';

export const routes = [
    {
        name: 'home',
        path: '/',
        component: LoansMainComponent
    },
    {
        name: 'view',
        path: '/loans/:id',
        component: LoanDetailsComponent
    },
    {
        name: 'create',
        path: '/loans/create',
        component: CreateLoanComponent
    },
    {
        name: 'edit',
        path: '/loans/:id/edit',
        component: UpdateLoanComponent
    }
];