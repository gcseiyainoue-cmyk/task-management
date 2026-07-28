<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1人目のテストユーザー（検証用アカウントA）を作成または取得
        $user1 = User::firstOrCreate(
            ['email' => 'a@example.com'],
            [
                'name' => 'テストユーザーA',
                'password' => Hash::make('password'),
            ]
        );

        // 2人目のテストユーザー（検証用アカウントB）を作成または取得
        $user2 = User::firstOrCreate(
            ['email' => 'b@example.com'],
            [
                'name' => 'テストユーザーB',
                'password' => Hash::make('password'),
            ]
        );

        $pools = [
            ['category' => 'work', 'sub' => 'project', 'titles' => ['Q3プロジェクトのキックオフ資料作成', 'クライアント向け進捗レポートのまとめ', '新機能の要件定義レビュー', 'デザインモックアップのフィードバック反映']],
            ['category' => 'work', 'sub' => 'meeting', 'titles' => ['週次定例ミーティングの準備', 'デザインチームとのすり合わせ', 'クライアントとのキックオフ面談']],
            ['category' => 'work', 'sub' => 'task', 'titles' => ['バグ修正：ログイン時のバリデーションエラー', 'APIレスポンスの高速化対応', 'コードレビュー（PR #142）']],
            ['category' => 'work', 'sub' => 'admin', 'titles' => ['経費精算書の提出と承認確認', '今週の工数入力とタスク整理']],
            
            ['category' => 'personal', 'sub' => 'shopping', 'titles' => ['日用品（洗剤・ティッシュ）のまとめ買い', 'スーパーで今週末の食材調達', 'コーヒー豆の買い出し']],
            ['category' => 'personal', 'sub' => 'housework', 'titles' => ['部屋全体の掃除機かけと換気', 'たまった洗濯物の整理とアイロンがけ', '水回り（キッチン・浴室）の清掃']],
            ['category' => 'personal', 'sub' => 'family', 'titles' => ['実家の両親へ近況連絡の電話', '週末の家族ディナーのお店予約']],
            ['category' => 'personal', 'sub' => 'event', 'titles' => ['友人との週末の予定調整', '映画のチケット事前予約']],
            
            ['category' => 'growth', 'sub' => 'study', 'titles' => ['TypeScriptの高度な型推論の学習', 'Vue 3 Composition APIの公式ドキュメント読み込み', '資格試験の過去問1年分を解く']],
            ['category' => 'growth', 'sub' => 'reading', 'titles' => ['ビジネス書の読書（第3章まで）', 'テックブログの最新記事チェック']],
            ['category' => 'growth', 'sub' => 'goal', 'titles' => ['今月の自己成長目標の進捗振り返り', '来期のスキルアップ計画の立案']],
            
            ['category' => 'health', 'sub' => 'fitness', 'titles' => ['ジムでの筋トレ（下半身メニュー）', '夜の軽めなランニング（3km）', 'ストレッチとヨガ（20分）']],
            ['category' => 'health', 'sub' => 'medical', 'titles' => ['定期歯科検診の予約', '処方薬の受け取り']],
            ['category' => 'health', 'sub' => 'mental', 'titles' => ['マインドフルネス瞑想（15分）', 'デジタルデトックスの時間を確保']],
            
            ['category' => 'finance', 'sub' => 'payment', 'titles' => ['クレジットカードの引き落とし口座残高確認', '今月の光熱費の支払い手続き']],
            ['category' => 'finance', 'sub' => 'procedure', 'titles' => ['ふるさと納税のワンストップ特例申請書類の発送', '保険内容の見直し手続き']],
            ['category' => 'finance', 'sub' => 'asset', 'titles' => ['つみたてNISAの運用状況チェック', '家計簿アプリの収支データ確認']],
            
            ['category' => 'inbox', 'sub' => 'general', 'titles' => ['後で読むブックマークの整理', 'PCデスクトップのファイル整理', '気になったアイデアのメモ書き', '未整理のメールチェックと返信']],
        ];

        $offsets = [0, 1, 1, 2, 3, 4, 5, 6, 7, 9, 11, 14];
        $priorities = ['high', 'medium', 'low'];

        $users = [$user1, $user2];

        foreach ($users as $user) {
            $tasks = [];
            $createdTitles = [];

            // ★ 毎固定で「当日2件」「昨日（期限切れ）1件」を確定させる枠を作る
            $mustHaveOffsets = [0, 0, -1];

            while (count($tasks) < 15) {
                $pool = $pools[array_rand($pools)];
                $title = $pool['titles'][array_rand($pool['titles'])];

                if (!in_array($title, $createdTitles)) {
                    $createdTitles[] = $title;

                    // 確定枠が残っていればそれを使い、無くなればランダム枠から選ぶ
                    if (!empty($mustHaveOffsets)) {
                        $offset = array_shift($mustHaveOffsets);
                    } else {
                        $offset = $offsets[array_rand($offsets)];
                    }

                    $priority = $priorities[array_rand($priorities)];

                    $tasks[] = [
                        'user_id' => $user->id,
                        'title' => $title,
                        'category' => $pool['category'],
                        'sub_category' => $pool['sub'],
                        'priority' => $priority,
                        'due_date' => Carbon::today()->addDays($offset)->toDateString(),
                        // 当日のタスクは確認しやすいように未完了（false）で固定
                        'is_completed' => ($offset === 0) ? false : (rand(1, 100) <= 15),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            Task::insert($tasks);
        }
    }
}