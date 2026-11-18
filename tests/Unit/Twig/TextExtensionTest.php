<?php

namespace App\Tests\Unit\Twig;

use App\Twig\TextExtension;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TextExtensionTest extends TestCase
{
    public static function htmlProvider(): iterable
    {
        yield 'null' => [null, ''];
        yield 'balises retirées' => ['<p>Bonjour <strong>toi</strong></p>', 'Bonjour toi'];
        yield 'pas de mots collés entre deux blocs' => ['<h3>Vigilance</h3><p>Vous</p>', 'Vigilance Vous'];
        yield 'entités décodées' => ['L&#039;&eacute;t&eacute; &amp; l&rsquo;hiver', "L'été & l’hiver"];
        yield 'double encodage CKEditor' => ['&lt;p&gt;Caf&amp;eacute;&lt;/p&gt;', 'Café'];
        yield 'espaces et insécables fusionnés' => ["  Un\n\n deux\u{00A0}\u{00A0}trois  ", 'Un deux trois'];
    }

    #[DataProvider('htmlProvider')]
    public function testPlainText(?string $html, string $expected): void
    {
        self::assertSame($expected, new TextExtension()->plainText($html));
    }

    public function testPlainTextIsTruncatedWithEllipsis(): void
    {
        $text = new TextExtension()->plainText('<p>'.str_repeat('é', 50).'</p>', 10);

        self::assertSame(str_repeat('é', 9).'…', $text);
        self::assertSame(10, mb_strlen($text));
    }
}
