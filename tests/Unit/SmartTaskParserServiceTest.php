<?php

namespace Tests\Unit;

use App\Services\SmartTaskParserService;
use Tests\TestCase;

class SmartTaskParserServiceTest extends TestCase
{
    protected SmartTaskParserService $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new SmartTaskParserService();
    }

    public function test_parser_correctly_parses_category_and_priority_from_string()
    {
        $result = $this->parser->parse('明日ミーティング [wo] [hi]');

        $this->assertEquals('ミーティング', $result['title']);
        $this->assertEquals('work', $result['category']);
        $this->assertEquals('task', $result['sub_category']);
        $this->assertEquals('high', $result['priority']);
        $this->assertFalse($result['is_completed']);
    }

    public function test_parser_infers_category_and_priority_from_keywords()
    {
        $result = $this->parser->parse('急ぎの会議');

        // パーサーがキーワードをタイトルに残す仕様に合わせる場合
        $this->assertEquals('急ぎの会議', $result['title']);
        $this->assertEquals('work', $result['category']);
        $this->assertEquals('high', $result['priority']);
    }

    public function test_parser_correctly_parses_due_date_keywords()
    {
        $result = $this->parser->parse('明日 買い物に行く');

        $this->assertEquals('買い物に行く', $result['title']);
        $this->assertEquals('personal', $result['category']);
        $this->assertEquals('shopping', $result['sub_category']);
        $this->assertEquals(now()->addDay()->toDateString(), $result['due_date']);
    }
}