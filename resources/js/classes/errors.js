class Errors {
    constructor() {
        this.errors = {};
    }

    get(field) {
        if(this.errors[field]) {
            return this.errors[field][0];
        }
    }

    has(field) {
        return this.errors.hasOwnProperty(field);
    }

    any(field) {
        return Object.keys(this.errors).length > 0;
    }

    record(errors) {
        this.errors = errors;
    }

    clear(field) {
        if(this.any()) this.errors = {};
    }
}

export default Errors;