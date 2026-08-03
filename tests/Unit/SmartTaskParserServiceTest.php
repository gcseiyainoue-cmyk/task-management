<?php
/**
 * =====================================================================================
 * 【ファイル名】 SmartTaskParserServiceTest.php
 * 【アーキテクチャ上の位置づけ】 テスト層（ユニットテスト / 自然言語解析ロジックの検証）
 * =====================================================================================
 * 【実務における設計思想】
 * SmartTaskParserService の自然言語パース処理（ショートカットタグやキーワードからの
 * 優先度・カテゴリ・期日抽出）が仕様通りに正しく動作するかを高速かつ独立して検証します。
 */
namespace Tests\Unit;

use App\Services\SmartTaskParserService;
use Tests\TestCase;

class SmartTaskParserServiceTest extends TestCase
{
    /**
     * =====================================================================================
     * 【メソッド名】 test_it_parses_task_string_with_tags_and_keywords
     * 【概要】 ショートカットタグとキーワードを含むタスク文字列が正しく構造化されることの検証
     * =====================================================================================
     * @return void
     */
    public function test_it_parses_task_string_with_tags_and_keywords(): void
    {
        $parser = new SmartTaskParserService();
        $result = $parser->parse('明日ミーティング [wo] [hi]');

        $this->assertEquals('ミーティング', $result['title']);
        $this->assertEquals('work', $result['category']);
        $this->assertEquals('task', $result['sub_category']);
        $this->assertEquals('high', $result['priority']);
        $this->assertEquals(now()->addDay()->toDateString(), $result['due_date']);
        $this->assertFalse($result['is_completed']);
    }
}