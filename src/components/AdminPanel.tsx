import { FormEvent, useState } from 'react';
import { CalendarPlus, FileText, Settings, Trash2, X } from 'lucide-react';
import { CalendarEvent, Hall, HallId, Note } from '../types';

interface AdminPanelProps {
  halls: Hall[];
  notes: Note[];
  events: CalendarEvent[];
  onAddNote: (note: Omit<Note, 'id'>) => Promise<void>;
  onAddEvent: (event: Omit<CalendarEvent, 'id'>) => Promise<void>;
  onDeleteNote: (id: string) => Promise<void>;
  onDeleteEvent: (id: string) => Promise<void>;
  onClose: () => void;
}

export function AdminPanel({
  halls,
  notes,
  events,
  onAddNote,
  onAddEvent,
  onDeleteNote,
  onDeleteEvent,
  onClose
}: AdminPanelProps) {
  const nonMainHalls = halls.filter((hall) => hall.id !== 'main');
  const [selectedHall, setSelectedHall] = useState<HallId>(nonMainHalls[0]?.id ?? 'history');
  const [noteTitle, setNoteTitle] = useState('');
  const [noteShort, setNoteShort] = useState('');
  const [noteContent, setNoteContent] = useState('');
  const [eventDate, setEventDate] = useState('');
  const [eventTitle, setEventTitle] = useState('');
  const [error, setError] = useState<string | null>(null);

  const submitNote = async (event: FormEvent) => {
    event.preventDefault();
    setError(null);
    if (!noteTitle.trim() || !noteShort.trim() || !noteContent.trim()) {
      setError('Заполните все поля заметки.');
      return;
    }

    try {
      await onAddNote({ hallId: selectedHall, title: noteTitle.trim(), short: noteShort.trim(), content: noteContent.trim() });
      setNoteTitle('');
      setNoteShort('');
      setNoteContent('');
    } catch {
      setError('Не удалось сохранить заметку.');
    }
  };

  const submitEvent = async (event: FormEvent) => {
    event.preventDefault();
    setError(null);
    if (!eventDate.trim() || !eventTitle.trim()) {
      setError('Заполните дату и название события.');
      return;
    }

    try {
      await onAddEvent({ date: eventDate.trim(), title: eventTitle.trim() });
      setEventDate('');
      setEventTitle('');
    } catch {
      setError('Не удалось добавить событие.');
    }
  };

  const handleDeleteNote = async (id: string) => {
    setError(null);
    try {
      await onDeleteNote(id);
    } catch {
      setError('Не удалось удалить заметку.');
    }
  };

  const handleDeleteEvent = async (id: string) => {
    setError(null);
    try {
      await onDeleteEvent(id);
    } catch {
      setError('Не удалось удалить событие.');
    }
  };

  return (
    <div className="fixed inset-0 z-[60] flex justify-end bg-slate-950/40" onClick={onClose}>
      <aside
        onClick={(event) => event.stopPropagation()}
        className="flex h-full w-full max-w-lg flex-col gap-4 overflow-y-auto border-l border-slate-200 bg-white p-5 shadow-2xl"
      >
        <div className="flex items-start justify-between">
          <div>
            <p className="flex items-center gap-2 text-xs uppercase tracking-[0.2em] text-slate-500">
              <Settings size={14} /> Панель управления
            </p>
            <h2 className="mt-1 text-xl font-semibold text-slate-800">Админка + БД</h2>
          </div>
          <button type="button" onClick={onClose} className="rounded-lg border border-slate-200 p-1.5 text-slate-500 hover:bg-slate-100">
            <X size={16} />
          </button>
        </div>

        {error ? <p className="rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-700">{error}</p> : null}

        <div className="grid grid-cols-2 gap-2">
          <div className="rounded-xl border border-slate-200 bg-slate-50 p-3">
            <p className="text-xs uppercase text-slate-500">Заметки</p>
            <p className="text-lg font-semibold text-slate-700">{notes.length}</p>
          </div>
          <div className="rounded-xl border border-slate-200 bg-slate-50 p-3">
            <p className="text-xs uppercase text-slate-500">События</p>
            <p className="text-lg font-semibold text-slate-700">{events.length}</p>
          </div>
        </div>

        <form onSubmit={submitNote} className="space-y-2 rounded-2xl border border-slate-200 p-4">
          <p className="flex items-center gap-2 text-sm font-semibold text-slate-700">
            <FileText size={15} /> Новая заметка
          </p>
          <select value={selectedHall} onChange={(event) => setSelectedHall(event.target.value as HallId)} className="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
            {nonMainHalls.map((hall) => (
              <option key={hall.id} value={hall.id}>{hall.title}</option>
            ))}
          </select>
          <input value={noteTitle} onChange={(event) => setNoteTitle(event.target.value)} placeholder="Заголовок" className="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
          <input value={noteShort} onChange={(event) => setNoteShort(event.target.value)} placeholder="Краткое описание" className="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
          <textarea value={noteContent} onChange={(event) => setNoteContent(event.target.value)} placeholder="Полный текст" rows={4} className="w-full resize-none rounded-lg border border-slate-200 px-3 py-2 text-sm" />
          <button type="submit" className="w-full rounded-lg bg-slate-800 py-2 text-sm text-white hover:bg-slate-700">Сохранить</button>
        </form>

        <form onSubmit={submitEvent} className="space-y-2 rounded-2xl border border-slate-200 p-4">
          <p className="flex items-center gap-2 text-sm font-semibold text-slate-700">
            <CalendarPlus size={15} /> Новое событие
          </p>
          <input value={eventDate} onChange={(event) => setEventDate(event.target.value)} placeholder="Дата" className="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
          <input value={eventTitle} onChange={(event) => setEventTitle(event.target.value)} placeholder="Название" className="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm" />
          <button type="submit" className="w-full rounded-lg bg-slate-800 py-2 text-sm text-white hover:bg-slate-700">Добавить</button>
        </form>

        <section className="space-y-2 rounded-2xl border border-slate-200 p-4">
          <p className="text-sm font-semibold text-slate-700">Управление заметками</p>
          {notes.slice(0, 6).map((note) => (
            <div key={note.id} className="flex items-center justify-between rounded-lg border border-slate-200 px-3 py-2">
              <span className="text-sm text-slate-700">{note.title}</span>
              <button type="button" onClick={() => handleDeleteNote(note.id)} className="rounded-md p-1 text-slate-500 hover:bg-slate-100">
                <Trash2 size={14} />
              </button>
            </div>
          ))}
        </section>

        <section className="space-y-2 rounded-2xl border border-slate-200 p-4">
          <p className="text-sm font-semibold text-slate-700">Управление событиями</p>
          {events.slice(0, 6).map((event) => (
            <div key={event.id} className="flex items-center justify-between rounded-lg border border-slate-200 px-3 py-2">
              <span className="text-sm text-slate-700">{event.date} — {event.title}</span>
              <button type="button" onClick={() => handleDeleteEvent(event.id)} className="rounded-md p-1 text-slate-500 hover:bg-slate-100">
                <Trash2 size={14} />
              </button>
            </div>
          ))}
        </section>
      </aside>
    </div>
  );
}
