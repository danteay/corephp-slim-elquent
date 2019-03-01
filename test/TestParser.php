<?php

namespace CorePHP\Slim\Dependency\Database\Test;

use PHPUnit\Framework\TestCase;
use CorePHP\Slim\Dependency\Database\Parser;

class TestParser extends TestCase
{
    protected $urls;
    protected $results;

    protected function setUp()
    {
        parent::setUp();

        $this->urls = [
            'postgres' => 'postgres://user:pass@host:5432/database',
            'mysql' => 'mysql://user:pass@host:3306/database',
            'sqlite' => 'sqlite://path/to/sqlite/file.database'
        ];

        $this->results = [
            'postgres' => [
                'driver'    => 'pgsql',
                'host'      => 'host',
                'port'      => 5432,
                'database'  => 'database',
                'username'  => 'user',
                'password'  => 'pass',
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'mysql' => [
                'driver'    => 'mysql',
                'host'      => 'host',
                'port'      => 3306,
                'database'  => 'database',
                'username'  => 'user',
                'password'  => 'pass',
                'charset'   => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
            'sqlite' => [
                'driver' => 'sqlite',
                'database' => '/path/to/sqlite/file.database',
                'foreign_key_constraints' => true,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ]
        ];
    }

    /**
     * Test Parser::parseGeneral with mysql
     *
     * @return void
     */
    public function testParseGeneralMysql()
    {
        $options = Parser::parseGeneral($this->urls['mysql']);
        $this->assertEquals($this->results['mysql'], $options);
    }

    /**
     * Test Parser::parseGeneral with postgres
     *
     * @return void
     */
    public function testParseGeneralPostgres()
    {
        $options = Parser::parseGeneral($this->urls['postgres']);
        $this->assertEquals($this->results['postgres'], $options);
    }

    public function testParseSqlite()
    {
        $options = Parser::parseSqlite($this->urls['sqlite']);
        $this->assertEquals($this->results['sqlite'], $options);
    }

    public function testParseConnectionMysql()
    {
        $options = Parser::parseConnection($this->urls['mysql']);
        $this->assertEquals($this->results['mysql'], $options);
    }

    public function testParseConnectionPostgres()
    {
        $options = Parser::parseConnection($this->urls['postgres']);
        $this->assertEquals($this->results['postgres'], $options);
    }

    public function testParseConnectionSqlite()
    {
        $options = Parser::parseConnection($this->urls['sqlite']);
        $this->assertEquals($this->results['sqlite'], $options);
    }
}