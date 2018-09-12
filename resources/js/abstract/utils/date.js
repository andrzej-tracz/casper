import dayjs from 'dayjs';

export function formatDate(date) {
  return dayjs(date).format('DD MMM YYYY');
}

export function formatToValue(date) {
  return dayjs(date).format('YYYY-MM-DD');
}
