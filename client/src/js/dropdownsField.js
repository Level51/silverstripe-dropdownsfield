import Vue from 'vue';
import DropdownsField from 'src/DropdownsField.vue';
import watchElement from './util';

Vue.config.productionTip = false;

const render = (el) => {
  new Vue({
    render(h) {
      return h(DropdownsField, {
        props: {
          payload: JSON.parse(el.dataset.payload)
        }
      });
    }
  }).$mount(`#${el.id}`);
};

watchElement('.level51-dropdownsfield', (el) => {
  setTimeout(() => {
    render(el);
  }, 1);
});
