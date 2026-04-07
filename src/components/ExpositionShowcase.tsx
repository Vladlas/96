import { motion } from 'framer-motion';
import { ArrowRight, Sparkles } from 'lucide-react';
import { Hall, MuseumSection } from '../types';

interface ExpositionShowcaseProps {
  section: MuseumSection;
  halls: Hall[];
  onHallChange: (hallId: Hall['id']) => void;
}

export function ExpositionShowcase({ section, halls, onHallChange }: ExpositionShowcaseProps) {
  return (
    <section className="rounded-3xl border border-slate-200 bg-gradient-to-b from-white to-slate-50 p-5 shadow-museum lg:p-6">
      <div className="grid gap-4 lg:grid-cols-[1.7fr_1fr]">
        <div className="rounded-2xl border border-slate-200/90 bg-white p-4 lg:p-5">
          <p className="mb-2 inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
            <Sparkles size={14} /> Центральная витрина
          </p>
          <h2 className="text-2xl font-semibold text-slate-800">{section.heading}</h2>
          <p className="mt-2 max-w-2xl text-sm leading-relaxed text-slate-600">{section.description}</p>

          <div className="mt-4 grid gap-2 sm:grid-cols-3">
            {section.items.map((item) => (
              <div key={item} className="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700">
                {item}
              </div>
            ))}
          </div>

          <div className="mt-4 flex flex-wrap gap-2">
            {halls.filter((hall) => hall.id !== 'main').map((hall) => (
              <motion.button
                key={hall.id}
                whileHover={{ y: -1 }}
                onClick={() => onHallChange(hall.id)}
                className="rounded-lg border border-slate-200 px-3 py-1.5 text-sm text-slate-600 transition-colors hover:bg-slate-100"
              >
                {hall.title}
              </motion.button>
            ))}
          </div>
        </div>

        <div className="space-y-2">
          <div className="rounded-2xl border border-slate-200 bg-white p-4">
            <p className="text-xs uppercase tracking-wide text-slate-500">Маршрут дня</p>
            <p className="mt-1 text-sm text-slate-700">История → Наука → Люди</p>
          </div>
          <button
            className="flex w-full items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700 transition-colors hover:bg-slate-100"
            onClick={() => onHallChange('history')}
          >
            Начать экскурсию
            <ArrowRight size={16} />
          </button>
        </div>
      </div>
    </section>
  );
}
