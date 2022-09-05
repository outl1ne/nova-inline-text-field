import IndexField from './components/IndexField';
import DetailField from './components/DetailField';

Nova.booting((Vue, router) => {
  Vue.component('index-inline-text-field', IndexField);
  Vue.component('detail-inline-text-field', DetailField);
});
