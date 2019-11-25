<template>
  <div class="level51-dropdownsField">
    <div class="flex flex-wrap mxn3">
      <div
        class="my2 mx3"
        v-for="item in options"
        :key="item.key">
        <label>{{ item.label }}</label>
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
    /**
     * Field config passed from the "getPayload" method of the DropdownsField class.
     */
    payload: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      options: []
    };
  },
  created() {
    // Get a copy of the available options as selection will manipulate the values.
    this.options = this.payload.source;

    // Select an initial value for each options without value
    this.options.forEach((o) => {
      if (!o.value) o.value = this.initialValue.key;
    });
  },
  computed: {
    /**
     * The value maintained in a hidden input field which will be written to the DB.
     *
     * Generates a JSON object with all options with a value.
     *
     * @return {string}
     */
    valueForStorage() {
      const selected = this.options.filter((option) => option.value !== null && option.value !== '');

      const value = {};
      selected.forEach((s) => {
        value[s.key] = s.value;
      });

      return JSON.stringify(value);
    },

    /**
     * The initial value for each option which has no value selected yet.
     *
     * Is either the option with null or "" as key if provided, otherwise the first one.
     *
     * @return {*}
     */
    initialValue() {
      const nullOption = this.payload.valueOptions.find((o) => o.key === null || o.key === '');

      return nullOption || this.payload.valueOptions[0];
    }
  },
  methods: {
    onSelect(item, event) {
      item.value = event.target.value;
    }
  }
};
</script>

<style lang="less" scoped>
  @import "~styles/base";

  .level51-dropdownsField {
    label {
      display: block;
      margin-bottom: @space-1;
    }
  }
</style>
