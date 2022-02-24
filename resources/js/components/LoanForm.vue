<script>
import moment, { months } from 'moment'

export default {
    props: {
        title: String,
        buttonTitle: String,
        loanAmount: String,
        loanTerm: String,
        interestRate: String,
        startDate: String
    },
    data() {
        return {
            loan: {
                loan_amount: this.loanAmount,
                loan_term: this.loanTerm,
                interest_rate: this.interestRate,
                start_month: moment(this.startDate).format('MMM') ?? "Jan",
                start_year: moment(this.startDate).format('YYYY') ?? "2022"
            },
            months: [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
                "Oct",
                "Nov",
                "Dec"
            ]
        }
    },
    computed: {
        years() {
            const year = new Date().getFullYear()
            return Array.from({ length: 29 }, (value, index) => 2022 + index)
        }
    },
    methods: {
        submitAction() {
            this.$emit("on-submit", this.loan)
        }
    }
}
</script>

<template>
  <div>
    <h1>{{ this.title }}</h1>
    <form @submit.prevent="submitAction">
      <div class="row mb-3">
        <label class="col-sm-2 col-form-label text-end">Loan Amount:</label>
        <div class="col-sm-3">
          <div class="input-group mb-3">
            <input type="number" class="form-control" v-model="loan.loan_amount" />
            <span class="input-group-text">฿</span>
          </div>
        </div>
      </div>
      <div class="row mb-3">
        <label class="col-sm-2 col-form-label text-end">Loan Term:</label>
        <div class="col-sm-3">
          <div class="input-group mb-3">
            <input type="number" class="form-control" v-model="loan.loan_term" />
            <span class="input-group-text">Years</span>
          </div>
        </div>
      </div>
      <div class="row mb-3">
        <label class="col-sm-2 col-form-label text-end">Interest Rate:</label>
        <div class="col-sm-3">
          <div class="input-group mb-3">
            <input type="number" class="form-control" v-model="loan.interest_rate" />
            <span class="input-group-text">%</span>
          </div>
        </div>
      </div>
      <div class="row mb-3">
        <label class="col-sm-2 col-form-label text-end">Start Date:</label>
        <div class="col-sm-3">
          <div class="input-group mb-3">
            <select name="start_month" class="form-select" v-model="loan.start_month">
                <option v-for="month in months" :value="month" :key="month">{{ month }}</option>
            </select>
            <select name="start-year" class="form-select" v-model="loan.start_year">
              <option v-for="year in years" :value="year" :key="year">{{ year }}</option>
            </select>
          </div>
        </div>
      </div>
      <div class="row mb-3">
        <label class="col-sm-2 col-form-label"></label>
        <div class="col-sm-3">
          <button type="submit" class="btn btn-primary">{{ buttonTitle }}</button>
          <router-link :to="{ name: 'home' }" type="button" class="btn btn-light">Back</router-link>
        </div>
      </div>
    </form>
  </div>    
</template>