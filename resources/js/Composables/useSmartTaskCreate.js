/**
 * =====================================================================================
 * 【ファイル名】 useSmartTaskCreate.js
 * 【アーキテクチャ上の位置づけ】 コンポーザブル層（ビジネスロジック / スマート一括タスク作成処理）
 * =====================================================================================
 * 【実務における設計思想】
 * ユーザが自由テキストとして入力した改行区切りのタスク群（raw_text）を、
 * Inertia.jsの `router.post` を用いてバックエンド（`tasks.store-bulk`）へ非同期送信するビジネスロジックをカプセル化しています。
 * 状態管理（入力テキスト `smartText`、処理中フラグ `isProcessing`）をコンポーネントから分離し、
 * 通信成功時にはフォームをクリアしてモーダル閉鎖イベント（`emit('close')`）を発火する、
 * 関心の分離（SoC）を徹底したクリーンな設計となっています。
 */

import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

/**
 * スマート一括タスク作成機能を提供するComposable
 * @param {Function} emit - コンポーネント間でイベントを発火させるためのVueのemit関数
 * @returns {Object} フォーム入力値、ローディング状態、およびサブミットハンドラー
 */
export function useSmartTaskCreate(emit) {
    // --- ローカルステート（入力テキストおよび非同期処理中のローディングフラグ） ---
    const smartText = ref('');
    const isProcessing = ref(false);

    /**
     * スマート一括作成の送信処理（バックエンドのバルクストアへPOST）
     * 入力されたテキストのバリデーション、通信中のローディング制御、
     * 成功時のフォームクリアおよびモーダル閉鎖処理を行う
     */
    const handleSmartSubmit = () => {
        // 入力値が空または空白のみの場合は処理を中断
        if (!smartText.value.trim()) return;

        // 非同期処理の開始に伴いローディングフラグを有効化
        isProcessing.value = true;

        // Inertia.jsを使用して一括作成のエンドポイントへ非同期POSTリクエストを送信
        router.post(route('tasks.store-bulk'), {
            raw_text: smartText.value
        }, {
            onSuccess: () => {
                isProcessing.value = false;
                smartText.value = '';
                emit('close');
            },
            onError: () => {
                isProcessing.value = false;
            }
        });
    };

    // --- 外部公開するステートおよびハンドラーの返却 ---
    return {
        smartText,
        isProcessing,
        handleSmartSubmit,
    };
}