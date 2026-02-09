/**
 * Date formatting utility (replaces vue-filter-date-format)
 * Using date-fns or similar library, or create custom formatter
 */
export function formatDate(date, format = 'MMM D, YYYY') {
  if (!date) return '';
  const d = new Date(date);

  // Simple formatter - consider using date-fns for more robust formatting
  const months = [
    'Jan',
    'Feb',
    'Mar',
    'Apr',
    'May',
    'Jun',
    'Jul',
    'Aug',
    'Sep',
    'Oct',
    'Nov',
    'Dec',
  ];

  if (format === 'MMM D, YYYY') {
    return `${months[d.getMonth()]} ${d.getDate()}, ${d.getFullYear()}`;
  }
  if (format === 'MMM DD, YYYY') {
    const day = String(d.getDate()).padStart(2, '0');
    return `${months[d.getMonth()]} ${day}, ${d.getFullYear()}`;
  }
  // Add more format patterns as needed
  return d.toLocaleDateString();
}

/**
 * Filter ID utility
 */
export function filterId(value) {
  if (!value) return '';
  return value.toString().replace(/[\s&]/gi, '').toLowerCase();
}

/**
 * Anchor link utility
 */
export function anchorLink(value) {
  if (!value) return '';
  return `#${value}`;
}

/**
 * Capitalize utility
 */
export function capitalize(value) {
  if (!value) return '';
  return value.toString().charAt(0).toUpperCase() + value.slice(1);
}
