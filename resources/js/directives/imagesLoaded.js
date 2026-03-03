import imagesLoaded from 'imagesloaded';

function setupImagesLoaded(el, callback) {
  // Guard: if the binding value isn't a function, there's nothing to do.
  if (typeof callback !== 'function') return;

  // Clean up any previous imagesLoaded instance attached to this element.
  if (el.__imagesLoadedInstance__) {
    el.__imagesLoadedInstance__.off('always');
  }

  // This will check this element for image els/bg imgs and monitor whether they've loaded/errored
  const instance = imagesLoaded(el);
  el.__imagesLoadedInstance__ = instance;

  // 'always' fires when all imgs have been loaded/errored
  instance.on('always', (imagesLoadedInstance) => {
    // Run the callback on the next microtask so any DOM changes
    // triggered by image load are settled before we resize Flickity.
    Promise.resolve().then(() => {
      callback(imagesLoadedInstance);
    });
  });
}

export default {
  mounted(el, binding) {
    setupImagesLoaded(el, binding.value);
  },
  updated(el, binding) {
    setupImagesLoaded(el, binding.value);
  },
  unmounted(el) {
    if (el.__imagesLoadedInstance__) {
      el.__imagesLoadedInstance__.off('always');
      el.__imagesLoadedInstance__ = null;
    }
  },
};
