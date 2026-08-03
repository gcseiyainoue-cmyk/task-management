/**
 * =====================================================================================
 * 【ファイル名】 task.js
 * 【アーキテクチャ上の位置づけ】 定数・設定層（マスタデータ / カテゴリ・優先度定義）
 * =====================================================================================
 * 【実務における設計思想】
 * アプリケーション全体で共通利用される固定値やマスタデータ（タスクの分類ツリー、
 * 表示順序、優先度設定など）を一元管理するための設定ファイルです。
 * アイコン、日本語ラベル、Tailwind CSSのバッジクラスなどをハードコーディングせず、
 * 単一のデータソース（Single Source of Truth）として定義することで、
 * デザインの変更やカテゴリの追加・修正が発生した際にも一箇所を書き換えるだけで
 * アプリケーション全体へ安全に反映できるよう保守性を高めています。
 */

/**
 * アプリケーション全体で利用されるタスクのカテゴリおよびサブカテゴリのツリー構造定義
 * @type {Object}
 */
export const categoryTree = {
    inbox: {
        label: '未分類',
        icon: '📥',
        badgeClass: 'bg-slate-100 text-slate-700 border-slate-200',
        defaultSub: 'general',
        items: [
            { key: 'general', label: 'その他', icon: '📝' },
        ]
    },
    work: {
        label: '仕事',
        icon: '💼',
        badgeClass: 'bg-blue-50 text-blue-700 border-blue-200',
        defaultSub: 'general',
        items: [
            { key: 'general', label: 'その他・未分類', icon: '📌' },
            { key: 'project', label: 'プロジェクト・業務', icon: '📊' },
            { key: 'meeting', label: 'ミーティング・商談', icon: '🤝' },
            { key: 'task', label: '通常タスク・作業', icon: '💻' },
            { key: 'admin', label: '事務・管理', icon: '📁' },
        ]
    },
    personal: {
        label: 'プライベート',
        icon: '🏠',
        badgeClass: 'bg-emerald-50 text-emerald-700 border-emerald-200',
        defaultSub: 'general',
        items: [
            { key: 'general', label: 'その他・未分類', icon: '📌' },
            { key: 'shopping', label: '買い物・購入', icon: '🛒' },
            { key: 'housework', label: '家事・用事', icon: '🧹' },
            { key: 'family', label: '家族・友人', icon: '👨‍👩‍👦' },
            { key: 'event', label: 'イベント・予定', icon: '🎉' },
        ]
    },
    growth: {
        label: '学習・成長',
        icon: '📚',
        badgeClass: 'bg-purple-50 text-purple-700 border-purple-200',
        defaultSub: 'general',
        items: [
            { key: 'general', label: 'その他・未分類', icon: '📌' },
            { key: 'study', label: '学習・スキルアップ', icon: '🎓' },
            { key: 'reading', label: '読書・インプット', icon: '📖' },
            { key: 'goal', label: '目標・計画', icon: '🎯' },
        ]
    },
    health: {
        label: '健康',
        icon: '🏃',
        badgeClass: 'bg-rose-50 text-rose-700 border-rose-200',
        defaultSub: 'general',
        items: [
            { key: 'general', label: 'その他・未分類', icon: '📌' },
            { key: 'fitness', label: '運動・フィットネス', icon: '💪' },
            { key: 'medical', label: '医療・健康管理', icon: '🏥' },
            { key: 'mental', label: 'メンタルケア・休息', icon: '🍵' },
        ]
    },
    finance: {
        label: 'お金・手続き',
        icon: '💳',
        badgeClass: 'bg-amber-50 text-amber-700 border-amber-200',
        defaultSub: 'general',
        items: [
            { key: 'general', label: 'その他・未分類', icon: '📌' },
            { key: 'payment', label: '支払い・請求', icon: '🧾' },
            { key: 'procedure', label: '手続き・行政', icon: '🏛️' },
            { key: 'asset', label: '資産管理・投資', icon: '📈' },
        ]
    }
};

/**
 * タスクカテゴリの表示順序を定義する配列
 * @type {Array<String>}
 */
export const categoryOrder = ['inbox', 'work', 'personal', 'growth', 'health', 'finance'];

/**
 * タスクの優先度（高・中・低）に対応するラベルやスタイル設定
 * @type {Object}
 */
export const priorityConfig = {
    high: {
        label: '高',
        badgeClass: 'bg-rose-50 text-rose-700 border-rose-200',
    },
    medium: {
        label: '中',
        badgeClass: 'bg-amber-50 text-amber-700 border-amber-200',
    },
    low: {
        label: '低',
        badgeClass: 'bg-slate-50 text-slate-700 border-slate-200',
    },
};