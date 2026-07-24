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

export function useSmartTaskCreate(emit) {
    // --- ローカルステート（入力テキストおよび非同期処理中のローディングフラグ） ---
    const smartText = ref('');
    const isProcessing = ref(false);

    // --- スマート一括作成の送信処理（バックエンドのバルクストアへPOST） ---
    const handleSmartSubmit = () => {
        if (!smartText.value.trim()) return;

        isProcessing.value = true;

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