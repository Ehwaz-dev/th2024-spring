import dayjs from 'dayjs';
import 'dayjs/locale/ru';

export function formatDateRange(startDate: string, endDate: string): string {
  const start = dayjs(startDate);
  const end = dayjs(endDate);
  const currentYear = dayjs().year();

  if (start.year() === currentYear && end.year() === currentYear) {
    return `${start.format('DD.MM')}-${end.format('DD.MM')}`;
  } else {
    return `${start.format('DD.MM.YYYY')}-${end.format('DD.MM.YYYY')}`;
  }
}


export function formatDateTime(dateString: string): string {
  const date = dayjs(dateString);
  const currentYear = dayjs().year();

  if (date.year() === currentYear) {
    return date.locale('ru').format('D MMMM в H:mm');
  } else {
    return date.locale('ru').format('D MMMM YYYY в H:mm');
  }
}