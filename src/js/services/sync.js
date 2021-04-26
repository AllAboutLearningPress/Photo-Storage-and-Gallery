import { debounce } from 'throttle-debounce';

const sync = debounce(200, (topic, data) => {
  if (process.env.NODE_ENV === 'development') {
    console.log(`Server sync, ${topic}: ${JSON.stringify(data)}`);
  }
});

export default sync;
