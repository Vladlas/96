import { FormEvent, useState } from 'react';
import { motion } from 'framer-motion';
import { CalendarPlus, FileText, Settings, X } from 'lucide-react';
import { CalendarEvent, Hall, HallId, Note } from '../types';

interface AdminPanelProps {
  halls: Hall[];
  notesCount: number;
  calendarCount: number;
  onAddNote: (note: Omit<Note, 'id'>) => void;
  onAddEvent: (event: Omit<CalendarEvent, 'id'>) => void;
  onClose: () => void;
}

export function AdminPanel({
  halls,
  notesCount,
  calendarCount,
  onAddNote,
  onAddEvent,
  onClose
}: AdminPanelProps) {
  const nonMainHalls = halls.filter((hall) => hall.id !== 'main');
  const [selectedHall, setSelectedHall] = useState<HallId>(nonMainHalls[0]?.id ?? 'history');
  const [noteTitle, setNoteTitle] = useState('');
  const [noteShort, setNoteShort] = useState('');
  const [noteContent, setNoteContent] = useState('');

  const [eventDate, setEventDate] = useState('');
  const [eventTitle, setEventTitle] = useState('');

  const submitNote = (event: FormEvent) => {
    event.preventDefault();
    if (!noteTitle.trim() || !noteShort.trim() || !noteContent.trim()) {
      return;
    }

    onAddNote({
      hallId: selectedHall,
      title: noteTitle.trim(),
      short: noteShort.trim(),
      content: noteContent.trim()
    });

    setNoteTitle('');
    setNoteShort('');
    setNoteContent('');
  };

  const submitEvent = (event: FormEvent) => {
    event.preventDefault();
    if (!eventDate.trim() || !eventTitle.trim()) {
      return;
    }

    onAddEvent({
      date: eventDate.trim(),
      title: eventTitle.trim()
    });

    setEventDate('');
    setEventTitle('');
  };

  return (
    <motion.div
      className="fixed inset-0 z-[60] flex justify-end bg-slate-950/40"
      initial={{ opacity: 0 }}
      animate={{ opacity: 1 }}
      exit={{ opacity: 0 }}
      onClick={onClose}
    >
      <motion.aside
        initial={{ x: 360 }}
        animate={{ x: 0 }}
        exit={{ x: 360 }}
        transition={{ type: 'spring', stiffness: 250, damping: 28 }}
        onClick={(event) => event.stopPropagation()}
        className="flex h-full w-full max-w-md flex-col gap-4 border-l border-slate-200 bg-white p-5 shadow-2xl"
      >
        <div className="flex items-start justify-between">
          <div>
            <p className="flex items-center gap-2 text-xs uppercase tracking-[0.2em] text-slate-500">
              <Settings size={14} /> Панель управления
            </p>
            <h2 className="mt-1 text-xl font-semibold text-slate-800">Админ-панель музея</h2>
          </div>
          <button onClick={onClose} className="rounded-lg border border-slate-200 p-1.5 text-slate-500 hover:bg-slate-100">
            <X size={16} />
          </button>
        </div>

        <div className="grid grid-cols-2 gap-2">
          <div className="rounded-xl border border-slate-200 bg-slate-50 p-3">
            <p className="text-xs uppercase text-slate-500">Заметки</p>
            <p className="text-lg font-semibold text-slate-700">{notesCount}</p>
          </div>
          <div className="rounded-xl border border-slate-200 bg-slate-50 p-3">
            <p className="text-xs uppercase text-slate-500">События</p>
            <p className="text-lg font-semibold text-slate-700">{calendarCount}</p>
          </div>
        </div>

        <form onSubmit={submitNote} className="space-y-2 rounded-2xl border border-slate-200 p-4">
          <p className="flex items-center gap-2 text-sm font-semibold text-slate-700">
            <FileText size={15} /> Добавить заметку
          </p>
          <select
            value={selectedHall}
            onChange={(event) => setSelectedHall(event.target.value as HallId)}
            className="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 outline-none focus:border-slate-400"
          >
            {nonMainHalls.map((hall) => (
              <option key={hall.id} value={hall.id}>
                {hall.title}
              </option>
            ))}
          </select>
          <input
            value={noteTitle}
            onChange={(event) => setNoteTitle(event.target.value)}
            placeholder="Заголовок"
            className="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-slate-400"
          />
          <input
            value={noteShort}
            onChange={(event) => setNoteShort(event.target.value)}
            placeholder="Краткое описание"
            className="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-slate-400"
          />
          <textarea
            value={noteContent}
            onChange={(event) => setNoteContent(event.target.value)}
            placeholder="Полный текст заметки"
            rows={4}
            className="w-full resize-none rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-slate-400"
          />
          <button type="submit" className="w-full rounded-lg bg-slate-800 py-2 text-sm text-white hover:bg-slate-700">
            Сохранить заметку
          </button>
        </form>

        <form onSubmit={submitEvent} className="space-y-2 rounded-2xl border border-slate-200 p-4">
          <p className="flex items-center gap-2 text-sm font-semibold text-slate-700">
            <CalendarPlus size={15} /> Добавить событие
          </p>
          <input
            value={eventDate}
            onChange={(event) => setEventDate(event.target.value)}
            placeholder="Дата (например, 4 июня)"
            className="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-slate-400"
          />
          <input
            value={eventTitle}
            onChange={(event) => setEventTitle(event.target.value)}
            placeholder="Название события"
            className="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-slate-400"
          />
          <button type="submit" className="w-full rounded-lg bg-slate-800 py-2 text-sm text-white hover:bg-slate-700">
            Добавить событие
          </button>
        </form>
      </motion.aside>
    </motion.div>
  );
}
