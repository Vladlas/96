import { CalendarDays } from 'lucide-react';
import { CalendarEvent } from '../types';

interface HallCalendarProps {
  events: CalendarEvent[];
}

export function HallCalendar({ events }: HallCalendarProps) {
  return (
    <aside className="rounded-2xl border border-slate-200/80 bg-white/80 p-4 shadow-museum">
      <h3 className="mb-3 flex items-center gap-2 text-sm font-semibold text-slate-700">
        <CalendarDays size={16} /> Календарь кафедры
      </h3>

      <div className="space-y-2">
        {events.slice(0, 3).map((event) => (
          <div key={event.id} className="rounded-lg border border-slate-200 bg-slate-50/80 px-3 py-2">
            <p className="text-xs font-medium uppercase tracking-wide text-slate-500">{event.date}</p>
            <p className="mt-0.5 text-sm text-slate-700">{event.title}</p>
          </div>
        ))}
      </div>
    </aside>
  );
}
