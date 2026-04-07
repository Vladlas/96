import { useEffect, useMemo, useState } from 'react';
import { AnimatePresence, motion } from 'framer-motion';
import { Settings2, Shield, X } from 'lucide-react';
import { createEvent, createNote, loadEvents, loadNotes, removeEvent, removeNote } from './api';
import { AdminPanel } from './components/AdminPanel';
import { DepartmentGallery } from './components/DepartmentGallery';
import { ExpositionShowcase } from './components/ExpositionShowcase';
import { HallCalendar } from './components/HallCalendar';
import { QuickRoutes } from './components/QuickRoutes';
import {
  curatorRoutes,
  departmentGallery,
  departmentHeraldry,
  hallCalendar,
  halls,
  museumContent,
  notesData
} from './data/museumData';
import { CalendarEvent, CuratorRoute, HallId, Note } from './types';

function App() {
  const [activeHall, setActiveHall] = useState<HallId>('main');
  const [activeNote, setActiveNote] = useState<Note | null>(null);
  const [isAdminOpen, setIsAdminOpen] = useState(false);
  const [notes, setNotes] = useState<Note[]>(notesData);
  const [calendarEvents, setCalendarEvents] = useState<CalendarEvent[]>(hallCalendar);
  const [apiStatus, setApiStatus] = useState<'ready' | 'fallback'>('ready');

  useEffect(() => {
    const bootstrap = async () => {
      try {
        const [dbNotes, dbEvents] = await Promise.all([loadNotes(), loadEvents()]);
        setApiStatus('ready');
        if (dbNotes.length > 0) {
          setNotes(dbNotes);
        }
        if (dbEvents.length > 0) {
          setCalendarEvents(dbEvents);
        }
      } catch {
        setApiStatus('fallback');
      }
    };

    bootstrap();
  }, []);

  const section = useMemo(
    () => museumContent.find((entry) => entry.hallId === activeHall) ?? museumContent[0],
    [activeHall]
  );

  const visibleNotes = useMemo(() => {
    if (activeHall === 'main') {
      return notes;
    }
    return notes.filter((note) => note.hallId === activeHall);
  }, [activeHall, notes]);

  const runRoute = (route: CuratorRoute) => {
    const [firstHall] = route.hallIds;
    if (firstHall) {
      setActiveHall(firstHall);
    }
  };

  const addNote = async (payload: Omit<Note, 'id'>) => {
    try {
      const created = await createNote(payload);
      setNotes((previous) => [created, ...previous]);
    } catch {
      const nextNote: Note = { ...payload, id: `n-${Date.now()}` };
      setNotes((previous) => [nextNote, ...previous]);
    }
  };

  const addEvent = async (payload: Omit<CalendarEvent, 'id'>) => {
    try {
      const created = await createEvent(payload);
      setCalendarEvents((previous) => [created, ...previous]);
    } catch {
      const nextEvent: CalendarEvent = { ...payload, id: `c-${Date.now()}` };
      setCalendarEvents((previous) => [nextEvent, ...previous]);
    }
  };

  const deleteNoteById = async (id: string) => {
    try {
      await removeNote(id);
    } catch {
      // ignore and proceed local
    }
    setNotes((previous) => previous.filter((note) => note.id !== id));
  };

  const deleteEventById = async (id: string) => {
    try {
      await removeEvent(id);
    } catch {
      // ignore and proceed local
    }
    setCalendarEvents((previous) => previous.filter((event) => event.id !== id));
  };

  return (
    <div className="min-h-screen bg-museum-bg text-museum-ink">
      <main className="mx-auto max-w-6xl space-y-5 px-4 py-6 lg:space-y-6 lg:px-6">
        <header className="space-y-3 rounded-2xl border border-slate-200/80 bg-white/85 p-5 shadow-museum backdrop-blur-sm">
          <div className="flex flex-wrap items-start justify-between gap-3">
            <div className="space-y-2">
              <p className="text-xs uppercase tracking-[0.2em] text-slate-500">Виртуальный музей кафедры 96</p>
              <h1 className="text-2xl font-semibold text-slate-800 lg:text-3xl">Кафедральная экспозиция и летопись</h1>
              <p className="max-w-3xl text-sm leading-relaxed text-slate-600">
                Материалы кафедры: символика, учебный процесс, выставочная деятельность и кураторские заметки в едином цифровом пространстве.
              </p>
            </div>
            <button
              onClick={() => setIsAdminOpen(true)}
              className="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-600 transition-colors hover:bg-slate-100"
            >
              <Settings2 size={15} /> Админ-панель
            </button>
          </div>

          <div className="rounded-xl border border-slate-200 bg-slate-50/70 p-3 text-sm text-slate-600">
            <p className="inline-flex items-center gap-2 font-medium text-slate-700">
              <Shield size={15} /> Кафедральный профиль
            </p>
            <p className="mt-1">Раздел дополнен символикой и фотографиями, предоставленными для наполнения виртуального музея.</p>
          </div>
        </header>


        {apiStatus === 'fallback' ? (
          <div className="rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-sm text-amber-800">
            API недоступен: админка работает в локальном режиме браузера до восстановления PHP/SQLite.
          </div>
        ) : null}

        <nav className="flex flex-wrap gap-2">
          {halls.map((hall) => (
            <button
              key={hall.id}
              onClick={() => setActiveHall(hall.id)}
              className={`rounded-xl px-3 py-2 text-sm transition-all ${
                activeHall === hall.id
                  ? 'bg-slate-800 text-white shadow-sm'
                  : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-100'
              }`}
            >
              {hall.title}
            </button>
          ))}
        </nav>

        {activeHall === 'main' ? (
          <>
            <section className="grid gap-4 lg:grid-cols-[2.2fr_1fr]">
              <ExpositionShowcase section={section} halls={halls} onHallChange={setActiveHall} />
              <HallCalendar events={calendarEvents} />
            </section>

            <DepartmentGallery
              title="Кафедральная символика"
              subtitle="Эмблемы кафедры и связанные знаки из предоставленного набора материалов"
              items={departmentHeraldry}
              compact
            />
          </>
        ) : (
          <section className="rounded-2xl border border-slate-200 bg-white p-5 shadow-museum">
            <h2 className="text-xl font-semibold text-slate-800">{section.heading}</h2>
            <p className="mt-2 text-sm text-slate-600">{section.description}</p>
            <div className="mt-4 grid gap-2 sm:grid-cols-2">
              {section.items.map((item) => (
                <div key={item} className="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700">
                  {item}
                </div>
              ))}
            </div>
          </section>
        )}

        <DepartmentGallery
          title="Фотолетопись кафедры"
          subtitle="Учебные занятия, выездные этапы и демонстрация разработок на профильных площадках"
          items={departmentGallery}
        />

        <QuickRoutes routes={curatorRoutes} activeHall={activeHall} onRoutePick={runRoute} />

        <section className="rounded-2xl border border-slate-200 bg-white p-5 shadow-museum">
          <h3 className="mb-3 text-lg font-semibold text-slate-800">Кураторские заметки</h3>
          <div className="grid gap-3 md:grid-cols-3">
            {visibleNotes.map((note) => (
              <button
                key={note.id}
                onClick={() => setActiveNote(note)}
                className="rounded-xl border border-slate-200 bg-slate-50 p-3 text-left transition-colors hover:bg-slate-100"
              >
                <p className="text-sm font-medium text-slate-700">{note.title}</p>
                <p className="mt-1 text-xs text-slate-500">{note.short}</p>
              </button>
            ))}
          </div>
        </section>
      </main>

      <AnimatePresence>
        {activeNote ? (
          <motion.div
            className="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 p-4"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            onClick={() => setActiveNote(null)}
          >
            <motion.div
              initial={{ y: 16, opacity: 0 }}
              animate={{ y: 0, opacity: 1 }}
              exit={{ y: 10, opacity: 0 }}
              onClick={(event) => event.stopPropagation()}
              className="w-full max-w-xl rounded-2xl border border-slate-200 bg-white p-5 shadow-xl"
            >
              <div className="mb-3 flex items-start justify-between gap-3">
                <h4 className="text-lg font-semibold text-slate-800">{activeNote.title}</h4>
                <button
                  onClick={() => setActiveNote(null)}
                  className="rounded-lg border border-slate-200 p-1 text-slate-500 transition-colors hover:bg-slate-100"
                >
                  <X size={16} />
                </button>
              </div>
              <p className="text-sm leading-relaxed text-slate-600">{activeNote.content}</p>
            </motion.div>
          </motion.div>
        ) : null}
      </AnimatePresence>

      <AnimatePresence>
        {isAdminOpen ? (
          <AdminPanel
            halls={halls}
            notes={notes}
            events={calendarEvents}
            onAddNote={addNote}
            onAddEvent={addEvent}
            onDeleteNote={deleteNoteById}
            onDeleteEvent={deleteEventById}
            onClose={() => setIsAdminOpen(false)}
          />
        ) : null}
      </AnimatePresence>
    </div>
  );
}

export default App;
