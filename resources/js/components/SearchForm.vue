<script>
export default {
    emits: ['searchSubmit'],
    data() {
        return {
            search: {
                loanAmount: {},
                loanTerm: {},
                interestRate: {}
            }
        }
    }
}
</script>

<template>
    <div>
        <form @submit.prevent="$emit('searchSubmit', search)">
            <div class="row">
                 <label class="col-sm-2 col-form-label">Loan Amount(THB)</label>
            </div>

            <div class="row mb-2">
                <div class="col-sm-5">
                    <div class="input-group">
                        <label class="col-auto col-form-label">
                            Min:
                        </label>
                        <div class="col-sm-3 ms-1 me-2">
                            <input
                                type="number"
                                class="form-control"
                                placeholder="10000"
                                v-model="search.loanAmount.min"
                            />
                        </div>

                        <label class="col-auto col-form-label">
                            Max:
                        </label>
                        <div class="col-sm-3 ms-1 me-2">
                            <input
                                type="number"
                                class="form-control"
                                placeholder="100000000"
                                v-model="search.loanAmount.max"
                            />
                        </div>                        
                    </div>
                </div>
            </div>

            <div class="row">
                 <label class="col-sm-2 col-form-label">Loan Term(Year)</label>
            </div>            

            <div class="row mb-2">
                <div class="col-sm-5">
                    <div class="input-group">
                        <label class="col-auto col-form-label">
                            Min:
                        </label>
                        <div class="col-sm-3 ms-1 me-2">
                            <input
                                type="number"
                                class="form-control"
                                placeholder="1"
                                v-model="search.loanTerm.min"
                            />
                        </div>

                        <label class="col-auto col-form-label">
                            Max:
                        </label>
                        <div class="col-sm-3 ms-1 me-2">
                            <input
                                type="number"
                                class="form-control"
                                placeholder="50"
                                v-model="search.loanTerm.max"
                            />
                        </div>                        
                    </div>
                </div>
            </div>

            <div class="row">
                 <label class="col-sm-2 col-form-label">Interest Rate(%)</label>
            </div>            

            <div class="row mb-2">
                <div class="col-sm-5">
                    <div class="input-group">
                        <label class="col-auto col-form-label">
                            Min:
                        </label>
                        <div class="col-sm-3 ms-1 me-2">
                            <input
                                type="number"
                                class="form-control"
                                placeholder="1"
                                v-model="search.interestRate.min"
                            />
                        </div>

                        <label class="col-auto col-form-label">
                            Max:
                        </label>
                        <div class="col-sm-3 ms-1 me-2">
                            <input
                                type="number"
                                class="form-control"
                                placeholder="36"
                                v-model="search.interestRate.max"
                            />
                        </div>                        
                    </div>
                </div>
            </div>                      

            <div class="row mb-3">
              <div class="col-sm-3">
                <button type="submit" class="btn btn-secondary">Search</button>
              </div>
            </div>
        </form>
    </div>
</template>