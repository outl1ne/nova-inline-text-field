import IndexField from './components/IndexField';
import DetailField from './components/DetailField';
import FormField from './components/FormField';

Nova.booting((Vue, router) => {
  Vue.component('index-inline-text-field', IndexField);
  Vue.component('detail-inline-text-field', DetailField);
  Vue.component('form-inline-text-field', FormField);
});
