export default function isIos() {
  let ua = '';
  if (window.navigator && window.navigator.userAgent) {
    ua = window.navigator.userAgent;
  }

  const match = ua.match(/OS (\d+)_/i);
  if (match && match[1]) {
    return true;
  }
  return false;
}
