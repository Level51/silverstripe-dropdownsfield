<template>
  <div class="level51-dropdownsField">
    <div class="flex">
      <div
        class="m1"
        v-for="item in options"
        :key="item.key">
        {{ item.label }}
        <select
          @input="onSelect(item, $event)">
          <option
            v-for="valueOption in payload.valueOptions"
            :key="valueOption.key"
            :value="valueOption.key"
            :selected="valueOption.key === item.value">
            {{ valueOption.label }}
          </option>
        </select>
      </div>
    </div>

    <input
      type="hidden"
      :name="payload.name"
      :value="valueForStorage">
  </div>
</template>

<script>
export default {
  props: {
    payload: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      newTagMinLength: 1,
      newTag: '',
      options: []
    };
  },
  created() {
    this.options = this.payload.source;
  },
  computed: {
    valueForStorage() {
      const selected = this.options.filter(option => option.value !== null && option.value !== '');

      const value = {};
      selected.forEach((s) => {
        value[s.key] = s.value;
      });

      return JSON.stringify(value);
    }
  },
  methods: {
    i18n(label) {
      const { i18n } = this.payload;
      return i18n.hasOwnProperty(label) ? i18n[label] : label;
    },
    onSelect(item, event) {
      item.value = event.target.value;
    }
  }
};
</script>

<style lang="less" scoped>
  // TODO proper styling
  @import "~styles/base";

  .level51-dropdownsField {
    border: 1px solid #ccc;
    padding: @space-2;
  }
</style>
