import { CalendarEvent, Note } from './types';

const NOTES_API = './api/notes.php';
const EVENTS_API = './api/events.php';

async function requestJson<T>(input: RequestInfo, init?: RequestInit): Promise<T> {
  const response = await fetch(input, init);
  if (!response.ok) {
    throw new Error(`API error ${response.status}`);
  }
  return (await response.json()) as T;
}

export async function loadNotes(): Promise<Note[]> {
  return requestJson<Note[]>(NOTES_API);
}

export async function loadEvents(): Promise<CalendarEvent[]> {
  return requestJson<CalendarEvent[]>(EVENTS_API);
}

export async function createNote(payload: Omit<Note, 'id'>): Promise<Note> {
  return requestJson<Note>(NOTES_API, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  });
}

export async function createEvent(payload: Omit<CalendarEvent, 'id'>): Promise<CalendarEvent> {
  return requestJson<CalendarEvent>(EVENTS_API, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  });
}

export async function removeNote(id: string): Promise<void> {
  await requestJson<{ ok: true }>(`${NOTES_API}?id=${encodeURIComponent(id)}`, {
    method: 'DELETE'
  });
}

export async function removeEvent(id: string): Promise<void> {
  await requestJson<{ ok: true }>(`${EVENTS_API}?id=${encodeURIComponent(id)}`, {
    method: 'DELETE'
  });
}
