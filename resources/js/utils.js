export function convertSecondsToTime(seconds) {
  return (new Date(seconds * 1000)).toUTCString().match(/(\d\d:\d\d:\d\d)/)[0];
}
export function convertTimeToSeconds(timeString) {
  return new Date(`1970-01-01T${timeString}Z`).getTime() / 1000;
}

function pluralise(value, singular, plural) {
  if (value <= 0) return '';
  if (value === 1) return [value, singular].join(' ');
  return [value, plural].join(' ');
}

export function convertSecondsToHumanReadableTime(inputSeconds) {
  const hours = Math.floor(inputSeconds / 3600);
  const minutes = Math.floor((inputSeconds - hours * 3600) / 60);
  const seconds = inputSeconds - hours * 3600 - minutes * 60;

  const humanReadableHours = pluralise(hours, 'hour', 'hours');
  const humanReadableMinutes = pluralise(minutes, 'minute', 'minutes');
  const humanReadableSeconds = pluralise(seconds, 'second', 'seconds');

  const elements = [
    humanReadableHours,
    humanReadableMinutes,
    humanReadableSeconds,
  ].filter(Boolean);

  if (elements.length === 0) return '';
  if (elements.length === 1) return elements[0];

  const lastElement = elements.pop();

  return [elements.join(', '), 'and', lastElement].join(' ');
}
