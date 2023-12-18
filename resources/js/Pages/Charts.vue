<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Charts
      </h2>
      <a href="#" class="text-lg text-gray-700 mx-2" :class="{underline: selected !== 'days'}" @click.prevent="selected = 'days'">
        Day
      </a>
      <a href="#" class="text-lg text-gray-700 mx-2" :class="{underline: selected !== 'weeks'}" @click.prevent="selected = 'weeks'">
        Week
      </a>
      <a href="#" class="text-lg text-gray-700 mx-2" :class="{underline: selected !== 'months'}" @click.prevent="selected = 'months'">
        Month
      </a>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <highcharts :constructor-type="'stockChart'" :options="series" />
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import Highcharts from 'highcharts';
import stockinit from 'highcharts/modules/stock';
import { Chart } from 'highcharts-vue';

stockinit(Highcharts);

export default {
  components: {
    AppLayout,
    highcharts: Chart,
  },
  props: {
    days: {
      type: Array,
      default: () => [],
    },
    weeks: {
      type: Array,
      default: () => [],
    },
    months: {
      type: Array,
      default: () => [],
    },
  },

  data() {
    return {
      selected: 'days',
    };
  },

  computed: {
    series: function () {
      let s = this.selected;
      return {
        rangeSelector: {
          selected: 1
        },

        title: {
          text: 'Data per ' + this.selected,
        },

        chart: {
          height: 600,
          zoomType: 'xy'
        },

        tooltip: {
          shared: true
        },

        series: [
          {
            data: this.$props[s].map((d) => [d.date, d.number_of_contacts]),
            name: 'Contacts',
          },
          {
            data: this.$props[s].map((d) => [d.date, d.count]),
            name: 'Instances',
            yAxis: 1,
          },
          {
            data: this.$props[s].map((d) => [d.date, Math.round(d.new / d.count * 10000) / 100]),
            name: 'New',
            type: 'column',
            yAxis: 2,
            tooltip: {
              valueSuffix: '%'
            },
          },
          {
            data: this.$props[s].map((d) => [d.date, Math.round(d.stale / d.count * 10000) / 100]),
            name: 'Stale',
            type: 'column',
            yAxis: 3,
            tooltip: {
              valueSuffix: '%'
            },
          },
        ],

        yAxis: [
          {
            title: {
              text: 'Contacts',
            },
            labels: {
              align: 'right',
              x: -3,
            },
            height: '70%',
            lineWidth: 2,
          },
          {
            labels: {
              align: 'right',
              x: -3
            },
            title: {
              text: 'Instances',
            },
            top: '75%',
            height: '25%',
            offset: 0,
            lineWidth: 2,
          },
          {
            title: {
              text: 'New',
            },
            format: '{value}%',
            top: '75%',
            height: '25%',
            offset: 0,
            lineWidth: 2,
            visible: false,
          },
          {
            title: {
              text: 'Stale',
            },
            format: '{value}%',
            top: '75%',
            height: '25%',
            offset: 0,
            lineWidth: 2,
            visible: false,
          },
        ],
      };
    },
  },
};
</script>
