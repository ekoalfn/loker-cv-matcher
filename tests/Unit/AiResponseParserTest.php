<?php

use App\Services\AiResponseParser;

beforeEach(function () {
    $this->parser = new AiResponseParser;
});

// ============================================================================
// parseJobSummary -- Valid JSON
// ============================================================================

test('parseJobSummary parses valid JSON response', function () {
    $content = json_encode([
        'summary' => 'Lowongan untuk posisi Software Engineer di perusahaan teknologi. Membutuhkan pengalaman 3 tahun di bidang backend development.',
        'tags' => ['Software Engineer', 'Backend', 'PHP', 'Laravel'],
    ]);

    $result = $this->parser->parseJobSummary($content);

    expect($result)
        ->toHaveKeys(['summary', 'tags'])
        ->and($result['summary'])->toBe('Lowongan untuk posisi Software Engineer di perusahaan teknologi. Membutuhkan pengalaman 3 tahun di bidang backend development.')
        ->and($result['tags'])->toBe(['Software Engineer', 'Backend', 'PHP', 'Laravel']);
});

test('parseJobSummary parses JSON inside markdown code block', function () {
    $content = "```json\n{\"summary\": \"Posisi data analyst.\", \"tags\": [\"Data\", \"SQL\"]}\n```";

    $result = $this->parser->parseJobSummary($content);

    expect($result['summary'])->toBe('Posisi data analyst.')
        ->and($result['tags'])->toBe(['Data', 'SQL']);
});

test('parseJobSummary parses JSON embedded in surrounding text', function () {
    $content = "Here is the result:\n{\"summary\": \"Full stack developer role.\", \"tags\": [\"React\", \"Node.js\"]}\nEnd.";

    $result = $this->parser->parseJobSummary($content);

    expect($result['summary'])->toBe('Full stack developer role.')
        ->and($result['tags'])->toBe(['React', 'Node.js']);
});

// ============================================================================
// parseJobSummary -- Malformed JSON (regex fallback)
// ============================================================================

test('parseJobSummary falls back to regex for malformed JSON', function () {
    $content = "Summary: Lowongan backend developer di startup fintech. Menguasai Laravel dan PostgreSQL.\nTags: PHP, Laravel, PostgreSQL, Fintech";

    $result = $this->parser->parseJobSummary($content);

    expect($result['summary'])->not->toBeEmpty()
        ->and($result['tags'])->toBeArray();
});

test('parseJobSummary extracts first two sentences when no labels present', function () {
    $content = "Ini adalah lowongan untuk data engineer. Membutuhkan pengalaman di Apache Spark dan Python.";

    $result = $this->parser->parseJobSummary($content);

    expect($result['summary'])->toContain('lowongan untuk data engineer')
        ->and($result['tags'])->toBeArray();
});

// ============================================================================
// parseJobSummary -- Missing fields
// ============================================================================

test('parseJobSummary returns empty tags when tags field is missing from JSON', function () {
    $content = json_encode(['summary' => 'Posisi marketing manager.']);

    $result = $this->parser->parseJobSummary($content);

    expect($result['summary'])->toBe('Posisi marketing manager.')
        ->and($result['tags'])->toBe([]);
});

test('parseJobSummary handles completely empty content', function () {
    $result = $this->parser->parseJobSummary('');

    expect($result['summary'])->toBeString()
        ->and($result['tags'])->toBeArray();
});

test('parseJobSummary sanitizes HTML tags from content', function () {
    $content = json_encode([
        'summary' => '<script>alert("xss")</script>Posisi developer.',
        'tags' => ['<b>PHP</b>', 'Laravel'],
    ]);

    $result = $this->parser->parseJobSummary($content);

    expect($result['summary'])->not->toContain('<script>')
        ->and($result['summary'])->toContain('Posisi developer.')
        ->and($result['tags'][0])->toBe('PHP');
});

// ============================================================================
// parseCvMatch -- Valid JSON
// ============================================================================

test('parseCvMatch parses valid JSON response', function () {
    $content = json_encode([
        'match_score' => 85,
        'strengths' => ['Pengalaman 5 tahun di Laravel', 'Menguasai PostgreSQL'],
        'weaknesses' => ['Belum ada pengalaman Docker'],
        'suggestions' => ['Pelajari containerization', 'Ambil sertifikasi AWS'],
    ]);

    $result = $this->parser->parseCvMatch($content);

    expect($result)
        ->toHaveKeys(['match_score', 'strengths', 'weaknesses', 'suggestions'])
        ->and($result['match_score'])->toBe(85)
        ->and($result['strengths'])->toHaveCount(2)
        ->and($result['weaknesses'])->toHaveCount(1)
        ->and($result['suggestions'])->toHaveCount(2);
});

test('parseCvMatch parses JSON inside code block', function () {
    $content = "```json\n{\"match_score\": 72, \"strengths\": [\"React\"], \"weaknesses\": [\"No backend\"], \"suggestions\": [\"Learn Node\"]}\n```";

    $result = $this->parser->parseCvMatch($content);

    expect($result['match_score'])->toBe(72)
        ->and($result['strengths'])->toBe(['React'])
        ->and($result['weaknesses'])->toBe(['No backend'])
        ->and($result['suggestions'])->toBe(['Learn Node']);
});

// ============================================================================
// parseCvMatch -- Score clamping
// ============================================================================

test('parseCvMatch clamps score above 100 to 100', function () {
    $content = json_encode([
        'match_score' => 150,
        'strengths' => ['Excellent'],
        'weaknesses' => [],
        'suggestions' => [],
    ]);

    $result = $this->parser->parseCvMatch($content);

    expect($result['match_score'])->toBe(100);
});

test('parseCvMatch clamps negative score to 0', function () {
    $content = json_encode([
        'match_score' => -20,
        'strengths' => [],
        'weaknesses' => ['No relevant experience'],
        'suggestions' => [],
    ]);

    $result = $this->parser->parseCvMatch($content);

    expect($result['match_score'])->toBe(0);
});

test('parseCvMatch handles non-numeric score', function () {
    $content = json_encode([
        'match_score' => 'high',
        'strengths' => [],
        'weaknesses' => [],
        'suggestions' => [],
    ]);

    $result = $this->parser->parseCvMatch($content);

    expect($result['match_score'])->toBe(0)
        ->and($result['match_score'])->toBeInt();
});

// ============================================================================
// parseCvMatch -- Missing fields (default values)
// ============================================================================

test('parseCvMatch returns empty arrays when list fields are missing', function () {
    $content = json_encode(['match_score' => 60]);

    $result = $this->parser->parseCvMatch($content);

    expect($result['match_score'])->toBe(60)
        ->and($result['strengths'])->toBe([])
        ->and($result['weaknesses'])->toBe([])
        ->and($result['suggestions'])->toBe([]);
});

test('parseCvMatch handles completely empty content', function () {
    $result = $this->parser->parseCvMatch('');

    expect($result['match_score'])->toBe(0)
        ->and($result['strengths'])->toBe([])
        ->and($result['weaknesses'])->toBe([])
        ->and($result['suggestions'])->toBe([]);
});

// ============================================================================
// parseCvMatch -- Malformed JSON (regex fallback)
// ============================================================================

test('parseCvMatch falls back to regex for plain text response', function () {
    $content = <<<'TEXT'
Match Score: 65

Strengths:
- Good communication skills
- Strong leadership experience

Weaknesses:
- Limited technical background

Suggestions:
- Take online courses in programming
- Get certified in project management
TEXT;

    $result = $this->parser->parseCvMatch($content);

    expect($result['match_score'])->toBe(65)
        ->and($result['strengths'])->toHaveCount(2)
        ->and($result['weaknesses'])->toHaveCount(1)
        ->and($result['suggestions'])->toHaveCount(2);
});

test('parseCvMatch sanitizes HTML in array values', function () {
    $content = json_encode([
        'match_score' => 70,
        'strengths' => ['<em>Good skills</em>'],
        'weaknesses' => ['No <b>Docker</b> experience'],
        'suggestions' => ['Learn <b>more</b>'],
    ]);

    $result = $this->parser->parseCvMatch($content);

    expect($result['strengths'][0])->toBe('Good skills')
        ->and($result['weaknesses'][0])->toBe('No Docker experience')
        ->and($result['suggestions'][0])->toBe('Learn more');
});
