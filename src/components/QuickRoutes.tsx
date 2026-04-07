import { motion } from 'framer-motion';
import { Compass, Route } from 'lucide-react';
import { CuratorRoute, HallId } from '../types';

interface QuickRoutesProps {
  routes: CuratorRoute[];
  activeHall: HallId;
  onRoutePick: (route: CuratorRoute) => void;
}

export function QuickRoutes({ routes, activeHall, onRoutePick }: QuickRoutesProps) {
  return (
    <section className="rounded-2xl border border-slate-200/80 bg-white/85 p-4 shadow-museum backdrop-blur-sm">
      <div className="mb-3 flex items-center justify-between">
        <h3 className="flex items-center gap-2 text-sm font-semibold text-slate-700">
          <Route size={16} /> Быстрые маршруты
        </h3>
        <span className="text-xs text-slate-400">{activeHall === 'main' ? 'все залы' : 'текущий фокус'}</span>
      </div>

      <div className="grid gap-2 sm:grid-cols-2">
        {routes.map((route) => (
          <motion.button
            key={route.id}
            whileHover={{ y: -1 }}
            whileTap={{ scale: 0.99 }}
            onClick={() => onRoutePick(route)}
            className="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50/70 px-3 py-2 text-left transition-colors hover:bg-slate-100"
          >
            <div>
              <p className="text-sm font-medium text-slate-700">{route.title}</p>
              <p className="text-xs text-slate-500">{route.duration}</p>
            </div>
            <Compass size={14} className="text-slate-500" />
          </motion.button>
        ))}
      </div>
    </section>
  );
}
