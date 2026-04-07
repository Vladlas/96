import { useState } from 'react';
import { Image as ImageIcon } from 'lucide-react';
import { MediaItem } from '../types';

interface DepartmentGalleryProps {
  title: string;
  subtitle: string;
  items: MediaItem[];
  compact?: boolean;
}

export function DepartmentGallery({ title, subtitle, items, compact = false }: DepartmentGalleryProps) {
  const [missing, setMissing] = useState<Record<string, boolean>>({});

  const gridClass = compact ? 'grid-cols-2 md:grid-cols-3 lg:grid-cols-6' : 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3';

  return (
    <section className="rounded-2xl border border-slate-200 bg-white p-5 shadow-museum">
      <div className="mb-3">
        <h3 className="text-lg font-semibold text-slate-800">{title}</h3>
        <p className="text-sm text-slate-600">{subtitle}</p>
      </div>

      <div className={`grid gap-3 ${gridClass}`}>
        {items.map((item) => (
          <article key={item.id} className="overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
            {!missing[item.id] ? (
              <img
                src={item.image}
                alt={item.title}
                loading="lazy"
                className="h-36 w-full object-cover"
                onError={() => setMissing((prev) => ({ ...prev, [item.id]: true }))}
              />
            ) : (
              <div className="flex h-36 items-center justify-center bg-slate-100 text-slate-500">
                <div className="text-center">
                  <ImageIcon size={20} className="mx-auto mb-1" />
                  <p className="px-2 text-xs">Добавьте файл: {item.image.replace('/media/', '')}</p>
                </div>
              </div>
            )}
            <div className="space-y-1 p-3">
              <span className="inline-block rounded-full bg-slate-200 px-2 py-0.5 text-[11px] text-slate-600">{item.tag}</span>
              <p className="text-sm font-medium text-slate-700">{item.title}</p>
              <p className="text-xs text-slate-500">{item.description}</p>
            </div>
          </article>
        ))}
      </div>
    </section>
  );
}
