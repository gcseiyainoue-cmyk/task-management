/**
 * =====================================================================================
 * 【ファイル名】 useRoutineOperations.js
 * 【アーキテクチャ上の位置づけ】 ビジネスロジック層（Composables / ルーティン操作・API通信）
 * 【概要】 ルーティンテンプレートのCRUD操作およびタスクとの相互変換・紐付け解除に関する
 *         Inertia.jsを利用した非同期通信処理とUIフィードバック（トースト通知）を管理するカスタムフック。
 * =====================================================================================
 */

import { router } from '@inertiajs/vue3';

/**
 * ルーティン関連の操作ロジックを提供するComposable
 * @param {Function} showToast - 画面上にトースト通知を表示するためのコールバック関数
 * @returns {Object} ルーティン操作に関連する各種ハンドラー関数
 */
export function useRoutineOperations(showToast) {

    /**
     * 新規ルーティンテンプレートを作成する関数
     * @param {Object} formValues - フォームに入力されたルーティンの設定値データ（タイトル、カテゴリ、頻度など）
     */
    const storeRoutine = (formValues) => {
        router.post(route('routines.store'), formValues, {
            preserveScroll: true, // リクエスト前後で現在のスクロール位置を保持
            onSuccess: () => {
                showToast('ルーティンを作成しました ✨');
            },
            onError: (errors) => {
                console.error(errors);
                showToast('ルーティンの作成に失敗しました');
            }
        });
    };

    /**
     * 既存のルーティンテンプレートを更新する関数
     * @param {Number|String} id - 更新対象のルーティンテンプレートID
     * @param {Object} formValues - 変更後のフォーム入力値データ
     */
    const updateRoutine = (id, formValues) => {
        router.put(route('routines.update', id), formValues, {
            preserveScroll: true, // リクエスト前後で現在のスクロール位置を保持
            onSuccess: () => {
                showToast('ルーティンを更新しました 📝');
            },
            onError: (errors) => {
                console.error(errors);
                showToast('ルーティンの更新に失敗しました');
            }
        });
    };

    /**
     * ルーティンの有効状態（アクティブ・非アクティブ）をトグル切り替えする関数
     * @param {Object} routine - 操作対象のルーティンオブジェクト（現在の `is_active` 状態を含む）
     */
    const toggleRoutine = (routine) => {
        router.put(route('routines.toggle', routine.id), {
            is_active: !routine.is_active // 現在の状態を反転させてサーバーに送信
        }, {
            preserveScroll: true, // リクエスト前後で現在のスクロール位置を保持
            onSuccess: () => {
                // 切り替え後の状態（反転前基準）に応じて適切なトーストメッセージを分岐表示
                showToast(routine.is_active ? 'ルーティンを一時停止しました' : 'ルーティンを有効化しました 🔄');
            },
            onError: (errors) => {
                console.error(errors);
                showToast('状態の切り替えに失敗しました');
            }
        });
    };

    /**
     * 指定したルーティンテンプレートを削除する関数
     * @param {Number|String} id - 削除対象のルーティンテンプレートID
     */
    const deleteRoutine = (id) => {
        // 誤操作によるデータの消失を防ぐためのブラウザ確認ダイアログ
        if (!confirm('このルーティンテンプレートを削除してもよろしいですか？')) return;

        router.delete(route('routines.destroy', id), {
            preserveScroll: true, // リクエスト前後で現在のスクロール位置を保持
            onSuccess: () => {
                showToast('ルーティンを削除しました 🗑️');
            },
            onError: (errors) => {
                console.error(errors);
                showToast('ルーティンの削除に失敗しました');
            }
        });
    };

    /**
     * 既存の通常タスクをベースに新規ルーティンを作成し、成功後に元のタスクを削除して一本化する関数
     * @param {Object} task - ルーティン化のベースとなる対象の通常タスクオブジェクト
     */
    const convertTaskToRoutine = (task) => {
        // タスクの情報を流用してルーティンを新規作成（デフォルト頻度はデイリーに設定）
        router.post(route('routines.store'), {
            title: task.title,
            category: task.category,
            sub_category: task.sub_category,
            priority: task.priority,
            frequency_type: 'daily',
        }, {
            preserveScroll: true, // リクエスト前後で現在のスクロール位置を保持
            onSuccess: () => {
                // ルーティン化の成功をトリガーに、元の通常タスクを削除
                router.delete(route('tasks.destroy', task.id), {
                    preserveScroll: true, // リクエスト前後で現在のスクロール位置を保持
                    onSuccess: () => {
                        showToast('ルーティン化して1本化しました ✨');
                    },
                    onError: (errors) => {
                        console.error('Delete task error:', errors);
                        showToast('ルーティン化は成功しましたが、元タスクの削除に失敗しました');
                    }
                });
            },
            onError: (errors) => {
                console.error('Convert to routine error:', errors);
                showToast('ルーティン化に失敗しました');
            }
        });
    };

    /**
     * タスクとルーティンの紐付けを解除し、通常タスクへ完全に一本化する関数
     * @param {Object} task - ルーティン紐付けを解除する対象のタスクオブジェクト
     */
    const removeTaskRoutine = (task) => {
        router.post(route('tasks.remove-routine', task.id), {}, {
            preserveScroll: true, // リクエスト前後で現在のスクロール位置を保持
            onSuccess: () => {
                showToast('ルーティンを解除して通常タスクに一本化しました ✨');
            },
            onError: (errors) => {
                console.error(errors);
                showToast('ルーティン解除に失敗しました');
            }
        });
    };

    return {
        storeRoutine,
        updateRoutine,
        toggleRoutine,
        deleteRoutine,
        convertTaskToRoutine,
        removeTaskRoutine,
    };
}