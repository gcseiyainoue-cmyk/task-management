// ==========================================
// 1. 各種スタイルのインポート
// ==========================================
import '../css/app.css'; // アプリ全体のスタイル（Tailwind CSS等）を読み込み

// ==========================================
// 2. 基本セットアップ（Axios等の初期設定）
// ==========================================
import './bootstrap'; // HTTP通信（Axios）やCSRFトークン等の基本設定モジュールを読み込み

// ==========================================
// 3. ライブラリ・コンポーネントのインポート
// ==========================================
import { createInertiaApp } from '@inertiajs/vue3'; // Inertia.jsの初期化関数
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'; // Vueページの動的読み込みヘルパー
import { createApp, h } from 'vue'; // Vue 3 のコア機能
import { ZiggyVue } from '../../vendor/tightenco/ziggy'; // Laravelのルーティング（route()関数）をVue側で使えるようにするプラグイン

// ==========================================
// 4. アプリケーション名（共通タイトル用）の設定
// ==========================================
// .env の VITE_APP_NAME があればそれを使い、未設定ならデフォルトで 'Tasks' を使用
const appName = import.meta.env.VITE_APP_NAME || 'Tasks';

// ==========================================
// 5. Inertia × Vue アプリケーションの初期化
// ==========================================
createInertiaApp({
    // 【ページタイトルの自動フォーマット設定】
    // Vue側で <Head title="タスク一覧" /> と指定すると 「タスク一覧 - Tasks」 に変換されます。
    // タイトル指定がない場合は 「Tasks」 のみ表示されます。
    title: (title) => title ? `${title} - ${appName}` : appName,

    // 【コンポーネントの読み込み設定】
    // controllersから指定された名前（例: 'Tasks/Index'）を元に
    // ./Pages/Tasks/Index.vue を自動的に探してインポートします。
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),

    // 【Vueアプリのマウント（立ち上げ）設定】
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)   // Inertiaプラグインの登録
            .use(ZiggyVue) // route('tasks.index') などのLaravelルートを使えるように登録
            .mount(el);    // app.blade.php 内の #app 要素にVueアプリを描画
    },

    // 【ページ遷移時の上部ローディングバー（プログレスバー）設定】
    progress: {
        color: '#4B5563', // プログレスバーのカラー（シックなグレー）
    },
});