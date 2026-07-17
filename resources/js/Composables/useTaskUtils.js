export function useTaskUtils() {
    const isToday = (dateString) => {
        const today = new Date().toISOString().split('T')[0];
        return dateString === today;
    };

    const isExpired = (dateString) => {
        if (!dateString) return false;
        const today = new Date().toISOString().split('T')[0];
        return dateString < today;
    };

    const getStatusBadge = (status) => {
        const statuses = {
            0: { text: '⚪ 未着手', class: 'bg-slate-100/70 text-slate-600 border-slate-200' },
            1: { text: '⚡ 進行中', class: 'bg-indigo-50 text-indigo-600 border-indigo-150 font-medium' },
            2: { text: '✅ 完了', class: 'bg-emerald-50 text-emerald-600 border-emerald-150 font-medium' },
        };
        return statuses[status] || { text: '❓ 不明', class: 'bg-rose-50 text-rose-600 border-rose-200' };
    };

    const getCardClass = (task) => {
        if (isExpired(task.due_date) && task.status !== 2) {
            return 'border-l-2 border-l-rose-500 border-y-slate-200 border-r-slate-200 bg-white shadow-[0_2px_12px_rgba(15,23,42,0.06)]';
        }
        if (task.status === 2) {
            return 'border-slate-200 bg-slate-50/60 opacity-60 shadow-none';
        }
        if (task.status === 1) {
            return 'border-l-2 border-l-indigo-500 border-y-slate-200 border-r-slate-200 bg-white shadow-[0_4px_16px_rgba(99,102,241,0.08)]';
        }
        return 'border-slate-200 bg-white shadow-[0_4px_12px_rgba(15,23,42,0.06)] hover:border-slate-300 hover:shadow-[0_6px_20px_rgba(15,23,42,0.1)] transition-all duration-200';
    };

    const getPriorityBadgeClass = (index) => {
        if (index === 0) return 'bg-slate-800 text-white border-transparent font-semibold shadow-sm';
        if (index === 1 || index === 2) return 'bg-slate-100 text-slate-700 border-slate-200 font-medium';
        return 'bg-slate-50 text-slate-400 border-slate-100';
    };

    return { isToday, isExpired, getStatusBadge, getCardClass, getPriorityBadgeClass };
}