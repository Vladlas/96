import { CalendarEvent, Note } from './types';

const appBase = new URL('./', window.location.href);
const notesUrl = new URL('api/notes.php', appBase);
const eventsUrl = new URL('api/events.php', appBase);

async function requestJson<T>(input: RequestInfo | URL, init?: RequestInit): Promise<T> {
  const response = await fetch(input, init);
  if (!response.ok) {
    throw new Error(`API error ${response.status}`);
  }
  return (await response.json()) as T;
}

export async function loadNotes(): Promise<Note[]> {
  return requestJson<Note[]>(notesUrl);
}

export async function loadEvents(): Promise<CalendarEvent[]> {
  return requestJson<CalendarEvent[]>(eventsUrl);
}

export async function createNote(payload: Omit<Note, 'id'>): Promise<Note> {
  return requestJson<Note>(notesUrl, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  });
}

export async function createEvent(payload: Omit<CalendarEvent, 'id'>): Promise<CalendarEvent> {
  return requestJson<CalendarEvent>(eventsUrl, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(payload)
  });
}

export async function removeNote(id: string): Promise<void> {
  const url = new URL(notesUrl);
  url.searchParams.set('id', id);
  await requestJson<{ ok: true }>(url, { method: 'DELETE' });
}

export async function removeEvent(id: string): Promise<void> {
  const url = new URL(eventsUrl);
  url.searchParams.set('id', id);
  await requestJson<{ ok: true }>(url, { method: 'DELETE' });
}
