export type HallId = 'main' | 'history' | 'science' | 'people';

export interface Hall {
  id: HallId;
  title: string;
  subtitle: string;
}

export interface MuseumSection {
  hallId: HallId;
  heading: string;
  description: string;
  items: string[];
}

export interface Note {
  id: string;
  hallId: HallId;
  title: string;
  short: string;
  content: string;
}

export interface CuratorRoute {
  id: string;
  title: string;
  hallIds: HallId[];
  duration: string;
}

export interface CalendarEvent {
  id: string;
  date: string;
  title: string;
}

export interface MediaItem {
  id: string;
  title: string;
  description: string;
  image: string;
  tag: string;
}
