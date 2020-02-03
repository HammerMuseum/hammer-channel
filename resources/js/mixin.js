import axios from 'axios';

const getData = function (to) {
  return new Promise((resolve, reject) => {
    const initialState = JSON.parse(window._INITIAL_STATE_) || {};
    if (!initialState.path || to.path !== initialState.path) {
        axios.get(`${to.path}`).then(({ data }) => {
            resolve(data);
        });
    } else {
        resolve(initialState);
    }
  });
};

export default {
  beforeRouteEnter(to, from, next) {
    getData(to).then((data) => {
      next(vm => Object.assign(vm.$data, data));
    });
  },
};
