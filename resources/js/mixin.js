import axios from 'axios';

let getData = function(to) {
    console.log('Running getData');
    return new Promise((resolve, reject) => {
        let initialState = JSON.parse(window.__INITIAL_STATE__) || {};
        if (!initialState.path || to.path !== initialState.path) {
            axios.get(`${to.path}`).then(({ data }) => {
                resolve(data);
            })
        } else {
            resolve(initialState);
        }
    });
};

export default {
    mounted() {
      console.log('Mixin loaded');
    },
    beforeRouteEnter (to, from, next) {
        getData(to).then((data) => {
            next(vm => Object.assign(vm.$data, data))
        });
    }
};
