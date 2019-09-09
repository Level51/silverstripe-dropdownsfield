import Vue from 'vue';
import ObjectTagField from 'src/ObjectTagField.vue';
import watchElement from './util';

Vue.config.productionTip = false;

const render = (el) => {
  new Vue({
    render(h) {
      return h(ObjectTagField, {
        props: {
          payload: JSON.parse(el.dataset.payload)
        }
      });
    }
  }).$mount(`#${el.id}`);
};

watchElement('.level51-object-tagfield', (el) => {
  setTimeout(() => {
    render(el);
  }, 1);
});
