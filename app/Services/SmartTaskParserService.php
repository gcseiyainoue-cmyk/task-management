<?php
/**
 * =====================================================================================
 * 【ファイル名】 SmartTaskParserService.php
 * 【アーキテクチャ上の位置づけ】 サーバーサイド層（サービス / ビジネスロジック処理）
 * =====================================================================================
 * 【実務における設計思想】
 * 自然言語によるタスク文字列（例：「明日ミーティング [wo] [hi]」）から、カテゴリ、
 * サブカテゴリ、優先度、期日などを解析（パース）するロジックをコントローラーから
 * 完全分離したサービスクラスです。単一責任の原則（SRP）に基づき、複雑な正規表現や
 * キーワード判定をカプセル化することで、コントローラーを肥大化させず、ユニットテストも
 * 容易に行えるよう保守性を高めています。
 */
namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Str;

class SmartTaskParserService
{
    /**
     * 自然言語のタスク文字列を解析し、構造化されたデータ配列を返却する
     *
     * @param string $line
     * @return array<string, mixed>
     */
    public function parse(string $line): array
    {
        $category = 'inbox';
        $subCategory = 'general';
        $priority = 'medium'; 
        $dueDate = now()->toDateString(); 
        $cleanTitle = $line;

        // 優先度を表すキーワード・ショートカットのパターンマッチング
        $priorityPatterns = [
            '/(?:\[hi\]|\(hi\)|\bhi\b|(?<=[^\x00-\x7F])hi)/ui' => 'high',
            '/(?:\[lo\]|\(lo\)|\blo\b|(?<=[^\x00-\x7F])lo)/ui' => 'low',
            '/(?:\[md\]|\(md\)|\bmd\b|(?<=[^\x00-\x7F])md)/ui' => 'medium',
        ];
        foreach ($priorityPatterns as $pattern => $prioVal) {
            if (preg_match($pattern, $cleanTitle)) {
                $priority = $prioVal;
                $cleanTitle = preg_replace($pattern, '', $cleanTitle);
                break;
            }
        }

        // カテゴリおよびサブカテゴリを表すパターンマッチング
        $categoryPatterns = [
            '/(?:\[wo\]|\(wo\)|\bwo\b|(?<=[^\x00-\x7F])wo)/ui' => ['work', 'task'],
            '/(?:\[pe\]|\(pe\)|\bpe\b|(?<=[^\x00-\x7F])pe)/ui' => ['personal', 'shopping'],
            '/(?:\[gr\]|\(gr\)|\bgr\b|(?<=[^\x00-\x7F])gr)/ui' => ['growth', 'learning'],
            '/(?:\[he\]|\(he\)|\bhe\b|(?<=[^\x00-\x7F])he)/ui' => ['health', 'fitness'],
            '/(?:\[fi\]|\(fi\)|\bfi\b|(?<=[^\x00-\x7F])fi)/ui' => ['finance', 'budget'],
        ];
        foreach ($categoryPatterns as $pattern => $catInfo) {
            if (preg_match($pattern, $cleanTitle)) {
                $category = $catInfo[0];
                $subCategory = $catInfo[1];
                $cleanTitle = preg_replace($pattern, '', $cleanTitle);
                break;
            }
        }

        // 明示的な指定がない場合、タイトル内のキーワードから優先度を推論
        if ($priority === 'medium') {
            if (Str::contains($cleanTitle, ['急ぎ', '緊急', '最優先', '今すぐ', '至急', '重要', '!'])) {
                $priority = 'high';
            } elseif (Str::contains($cleanTitle, ['後で', 'いつでも', 'いつか', '急ぎではない', '暇なとき'])) {
                $priority = 'low';
            }
        }

        // 明示的な指定がない場合、タイトル内のキーワードからカテゴリを推論
        if ($category === 'inbox') {
            if (Str::contains($cleanTitle, ['ミーティング', '会議', '資料', '修正', '開発', 'バグ', 'PR', 'メール', '返信'])) {
                $category = 'work';
                $subCategory = 'task';
            } elseif (Str::contains($cleanTitle, ['買い物', 'スーパー', '食材', '掃除', '洗濯', '片付け', '購入'])) {
                $category = 'personal';
                $subCategory = 'shopping';
            } elseif (Str::contains($cleanTitle, ['勉強', '読書', '学習', '英語', '資格', 'スキル', '本'])) {
                $category = 'growth';
                $subCategory = 'learning';
            } elseif (Str::contains($cleanTitle, ['ジム', 'ランニング', '筋トレ', '病院', '薬', '運動', '散歩'])) {
                $category = 'health';
                $subCategory = 'fitness';
            } elseif (Str::contains($cleanTitle, ['銀行', '振込', '家計簿', '税金', '支払い', 'ATM'])) {
                $category = 'finance';
                $subCategory = 'budget';
            }
        }

        // 日付を表す日本語キーワードとクロージャーによる動的日付計算
        $dateKeywords = [
            '明後日' => fn() => now()->addDays(2)->toDateString(),
            'あさって' => fn() => now()->addDays(2)->toDateString(),
            '明日' => fn() => now()->addDay()->toDateString(),
            'あした' => fn() => now()->addDay()->toDateString(),
            '今週末' => fn() => now()->next(Carbon::SATURDAY)->toDateString(),
            '週末' => fn() => now()->next(Carbon::SATURDAY)->toDateString(),
            '月末' => fn() => now()->endOfMonth()->toDateString(),
            '来週' => fn() => now()->addWeek()->toDateString(),
            '月曜日' => fn() => now()->next(Carbon::MONDAY)->toDateString(),
            '月曜' => fn() => now()->next(Carbon::MONDAY)->toDateString(),
            '火曜日' => fn() => now()->next(Carbon::TUESDAY)->toDateString(),
            '火曜' => fn() => now()->next(Carbon::TUESDAY)->toDateString(),
            '水曜日' => fn() => now()->next(Carbon::WEDNESDAY)->toDateString(),
            '水曜' => fn() => now()->next(Carbon::WEDNESDAY)->toDateString(),
            '木曜日' => fn() => now()->next(Carbon::THURSDAY)->toDateString(),
            '木曜' => fn() => now()->next(Carbon::THURSDAY)->toDateString(),
            '金曜日' => fn() => now()->next(Carbon::FRIDAY)->toDateString(),
            '金曜' => fn() => now()->next(Carbon::FRIDAY)->toDateString(),
            '土曜日' => fn() => now()->next(Carbon::SATURDAY)->toDateString(),
            '土曜' => fn() => now()->next(Carbon::SATURDAY)->toDateString(),
            '日曜日' => fn() => now()->next(Carbon::SUNDAY)->toDateString(),
            '日曜' => fn() => now()->next(Carbon::SUNDAY)->toDateString(),
        ];

        foreach ($dateKeywords as $keyword => $calculator) {
            if (Str::contains($cleanTitle, $keyword)) {
                $dueDate = $calculator();
                $cleanTitle = str_replace($keyword, '', $cleanTitle);
                break;
            }
        }

        // 抽出用キーワードが取り除かれたタイトルの余分な空白や句読点をクレンジング
        $cleanTitle = preg_replace('/^[、。\s]+|[、。\s]+$/u', '', $cleanTitle);
        $cleanTitle = preg_replace('/[、。\s]{2,}/u', '', $cleanTitle);
        $cleanTitle = trim($cleanTitle);

        return [
            'title' => $cleanTitle,
            'category' => $category,
            'sub_category' => $subCategory,
            'priority' => $priority,
            'due_date' => $dueDate,
            'is_completed' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}