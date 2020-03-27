export default {
    data() {
        return {
            form: {},
            formUrl: '',
            formErrors: {},
            submitting: false
        }
    },
    methods: {
        initializeForm(url) {
            this.formUrl = url
        },
        submitForm() {
            this.submitting = true

            return axios
                .post(this.formUrl)
                .then(({ data }) => {
                    return data
                })
                .catch(({ response }) => {
                    if (response.status === 422) {
                        this.formErrors = response.data.errors
                    }

                    failure(response)
                })
                .finally(() => {
                    this.submitting = false
                })
        }
    }
}
