import axios from 'axios';

const getData = function (to) {
  return new Promise((resolve) => {
    const initialState = JSON.parse(window.INITIAL_STATE) || {};
    if (!initialState.path || to.path !== initialState.path) {
      axios.get(`/api${to.path}`).then(({ data }) => {
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
      next(
        (vm) => Object.assign(vm.$data, data),
      );
    });
  },
};
